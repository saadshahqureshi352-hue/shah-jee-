<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('setup-filament:pages', function () {
    require base_path('setup-filament-pages.php');
})->purpose('Setup Filament Pages directories and files');
