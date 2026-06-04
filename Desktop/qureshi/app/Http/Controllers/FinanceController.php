<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltersBookings;
use App\Services\OrderFinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    use FiltersBookings;

    private const COURIERS = [
        'Leopards' => ['label' => 'Leopards', 'bg' => 'bg-yellow-400', 'ring' => 'ring-yellow-400'],
        'Trax' => ['label' => 'Trax', 'bg' => 'bg-emerald-500', 'ring' => 'ring-emerald-500'],
        'M&P' => ['label' => 'M&P', 'bg' => 'bg-red-500', 'ring' => 'ring-red-500'],
        'TCS' => ['label' => 'TCS', 'bg' => 'bg-red-600', 'ring' => 'ring-red-600'],
        'BarqRaftar' => ['label' => 'BarqRaftar', 'bg' => 'bg-amber-500', 'ring' => 'ring-amber-500'],
    ];

    public function index(Request $request)
    {
        $baseQuery = $this->bookingsBaseQuery($request);

        $deliveredQuery = (clone $baseQuery)->where('bookings.status', 'delivered');

        $deliveredCount = (clone $deliveredQuery)->count();
        $deliveredAmount = (float) (clone $deliveredQuery)->sum('bookings.cod_amount');
        $deliveryCharges = (float) (clone $deliveredQuery)->sum('bookings.delivery_charges');

        $financeSummary = OrderFinanceService::summaryFromTotals(
            $deliveredAmount,
            $deliveryCharges,
            $deliveredCount
        );

        $inProgressBase = (clone $baseQuery)->whereNotIn('bookings.status', ['delivered', 'returned', 'cancelled', 'lost']);
        $inProgressCount = (clone $inProgressBase)->count();
        $inProgressCodAmount = (float) (clone $inProgressBase)->sum('bookings.cod_amount');

        $perPage = (int) $request->get('per_page', 50);
        $perPage = in_array($perPage, [25, 50, 100], true) ? $perPage : 50;

        $orders = (clone $deliveredQuery)
            ->select('bookings.*', 'courier_integrations.courier_name')
            ->orderByDesc('bookings.created_at')
            ->paginate($perPage)
            ->withQueryString();

        $courierCounts = $this->buildCourierCounts($baseQuery);
        $dateRange = $request->get('date_range', 'last_60_days');

        return view('finance.index', compact(
            'orders',
            'financeSummary',
            'courierCounts',
            'dateRange',
            'perPage',
            'inProgressCount',
            'inProgressCodAmount',
        ));
    }

    private function buildCourierCounts($baseQuery): array
    {
        $counts = (clone $baseQuery)
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
