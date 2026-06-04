<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TaxEngine extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-calculator";

    protected string $view = "filament.pages.tax-engine";

    protected static ?string $navigationLabel = "Tax Engine";
    protected static ?string $title = "Tax Engine";
    protected static ?int $navigationSort = 9;
}
