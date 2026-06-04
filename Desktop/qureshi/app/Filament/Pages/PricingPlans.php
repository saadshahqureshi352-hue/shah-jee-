<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PricingPlans extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = "heroicon-o-tag";

    protected string $view = "filament.pages.pricing-plans";

    protected static ?string $navigationLabel = "Pricing Plans";
    protected static ?string $title = "Pricing Plans";
    protected static ?int $navigationSort = 7;
}
