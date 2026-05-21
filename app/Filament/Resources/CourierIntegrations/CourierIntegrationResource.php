<?php

namespace App\Filament\Resources\CourierIntegrations;

use App\Filament\Resources\CourierIntegrations\Pages\CreateCourierIntegration;
use App\Filament\Resources\CourierIntegrations\Pages\EditCourierIntegration;
use App\Filament\Resources\CourierIntegrations\Pages\ListCourierIntegrations;
use App\Filament\Resources\CourierIntegrations\Schemas\CourierIntegrationForm;
use App\Filament\Resources\CourierIntegrations\Tables\CourierIntegrationsTable;
use App\Models\CourierIntegration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CourierIntegrationResource extends Resource
{
    protected static ?string $model = CourierIntegration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'TCS';

    public static function form(Schema $schema): Schema
    {
        return CourierIntegrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourierIntegrationsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourierIntegrations::route('/'),
            'create' => CreateCourierIntegration::route('/create'),
            'edit' => EditCourierIntegration::route('/{record}/edit'),
        ];
    }
}
