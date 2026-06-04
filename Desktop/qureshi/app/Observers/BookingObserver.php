<?php

namespace App\Observers;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Called before saving an update.
     * When status changes to "delivered", auto-calculate:
     *  - delivered_at timestamp
     *  - cod_commission (4% govt tax on COD)
     *  - profit (delivery_charges - courier_cost) if courier_cost is known
     */
    public function updating(Booking $booking): void
    {
        if (! $booking->isDirty('status')) {
            return;
        }

        if ($booking->status === Booking::STATUS_DELIVERED) {
            if (! $booking->delivered_at) {
                $booking->delivered_at = now();
            }

            // 4% total govt tax on COD collected
            $cod = (float) ($booking->cod_amount ?? 0);
            $booking->cod_commission = round($cod * 0.04, 2);

            // Net profit = what merchant pays us (delivery charge) minus what we pay courier
            $courierCost = (float) ($booking->courier_cost ?? 0);
            $deliveryCharges = (float) ($booking->delivery_charges ?? 0);
            if ($courierCost > 0) {
                $booking->profit = round($deliveryCharges - $courierCost, 2);
            }
        }
    }
}
