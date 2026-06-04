<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Orders extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'filament.pages.orders';

    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $title = 'Orders';
    protected static ?int $navigationSort = 2;
}
