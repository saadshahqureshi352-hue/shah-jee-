<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltersBookings;
use App\Services\OrderFinanceService;
use App\Services\SellerInvoiceSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentsController extends Controller
{
    use FiltersBookings;

    private const COURIERS = [
        'Leopards' => ['label' => 'Leopards', 'bg' => 'bg-yellow-400', 'ring' => 'ring-yellow-400'],
        'Trax' => ['label' => 'Trax', 'bg' => 'bg-emerald-500', 'ring' => 'ring-emerald-500'],
        'M&P' => ['label' => 'M&P', 'bg' => 'bg-red-500', 'ring' => 'ring-red-500'],
        'TCS' => ['label' => 'TCS', 'bg' => 'bg-red-600', 'ring' => 'ring-red-600'],
        'BarqRaftar' => ['label' => 'BarqRaftar', 'bg' => 'bg-amber-500', 'ring' => 'ring-amber-500'],
    ];

    public function overallSales(Request $request)
    {
        $baseQuery = $this->bookingsBaseQuery($request);
        $deliveredBase = (clone $baseQuery)->where('bookings.status', 'delivered');

        $deliveredCount = (clone $deliveredBase)->count();
        $deliveredAmount = (float) (clone $deliveredBase)->sum('bookings.cod_amount');
        $deliveryCharges = (float) (clone $deliveredBase)->sum('bookings.delivery_charges');

        $summary = OrderFinanceService::summaryFromTotals($deliveredAmount, $deliveryCharges, $deliveredCount);

        $courierCounts = $this->buildDeliveredCourierCounts($baseQuery);
        $dateRange = $request->get('date_range', 'last_60_days');

        return view('payments.overall-sales', compact('summary', 'courierCounts', 'dateRange'));
    }

    public function invoices(Request $request)
    {
        SellerInvoiceSyncService::syncForUser((int) auth()->id());

        $filter = $request->get('invoice_status', 'all');
        $q = DB::table('seller_invoices')->where('user_id', auth()->id())->orderByDesc('period_end');

        if ($filter === 'paid') {
            $q->where('status', 'paid');
        } elseif ($filter === 'unpaid') {
            $q->where('status', 'unpaid');
        }

        $perPage = (int) $request->get('per_page', 50);
        $perPage = in_array($perPage, [25, 50, 100], true) ? $perPage : 50;

        $invoices = $q->paginate($perPage)->withQueryString();

        $all = DB::table('seller_invoices')->where('user_id', auth()->id());
        $paidSum = (clone $all)->where('status', 'paid')->sum('net_amount');
        $unpaidSum = (clone $all)->where('status', 'unpaid')->sum('net_amount');
        $overallDeductions = (clone $all)->sum('total_deductions');

        $canMarkPaid = $this->financeAdmin();

        return view('payments.invoices', compact(
            'invoices',
            'filter',
            'paidSum',
            'unpaidSum',
            'overallDeductions',
            'perPage',
            'canMarkPaid',
        ));
    }

    public function markInvoicePaid(Request $request, int $invoice)
    {
        if (! $this->financeAdmin()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|string|max:64',
        ]);

        $row = DB::table('seller_invoices')->where('id', $invoice)->first();
        abort_unless($row, 404);
        if ($row->status === 'paid') {
            return back()->with('success', 'Invoice already marked paid.');
        }
        DB::table('seller_invoices')->where('id', $invoice)->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'paid_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Invoice '.$row->invoice_number.' marked as paid.');
    }

    public function nonCod(Request $request)
    {
        $requests = DB::table('non_cod_payment_requests')
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $bank = config('payment_wallets.bank', []);
        $wallets = config('payment_wallets.wallets', []);

        $pendingCount = DB::table('non_cod_payment_requests')
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        return view('payments.non-cod', compact('requests', 'bank', 'wallets', 'pendingCount'));
    }

    public function nonCodStore(Request $request)
    {
        $request->validate([
            'channel' => 'required|in:bank,jazzcash,easypaisa,nayapay,upaisa',
            'amount' => 'required|numeric|min:1',
            'screenshot' => 'required|image|max:5120',
        ]);

        $path = $request->file('screenshot')->store('non_cod', 'public');

        DB::table('non_cod_payment_requests')->insert([
            'user_id' => auth()->id(),
            'channel' => $request->channel,
            'amount' => $request->amount,
            'screenshot_path' => $path,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Payment request submitted. Our team will verify shortly.');
    }

    private function financeAdmin(): bool
    {
        $emails = config('finance.admin_emails', []);

        return in_array((string) auth()->user()->email, $emails, true);
    }

    private function buildDeliveredCourierCounts($baseQuery): array
    {
        $counts = (clone $baseQuery)
            ->where('bookings.status', 'delivered')
            ->select('courier_integrations.courier_name', DB::raw('count(*) as total'))
            ->groupBy('courier_integrations.courier_name')
            ->pluck('total', 'courier_name');

        $result = [];
        foreach (self::COURIERS as $name => $meta) {
            $dbName = collect($counts->keys())->first(fn ($k) => strcasecmp((string) $k, $name) === 0);
            $result[] = array_merge($meta, [
                'name' => $name,
                'count' => $dbName ? (int) $counts[$dbName] : 0,
            ]);
        }

        return $result;
    }
}
