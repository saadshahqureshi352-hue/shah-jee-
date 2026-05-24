<?php

namespace App\Services;

class CourierTrackingService
{
    /**
     * Returns timeline + human friendly status meta.
     * Currently stubbed: no real courier API calls are made.
     *
     * Accepts array|object to avoid model-instantiation issues.
     */
    public function track(array|object $booking): array
    {
        $b = is_array($booking) ? (object) $booking : $booking;

        $rawStatus = $b->status ?? null;
        $status = strtolower((string) $rawStatus);

        // Default meta
        $meta = [
            'key' => $rawStatus,
            'label' => 'In Transit',
            'badge' => 'bg-teal-100 text-teal-800 border border-teal-200',
            'shipment' => 'Your parcel is moving between locations.',
        ];

        // Map common statuses (best-effort)
        if (in_array($status, ['delivered', 'success', 'completed'], true)) {
            $meta['label'] = 'Delivered';
            $meta['badge'] = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
            $meta['shipment'] = 'Parcel has been delivered successfully.';
        } elseif (in_array($status, ['out_for_delivery', 'out for delivery', 'ofd', 'ready'], true)) {
            $meta['label'] = 'Out for Delivery';
            $meta['badge'] = 'bg-amber-100 text-amber-800 border border-amber-200';
            $meta['shipment'] = 'Courier is on the way to deliver your parcel.';
        } elseif (in_array($status, ['picked', 'pickup', 'collected'], true)) {
            $meta['label'] = 'Picked Up';
            $meta['badge'] = 'bg-indigo-100 text-indigo-800 border border-indigo-200';
            $meta['shipment'] = 'Parcel has been collected by courier.';
        } elseif (in_array($status, ['cancelled', 'rejected'], true)) {
            $meta['label'] = 'Cancelled';
            $meta['badge'] = 'bg-rose-100 text-rose-800 border border-rose-200';
            $meta['shipment'] = 'Shipment was cancelled or rejected.';
        }

        $events = [];

        // Helper: safe time formatting
        $timeString = function ($dt) {
            if (! $dt) return null;
            try {
                return $dt instanceof \DateTimeInterface
                    ? $dt->format('Y-m-d H:i:s')
                    : optional($dt)->toDateTimeString();
            } catch (\Throwable) {
                return null;
            }
        };

        // Build a timeline (best-effort using available fields)
        $events[] = [
            'title' => 'Booked',
            'detail' => 'Order is created in our system.',
            'time' => $timeString($b->created_at ?? null),
            'status' => 'done',
        ];

        if ($status === 'cancelled' || in_array($status, ['cancelled', 'rejected'], true)) {
            $events[] = [
                'title' => 'Cancelled',
                'detail' => 'No further updates expected.',
                'time' => $timeString($b->updated_at ?? null),
                'status' => 'current',
            ];
        } else {
            $events[] = [
                'title' => 'Picked/Dispatched',
                'detail' => 'Parcel left origin facility.',
                'time' => $timeString($b->updated_at ?? null),
                'status' => in_array($status, ['picked', 'pickup', 'collected'], true) ? 'current' : 'done',
            ];

            $events[] = [
                'title' => 'In Transit',
                'detail' => 'Parcel is moving toward destination.',
                'time' => null,
                'status' => in_array($status, ['in_transit', 'in transit', ''], true) ? 'current' : 'pending',
            ];

            $events[] = [
                'title' => 'Out for Delivery',
                'detail' => 'Courier will attempt delivery soon.',
                'time' => null,
                'status' => in_array($status, ['out_for_delivery', 'out for delivery', 'ofd', 'ready'], true) ? 'current' : 'pending',
            ];

            $events[] = [
                'title' => 'Delivered',
                'detail' => 'Parcel delivered to consignee.',
                'time' => null,
                'status' => in_array($status, ['delivered', 'success', 'completed'], true) ? 'current' : 'pending',
            ];
        }

        return [
            'meta' => $meta,
            'events' => $events,
        ];
    }
}
