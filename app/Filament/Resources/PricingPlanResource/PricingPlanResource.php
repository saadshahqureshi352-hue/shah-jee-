<?php

namespace App\Filament\Resources\PricingPlanResource;

use App\Models\PricingPlan;
use Filament\Resources\Resource;

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    // Layout-only / disable pages so Filament boot won't require old/broken page classes.
    public static function getPages(): array
    {
        return [];
    }
}

