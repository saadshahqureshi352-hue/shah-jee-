<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\PricingPlan;
use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Page;

class Merchants extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-users";

    protected string $view = "filament.pages.merchants";

    protected static ?string $navigationLabel = "Merchants";
    protected static ?string $title = "Merchants";
    protected static ?int $navigationSort = 5;

    public function getViewData(): array
    {
        $now = Carbon::now();
        $request = request();

        // Date range handling
        $period = $request->get('period', 'all');
        $fromDate = $request->get('from');
        $toDate = $request->get('to');

        $dateRange = match ($period) {
            'today' => [$now->copy()->startOfDay(), $now->copy()->endOfDay()],
            'yesterday' => [$now->copy()->subDay()->startOfDay(), $now->copy()->subDay()->endOfDay()],
            '3days' => [$now->copy()->subDays(2)->startOfDay(), $now->copy()->endOfDay()],
            'week' => [$now->copy()->startOfWeek()->startOfDay(), $now->copy()->endOfWeek()->endOfDay()],
            'month' => [$now->copy()->startOfMonth()->startOfDay(), $now->copy()->endOfMonth()->endOfDay()],
            'date_to_date' => [
                $fromDate ? Carbon::parse($fromDate)->startOfDay() : $now->copy()->startOfMonth()->startOfDay(),
                $toDate ? Carbon::parse($toDate)->endOfDay() : $now->copy()->endOfDay(),
            ],
            default => [null, null],
        };

        // ==================== PENDING APPROVAL ====================
        $pendingMerchants = User::where('role', 'merchant')
            ->where('is_approved', false)
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'name' => $m->brand_name ?? $m->name,
                    'business_type' => $m->business_type ?? 'N/A',
                    'city' => $m->city ?? 'N/A',
                    'plan' => $m->pricingPlan->name ?? 'Basic',
                    'plan_id' => $m->pricing_plan_id,
                    'joined' => $m->created_at->format('d M'),
                    'phone' => $m->phone ?? 'N/A',
                    'email' => $m->email,
                ];
            });

        // ==================== ACTIVE MERCHANTS ====================
        $activeQuery = User::where('role', 'merchant')
            ->where('is_approved', true);

        $applyDateFilter = function ($query) use ($dateRange) {
            if ($dateRange[0] && $dateRange[1]) {
                return $query->whereBetween('users.created_at', $dateRange);
            }

            return $query;
        };

        $activeMerchants = $applyDateFilter($activeQuery)
            ->get()
            ->map(function ($m) {
                $query = Booking::where('user_id', $m->id);

                $deliveredQuery = (clone $query)->where('status', Booking::STATUS_DELIVERED);
                $dispatchedQuery = (clone $query)->where('status', Booking::STATUS_DISPATCHED);
                $returnedQuery = (clone $query)->whereIn('status', [
                    Booking::STATUS_RETURNED,
                    Booking::STATUS_RETURN_CONFIRMED,
                    Booking::STATUS_READY_TO_RETURN,
                ]);

                $totalCod = $deliveredQuery->sum('cod_amount');
                $deliveryCharges = $deliveredQuery->sum('delivery_charges');
                $tax4 = round($totalCod * 0.04);
                $netPayable = round($totalCod - $deliveryCharges - $tax4, 2);

                $customReturnCharge = $m->custom_return_charge ?? $m->pricingPlan->return_charge ?? 200;

                return [
                    'id' => $m->id,
                    'name' => $m->brand_name ?? $m->name,
                    'plan' => $m->pricingPlan->name ?? 'Basic',
                    'plan_id' => $m->pricing_plan_id,
                    'dispatched' => $dispatchedQuery->count(),
                    'delivered' => $deliveredQuery->count(),
                    'returned' => $returnedQuery->count(),
                    'total_cod' => $totalCod,
                    'delivery_charges' => $deliveryCharges,
                    'tax_4percent' => $tax4,
                    'net_payable' => $netPayable,
                    'status' => $m->account_status ?? 'active',
                    'suspended' => ($m->account_status ?? 'active') === 'suspended',
                    'phone' => $m->phone ?? 'N/A',
                    'custom_return_charge' => $customReturnCharge,
                    'standard_return_rate' => $m->pricingPlan->return_charge ?? 200,
                ];
            });

        // ==================== PRICING PLANS WITH COUNTS ====================
        $pricingPlans = PricingPlan::where('is_active', true)
            ->get()
            ->map(function ($plan) {
                $count = User::where('role', 'merchant')
                    ->where('is_approved', true)
                    ->where('pricing_plan_id', $plan->id)
                    ->count();

                return [
                    'id' => $plan->id,
                    'name' => $plan->name,
                    'description' => $plan->description,
                    'diff_city_rate' => $plan->different_city_delivery ?? $plan->base_delivery_charge,
                    'same_city_rate' => $plan->same_city_delivery,
                    'return_rate' => $plan->return_charge,
                    'additional_kg_rate' => $plan->additional_kg_rate,
                    'merchant_count' => $count,
                ];
            });

        // ==================== MERCHANTS BY PLAN TAB ====================
        $merchantsByPlan = $activeMerchants
            ->groupBy('plan')
            ->map(fn($group) => $group->values())
            ->toArray();

        return [
            'pendingMerchants' => $pendingMerchants,
            'pendingCount' => $pendingMerchants->count(),
            'activeMerchants' => $activeMerchants,
            'activeCount' => $activeMerchants->count(),
            'pricingPlans' => $pricingPlans,
            'merchantsByPlan' => $merchantsByPlan,
            'currentPeriod' => $period,
            'dateFrom' => $dateRange[0]?->format('Y-m-d'),
            'dateTo' => $dateRange[1]?->format('Y-m-d'),
        ];
    }
}

