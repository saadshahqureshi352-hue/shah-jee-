<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->colors([
                'primary' => Color::Blue,
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Orange,
            ])
            ->darkMode(false)
            ->brandName('Shah Jee Courier')
            ->favicon(asset('images/logo.png'))
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(false)
            ->login()
            ->pages([
                \App\Filament\Pages\AdminDashboard::class,
                \App\Filament\Pages\Orders::class,
                \App\Filament\Pages\CodSettlement::class,
                \App\Filament\Pages\Invoices::class,
                \App\Filament\Pages\MerchantApproval::class,
                \App\Filament\Pages\Merchants::class,
                \App\Filament\Pages\Couriers::class,
                \App\Filament\Pages\CourierManagement::class,
                \App\Filament\Pages\CourierHub::class,
                \App\Filament\Pages\ShipmentManagement::class,
                \App\Filament\Pages\PricingPlans::class,
                \App\Filament\Pages\ProfitReport::class,
                \App\Filament\Pages\TaxEngine::class,
                \App\Filament\Pages\NotificationsPage::class,
                \App\Filament\Pages\SystemSettings::class,
                \App\Filament\Pages\GlobalRateSetup::class,
                \App\Filament\Pages\Financials::class,
                \App\Filament\Pages\ShipperManagement::class,
                \App\Filament\Pages\ShipperSpecificRates::class,
            ])
            ->widgets([])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

