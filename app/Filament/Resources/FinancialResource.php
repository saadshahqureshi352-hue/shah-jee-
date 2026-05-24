<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Resources\Resource;

class FinancialResource extends Resource
{
    protected static ?string $model = Booking::class;

    // Layout-only stub to prevent app boot crashes from broken/legacy resource wiring.
    public static function getPages(): array
    {
        return [];
    }
}

