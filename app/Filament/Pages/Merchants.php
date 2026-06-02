<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Merchants extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-users";

    protected string $view = "filament.pages.merchants";

    protected static ?string $navigationLabel = "Merchants";
    protected static ?string $title = "Merchants";
    protected static ?int $navigationSort = 5;
}
