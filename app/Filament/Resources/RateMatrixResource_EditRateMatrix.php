<?php

namespace App\Filament\Resources\RateMatrixResource\Pages;

use App\Filament\Resources\RateMatrixResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRateMatrix extends EditRecord
{
    protected static string $resource = RateMatrixResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
