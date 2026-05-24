<?php

namespace App\Filament\Resources;

use App\Models\RateMatrix;
use Filament\Resources\Resource;

use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Notifications\Notification;

class RateMatrixResource extends Resource
{
    protected static ?string $model = RateMatrix::class;

    protected static ?string $recordTitleAttribute = 'weight_category';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-table-cells';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Courier Management';
    }

    // form() override removed because Filament\Forms\Form is not available in this runtime.


    // (original schema removed)

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('courierIntegration.courier_name')
                    ->label('Courier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city_zone')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'local' => 'success',
                        'regional' => 'warning',
                        'national' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('weight_category')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rate')
                    ->label('Rate')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('courier_integration_id')
                    ->label('Courier')
                    ->relationship('courierIntegration', 'courier_name'),
                SelectFilter::make('city_zone')
                    ->options([
                        'local' => 'Local',
                        'regional' => 'Regional',
                        'national' => 'National',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn ($records) => \App\Services\ExportService::export($records, 'rate-matrix')),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\RateMatrixResource\Pages\ListRateMatrices::route('/'),
            'create' => \App\Filament\Resources\RateMatrixResource\Pages\CreateRateMatrix::route('/create'),
            'edit' => \App\Filament\Resources\RateMatrixResource\Pages\EditRateMatrix::route('/{record}/edit'),
        ];
    }
}