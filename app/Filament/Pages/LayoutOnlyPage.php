<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LayoutOnlyPage extends Page
{


    // protected static string $view = 'filament.layout-only';



    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'layout-only';
    }
}

