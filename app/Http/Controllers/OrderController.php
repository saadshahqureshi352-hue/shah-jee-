<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltersBookings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
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
        $query = $this->bookingsBaseQuery($request)
            ->select('bookings.*', 'courier_integrations.courier_name');

        $this->applyStatusFilter($query, $request->get('status_filter', 'all'));

        $perPage = (int) $request->get('per_page', 50);
        $perPage = in_array($perPage, [25, 50, 100], true) ? $perPage : 50;

        $orders = (clone $query)->orderByDesc('bookings.created_at')->paginate($perPage)->withQueryString();

        $baseQuery = $this->bookingsBaseQuery($request);
        $statusCards = $this->buildStatusCards($baseQuery);
        $courierCounts = $this->buildCourierCounts($baseQuery);

        $dateRange = $request->get('date_range', 'last_60_days');
        $statusFilter = $request->get('status_filter', 'all');

        return view('bookings.index', compact(
            'orders',
            'statusCards',
            'courierCounts',
            'dateRange',
            'statusFilter',
            'perPage',
        ));
    }

    private function applyStatusFilter($query, string $filter): void
    {
        match ($filter) {
            'active' => $query->whereNotIn('bookings.status', ['delivered', 'cancelled', 'returned', 'lost']),
            'booked' => $query->where('bookings.status', 'pending'),
            'rider_picked' => $query->where('bookings.status', 'dispatched'),
            'in_transit' => $query->where('bookings.status', 'in_transit'),
            'out_for_delivery' => $query->where('bookings.status', 'out_for_delivery'),
            'issue_detected' => $query->whereRaw('0 = 1'),
            're_attempt' => $query->whereRaw('0 = 1'),
            'delivered' => $query->where('bookings.status', 'delivered'),
            'return_confirmed' => $query->whereRaw('0 = 1'),
            'returned' => $query->where('bookings.status', 'returned'),
            'lost' => $query->where('bookings.status', 'lost'),
            'cancelled' => $query->where('bookings.status', 'cancelled'),
            'at_destination' => $query->whereRaw('0 = 1'),
            default => null,
        };
    }

    private function buildStatusCards($baseQuery): array
    {
        $clone = fn () => clone $baseQuery;

        $all = $clone()->count();

        return [
            ['key' => 'all', 'label' => 'All', 'count' => $all],
            ['key' => 'active', 'label' => 'Active', 'count' => $clone()->whereNotIn('bookings.status', ['delivered', 'cancelled', 'returned', 'lost'])->count()],
            ['key' => 'booked', 'label' => 'Booked', 'count' => $clone()->where('bookings.status', 'pending')->count()],
            ['key' => 'rider_picked', 'label' => 'Rider Picked', 'count' => $clone()->where('bookings.status', 'dispatched')->count()],
            ['key' => 'in_transit', 'label' => 'In Transit', 'count' => $clone()->where('bookings.status', 'in_transit')->count()],
            ['key' => 'out_for_delivery', 'label' => 'Out for Delivery', 'count' => $clone()->where('bookings.status', 'out_for_delivery')->count()],
            ['key' => 'issue_detected', 'label' => 'Issue Detected', 'count' => 0],
            ['key' => 're_attempt', 'label' => 'Re-Attempt', 'count' => 0],
            ['key' => 'delivered', 'label' => 'Delivered', 'count' => $clone()->where('bookings.status', 'delivered')->count()],
            ['key' => 'return_confirmed', 'label' => 'Return Confirmed', 'count' => 0],
            ['key' => 'returned', 'label' => 'Returned to Shipper', 'count' => $clone()->where('bookings.status', 'returned')->count()],
            ['key' => 'lost', 'label' => 'Lost', 'count' => $clone()->where('bookings.status', 'lost')->count()],
            ['key' => 'cancelled', 'label' => 'Cancelled', 'count' => $clone()->where('bookings.status', 'cancelled')->count()],
            ['key' => 'at_destination', 'label' => 'At Destination', 'count' => 0],
        ];
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

    public static function statusMeta(string $status): array
    {
        return match ($status) {
            'delivered' => ['label' => 'Delivered', 'badge' => 'bg-emerald-100 text-emerald-700', 'shipment' => 'Shipment - Delivered'],
            'cancelled' => ['label' => 'Cancelled', 'badge' => 'bg-sky-100 text-sky-700', 'shipment' => 'Shipment - Cancelled'],
            'returned' => ['label' => 'Returned', 'badge' => 'bg-orange-100 text-orange-700', 'shipment' => 'Shipment - Returned'],
            'in_transit' => ['label' => 'In Transit', 'badge' => 'bg-violet-100 text-violet-700', 'shipment' => 'Shipment - In Transit'],
            'out_for_delivery' => ['label' => 'Out for Delivery', 'badge' => 'bg-indigo-100 text-indigo-700', 'shipment' => 'Shipment - Out for Delivery'],
            'dispatched' => ['label' => 'Rider Picked', 'badge' => 'bg-amber-100 text-amber-700', 'shipment' => 'Shipment - Picked'],
            'lost' => ['label' => 'Lost', 'badge' => 'bg-rose-100 text-rose-700', 'shipment' => 'Shipment - Lost'],
            default => ['label' => 'Booked', 'badge' => 'bg-slate-100 text-slate-700', 'shipment' => 'Shipment is booked.'],
        };
    }
}
