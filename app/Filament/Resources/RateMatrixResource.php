<?php

namespace App\Filament\Resources;

use App\Models\RateMatrix;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rate Details')
                    ->columns(2)
                    ->schema([
                        Select::make('courier_integration_id')
                            ->label('Courier')
                            ->relationship('courierIntegration', 'courier_name')
                            ->searchable()
                            ->required(),
                        Select::make('city_zone')
                            ->label('City Zone')
                            ->options([
                                'local' => 'Local',
                                'regional' => 'Regional',
                                'national' => 'National',
                            ])
                            ->required(),
                        TextInput::make('weight_category')
                            ->label('Weight Category')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('rate')
                            ->label('Rate (PKR)')
                            ->numeric()
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
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
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
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