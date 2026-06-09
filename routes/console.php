<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('setup-filament:pages', function () {
    require base_path('setup-filament-pages.php');
})->purpose('Setup Filament Pages directories and files');

// ==================== DAILY INVOICE SCHEDULER ====================
// This runs every day at 11:59 PM to generate daily invoices for all merchants
Schedule::command('generate:daily-invoices')
    ->dailyAt('23:59')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/daily-invoices.log'));

// Alternative: Run every minute for immediate invoice generation (for testing)
// Schedule::command('generate:daily-invoices')->everyMinute()->withoutOverlapping();
