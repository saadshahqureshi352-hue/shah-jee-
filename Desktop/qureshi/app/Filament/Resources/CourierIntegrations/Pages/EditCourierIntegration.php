<?php

namespace App\Filament\Resources\CourierIntegrations\Pages;

use App\Filament\Resources\CourierIntegrations\CourierIntegrationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCourierIntegration extends EditRecord
{
    protected static string $resource = CourierIntegrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
