<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\CourierIntegration;
use App\Models\Payout;
use App\Models\SellerInvoice;
use App\Models\PricingPlan;
use App\Models\Wallet;
use App\Models\CourierRateMatrix;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ==================== AUTH ACTIONS ====================

    public function showLogin()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin')->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
    }

    public function panel()
    {
        return redirect('/admin');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login')->with('success', 'Logged out successfully.');
    }

    // ==================== MERCHANT ACTIONS ====================

    public function approveMerchant(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
            'account_status' => 'active',
        ]);

        return response()->json(['success' => true, 'message' => $user->name . ' approved!']);
    }

    public function rejectMerchant(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update([
            'is_approved' => false,
            'account_status' => 'rejected',
        ]);

        return response()->json(['success' => true, 'message' => $user->name . ' rejected.']);
    }

    public function suspendMerchant(Request $request)
    {
        $user = User::findOrFail($request->id);
        $newStatus = $user->account_status === 'suspended' ? 'active' : 'suspended';
        $user->update(['account_status' => $newStatus]);

        $msg = $newStatus === 'suspended' ? $user->name . ' suspended.' : $user->name . ' reactivated!';
        return response()->json(['success' => true, 'message' => $msg, 'status' => $newStatus]);
    }

    public function updateMerchantPlan(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update(['pricing_plan_id' => $request->plan_id]);

        return response()->json(['success' => true, 'message' => 'Plan updated!']);
    }

    public function saveReturnCharge(Request $request)
    {
        $user = User::findOrFail($request->id);
        DB::table('shipper_specific_rates')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'return_charge' => $request->return_charge,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Return charge saved for ' . $user->name]);
    }

    public function saveMerchantCustomReturnCharge(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update(['custom_return_charge' => $request->custom_return_charge]);

        return response()->json(['success' => true, 'message' => 'Custom return charge saved!']);
    }

    public function updateMerchantStatus(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->update(['account_status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Status updated!']);
    }

    public function editMerchantPayment(Request $request)
    {
        $user = User::findOrFail($request->id);
        $deliveredOrders = Booking::where('user_id', $user->id)
            ->where('status', Booking::STATUS_DELIVERED);

        $totalCod = $deliveredOrders->sum('cod_amount');
        $totalCharges = $deliveredOrders->sum('delivery_charges');
        $tax4 = round($totalCod * 0.04);
        $courierPaid = $deliveredOrders->sum('courier_cost');

        return response()->json([
            'success' => true,
            'data' => [
                'total_cod' => $totalCod,
                'delivery_charges' => $totalCharges,
                'tax_4percent' => $tax4,
                'net_payable' => $totalCod - $totalCharges - $tax4,
                'courier_paid' => $courierPaid,
            ]
        ]);
    }

    // ==================== COURIER ACTIONS ====================

    public function toggleCourier(Request $request)
    {
        $courier = CourierIntegration::findOrFail($request->id);
        $courier->update(['is_active' => !$courier->is_active]);

        $msg = $courier->is_active ? $courier->courier_name . ' activated!' : $courier->courier_name . ' disabled!';
        return response()->json(['success' => true, 'message' => $msg, 'is_active' => $courier->is_active]);
    }

    public function saveCourierRates(Request $request)
    {
        $courier = CourierIntegration::findOrFail($request->id);
        DB::table('courier_rate_matrices')->updateOrInsert(
            ['courier_integration_id' => $courier->id],
            [
                'courier_rate' => $request->courier_rate ?? 0,
                'merchant_rate' => $request->merchant_rate ?? 0,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => $courier->courier_name . ' rates saved!']);
    }

    public function addCourier(Request $request)
    {
        $request->validate([
            'courier_name' => 'required|string|max:255',
            'logo_path' => 'nullable|string|max:500',
            'api_key' => 'nullable|string|max:255',
            'api_secret' => 'nullable|string|max:255',
        ]);

        $courier = CourierIntegration::create([
            'courier_name' => $request->courier_name,
            'logo_path' => $request->logo_path,
            'api_key' => $request->api_key,
            'api_secret' => $request->api_secret,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Courier added!', 'courier' => $courier]);
    }

    // ==================== INVOICE ACTIONS ====================

    public function generateInvoice(Request $request)
    {
        $userId = $request->user_id;
        $start = $request->period_start ?? now()->subDay()->toDateString();
        $end = $request->period_end ?? now()->toDateString();

        // If no user_id specified, generate for all merchants
        if (!$userId) {
            return $this->generateAllDailyInvoices();
        }

        $deliveredOrders = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_DELIVERED)
            ->where('invoice_id', null) // Only uninvoiced orders
            ->whereBetween('delivered_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->get();

        if ($deliveredOrders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No uninvoiced delivered orders in this period.']);
        }

        $totalCod = $deliveredOrders->sum('cod_amount');
        $totalCharges = $deliveredOrders->sum('delivery_charges');
        $tax4 = round($totalCod * 0.04);
        $netPayable = $totalCod - $totalCharges - $tax4;

        $orderIds = $deliveredOrders->pluck('id')->toArray();

        $invoice = SellerInvoice::create([
            'user_id' => $userId,
            'invoice_number' => SellerInvoice::generateInvoiceNumber(),
            'period_start' => $start,
            'period_end' => $end,
            'total_cod' => $totalCod,
            'total_deductions' => $totalCharges + $tax4,
            'net_amount' => $netPayable,
            'status' => 'unpaid',
            'delivered_orders' => $orderIds,
        ]);

        // Mark orders as invoiced
        Booking::whereIn('id', $orderIds)->update(['invoice_id' => $invoice->id]);

        return response()->json(['success' => true, 'message' => 'Invoice ' . $invoice->invoice_number . ' generated! Net: Rs ' . number_format($netPayable), 'invoice' => $invoice]);
    }

    public function generateAllDailyInvoices()
    {
        $today = now()->toDateString();
        $merchants = User::where('role', 'merchant')
            ->where('is_approved', true)
            ->get();

        $generatedCount = 0;
        foreach ($merchants as $merchant) {
            $deliveredOrders = Booking::where('user_id', $merchant->id)
                ->where('status', Booking::STATUS_DELIVERED)
                ->whereNull('invoice_id')
                ->whereDate('delivered_at', $today)
                ->get();

            if ($deliveredOrders->isEmpty()) continue;

            $totalCod = $deliveredOrders->sum('cod_amount');
            $totalCharges = $deliveredOrders->sum('delivery_charges');
            $tax4 = round($totalCod * 0.04);
            $netPayable = $totalCod - $totalCharges - $tax4;

            $orderIds = $deliveredOrders->pluck('id')->toArray();

            $invoice = SellerInvoice::create([
                'user_id' => $merchant->id,
                'invoice_number' => SellerInvoice::generateInvoiceNumber(),
                'period_start' => $today,
                'period_end' => $today,
                'total_cod' => $totalCod,
                'total_deductions' => $totalCharges + $tax4,
                'net_amount' => $netPayable,
                'status' => 'unpaid',
                'delivered_orders' => $orderIds,
            ]);

            Booking::whereIn('id', $orderIds)->update(['invoice_id' => $invoice->id]);
            $generatedCount++;
        }

        return response()->json(['success' => true, 'message' => $generatedCount . ' daily invoice(s) generated!']);
    }

    public function markInvoicePaid(Request $request)
    {
        $invoice = SellerInvoice::findOrFail($request->id);
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Invoice marked as paid!']);
    }

    public function todayPay(Request $request)
    {
        $today = now()->toDateString();
        $invoices = SellerInvoice::with('user')
            ->where('status', 'unpaid')
            ->whereDate('period_end', $today)
            ->get();

        if ($invoices->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No pending invoices for today.']);
        }

        $totalAmount = $invoices->sum('net_amount');
        $merchants = $invoices->map(function ($inv) {
            return [
                'id' => $inv->id,
                'merchant' => $inv->user->brand_name ?? $inv->user->name ?? '—',
                'net_payable' => (float) $inv->net_amount,
                'delivered_count' => $inv->delivered_orders ? count($inv->delivered_orders) : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'total_amount' => $totalAmount,
            'count' => $invoices->count(),
            'merchants' => $merchants,
        ]);
    }

    // ==================== SETTLEMENT / PAYOUT ACTIONS ====================

    public function payMerchant(Request $request)
    {
        $userId = $request->user_id;
        $deliveredOrders = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_DELIVERED);

        $totalCod = $deliveredOrders->sum('cod_amount');
        $totalCharges = $deliveredOrders->sum('delivery_charges');
        $tax4 = round($totalCod * 0.04);
        $netPayable = $totalCod - $totalCharges - $tax4;

        if ($netPayable <= 0) {
            return response()->json(['success' => false, 'message' => 'No payable amount for this merchant.']);
        }

        // Mark pending invoices as paid
        SellerInvoice::where('user_id', $userId)
            ->where('status', 'unpaid')
            ->update(['status' => 'paid', 'paid_at' => now()]);

        $payout = Payout::create([
            'user_id' => $userId,
            'payout_reference' => Payout::generateReference(),
            'gross_amount' => $totalCod,
            'commissions_deducted' => $tax4,
            'other_charges' => $totalCharges,
            'net_amount' => $netPayable,
            'period_start' => now()->subDays(7),
            'period_end' => now(),
            'status' => 'paid',
            'payment_method' => 'Bank Transfer',
        ]);

        return response()->json(['success' => true, 'message' => 'Payout ' . $payout->payout_reference . ' initiated! Net: Rs ' . number_format($netPayable)]);
    }

    // ==================== PRICING PLANS ====================

    public function savePricingPlan(Request $request)
    {
        try {
            $planId = $request->plan_id;
            if (!$planId) {
                return response()->json(['success' => false, 'message' => 'Plan ID is required']);
            }
            
            $plan = PricingPlan::findOrFail($planId);
            
            $updateData = [];
            if ($request->has('different_city_delivery')) $updateData['different_city_delivery'] = $request->different_city_delivery;
            if ($request->has('same_city_delivery')) $updateData['same_city_delivery'] = $request->same_city_delivery;
            if ($request->has('return_charge')) $updateData['return_charge'] = $request->return_charge;
            if ($request->has('additional_kg_rate')) $updateData['additional_kg_rate'] = $request->additional_kg_rate;
            if ($request->has('base_delivery_charge')) $updateData['base_delivery_charge'] = $request->base_delivery_charge;

            // Service-type specific rates
            if ($request->has('overnight_base_rate')) $updateData['overnight_base_rate'] = $request->overnight_base_rate;
            if ($request->has('overnight_additional_rate')) $updateData['overnight_additional_rate'] = $request->overnight_additional_rate;

            if ($request->has('detain_base_rate')) $updateData['detain_base_rate'] = $request->detain_base_rate;
            if ($request->has('detain_additional_rate')) $updateData['detain_additional_rate'] = $request->detain_additional_rate;

            if ($request->has('overland_base_rate')) $updateData['overland_base_rate'] = $request->overland_base_rate;
            if ($request->has('overland_additional_rate')) $updateData['overland_additional_rate'] = $request->overland_additional_rate;

            if (!empty($updateData)) {
                $plan->update($updateData);
            }

            return response()->json(['success' => true, 'message' => $plan->name . ' plan saved!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving plan: ' . $e->getMessage()]);
        }
    }

    // ==================== NOTIFICATIONS ====================

    public function sendNotification(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:1',
        ]);

        $targets = [];
        $sendTo = $request->send_to ?? 'all';

        if ($sendTo === 'all') {
            $targets = DB::table('users')->where('role', 'merchant')->where('is_approved', true)->get();
        } elseif ($sendTo === 'all_shippers') {
            $targets = DB::table('users')->where('role', 'shipper')->get();
        } else {
            $targets = DB::table('users')->where('id', (int) $sendTo)->get();
        }

        $channel = $request->channel ?? 'website';
        $type = $request->type ?? 'info';

        foreach ($targets as $target) {
            DB::table('notification_logs')->insert([
                'user_id' => $target->id,
                'type' => $request->notification_type ?? 'custom',
                'subject' => $request->subject ?? 'Notification from Admin',
                'message' => $request->message,
                'status' => 'sent',
                'sent_via' => $channel === 'whatsapp' ? 'WhatsApp' : 'Website',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $count = count($targets);
        return response()->json(['success' => true, 'message' => "Notification sent to {$count} merchant(s) via {$channel}!"]);
    }

    // ==================== FILTERED ORDERS ====================

    public function filterOrders(Request $request)
    {
        $status = $request->status;
        $from = $request->from;
        $to = $request->to;
        $search = $request->search;

        $query = Booking::with(['user', 'courier_integration'])->orderByDesc('created_at');

        if ($from && $to) {
            $query->whereBetween('created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay()
            ]);
        }

        if ($status && $status !== 'all') {
            switch ($status) {
                case 'in_progress':
                case 'in_transit':
                    $query->whereIn('status', [
                        Booking::STATUS_DISPATCHED,
                        Booking::STATUS_IN_TRANSIT,
                        Booking::STATUS_OUT_FOR_DELIVERY,
                    ]);
                    break;
                case 'booked':
                case 'pending':
                    $query->whereIn('status', [
                        Booking::STATUS_PENDING,
                        Booking::STATUS_PICKED_UP,
                    ]);
                    break;
                case 'returned':
                    $query->whereIn('status', [
                        Booking::STATUS_RETURNED,
                    ]);
                    break;
                case 'ready_to_return':
                    $query->where('status', Booking::STATUS_READY_TO_RETURN);
                    break;
                case 'return_confirmed':
                    $query->where('status', Booking::STATUS_RETURN_CONFIRMED);
                    break;
                default:
                    $query->where('status', $status);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('consignment_no', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('brand_name', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->limit(200)->get()->map(function ($order) {
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

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // ==================== DASHBOARD DATA API ====================

    public function getDashboardData(Request $request)
    {
        $period = $request->get('period', 'today');
        $from = $request->get('from');
        $to = $request->get('to');
        $now = Carbon::now();

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
                $startDate = $from ? Carbon::parse($from)->startOfDay() : $now->copy()->subDays(6)->startOfDay();
                $endDate = $to ? Carbon::parse($to)->endOfDay() : $now->copy()->endOfDay();
                break;
            default:
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
        }

        $dateRange = [$startDate, $endDate];

        // Total COD all
        $totalCodAll = Booking::whereBetween('created_at', $dateRange)->sum('cod_amount');

        // Merchant Payables
        $merchantPayables = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) - COALESCE(delivery_charges, 0) - (COALESCE(cod_amount, 0) * 0.04)'));

        // Courier Receivables
        $courierReceivables = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)
            ->sum(DB::raw('COALESCE(cod_amount, 0) - COALESCE(courier_cost, 0) - (COALESCE(cod_amount, 0) * 0.02)'));

        $taxHeld = round($totalCodAll * 0.04);
        $merchantDeliveryCharges = Booking::whereBetween('created_at', $dateRange)->sum('delivery_charges');
        $courierDeliveryCharges = Booking::whereBetween('created_at', $dateRange)->sum('courier_cost');
        $availableCash = $merchantDeliveryCharges - $courierDeliveryCharges;
        $bankBalance = Wallet::sum('balance') ?? 0;

        // Operational cards
        $bookedToday = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PICKED_UP])->count();
        $bookedTodayCod = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_PICKED_UP])->sum('cod_amount');

        $dispatchedCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DISPATCHED)->count();
        $dispatchedCod = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DISPATCHED)->sum('cod_amount');

        $deliveredCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)->count();
        $deliveredCod = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_DELIVERED)->sum('cod_amount');

        $inProgressCount = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_DISPATCHED, Booking::STATUS_IN_TRANSIT, Booking::STATUS_OUT_FOR_DELIVERY])->count();

        $readyToReturnCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_READY_TO_RETURN)->count();
        $returnConfirmedCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_RETURN_CONFIRMED)->count();
        $totalReturnedCount = Booking::whereBetween('created_at', $dateRange)
            ->whereIn('status', [Booking::STATUS_RETURNED, Booking::STATUS_RETURN_CONFIRMED, Booking::STATUS_READY_TO_RETURN])->count();
        $returnedCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_RETURNED)->count();

        $issueCount = Booking::whereBetween('created_at', $dateRange)
            ->where('status', Booking::STATUS_ISSUE)->count();

        // Financial
        $dispatchedBase = Booking::whereBetween('created_at', $dateRange)->where('status', Booking::STATUS_DISPATCHED);
        $grossProfit = $dispatchedBase->sum(DB::raw('COALESCE(delivery_charges, 0) - COALESCE(courier_cost, 0)'));

        $deliveredBase = Booking::whereBetween('created_at', $dateRange)->where('status', Booking::STATUS_DELIVERED);
        $totalDeliveredCod = $deliveredBase->sum('cod_amount');
        $deliveredDeliveryCharges = $deliveredBase->sum('delivery_charges');
        $deliveredCourierCost = $deliveredBase->sum('courier_cost');
        $netProfit = $deliveredDeliveryCharges - $deliveredCourierCost;

        $tax4Collected = round($totalDeliveredCod * 0.04);
        $courierTax2 = round($totalDeliveredCod * 0.02);
        $ourTax2Balance = $tax4Collected - $courierTax2;

        $pendingSettlements = Payout::where('status', 'pending')->count();
        $activeMerchants = User::where('role', 'merchant')->where('is_approved', true)->count();
        $pendingMerchants = User::where('role', 'merchant')->where('is_approved', false)->count();

        return response()->json([
            'success' => true,
            'operationalCards' => [
                'bookedToday' => $bookedToday,
                'bookedTodayCod' => $bookedTodayCod,
                'dispatched' => $dispatchedCount,
                'dispatchedCod' => $dispatchedCod,
                'delivered' => $deliveredCount,
                'deliveredCod' => $deliveredCod,
                'inProgress' => $inProgressCount,
                'returned' => $returnedCount,
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
        ]);
    }

    // ==================== INVOICE ADDITIONAL ACTIONS ====================

    public function getInvoiceOrders(Request $request, $invoiceId)
    {
        $invoice = SellerInvoice::with(['user', 'bookings'])->findOrFail($invoiceId);
        $orders = $invoice->bookings->map(function ($b) {
            return [
                'id' => $b->id,
                'consignment_no' => $b->consignment_no ?? ('#' . $b->id),
                'customer_name' => $b->customer_name ?? '—',
                'cod_amount' => (float) $b->cod_amount,
                'delivery_charges' => (float) $b->delivery_charges,
                'status' => $b->status,
            ];
        });

        return response()->json(['success' => true, 'orders' => $orders, 'invoice' => $invoice]);
    }

    public function editInvoice(Request $request, $invoiceId)
    {
        $invoice = SellerInvoice::findOrFail($invoiceId);
        $invoice->update([
            'total_cod' => $request->total_cod ?? $invoice->total_cod,
            'total_deductions' => ($request->delivery_charges ?? 0) + ($request->tax ?? 0),
            'net_amount' => $request->net_amount ?? $invoice->net_amount,
        ]);

        return response()->json(['success' => true, 'message' => 'Invoice updated!']);
    }

    public function getPendingSettlements(Request $request)
    {
        $from = $request->get('from');
        $to = $request->get('to');
        $now = Carbon::now();

        $startDate = $from ? Carbon::parse($from)->startOfDay() : $now->copy()->subDays(30)->startOfDay();
        $endDate = $to ? Carbon::parse($to)->endOfDay() : $now->copy()->endOfDay();

        $merchants = User::where('role', 'merchant')
            ->where('is_approved', true)
            ->get()
            ->map(function ($m) use ($startDate, $endDate) {
                $orders = Booking::where('user_id', $m->id)
                    ->where('status', Booking::STATUS_DELIVERED)
                    ->whereBetween('created_at', [$startDate, $endDate]);

                $totalCod = $orders->sum('cod_amount');
                $deliveryCharges = $orders->sum('delivery_charges');
                $tax4 = round($totalCod * 0.04);
                $netPayable = $totalCod - $deliveryCharges - $tax4;

                return [
                    'id' => $m->id,
                    'name' => $m->brand_name ?? $m->name,
                    'plan' => $m->pricingPlan->name ?? 'Basic',
                    'delivered_orders' => $orders->count(),
                    'total_cod' => $totalCod,
                    'delivery_charges' => $deliveryCharges,
                    'tax_4percent' => $tax4,
                    'net_payable' => $netPayable,
                ];
            })
            ->filter(function ($m) {
                return $m['net_payable'] > 0;
            })
            ->values();

        return response()->json(['success' => true, 'merchants' => $merchants, 'count' => $merchants->count()]);
    }
}