<?php

namespace App\Filament\Resources\CourierIntegrations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourierIntegrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('courier_name')
                    ->required(),
                TextInput::make('api_key'),
                TextInput::make('api_secret'),
                TextInput::make('account_number'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
