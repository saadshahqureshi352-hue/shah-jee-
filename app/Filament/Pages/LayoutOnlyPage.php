<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LayoutOnlyPage extends Page
{


    protected static bool $shouldRegisterNavigation = false;



    public static function getSlug(?\Filament\Panel $panel = null): string
    {
        return 'layout-only';
    }
}

