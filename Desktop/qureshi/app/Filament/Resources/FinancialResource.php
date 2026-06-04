<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FinancialResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $recordTitleAttribute = 'consignment_no';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-banknotes';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Financials';
    }

    protected static ?string $modelLabel = 'Financial Report';

    protected static ?string $pluralModelLabel = 'Financial Reports';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('consignment_no')
                    ->label('Consignment #')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Merchant')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('courier_integration.courier_name')
                    ->label('Courier')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('cod_amount')
                    ->label('COD Amount')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label('Total COD')->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))),
                TextColumn::make('delivery_charges')
                    ->label('Charges')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Charges')->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))),
                TextColumn::make('profit')
                    ->label('Profit')
                    ->getStateUsing(fn (Booking $record): float =>
                        ($record->delivery_charges ?? 0) - (($record->cod_amount ?? 0) * 0.02)
                    )
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->sortable()
                    ->summarize(Sum::make()->label('Total Profit')->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))),
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
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'picked_up' => 'Picked Up',
                        'dispatched' => 'Dispatched',
                        'in_transit' => 'In Transit',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered',
                        'returned' => 'Returned',
                    ])
                    ->multiple(),
                SelectFilter::make('user_id')
                    ->label('Merchant')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->multiple(),
                SelectFilter::make('courier_integration_id')
                    ->label('Courier')
                    ->relationship('courier_integration', 'courier_name'),
                Filter::make('date_range')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('created_at', '<=', $date))
                    )
                    ->indicateUsing(function (array $data): ?string {
                        if (($data['from'] ?? null) && ($data['until'] ?? null)) {
                            return 'From ' . $data['from'] . ' to ' . $data['until'];
                        }
                        if ($data['from'] ?? null) {
                            return 'From ' . $data['from'];
                        }
                        if ($data['until'] ?? null) {
                            return 'Until ' . $data['until'];
                        }
                        return null;
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('success')
                        ->action(fn ($records) => \App\Services\ExportService::export($records, 'financial-reports')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\FinancialResource\Pages\ListFinancials::route('/'),
        ];
    }
}