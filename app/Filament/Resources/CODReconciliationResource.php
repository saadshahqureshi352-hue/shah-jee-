<?php

namespace App\Filament\Resources;

use App\Models\CODReconciliation;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CODReconciliationResource\Pages\ListCODReconciliations;

class CODReconciliationResource extends Resource
{
    protected static ?string $model = CODReconciliation::class;

    protected static ?string $recordTitleAttribute = 'reconciliation_date';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-receipt-percent';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Financial Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('courier_integration_id')
                    ->label('Courier')
                    ->relationship('courierIntegration', 'courier_name')
                    ->searchable()
                    ->required(),
                DatePicker::make('reconciliation_date')
                    ->required(),
                TextInput::make('reported_cash')
                    ->numeric()
                    ->prefix('PKR')
                    ->required(),
                TextInput::make('transferred_cash')
                    ->numeric()
                    ->prefix('PKR')
                    ->required(),
                TextInput::make('total_cod_shipments')
                    ->numeric()
                    ->default(0),
                TextInput::make('successful_deliveries')
                    ->numeric()
                    ->default(0),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'discrepancy' => 'Discrepancy',
                        'resolved' => 'Resolved',
                    ])
                    ->required(),
                Textarea::make('notes')
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('courierIntegration.courier_name')
                    ->label('Courier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reconciliation_date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('reported_cash')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable(),
                TextColumn::make('transferred_cash')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable(),
                TextColumn::make('variance')
                    ->formatStateUsing(fn ($state) => $state ? 'PKR ' . number_format($state, 2) : 'PKR 0.00')
                    ->color(fn ($state) => $state == 0 ? 'success' : 'danger'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        'discrepancy' => 'danger',
                        'resolved' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'discrepancy' => 'Discrepancy',
                        'resolved' => 'Resolved',
                    ]),
                Filter::make('reconciliation_date')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['from'], fn ($q, $date) => $q->whereDate('reconciliation_date', '>=', $date))
                        ->when($data['until'], fn ($q, $date) => $q->whereDate('reconciliation_date', '<=', $date))
                    ),
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCODReconciliations::route('/'),
        ];
    }
}