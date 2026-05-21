<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('consignment_no')
                    ->placeholder('-'),
                TextEntry::make('customer_name'),
                TextEntry::make('customer_phone'),
                TextEntry::make('customer_address')
                    ->columnSpanFull(),
                TextEntry::make('destination_city'),
                TextEntry::make('weight')
                    ->numeric(),
                TextEntry::make('pieces')
                    ->numeric(),
                TextEntry::make('cod_amount')
                    ->numeric(),
                TextEntry::make('delivery_charges')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('courier_integration_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
