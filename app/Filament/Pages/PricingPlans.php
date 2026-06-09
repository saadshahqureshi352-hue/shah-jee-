<?php

namespace App\Filament\Pages;

use App\Models\PricingPlan;
use App\Models\User;
use Filament\Pages\Page;

class PricingPlans extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-tag";
    protected string $view = "filament.pages.pricing-plans";
    protected static ?string $navigationLabel = "Pricing Plans";
    protected static ?string $title = "Pricing Plans";
    protected static ?int $navigationSort = 7;

    public function getViewData(): array
    {
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
                    'diff_city_rate' => (float) ($plan->different_city_delivery ?? $plan->base_delivery_charge ?? 0),
                    'same_city_rate' => (float) ($plan->same_city_delivery ?? 0),
                    'return_rate' => (float) ($plan->return_charge ?? 0),
                    'additional_kg_rate' => (float) ($plan->additional_kg_rate ?? 0),
                    'merchant_count' => $count,
                ];
            });

        return [
            'pricingPlans' => $pricingPlans,
            'totalMerchants' => User::where('role', 'merchant')->where('is_approved', true)->count(),
        ];
    }
}
