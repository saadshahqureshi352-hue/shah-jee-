<?php

// Legacy file intentionally disabled to avoid duplicate class declaration.
// This file is a legacy duplicate and should never be loaded.
// Keep PHP-valid structure and prevent execution.

return;




use App\Filament\Resources\RateMatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRateMatrices extends ListRecords
{
    protected static string $resource = RateMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
