<?php

namespace App\Filament\Resources;

use App\Models\PricingPlan;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PricingPlanResource extends Resource
{
    protected static ?string $model = PricingPlan::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-tag';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Merchant & User Management';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Plan Details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Plan Name')
                            ->required()
                            ->maxLength(100),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
                Section::make('Charges & Rates')
                    ->columns(3)
                    ->schema([
                        TextInput::make('base_delivery_charge')
                            ->label('Base Delivery Charge (PKR)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('cod_commission_percent')
                            ->label('COD Commission (%)')
                            ->numeric()
                            ->default(0)
                            ->suffix('%'),
                        TextInput::make('weight_charge_per_kg')
                            ->label('Per Kg Charge (PKR)')
                            ->numeric()
                            ->default(0),
                        TextInput::make('fuel_surcharge_percent')
                            ->label('Fuel Surcharge (%)')
                            ->numeric()
                            ->default(0)
                            ->suffix('%'),
                    ]),
            ]);
    }

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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
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