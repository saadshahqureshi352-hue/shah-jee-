<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class Invoices extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-duplicate';

    protected string $view = 'filament.pages.invoices';

    protected static ?string $navigationLabel = 'Invoices';
    protected static ?string $title = 'Invoices';
    protected static ?int $navigationSort = 4;
}
