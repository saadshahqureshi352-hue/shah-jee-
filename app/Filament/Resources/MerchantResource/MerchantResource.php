<?php

namespace App\Filament\Resources\MerchantResource;

use App\Models\User;
use Filament\Resources\Resource;

class MerchantResource extends Resource
{
    protected static ?string $model = User::class;

    // Layout-only: disable pages so this resource does not load backend pages in this environment.
    public static function getPages(): array
    {
        return [];
    }
}

