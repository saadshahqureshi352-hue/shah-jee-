<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Couriers extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-truck";

    protected string $view = "filament.pages.couriers";

    protected static ?string $navigationLabel = "Couriers";
    protected static ?string $title = "Couriers";
    protected static ?int $navigationSort = 6;
}
