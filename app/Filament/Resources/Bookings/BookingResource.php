<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Schema;

class BookingForm
{
    public static function configure($schema)
    {
        return [
            // 🔥 Automatically current login user ki ID backend par attach karega
            Hidden::make('user_id')
                ->default(fn () => auth()->id())
                ->required(),

            TextInput::make('customer_name')
                ->label('Customer Name')
                ->required()
                ->maxLength(255),

            TextInput::make('customer_phone')
                ->label('Customer Phone')
                ->tel()
                ->required()
                ->maxLength(255),

            TextInput::make('destination_city')
                ->label('Destination City')
                ->required()
                ->maxLength(255),

            Textarea::make('customer_address')
                ->label('Customer Address')
                ->required()
                ->rows(3),

            TextInput::make('weight')
                ->label('Weight (kg)')
                ->numeric()
                ->default(0.5)
                ->required(),

            TextInput::make('cod_amount')
                ->label('COD Amount (Rs.)')
                ->numeric()
                ->required(),

            TextInput::make('delivery_charges')
                ->label('Delivery Charges')
                ->numeric()
                ->default(200.00)
                ->required(),

            Select::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'dispatched' => 'Dispatched',
                    'delivered' => 'Delivered',
                    'returned' => 'Returned',
                ])
                ->default('pending')
                ->required(),

            Select::make('courier_integration_id')
                ->label('Courier Partner')
                ->relationship('courier_integration', 'courier_name')
                ->nullable(),
        ];
    }
}