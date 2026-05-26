<?php

namespace App\Providers\Filament;


use App\Filament\Widgets\StatsOverviewWidget_Enhanced;
use App\Filament\Widgets\CourierPerformanceWidget_Enhanced;
use App\Filament\Widgets\RevenueVsProfitWidget;
use App\Filament\Widgets\AlertsWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
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
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->colors([
                'primary' => Color::Amber,
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
            ->collapsibleNavigationGroups(true)
            ->navigationGroups([
                'Dashboard',
                'Merchant & User Management',
                'Courier Management',
                'Shipment Management',
                'Financials',
                'Settings',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\AdminDashboard::class,
                \App\Filament\Pages\LayoutOnlyPage::class,
                \App\Filament\Pages\Financials::class,
                \App\Filament\Pages\ShipmentManagement::class,
                \App\Filament\Pages\SystemSettings::class,
                \App\Filament\Pages\PricingPlans::class,
                \App\Filament\Pages\ShipperManagement::class,
                \App\Filament\Pages\CourierManagement::class,
                \App\Filament\Pages\NotificationsPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                StatsOverviewWidget_Enhanced::class,
                CourierPerformanceWidget_Enhanced::class,
                RevenueVsProfitWidget::class,
                AlertsWidget::class,
            ])
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