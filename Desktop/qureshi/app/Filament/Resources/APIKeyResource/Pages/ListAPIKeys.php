<?php

namespace App\Filament\Resources\APIKeyResource\Pages;

use App\Filament\Resources\APIKeyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAPIKeys extends ListRecords
{
    protected static string $resource = APIKeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}