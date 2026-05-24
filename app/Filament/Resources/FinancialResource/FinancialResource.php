<?php

namespace App\Filament\Resources\FinancialResource;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class FinancialResource extends Resource
{
    protected static ?string $model = Booking::class;

    // Temporarily omit navigationIcon due to type incompatibility in this environment.


    // Temporarily omit navigationGroup due to Filament type incompatibilities.


    protected static ?string $recordTitleAttribute = 'consignment_no';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('tracking_number')->searchable()->label('Tracking #'),
                TextColumn::make('user.name')->label('Merchant')->searchable()->sortable(),
                TextColumn::make('cod_amount')->money('PKR')->sortable()->label('COD Amount'),
                TextColumn::make('delivery_charges')->money('PKR')->sortable()->label('Delivery Charges'),
                TextColumn::make('profit')
                    ->label('Profit')
                    ->getStateUsing(fn (Booking $record): float => (float) $record->delivery_charges)
                    ->money('PKR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivered' => 'success',
                        'returned' => 'danger',
                        'pending' => 'warning',
                        'dispatched' => 'info',
                        'in_transit' => 'info',
                        'out_for_delivery' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('courier_integration.courier_name')->label('Courier'),
                TextColumn::make('created_at')->dateTime()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'dispatched' => 'Dispatched',
                        'in_transit' => 'In Transit',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered',
                        'returned' => 'Returned',
                    ]),
                SelectFilter::make('user_id')
                    ->label('Merchant')
                    ->relationship('user', 'name'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\FinancialResource\Pages\ListFinancials::route('/'),
        ];
    }
}

