<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\CourierIntegration;
use App\Models\PricingPlan;
use App\Models\RateMatrix;
use Illuminate\Support\Collection;

class ActiveCourierService
{
    /**
     * Admin panel ke Couriers page ke liye required fields prepare karta hai.
     *
     * View expects:
     *  - name
     *  - on
     *  - cRate (Courier rate)
     *  - mRate (Merchant rate baseline = Basic Plan)
     *  - dispatched (system-wide dispatched count for this courier)
     */
    public function getActiveCouriers(): Collection
    {
        // Client portal wali mirroring: courier integration ka active status exactly same.
        $couriers = CourierIntegration::query()
            ->orderBy('courier_name')
            ->get();

        $basicPlan = PricingPlan::query()
            ->whereRaw('LOWER(name) = ?', ['basic plan'])
            ->orWhereRaw('LOWER(name) = ?', ['basic'])
            ->orWhereRaw('LOWER(name) = ?', ['base'])
            ->orWhereRaw('LOWER(name) = ?', ['default'])
            ->first();

        // If Basic Plan missing, fallback to any active plan (safe default for admin UI).
        if (! $basicPlan) {
            $basicPlan = PricingPlan::query()->where('is_active', true)->first();
        }

        // Courier rate baseline: RateMatrix se default/first active combination.
        // Note: ye admin layout baseline view ke liye intended hai (aapke spec ke hisaab se Basic plan default).
        $defaultCourierRateByCourierId = $this->getBaselineCourierRates();

        // Dispatched counts: bookings me system-wide total.
        $dispatchedByCourierId = Booking::query()
            ->selectRaw('courier_integration_id, count(*) as dispatched')
            ->where('status', Booking::STATUS_DISPATCHED)
            ->groupBy('courier_integration_id')
            ->pluck('dispatched', 'courier_integration_id');

        return $couriers->map(function (CourierIntegration $c) use ($basicPlan, $defaultCourierRateByCourierId, $dispatchedByCourierId) {
            $cRate = (float) ($defaultCourierRateByCourierId[$c->id] ?? 0);

            // Baseline merchant rate logic:
            // Merchant Rate = Courier Rate + Basic Plan base charge + (cod/fuel adjustments are handled in booking engine,
            // admin page baseline needs just the baseline difference as per your screenshot-driven requirement).
            $mRate = $cRate;
            if ($basicPlan) {
                // Basic plan uses base_delivery_charge as baseline.
                $mRate = $cRate + (float) $basicPlan->base_delivery_charge;
            }

            $dispatched = (int) ($dispatchedByCourierId[$c->id] ?? 0);

            return [
                'id' => $c->id,
                'name' => $c->courier_name,
                'on' => (bool) $c->is_active,
                'cRate' => $cRate,
                'mRate' => $mRate,
                'dispatched' => $dispatched,
            ];
        });
    }

    /**
     * RateMatrix/CourierRateMatrix se baseline courier rate fetch karein.
     * Abhi repo me courier-specific matrices (courier_cost/shipper_charge) to bookings me stored milta hai,
     * lekin admin baseline UI ke liye courier rate token baseline chahiye.
     */
    private function getBaselineCourierRates(): array
    {
        // Best-effort baseline: active RateMatrix first match by courier.
        // (If your admin requires a specific city/weight category, we can refine this selection once those inputs are confirmed.)
        $rows = RateMatrix::query()
            ->where('is_active', true)
            ->get()
            ->groupBy('courier_integration_id');

        $out = [];
        foreach ($rows as $courierId => $group) {
            $out[$courierId] = (float) $group->first()->rate;
        }

        return $out;
    }
}

