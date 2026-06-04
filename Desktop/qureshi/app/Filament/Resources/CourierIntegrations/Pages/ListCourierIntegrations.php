<?php

namespace App\Filament\Resources\CourierIntegrations\Pages;

use App\Filament\Resources\CourierIntegrations\CourierIntegrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourierIntegrations extends ListRecords
{
    protected static string $resource = CourierIntegrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
