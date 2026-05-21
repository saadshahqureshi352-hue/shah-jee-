<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public const COURIERS = [
        'trax' => [
            'name' => 'Trax',
            'tagline' => 'Express delivery',
            'bg' => 'bg-emerald-500',
            'text' => 'text-emerald-600',
        ],
        'leopards' => [
            'name' => 'Leopards',
            'tagline' => 'Nation-wide coverage',
            'bg' => 'bg-yellow-400',
            'text' => 'text-yellow-700',
        ],
        'barqraftar' => [
            'name' => 'BarqRaftar',
            'tagline' => 'Fast & reliable',
            'bg' => 'bg-amber-500',
            'text' => 'text-amber-700',
        ],
        'mnp' => [
            'name' => 'M&P',
            'tagline' => 'Premium logistics',
            'bg' => 'bg-red-500',
            'text' => 'text-red-600',
        ],
        'tcs' => [
            'name' => 'TCS',
            'tagline' => 'Courier service',
            'bg' => 'bg-rose-600',
            'text' => 'text-rose-700',
        ],
    ];

    public function index()
    {
        $bq = DB::table('bookings')->where('user_id', auth()->id());

        $totalBookings = (clone $bq)->count();
        $totalRevenue = (float) (clone $bq)->where('status', 'delivered')->sum('cod_amount');

        $statusCounts = [
            'booked' => (clone $bq)->where('status', 'pending')->count(),
            'in_progress' => (clone $bq)->where('status', 'dispatched')->count(),
            'delivered' => (clone $bq)->where('status', 'delivered')->count(),
            'cancelled' => 0,
            'issued' => 0,
            'returned' => (clone $bq)->where('status', 'returned')->count(),
            'lost' => 0,
            'reattempt' => 0,
        ];

        $orderStats = collect($statusCounts)->mapWithKeys(function ($count, $key) use ($totalBookings) {
            return [$key => [
                'count' => $count,
                'percent' => $totalBookings > 0 ? (int) round(($count / $totalBookings) * 100) : 0,
            ]];
        })->all();

        $completedPercent = $totalBookings > 0
            ? (int) round(($statusCounts['delivered'] / $totalBookings) * 100)
            : 0;

        $pendingPercent = max(0, 100 - $completedPercent);

        $recentShipments = (clone $bq)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->values()
            ->map(function ($shipment, $index) use ($totalBookings) {
                $shipment->display_serial = $totalBookings - $index;
                $shipment->time_label = $shipment->created_at
                    ? Carbon::parse($shipment->created_at)->diffInDays(now()).'d'
                    : '—';

                return $shipment;
            });

        $balanceCards = [
            'balance_added' => 0,
            'not_approved' => 0,
            'deducted' => 0,
            'balance' => 0,
        ];

        return view('dashboard', compact(
            'totalBookings',
            'totalRevenue',
            'orderStats',
            'completedPercent',
            'pendingPercent',
            'recentShipments',
            'balanceCards',
        ));
    }

    public function create(Request $request)
    {
        $slug = $request->query('courier');

        if (! $slug || ! isset(self::COURIERS[$slug])) {
            return view('bookings.select-courier', [
                'couriers' => self::COURIERS,
            ]);
        }

        $courier = array_merge(self::COURIERS[$slug], [
            'slug' => $slug,
            'integration_id' => $this->resolveCourierIntegrationId(self::COURIERS[$slug]['name']),
        ]);

        $pickupAddresses = DB::table('pickup_addresses')
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->orderByDesc('created_at')
            ->get();

        $cities = collect(config('pakistan_cities', []))
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('bookings.form', compact('courier', 'pickupAddresses', 'cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'courier_slug' => 'required|string',
            'destination_city' => 'required|string|max:100',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'complete_address' => 'required|string|max:500',
            'quantity' => 'required|integer|min:1|max:99',
            'weight' => 'required|numeric|min:0.1',
            'cod_amount' => 'required|numeric|min:0',
            'product_name' => 'required|string|max:255',
            'service_type' => 'required|in:Overnight,Detain,Overland',
            'pickup_address_id' => 'nullable|exists:pickup_addresses,id',
            'delivery_charges' => 'required|numeric|min:0',
        ]);

        if (! isset(self::COURIERS[$request->courier_slug])) {
            return $this->bookingResponse($request, false, errors: ['courier_slug' => ['Invalid courier selected.']]);
        }

        $courierName = self::COURIERS[$request->courier_slug]['name'];
        $integrationId = $this->resolveCourierIntegrationId($courierName);

        $expectedCharges = self::calculateDeliveryCharges(
            (float) $request->weight,
            $request->service_type,
            $request->courier_slug
        );

        $deliveryCharges = (float) $request->delivery_charges;
        if (abs($deliveryCharges - $expectedCharges) > 1) {
            $deliveryCharges = $expectedCharges;
        }

        $pickup = null;
        if ($request->pickup_address_id) {
            $pickup = DB::table('pickup_addresses')
                ->where('id', $request->pickup_address_id)
                ->where('user_id', auth()->id())
                ->first();
        }

        $id = DB::table('bookings')->insertGetId([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'second_phone' => $request->second_phone,
            'destination_city' => $request->destination_city,
            'consignee_address' => $request->complete_address,
            'origin_city' => $pickup?->city,
            'cod_amount' => $request->cod_amount,
            'is_cod' => ! $request->boolean('is_non_cod'),
            'delivery_charges' => $deliveryCharges,
            'weight' => $request->weight,
            'quantity' => $request->quantity,
            'reference_no' => $request->reference_no,
            'product_name' => $request->product_name,
            'special_instructions' => $request->special_instructions ?? 'Handle with Care',
            'courier_integration_id' => $integrationId,
            'pickup_address_id' => $request->pickup_address_id,
            'status' => 'pending',
            'service_type' => $request->service_type,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $tracking = 'SJC'.str_pad((string) $id, 10, '0', STR_PAD_LEFT);

        DB::table('bookings')->where('id', $id)->update([
            'tracking_number' => $tracking,
        ]);

        return $this->bookingResponse($request, true, [
            'booking_id' => $id,
            'tracking_number' => $tracking,
            'courier_name' => $courierName,
            'slip_url' => route('bookings.slip', $id),
            'new_booking_url' => route('bookings.create', ['courier' => $request->courier_slug]),
        ]);
    }

    public function slip(int $booking)
    {
        $order = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.id', $booking)
            ->where('bookings.user_id', auth()->id())
            ->select('bookings.*', 'courier_integrations.courier_name')
            ->first();

        abort_unless($order, 404);

        return view('bookings.slip', compact('order'));
    }

    public function loadsheets(Request $request)
    {
        $courierSlug = $request->query('courier');
        $courier = self::COURIERS[$courierSlug] ?? null;

        if (!$courier) {
            return redirect()->route('bookings')->withErrors('Invalid courier selected.');
        }

        return view('bookings.loadsheets', compact('courier', 'courierSlug'));
    }

    public static function calculateDeliveryCharges(float $weight, string $serviceType, string $courierSlug): float
    {
        $base = match ($courierSlug) {
            'tcs', 'mnp' => 200,
            'leopards' => 180,
            default => 160,
        };

        $perKg = 55;
        $multiplier = match ($serviceType) {
            'Overnight' => 1.25,
            'Detain' => 1.0,
            'Overland' => 0.9,
            default => 1.0,
        };

        return round(($base + ($weight * $perKg)) * $multiplier);
    }

    private function bookingResponse(Request $request, bool $success, array $data = [], array $errors = [])
    {
        if ($request->expectsJson() || $request->ajax()) {
            if (! $success) {
                return response()->json(['message' => 'Validation failed', 'errors' => $errors], 422);
            }

            return response()->json(['success' => true, ...$data]);
        }

        if (! $success) {
            return back()->withErrors($errors)->withInput();
        }

        return redirect()->route('bookings')->with('success', 'Packet booked successfully.');
    }

    private function resolveCourierIntegrationId(string $name): int
    {
        $row = DB::table('courier_integrations')->where('courier_name', $name)->first();

        if ($row) {
            return (int) $row->id;
        }

        return (int) DB::table('courier_integrations')->insertGetId([
            'courier_name' => $name,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // =========================================================================
    // === BULK PRINT SYSTEM METHODS ===
    // =========================================================================

    // Modal ke andar database se filtered data load karne ke liye
    public function getBulkOrders(Request $request) 
    {
        // Security Check: Sirf current logged-in user ke apne 'pending' bookings aayein
        $query = DB::table('bookings')
            ->where('user_id', auth()->id())
            ->where('status', 'pending');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('customer_name', 'LIKE', "%{$search}%")
                  ->orWhere('destination_city', 'LIKE', "%{$search}%")
                  ->orWhere('tracking_number', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['orders' => $orders]);
    }

    // Multiples labels ko akatha ek hi page par print karne ka layout
    public function bulkPrintLabels(Request $request) 
    {
        if (!$request->has('ids') || empty($request->query('ids'))) {
            abort(400, 'No order IDs provided for bulk printing.');
        }

        $ids = explode(',', $request->query('ids'));
        
        // Database se un sabhi selected IDs ka data uthayein (aur confirm karein ke yeh isi user ki hain)
        $bookings = DB::table('bookings')
            ->whereIn('id', $ids)
            ->where('user_id', auth()->id())
            ->get();
        
        // Ek clean simple layout return karein jo automatically print command trigger kare
        return view('bookings.bulk_slips_print', compact('bookings'));
    }
}