<?php

namespace App\Filament\Resources;

use App\Models\PricingPlan;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    protected static ?string $recordTitleAttribute = 'name';

    // NOTE: navigation icon/group + form() override intentionally removed because
    // Filament\Forms\Form (and some Resource static type checks) are not available
    // in this runtime environment.

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('base_delivery_charge')
                    ->label('Base Charge')
                    ->formatStateUsing(fn ($state) => 'PKR ' . number_format($state, 2))
                    ->sortable(),
                TextColumn::make('cod_commission_percent')
                    ->label('COD Commission')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),
                TextColumn::make('weight_charge_per_kg')
                    ->label('Per kg Charge')
                    ->formatStateUsing(fn ($state) => $state ? 'PKR ' . number_format($state, 2) : 'N/A'),
                TextColumn::make('fuel_surcharge_percent')
                    ->label('Fuel Surcharge')
                    ->formatStateUsing(fn ($state) => $state . '%'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label('Merchants')
                    ->counts('users')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('exportToExcel')
                        ->label('Export to Excel')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(fn ($records) => \App\Services\ExportService::export($records, 'pricing-plans')),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\PricingPlanResource\Pages\ListPricingPlans::route('/'),
            'create' => \App\Filament\Resources\PricingPlanResource\Pages\CreatePricingPlan::route('/create'),
            'edit' => \App\Filament\Resources\PricingPlanResource\Pages\EditPricingPlan::route('/{record}/edit'),
        ];
    }
}

