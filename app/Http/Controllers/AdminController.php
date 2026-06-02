<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Models\CourierIntegration;
use App\Models\Payout;
use App\Models\SellerInvoice;
use App\Models\PricingPlan;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class AdminController extends Controller
{
    // ==================== AUTH ACTIONS (for /admin routes) ====================

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
        // Store custom return charge - you can add a column or use shipper_specific_rates
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
        // Store courier rates - using rate matrices or direct storage
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

    // ==================== INVOICE ACTIONS ====================

    public function generateInvoice(Request $request)
    {
        $userId = $request->user_id;
        $start = $request->period_start ?? now()->subDays(3)->toDateString();
        $end = $request->period_end ?? now()->toDateString();

        $deliveredOrders = Booking::where('user_id', $userId)
            ->where('status', Booking::STATUS_DELIVERED)
            ->whereBetween('delivered_at', [$start . ' 00:00:00', $end . ' 23:59:59'])
            ->get();

        if ($deliveredOrders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No delivered orders in this period.']);
        }

        $totalCod = $deliveredOrders->sum('cod_amount');
        $totalCharges = $deliveredOrders->sum('delivery_charges');
        $tax4 = round($totalCod * 0.04);
        $netPayable = $totalCod - $totalCharges - $tax4;

        $invoice = SellerInvoice::create([
            'user_id' => $userId,
            'invoice_number' => SellerInvoice::generateInvoiceNumber(),
            'period_start' => $start,
            'period_end' => $end,
            'total_cod' => $totalCod,
            'total_deductions' => $totalCharges + $tax4,
            'net_amount' => $netPayable,
            'status' => 'unpaid',
        ]);

        return response()->json(['success' => true, 'message' => 'Invoice ' . $invoice->invoice_number . ' generated!', 'invoice' => $invoice]);
    }

    public function markInvoicePaid(Request $request)
    {
        $invoice = SellerInvoice::findOrFail($request->id);
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Invoice marked as paid!']);
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

        // Create payout record
        $payout = Payout::create([
            'user_id' => $userId,
            'payout_reference' => Payout::generateReference(),
            'gross_amount' => $totalCod,
            'commissions_deducted' => $tax4,
            'other_charges' => $totalCharges,
            'net_amount' => $netPayable,
            'period_start' => now()->subDays(7),
            'period_end' => now(),
            'status' => 'processing',
            'payment_method' => 'Bank Transfer',
        ]);

        return response()->json(['success' => true, 'message' => 'Payout ' . $payout->payout_reference . ' initiated! Net: Rs ' . number_format($netPayable)]);
    }

    // ==================== PRICING PLANS ====================

    public function savePricingPlan(Request $request)
    {
        $plan = PricingPlan::findOrFail($request->plan_id);
        $plan->update([
            'base_delivery_charge' => $request->base_delivery_charge ?? $plan->base_delivery_charge,
            'cod_commission_percent' => $request->cod_commission_percent ?? $plan->cod_commission_percent,
        ]);

        return response()->json(['success' => true, 'message' => $plan->name . ' plan saved!']);
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
        $query = Booking::with(['user', 'courier_integration'])->orderByDesc('created_at');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->limit(200)->get()->map(function ($order) {
            $cod = (float) ($order->cod_amount ?? 0);
            $charges = (float) ($order->delivery_charges ?? 0);
            $tax4 = round($cod * 0.04);
            $courier2 = round($tax4 / 2);
            $our2 = $tax4 - $courier2;
            $profit = (float) $order->profit ?? ($charges - ((float) ($order->courier_cost ?? 0)));

            return [
                'id' => $order->id,
                'consignment_no' => $order->consignment_no ?? ('#' . $order->id),
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
}