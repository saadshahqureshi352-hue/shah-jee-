<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait FiltersBookings
{
    protected function bookingsBaseQuery(Request $request): Builder
    {
        $query = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.user_id', auth()->id());

        $this->applyDateRange($query, $request->get('date_range', 'last_60_days'), $request);
        $this->applySearch($query, $request->get('search'));
        $this->applyCourierFilter($query, $request->get('courier'));

        return $query;
    }

    protected function applyDateRange(Builder $query, string $range, Request $request): void
    {
        $from = $request->get('date_from');
        $to = $request->get('date_to');

        if ($from && $to) {
            $query->whereBetween('bookings.created_at', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ]);

            return;
        }

        $now = now();

        match ($range) {
            'today' => $query->whereDate('bookings.created_at', $now->toDateString()),
            'yesterday' => $query->whereDate('bookings.created_at', $now->copy()->subDay()->toDateString()),
            'last_7_days' => $query->where('bookings.created_at', '>=', $now->copy()->subDays(7)),
            'last_30_days' => $query->where('bookings.created_at', '>=', $now->copy()->subDays(30)),
            'this_month' => $query->whereMonth('bookings.created_at', $now->month)
                ->whereYear('bookings.created_at', $now->year),
            'last_month' => $query->whereMonth('bookings.created_at', $now->copy()->subMonth()->month)
                ->whereYear('bookings.created_at', $now->copy()->subMonth()->year),
            default => $query->where('bookings.created_at', '>=', $now->copy()->subDays(60)),
        };
    }

    protected function applySearch(Builder $query, ?string $search): void
    {
        if (! $search) {
            return;
        }

        $term = '%'.$search.'%';
        $query->where(function ($q) use ($term) {
            $q->where('bookings.tracking_number', 'like', $term)
                ->orWhere('bookings.customer_name', 'like', $term)
                ->orWhere('bookings.customer_phone', 'like', $term)
                ->orWhere('bookings.destination_city', 'like', $term)
                ->orWhere('bookings.origin_city', 'like', $term)
                ->orWhere('bookings.reference_no', 'like', $term)
                ->orWhere('bookings.product_name', 'like', $term);
        });
    }

    protected function applyCourierFilter(Builder $query, ?string $courier): void
    {
        if ($courier && $courier !== 'all') {
            $query->where('courier_integrations.courier_name', $courier);
        }
    }
}
