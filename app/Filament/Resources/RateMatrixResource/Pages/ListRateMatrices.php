<?php

// Current canonical file. Duplicate legacy file disabled.

namespace App\Filament\Resources\RateMatrixResource\Pages;

use App\Filament\Resources\RateMatrixResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRateMatrices extends ListRecords

{
    protected static string $resource = RateMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}