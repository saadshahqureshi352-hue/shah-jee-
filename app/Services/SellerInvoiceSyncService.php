<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SellerInvoiceSyncService
{
    /**
     * Group uninvoiced delivered bookings by calendar week (Mon–Sun) and create seller invoices.
     */
    public static function syncForUser(int $userId): void
    {
        $rows = DB::table('bookings')
            ->where('user_id', $userId)
            ->where('status', 'delivered')
            ->whereNull('invoice_id')
            ->orderBy('updated_at')
            ->get();

        if ($rows->isEmpty()) {
            return;
        }

        $grouped = $rows->groupBy(function ($b) {
            return Carbon::parse($b->updated_at)->startOfWeek(Carbon::MONDAY)->toDateString();
        });

        foreach ($grouped as $weekStartStr => $bookings) {
            $weekStart = Carbon::parse($weekStartStr)->startOfDay();
            $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY)->endOfDay();

            $totalCod = 0.0;
            $totalDed = 0.0;
            foreach ($bookings as $b) {
                $br = OrderFinanceService::parcelBreakdown($b);
                $totalCod += $br['cod'];
                $totalDed += $br['delivery_charges'] + $br['govt_tax'];
            }
            $net = round($totalCod - $totalDed, 2);

            $invoiceId = DB::table('seller_invoices')->insertGetId([
                'user_id' => $userId,
                'invoice_number' => self::nextInvoiceNumber(),
                'period_start' => $weekStart->toDateString(),
                'period_end' => $weekEnd->toDateString(),
                'total_cod' => round($totalCod, 2),
                'total_deductions' => round($totalDed, 2),
                'net_amount' => max(0, $net),
                'payment_method' => null,
                'status' => 'unpaid',
                'paid_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($bookings as $b) {
                DB::table('bookings')->where('id', $b->id)->update(['invoice_id' => $invoiceId]);
            }
        }
    }

    private static function nextInvoiceNumber(): string
    {
        $last = DB::table('seller_invoices')->orderByDesc('id')->value('invoice_number');
        if (! $last || ! preg_match('/IN(\d+)/', (string) $last, $m)) {
            return 'IN0001';
        }

        return 'IN'.str_pad((string) ((int) $m[1] + 1), 4, '0', STR_PAD_LEFT);
    }
}
