<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\SellerInvoice;
use Illuminate\Http\Request;

class MerchantInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $merchantId = (int) $request->user()->id;

        // invoice_status=all|paid|unpaid (using query string)
        $filter = (string) $request->query('invoice_status', 'all');

        $baseQuery = SellerInvoice::query()
            ->where('user_id', $merchantId)
            ->orderByDesc('period_end');

        // Top stats
        $paidSum = (float) (clone $baseQuery)->where('status', 'paid')->sum('total_cod');

        // Card2 expects pending + unpaid
        $pendingUnpaidSum = (float) (clone $baseQuery)
            ->whereIn('status', ['pending', 'unpaid'])
            ->sum('total_cod');

        // Card3: overall dc + govt tax combined
        $overallDeductions = (float) (clone $baseQuery)->sum('total_deductions');

        // Table filter
        $tableQuery = clone $baseQuery;
        if ($filter === 'paid') {
            $tableQuery->where('status', 'paid');
        } elseif ($filter === 'unpaid') {
            $tableQuery->whereIn('status', ['pending', 'unpaid']);
        }

        // Pagination
        $perPage = (int) $request->query('per_page', 50);
        $perPage = in_array($perPage, [25, 50, 100], true) ? $perPage : 50;

        $invoices = $tableQuery->paginate($perPage)->withQueryString();

        // finance admin check
        $canMarkPaid = in_array((string) $request->user()->email, config('finance.admin_emails', []), true)
            || (int) $request->user()->id === 1;

        return view('payments.my-invoices', [
            'filter' => $filter,
            'invoices' => $invoices,
            'paidSum' => $paidSum,
            'unpaidSum' => $pendingUnpaidSum,
            'overallDeductions' => $overallDeductions,
            'canMarkPaid' => $canMarkPaid,
        ]);
    }
}

