<?php

namespace App\Http\Controllers;

use App\Services\CourierTrackingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TrackingPublicController extends Controller
{
    public function __construct(private readonly CourierTrackingService $courierTrackingService)
    {
    }

    /**
     * Public tracking page - supports both /track/{tracking_number} and /track?q=SEARCH
     * No login required - works for both shippers and customers.
     */
    public function show(?string $tracking_number = null)
    {
        // If query param 'q' is provided, use that
        if (! $tracking_number && request()->has('q')) {
            $tracking_number = trim(request()->input('q'));
        }

        $tracking_number = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $tracking_number);

        $order = null;
        $events = [];
        $meta = [];

        if ($tracking_number !== '') {
            $order = DB::table('bookings')
                ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
                ->where('bookings.tracking_number', $tracking_number)
                ->select('bookings.*', 'courier_integrations.courier_name')
                ->first();

            if ($order) {
                $tracking = $this->courierTrackingService->track((array) $order);
                $events = $tracking['events'] ?? [];
                $meta = $tracking['meta'] ?? [];
            }
        }

        return view('track.public-show', compact('order', 'tracking_number', 'events', 'meta'));
    }

    /**
     * JSON endpoint for the tracking UI (auto fetch).
     * GET /track/api/{tracking_number}
     */
    public function api(string $tracking_number): JsonResponse
    {
        $tracking_number = preg_replace('/[^A-Za-z0-9_-]/', '', $tracking_number);

        $order = DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.tracking_number', $tracking_number)
            ->select('bookings.*', 'courier_integrations.courier_name')
            ->first();

        if (! $order) {
            return response()->json([
                'ok' => false,
                'message' => 'Tracking number not found.',
            ], 404);
        }

        // Pass as array|object so service stays robust without model-instantiation issues.
        $booking = (array) $order;

        $tracking = $this->courierTrackingService->track($booking);

        return response()->json([
            'ok' => true,
            'tracking_number' => $tracking_number,
            'order' => [
                'tracking_number' => $order->tracking_number,
                'reference_no' => $order->reference_no ?? null,
                'courier_name' => $order->courier_name ?? null,
                'customer_name' => $order->customer_name ?? null,
                'destination_city' => $order->destination_city ?? null,
                'cod_amount' => $order->cod_amount ?? null,
                'status' => $order->status ?? null,
                'created_at' => optional($order->created_at)->toDateTimeString(),
                'updated_at' => optional($order->updated_at)->toDateTimeString(),
            ],
            'meta' => $tracking['meta'],
            'events' => $tracking['events'],
        ]);
    }
}
