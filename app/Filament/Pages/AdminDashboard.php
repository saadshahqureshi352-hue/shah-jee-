<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Booking;
use App\Models\User;
use App\Models\CourierIntegration;
use App\Models\Payout;
use App\Models\SellerInvoice;
use App\Models\Wallet;
use App\Models\PricingPlan;
use App\Models\NotificationLog;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';
    protected static ?string $navigationLabel = 'Shah Jee Courier';
    protected static ?string $title = 'Shah Jee Courier';
    protected static ?string $slug = '/';
    protected static ?int $navigationSort = 1;

    public function getViewData(): array
    {
        $now = now();

        // ==================== DASHBOARD DATA ====================
        $bookedToday = Booking::whereDate('created_at', $now->toDateString())->count();
        $bookedTodayCod = Booking::whereDate('created_at', $now->toDateString())->sum('cod_amount');

        $dispatchedCount = Booking::where('status', Booking::STATUS_DISPATCHED)->count();
        $dispatchedCod = Booking::where('status', Booking::STATUS_DISPATCHED)->sum('cod_amount');

        $deliveredCount = Booking::where('status', Booking::STATUS_DELIVERED)->count();
        $deliveredCod = Booking::where('status', Booking::STATUS_DELIVERED)->sum('cod_amount');

        $inProgressCount = Booking::whereIn('status', [
            Booking::STATUS_PENDING,
            Booking::STATUS_PICKED_UP,
            Booking::STATUS_DISPATCHED,
            Booking::STATUS_IN_TRANSIT,
            Booking::STATUS_OUT_FOR_DELIVERY,
        ])->count();

        $returnedCount = Booking::where('status', Booking::STATUS_RETURNED)->count();
        $returnedCod = Booking::where('status', Booking::STATUS_RETURNED)->sum('cod_amount');

        $issueCount = Booking::where('status', Booking::STATUS_ISSUE)->count();

        $grossProfit = Booking::where('status', Booking::STATUS_DISPATCHED)
            ->sum(DB::raw('COALESCE(delivery_charges, 0) - COALESCE(courier_cost, 0)'));

        $tax4Collected = Booking::where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) * 0.04'));

        $courierTax2 = $tax4Collected / 2;

        $ourTax2Balance = $tax4Collected - $courierTax2;

        $netProfit = $grossProfit - $ourTax2Balance;

        $merchantPayables = Booking::where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) - COALESCE(delivery_charges, 0) - (COALESCE(cod_amount, 0) * 0.04)'));

        $courierReceivables = Booking::where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) - (COALESCE(cod_amount, 0) * 0.02)'));

        $bankBalance = Wallet::sum('balance') ?? 0;

        $availableCash = $bankBalance - $merchantPayables + $courierReceivables;

        $pendingSettlements = Payout::where('status', 'pending')->count();

        $activeMerchants = User::where('role', 'merchant')
            ->where('is_approved', true)
            ->count();

        $pendingMerchants = User::where('role', 'merchant')
            ->where('is_approved', false)
            ->count();

        // ==================== ORDERS DATA ====================
        $allOrders = Booking::with(['user', 'courier_integration'])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function ($order) {
                $cod = (float) ($order->cod_amount ?? 0);
                $charges = (float) ($order->delivery_charges ?? 0);
                $tax4 = round($cod * 0.04);
                $courier2 = round($tax4 / 2);
                $our2 = $tax4 - $courier2;
                $profit = (float) $order->profit ?? ($charges - ((float) ($order->courier_cost ?? 0)));

                return [
                    'id' => $order->id,
                    'consignment_no' => $order->consignment_no ?? ('#'.$order->id),
                    'merchant' => $order->user->brand_name ?? $order->user->name ?? '—',
                    'city' => $order->destination_city ?? '—',
                    'courier' => $order->courier_integration->courier_name ?? '—',
                    'cod_amount' => $cod,
                    'tax_4percent' => $tax4,
                    'courier_2percent' => $courier2,
                    'our_2percent' => $our2,
                    'delivery_charge' => $charges,
                    'profit' => $profit,
                    'status' => $order->status ?? 'pending',
                    'status_label' => Booking::getStatusLabel($order->status ?? 'pending'),
                    'status_class' => Booking::getStatusBadgeClass($order->status ?? 'pending'),
                ];
            });

        // ==================== COURIERS DATA ====================
        $couriers = CourierIntegration::all()->map(function ($c) {
            $dispatchedCount = Booking::where('courier_integration_id', $c->id)
                ->where('status', Booking::STATUS_DISPATCHED)
                ->count();

            $profitPerOrder = Booking::where('courier_integration_id', $c->id)
                ->where('status', Booking::STATUS_DISPATCHED)
                ->sum(DB::raw('COALESCE(delivery_charges, 0) - COALESCE(courier_cost, 0)'));

            return [
                'id' => $c->id,
                'name' => $c->courier_name,
                'is_active' => (bool) $c->is_active,
                'courier_rate' => 0, // will need rate matrix lookup
                'merchant_rate' => 0,
                'dispatched' => $dispatchedCount,
                'total_profit' => $profitPerOrder,
            ];
        });

        // ==================== MERCHANTS DATA ====================
        $pendingMerchantList = User::where('role', 'merchant')
            ->where('is_approved', false)
            ->latest()
            ->limit(20)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->brand_name ?? $m->name,
                    'business' => $m->business_type ?? '—',
                    'city' => $m->city ?? '—',
                    'plan' => $m->pricingPlan->name ?? 'Basic',
                    'joined' => $m->created_at ? $m->created_at->format('d M') : '—',
                    'phone' => $m->phone ?? '—',
                ];
            });

        $activeMerchantList = User::where('role', 'merchant')
            ->where('is_approved', true)
            ->get()
            ->map(function ($m) {
                $deliveredOrders = Booking::where('user_id', $m->id)
                    ->where('status', Booking::STATUS_DELIVERED);

                $totalCod = $deliveredOrders->sum('cod_amount');
                $totalCharges = $deliveredOrders->sum('delivery_charges');
                $tax4 = round($totalCod * 0.04);
                $netPayable = $totalCod - $totalCharges - $tax4;

                return [
                    'id' => $m->id,
                    'name' => $m->brand_name ?? $m->name,
                    'plan' => $m->pricingPlan->name ?? 'Basic',
                    'dispatched' => Booking::where('user_id', $m->id)->where('status', Booking::STATUS_DISPATCHED)->count(),
                    'delivered' => $deliveredOrders->count(),
                    'returned' => Booking::where('user_id', $m->id)->where('status', Booking::STATUS_RETURNED)->count(),
                    'total_cod' => $totalCod,
                    'delivery_charges' => $totalCharges,
                    'tax_4percent' => $tax4,
                    'net_payable' => $netPayable,
                    'is_suspended' => ($m->account_status === 'suspended'),
                    'account_status' => $m->account_status ?? 'active',
                ];
            });

        // ==================== INVOICES DATA ====================
        $invoices = SellerInvoice::with('user')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'merchant' => $inv->user->brand_name ?? $inv->user->name ?? '—',
                    'period_start' => $inv->period_start ? $inv->period_start->format('d M') : '—',
                    'period_end' => $inv->period_end ? $inv->period_end->format('d M') : '—',
                    'delivered_count' => $inv->total_deductions > 0 ? round(((float) $inv->total_deductions - ($inv->total_cod * 0.04)) / 200) : 0,
                    'total_cod' => (float) $inv->total_cod,
                    'delivery_charges' => (float) $inv->total_deductions - ((float) $inv->total_cod * 0.04),
                    'tax_4percent' => round((float) $inv->total_cod * 0.04),
                    'net_payable' => (float) $inv->net_amount,
                    'status' => $inv->status,
                ];
            });

        $totalInvoices = SellerInvoice::count();
        $pendingInvoices = SellerInvoice::where('status', 'unpaid')->count();
        $paidInvoices = SellerInvoice::where('status', 'paid')->count();
        $overdueInvoices = SellerInvoice::where('status', 'unpaid')
            ->where('period_end', '<', $now->subDays(3))
            ->count();

        // ==================== PRICING PLANS DATA ====================
        $pricingPlans = PricingPlan::where('is_active', true)->get()->map(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description ?? '',
                'base_delivery_charge' => (float) $plan->base_delivery_charge,
                'cod_commission_percent' => (float) $plan->cod_commission_percent,
            ];
        })->toArray();

        // ==================== NOTIFICATION HISTORY ====================
        $notifHistory = DB::table('notification_logs')
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($n) {
                $user = User::find($n->user_id);
                return [
                    'time' => $n->created_at,
                    'merchant' => $user->brand_name ?? $user->name ?? '—',
                    'type' => $n->subject ?? $n->type ?? '—',
                    'message' => \Illuminate\Support\Str::limit($n->message, 40),
                    'channel' => $n->sent_via ?? ($n->type === 'whatsapp' ? 'WhatsApp' : 'Website'),
                    'status' => $n->status ?? 'sent',
                ];
            });

        // ==================== RECENT ORDERS (for dashboard table) ====================
        $recentOrders = Booking::with(['user', 'courier_integration'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ==================== TAX REGISTER DATA ====================
        $taxRegister = Booking::with('user')
            ->where('cod_amount', '>', 0)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($order) {
                $cod = (float) ($order->cod_amount ?? 0);
                $tax4 = round($cod * 0.04);
                $courier2 = round($tax4 / 2);
                return [
                    'id' => $order->id,
                    'consignment' => $order->consignment_no ?? ('#'.$order->id),
                    'merchant' => $order->user->brand_name ?? $order->user->name ?? '—',
                    'cod' => $cod,
                    'tax_4percent' => $tax4,
                    'courier_2percent' => $courier2,
                    'our_2percent' => $tax4 - $courier2,
                    'paid_to_govt' => $order->status === 'delivered',
                ];
            });

        // ==================== PER COURIER PROFIT ====================
        $courierProfits = CourierIntegration::all()->map(function ($c) {
            $dispatched = Booking::where('courier_integration_id', $c->id)
                ->where('status', Booking::STATUS_DISPATCHED)
                ->count();
            $profit = Booking::where('courier_integration_id', $c->id)
                ->where('status', Booking::STATUS_DISPATCHED)
                ->sum(DB::raw('COALESCE(delivery_charges, 0) - COALESCE(courier_cost, 0)'));
            return ['name' => $c->courier_name, 'dispatched' => $dispatched, 'profit' => $profit];
        });

        // ==================== PER MERCHANT PROFIT ====================
        $merchantProfitData = User::where('role', 'merchant')
            ->where('is_approved', true)
            ->get()
            ->map(function ($m) {
                $dispatchedOrders = Booking::where('user_id', $m->id)
                    ->where('status', Booking::STATUS_DISPATCHED);
                $totalCod = $dispatchedOrders->sum('cod_amount');
                $deliveryCharges = $dispatchedOrders->sum('delivery_charges');
                $profitSum = $dispatchedOrders->sum(DB::raw('COALESCE(delivery_charges, 0) - COALESCE(courier_cost, 0)'));

                $tax4 = round($totalCod * 0.04);
                $courier2 = round($deliveryCharges * 0.02);
                $our2 = $tax4 - $courier2;

                return [
                    'name' => $m->brand_name ?? $m->name,
                    'plan' => $m->pricingPlan->name ?? 'Basic',
                    'dispatched' => $dispatchedOrders->count(),
                    'total_cod' => $totalCod,
                    'tax_4percent' => $tax4,
                    'courier_2percent' => $courier2,
                    'our_2percent' => $our2,
                    'delivery_charges' => $deliveryCharges,
                    'net_profit' => $profitSum,
                ];
            });

        return [
            'companyPosition' => [
                'bankBalance' => $bankBalance,
                'merchantPayables' => $merchantPayables,
                'courierReceivables' => $courierReceivables,
                'taxHeld' => $tax4Collected,
                'availableCash' => $availableCash,
            ],
            'operationalCards' => [
                'bookedToday' => $bookedToday,
                'bookedTodayCod' => $bookedTodayCod,
                'dispatched' => $dispatchedCount,
                'dispatchedCod' => $dispatchedCod,
                'delivered' => $deliveredCount,
                'deliveredCod' => $deliveredCod,
                'inProgress' => $inProgressCount,
                'returned' => $returnedCount,
                'returnedCod' => $returnedCod,
                'issueOrders' => $issueCount,
            ],
            'financialCards' => [
                'grossProfit' => $grossProfit,
                'netProfit' => $netProfit,
                'tax4Collected' => $tax4Collected,
                'courierTax2' => $courierTax2,
                'ourTax2Balance' => $ourTax2Balance,
                'pendingSettlements' => $pendingSettlements,
                'activeMerchants' => $activeMerchants,
                'pendingMerchants' => $pendingMerchants,
            ],
            'allOrders' => $allOrders,
            'recentOrders' => $recentOrders,
            'couriers' => CourierIntegration::all(),
            'courierData' => $couriers,
            'courierProfits' => $courierProfits,
            'merchantProfitData' => $merchantProfitData,
            'invoiceStats' => [
                'total' => $totalInvoices,
                'pending' => $pendingInvoices,
                'paid' => $paidInvoices,
                'overdue' => $overdueInvoices,
            ],
            'invoices' => $invoices,
            'pendingMerchantsList' => $pendingMerchantList,
            'activeMerchantsList' => $activeMerchantList,
            'pricingPlans' => $pricingPlans,
            'taxRegister' => $taxRegister,
            'notifHistory' => $notifHistory,
            'allMerchants' => User::where('role', 'merchant')->where('is_approved', true)->get(['id', 'name', 'brand_name']),
        ];
    }
}