<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ProfitReport extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-chart-bar";

    protected string $view = "filament.pages.profit-report";

    protected static ?string $navigationLabel = "Profit Report";
    protected static ?string $title = "Profit Report";
    protected static ?int $navigationSort = 8;
}
