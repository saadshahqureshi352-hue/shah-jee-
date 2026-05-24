<?php

namespace App\Filament\Resources;

use App\Models\APIKey;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\APIKeyResource\Pages\ListAPIKeys;
use App\Filament\Resources\APIKeyResource\Pages\CreateAPIKey;
use App\Filament\Resources\APIKeyResource\Pages\EditAPIKey;

class APIKeyResource extends Resource
{
    protected static ?string $model = APIKey::class;

    protected static ?string $recordTitleAttribute = 'key_name';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-key';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Courier Management';
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
                TextInput::make('key_name')
                    ->label('Key Name (e.g., "Trax Production")')
                    ->required(),
                Select::make('environment')
                    ->options([
                        'production' => 'Production',
                        'testing' => 'Testing',
                    ])
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active'),
                TextInput::make('api_key')
                    ->label('API Key')
                    ->password()
                    ->required(),
                TextInput::make('api_secret')
                    ->label('API Secret')
                    ->password(),
                TextInput::make('account_id')
                    ->label('Account ID')
                    ->password(),
                TextInput::make('account_title')
                    ->label('Account Title')
                    ->password(),
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
                TextColumn::make('key_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('environment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'production' => 'success',
                        'testing' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
                TextColumn::make('last_used_at')
                    ->label('Last Used')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('environment')
                    ->options([
                        'production' => 'Production',
                        'testing' => 'Testing',
                    ]),
                SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAPIKeys::route('/'),
            'create' => CreateAPIKey::route('/create'),
            'edit' => EditAPIKey::route('/{record}/edit'),
        ];
    }
}