<?php

namespace App\Services;

use App\Models\Booking;

class ProfitCalculatorService
{
    /**
     * Calculate commission for a single booking
     */
    public function calculateBookingCommission(Booking $booking, float $commissionPercent = 5.0): float
    {
        return ($booking->delivery_charges * $commissionPercent) / 100;
    }

    /**
     * Calculate total profit for a date range
     */
    public function calculateProfitByDateRange(\DateTime $startDate, \DateTime $endDate, float $commissionPercent = 5.0): array
    {
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRevenue = $bookings->sum('delivery_charges');
        $totalProfit = 0;

        foreach ($bookings as $booking) {
            $totalProfit += $this->calculateBookingCommission($booking, $commissionPercent);
        }

        return [
            'total_revenue' => $totalRevenue,
            'total_profit' => $totalProfit,
            'profit_percentage' => $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0,
            'booking_count' => $bookings->count(),
            'average_profit_per_booking' => $bookings->count() > 0 ? $totalProfit / $bookings->count() : 0,
        ];
    }

    /**
     * Calculate profit for a specific merchant
     */
    public function calculateMerchantProfit(int $userId, \DateTime $startDate, \DateTime $endDate, float $commissionPercent = 5.0): array
    {
        $bookings = Booking::where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalCharges = $bookings->sum('delivery_charges');
        $totalProfit = 0;

        foreach ($bookings as $booking) {
            $totalProfit += $this->calculateBookingCommission($booking, $commissionPercent);
        }

        return [
            'user_id' => $userId,
            'total_delivery_charges' => $totalCharges,
            'total_commission' => $totalProfit,
            'net_earning' => $totalCharges - $totalProfit,
            'booking_count' => $bookings->count(),
        ];
    }

    /**
     * Get breakdown of profits by courier
     */
    public function getProfitByCourier(\DateTime $startDate, \DateTime $endDate, float $commissionPercent = 5.0): array
    {
        $bookings = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->with('courier_integration')
            ->get()
            ->groupBy('courier_integration_id');

        $breakdown = [];

        foreach ($bookings as $courierId => $courierBookings) {
            $revenue = $courierBookings->sum('delivery_charges');
            $profit = 0;
            
            foreach ($courierBookings as $booking) {
                $profit += $this->calculateBookingCommission($booking, $commissionPercent);
            }

            $breakdown[] = [
                'courier_id' => $courierId,
                'courier_name' => $courierBookings->first()?->courier_integration?->courier_name,
                'revenue' => $revenue,
                'profit' => $profit,
                'booking_count' => $courierBookings->count(),
            ];
        }

        return $breakdown;
    }
}
