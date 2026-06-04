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
use App\Models\CourierRateMatrix;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';
    protected static ?string $navigationLabel = 'Shah Jee Courier';
    protected static ?string $title = 'Shah Jee Courier';
    protected static ?string $slug = '/';
    protected static ?int $navigationSort = 1;

    public function getViewData(): array
    {
        $now = Carbon::now();
        $request = request();

        // ==================== DATE RANGE HANDLING ====================
        $period = $request->get('period', 'today');
        $fromDate = $request->get('from');
        $toDate = $request->get('to');

        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                break;
            case '3days':
                $startDate = $now->copy()->subDays(2)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'week':
                $startDate = $now->copy()->startOfWeek()->startOfDay();
                $endDate = $now->copy()->endOfWeek()->endOfDay();
                break;
            case 'month':
                $startDate = $now->copy()->startOfMonth()->startOfDay();
                $endDate = $now->copy()->endOfMonth()->endOfDay();
                break;
            case 'last7days':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case 'date_to_date':
                $startDate = $fromDate ? Carbon::parse($fromDate)->startOfDay() : $now->copy()->subDays(6)->startOfDay();
                $endDate = $toDate ? Carbon::parse($toDate)->endOfDay() : $now->copy()->endOfDay();
                break;
            default:
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
        }

        $dateRange = [$startDate, $endDate];

        // ==================== HELPER: APPLY DATE RANGE ====================
        $applyDateRange = function ($query) use ($dateRange) {
            return $query->whereBetween('bookings.created_at', $dateRange);
        };

        // ==================== COMPANY LIVE POSITION (ALL TIME - DATE FILTERED) ====================
        // Total COD Amount (all statuses in date range)
        $totalCodAll = Booking::whereBetween('created_at', $dateRange)->sum('cod_amount');

        // Merchant Payables = Total Delivered COD - (Merchant Delivery Charges + 4% Merchant Tax)
        $merchantPayables = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) - COALESCE(delivery_charges, 0) - (COALESCE(cod_amount, 0) * 0.04)'));

        // Courier Receivable = Total Delivered COD - (Courier Delivery Charges + 2% Courier Tax)
        $courierReceivables = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) - COALESCE(courier_cost, 0) - (COALESCE(cod_amount, 0) * 0.02)'));

        // Tax Collected (4% Held) = 4% of Total COD Amount (all statuses)
        $taxHeld = round($totalCodAll * 0.04);

        // Available Cash = Company's profit without tax = Merchant Delivery Charges - Courier Delivery Charges
        $merchantDeliveryCharges = Booking::whereBetween('created_at', $dateRange)->sum('delivery_charges');
        $courierDeliveryCharges = Booking::whereBetween('created_at', $dateRange)->sum('courier_cost');
        $availableCash = $merchantDeliveryCharges - $courierDeliveryCharges;

        // Bank Balance from wallet
        $bankBalance = Wallet::sum('balance') ?? 0;

        // ==================== OPERATIONAL WORKFLOW STATUS CARDS ====================
        // Booked Today
        $bookedToday = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PICKED_UP])
            ->count();
        $bookedTodayCod = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PICKED_UP])
            ->sum('cod_amount');

        // Order Dispatched
        $dispatchedCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DISPATCHED)
            ->count();
        $dispatchedCod = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DISPATCHED)
            ->sum('cod_amount');

        // Delivered
        $deliveredCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)
            ->count();
        $deliveredCod = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)
            ->sum('cod_amount');

        // In Progress: dispatched but not delivered, returned, or issue
        $inProgressCount = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [
                Booking::STATUS_DISPATCHED,
                Booking::STATUS_IN_TRANSIT,
                Booking::STATUS_OUT_FOR_DELIVERY,
            ])
            ->count();

        // Issue Orders
        $issueCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_ISSUE)
            ->count();

        // Ready to Return
        $readyToReturnCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_READY_TO_RETURN)
            ->count();

        // Return Confirmed
        $returnConfirmedCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_RETURN_CONFIRMED)
            ->count();

        // Total Returned (all return statuses)
        $totalReturnedCount = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_RETURNED, Booking::STATUS_RETURN_CONFIRMED, Booking::STATUS_READY_TO_RETURN])
            ->count();

        // ==================== PROFITABILITY & TAX METRICS ====================
        // Gross Profit = on DISPATCHED orders: Merchant Delivery Rate - Courier Rate
        $dispatchedBase = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DISPATCHED);
        $grossProfit = $dispatchedBase->sum(DB::raw('COALESCE(delivery_charges, 0) - COALESCE(courier_cost, 0)'));

        // Net Profit = on DELIVERED orders: Merchant Delivery Charges Collected - Courier Delivery Charges Paid
        $deliveredBase = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED);
        $totalDeliveredCod = $deliveredBase->sum('cod_amount');
        $deliveredDeliveryCharges = $deliveredBase->sum('delivery_charges');
        $deliveredCourierCost = $deliveredBase->sum('courier_cost');
        $netProfit = $deliveredDeliveryCharges - $deliveredCourierCost;

        // Tax calculations
        // 4% from merchants on total COD
        $tax4Collected = round($totalDeliveredCod * 0.04);
        // Courier deducts 2% before remitting
        $courierTax2 = round($totalDeliveredCod * 0.02);
        // Our remaining tax = 4% - 2% = 2%
        $ourTax2Balance = $tax4Collected - $courierTax2;

        // ==================== MERCHANT ACTIVITIES ====================
        $activeMerchants = User::where('role', 'merchant')
            ->where('is_approved', true)
            ->count();
        $pendingMerchants = User::where('role', 'merchant')
            ->where('is_approved', false)
            ->count();

        $pendingSettlements = Payout::where('status', 'pending')->count();

        // ==================== ALL ORDERS DATA ====================
        $allOrders = Booking::with(['user', 'courier_integration'])
            ->orderByDesc('created_at')
            ->limit(100)
            ->get()
            ->map(function ($order) {
                $cod = (float) ($order->cod_amount ?? 0);
                $charges = (float) ($order->delivery_charges ?? 0);
                $tax4 = round($cod * 0.04);
                $courier2 = round($cod * 0.02);
                $our2 = $tax4 - $courier2;
                $profit = (float) ($order->profit ?? ($charges - ((float) ($order->courier_cost ?? 0))));

                return [
                    'id' => $order->id,
                    'consignment_no' => $order->consignment_no ?? ('#' . $order->id),
                    'tracking_number' => $order->tracking_number ?? '—',
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

            // Get rate from first rate matrix entry
            $rateMatrix = $c->rateMatrices()->first();

            return [
                'id' => $c->id,
                'name' => $c->courier_name,
                'logo_path' => $c->logo_path,
                'is_active' => (bool) $c->is_active,
                'courier_rate' => $rateMatrix->courier_cost ?? 0,
                'merchant_rate' => $rateMatrix->shipper_charge ?? 0,
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

                $plan = $m->pricingPlan;
                $planName = $plan->name ?? 'Basic';

                return [
                    'id' => $m->id,
                    'name' => $m->brand_name ?? $m->name,
                    'email' => $m->email ?? '',
                    'phone' => $m->phone ?? '',
                    'plan' => $planName,
                    'plan_id' => $plan->id ?? null,
                    'dispatched' => Booking::where('user_id', $m->id)->where('status', Booking::STATUS_DISPATCHED)->count(),
                    'delivered' => $deliveredOrders->count(),
                    'returned' => Booking::where('user_id', $m->id)->where('status', Booking::STATUS_RETURNED)->count(),
                    'total_cod' => $totalCod,
                    'delivery_charges' => $totalCharges,
                    'tax_4percent' => $tax4,
                    'net_payable' => $netPayable,
                    'is_suspended' => ($m->account_status === 'suspended'),
                    'account_status' => $m->account_status ?? 'active',
                    'custom_return_charge' => $m->custom_return_charge,
                ];
            });

        // ==================== INVOICES DATA (Daily Invoice System) ====================
        $invoices = SellerInvoice::with('user')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'merchant' => $inv->user->brand_name ?? $inv->user->name ?? '—',
                    'period_start' => $inv->period_start ? $inv->period_start->format('d M Y') : '—',
                    'period_end' => $inv->period_end ? $inv->period_end->format('d M Y') : '—',
                    'delivered_count' => $inv->delivered_orders ? count($inv->delivered_orders) : 0,
                    'total_cod' => (float) $inv->total_cod,
                    'delivery_charges' => (float) $inv->total_deductions - round((float) $inv->total_cod * 0.04),
                    'tax_4percent' => round((float) $inv->total_cod * 0.04),
                    'net_payable' => (float) $inv->net_amount,
                    'status' => $inv->status,
                    'paid_at' => $inv->paid_at ? $inv->paid_at->format('d M Y H:i') : null,
                ];
            });

        $totalInvoices = SellerInvoice::count();
        $pendingInvoices = SellerInvoice::where('status', 'unpaid')->count();
        $paidInvoices = SellerInvoice::where('status', 'paid')->count();
        $overdueInvoices = SellerInvoice::where('status', 'unpaid')
            ->where('period_end', '<', $now->copy()->subDay())
            ->count();

        // ==================== PRICING PLANS DATA ====================
        $pricingPlans = PricingPlan::where('is_active', true)->get()->map(function ($plan) {
            return [
                'id' => $plan->id,
                'name' => $plan->name,
                'description' => $plan->description ?? '',
                'different_city_delivery' => (float) $plan->different_city_delivery,
                'same_city_delivery' => (float) $plan->same_city_delivery,
                'return_charge' => (float) $plan->return_charge,
                'additional_kg_rate' => (float) $plan->additional_kg_rate,
                'base_delivery_charge' => (float) $plan->base_delivery_charge,
                'cod_commission_percent' => (float) $plan->cod_commission_percent,
                'merchant_count' => User::where('pricing_plan_id', $plan->id)
                    ->where('role', 'merchant')
                    ->where('is_approved', true)
                    ->count(),
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

        // ==================== RECENT ORDERS ====================
        $recentOrders = Booking::with(['user', 'courier_integration'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ==================== TAX REGISTER DATA ====================
        $taxRegister = Booking::with('user')
            ->where('cod_amount', '>', 0)
            ->whereBetween('created_at', $dateRange)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get()
            ->map(function ($order) {
                $cod = (float) ($order->cod_amount ?? 0);
                $tax4 = round($cod * 0.04);
                $courier2 = round($cod * 0.02);
                $our2 = $tax4 - $courier2;
                return [
                    'id' => $order->id,
                    'consignment' => $order->consignment_no ?? ('#' . $order->id),
                    'merchant' => $order->user->brand_name ?? $order->user->name ?? '—',
                    'cod' => $cod,
                    'tax_4percent' => $tax4,
                    'courier_2percent' => $courier2,
                    'our_2percent' => $our2,
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

        // ==================== OVERALL SALES DATA ====================
        $overallSalesBase = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id');

        $overallDeliveredBase = (clone $overallSalesBase)
            ->where('bookings.status', Booking::STATUS_DELIVERED);

        $overallDeliveredCount = (clone $overallDeliveredBase)->count();
        $overallDeliveredAmount = (float) (clone $overallDeliveredBase)->sum('bookings.cod_amount');
        $overallDeliveryCharges = (float) (clone $overallDeliveredBase)->sum('bookings.delivery_charges');
        $overallCourierCost = (float) (clone $overallDeliveredBase)->sum('bookings.courier_cost');
        $overallGrossProfit = $overallDeliveryCharges - $overallCourierCost;
        $overallTax4 = round($overallDeliveredAmount * 0.04);
        $overallNetProfit = $overallGrossProfit - round($overallTax4 / 2);

        $overallCourierCounts = (clone $overallDeliveredBase)
            ->select('courier_integrations.courier_name', DB::raw('count(*) as total'),
                DB::raw('SUM(bookings.cod_amount) as cod_sum'),
                DB::raw('SUM(bookings.delivery_charges) as charges_sum'),
                DB::raw('SUM(bookings.courier_cost) as cost_sum'))
            ->groupBy('courier_integrations.courier_name')
            ->get()
            ->map(function ($row) {
                $cod = (float) ($row->cod_sum ?? 0);
                $charges = (float) ($row->charges_sum ?? 0);
                $cost = (float) ($row->cost_sum ?? 0);
                $tax4 = round($cod * 0.04);
                $courierTax2 = round($cod * 0.02);
                $ourTax2 = $tax4 - $courierTax2;
                $profit = $charges - $cost;
                $netPayable = $cod - $charges - $tax4;
                return [
                    'name' => $row->courier_name ?? 'Unknown',
                    'delivered' => (int) $row->total,
                    'cod_amount' => $cod,
                    'delivery_charges' => $charges,
                    'courier_cost' => $cost,
                    'tax_4percent' => $tax4,
                    'courier_2percent' => $courierTax2,
                    'our_2percent' => $ourTax2,
                    'gross_profit' => $profit,
                    'net_payable' => $netPayable,
                ];
            });

        $overallSalesSummary = [
            'delivered_count' => $overallDeliveredCount,
            'delivered_amount' => $overallDeliveredAmount,
            'delivery_charges' => $overallDeliveryCharges,
            'courier_cost' => $overallCourierCost,
            'gross_profit' => $overallGrossProfit,
            'tax_4percent' => $overallTax4,
            'net_profit' => $overallNetProfit,
        ];

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

        // ==================== TODAY PAY - Aggregated ====================
        $todayPayMerchants = SellerInvoice::with('user')
            ->where('status', 'unpaid')
            ->whereDate('period_end', $now->toDateString())
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'merchant' => $inv->user->brand_name ?? $inv->user->name ?? '—',
                    'net_payable' => (float) $inv->net_amount,
                    'delivered_count' => $inv->delivered_orders ? count($inv->delivered_orders) : 0,
                ];
            });

        return [
            'companyPosition' => [
                'bankBalance' => $bankBalance,
                'merchantPayables' => $merchantPayables,
                'courierReceivables' => $courierReceivables,
                'taxHeld' => $taxHeld,
                'availableCash' => $availableCash,
                'totalCodAll' => $totalCodAll,
                'merchantDeliveryCharges' => $merchantDeliveryCharges,
                'courierDeliveryCharges' => $courierDeliveryCharges,
            ],
            'operationalCards' => [
                'bookedToday' => $bookedToday,
                'bookedTodayCod' => $bookedTodayCod,
                'dispatched' => $dispatchedCount,
                'dispatchedCod' => $dispatchedCod,
                'delivered' => $deliveredCount,
                'deliveredCod' => $deliveredCod,
                'inProgress' => $inProgressCount,
                'returned' => Booking::whereBetween('created_at', $dateRange)->where('status', Booking::STATUS_RETURNED)->count(),
                'returnedCod' => Booking::whereBetween('created_at', $dateRange)->whereIn('status', [Booking::STATUS_RETURNED, Booking::STATUS_RETURN_CONFIRMED])->sum('cod_amount'),
                'issueOrders' => $issueCount,
                'readyToReturn' => $readyToReturnCount,
                'returnConfirmed' => $returnConfirmedCount,
                'totalReturned' => $totalReturnedCount,
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
                'totalDeliveredCod' => $totalDeliveredCod,
                'totalDeliveryCharges' => $deliveredDeliveryCharges,
                'totalCourierCost' => $deliveredCourierCost,
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
            'overallCourierCounts' => $overallCourierCounts,
            'overallSalesSummary' => $overallSalesSummary,
            'allMerchants' => User::where('role', 'merchant')->where('is_approved', true)->get(['id', 'name', 'brand_name']),
            'todayPayMerchants' => $todayPayMerchants,
            'currentPeriod' => $period,
            'dateFrom' => $startDate->format('Y-m-d'),
            'dateTo' => $endDate->format('Y-m-d'),
        ];
    }
}