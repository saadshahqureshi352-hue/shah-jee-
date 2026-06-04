<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CodSettlement extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-currency-dollar';

    protected string $view = 'filament.pages.cod-settlement';

    protected static ?string $navigationLabel = 'COD & Settlement';
    protected static ?string $title = 'COD & Settlement';
    protected static ?int $navigationSort = 3;
}
