<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\User;
use App\Models\Booking;

class PayoutService
{
    public function generatePayout(
        User $user,
        float $commissionPercent = 5.0,
        ?\DateTime $periodStart = null,
        ?\DateTime $periodEnd = null
    ): Payout {
        $periodStart = $periodStart ?? now()->startOfMonth();
        $periodEnd = $periodEnd ?? now()->endOfMonth();

        // Calculate gross amount from bookings
        $bookings = Booking::where('user_id', $user->id)
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->get();

        $grossAmount = $bookings->sum('delivery_charges');
        $commissionsDeducted = ($grossAmount * $commissionPercent) / 100;
        $netAmount = $grossAmount - $commissionsDeducted;

        $payout = Payout::create([
            'user_id' => $user->id,
            'payout_reference' => Payout::generateReference(),
            'gross_amount' => $grossAmount,
            'commissions_deducted' => $commissionsDeducted,
            'net_amount' => $netAmount,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
        ]);

        return $payout;
    }

    public function markAsPaid(Payout $payout): Payout
    {
        $payout->status = 'completed';
        $payout->paid_at = now();
        $payout->save();

        return $payout;
    }

    public function generatePayoutReport(\DateTime $startDate, \DateTime $endDate): array
    {
        $payouts = Payout::whereBetween('created_at', [$startDate, $endDate])
            ->with('user')
            ->get();

        return [
            'total_gross' => $payouts->sum('gross_amount'),
            'total_commission' => $payouts->sum('commissions_deducted'),
            'total_paid' => $payouts->sum('net_amount'),
            'pending_count' => $payouts->where('status', 'pending')->count(),
            'completed_count' => $payouts->where('status', 'completed')->count(),
            'payouts' => $payouts,
        ];
    }
}
