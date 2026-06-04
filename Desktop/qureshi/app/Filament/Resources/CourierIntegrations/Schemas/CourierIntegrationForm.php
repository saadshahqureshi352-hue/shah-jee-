<?php

namespace App\Filament\Resources\CourierIntegrations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Schemas\Schema;

class CourierIntegrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Courier Information')
                    ->description('Basic courier details and API credentials')
                    ->columns(2)
                    ->schema([
                        TextInput::make('courier_name')
                            ->required()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label('Service Active')
                            ->helperText('Disable to hide this courier at checkout')
                            ->required(),
                    ]),
                Section::make('API Credentials')
                    ->description('API keys and account information for courier integration')
                    ->columns(2)
                    ->schema([
                        TextInput::make('api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('api_secret')
                            ->label('Secret Key')
                            ->password()
                            ->revealable(),
                        TextInput::make('account_number')
                            ->label('Account ID / Number'),
                    ]),
                Section::make('Rate Matrix')
                    ->description('Set delivery rates based on weight and zone')
                    ->schema([
                        Repeater::make('rateMatrices')
                            ->relationship('rateMatrices')
                            ->schema([
                                Select::make('weight_category')
                                    ->options([
                                        '0-0.5kg' => '0 - 0.5 kg',
                                        '0.5-1kg' => '0.5 - 1 kg',
                                        '1-2kg' => '1 - 2 kg',
                                        '2-5kg' => '2 - 5 kg',
                                        '5-10kg' => '5 - 10 kg',
                                        '10kg+' => '10 kg+',
                                    ])
                                    ->required(),
                                TextInput::make('weight_from')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('weight_to')
                                    ->numeric()
                                    ->required(),
                                Select::make('zone')
                                    ->options([
                                        'local' => 'Local',
                                        'regional' => 'Regional',
                                        'national' => 'National',
                                    ]),
                                TextInput::make('rate')
                                    ->label('Delivery Rate (Rs.)')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('cod_commission_percent')
                                    ->label('COD Commission (%)')
                                    ->numeric()
                                    ->suffix('%'),
                                TextInput::make('fuel_surcharge_percent')
                                    ->label('Fuel Surcharge (%)')
                                    ->numeric()
                                    ->suffix('%'),
                                Toggle::make('is_active')
                                    ->label('Active'),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->collapsible(),
                    ]),
            ]);
    }
}