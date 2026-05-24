<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AlertsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        // Pending pickup requests (bookings with status 'pending' and no pickup date)
        $pendingPickups = Booking::where('status', 'pending')
            ->whereNull('pickup_date')
            ->count();

        // Delayed shipments (dispatched but not delivered within 48 hours)
        $delayedShipments = Booking::whereIn('status', ['dispatched', 'in_transit'])
            ->where('created_at', '<', now()->subHours(48))
            ->count();

        // Returned shipments today
        $returnedToday = Booking::where('status', 'returned')
            ->whereDate('updated_at', today())
            ->count();

        // Pending merchant approvals
        $pendingMerchants = User::where('is_approved', false)
            ->where('id', '!=', 1)
            ->count();

        // High-value COD shipments pending delivery
        $highValuePending = Booking::whereNotIn('status', ['delivered', 'returned', 'cancelled'])
            ->where('cod_amount', '>', 50000)
            ->count();

        // Discrepancies in COD reconciliation
        $codDiscrepancies = \App\Models\CODReconciliation::where('status', 'discrepancy')->count();

        $totalAlerts = $pendingPickups + $delayedShipments + $pendingMerchants + $codDiscrepancies;
        $criticalAlerts = $delayedShipments + $codDiscrepancies;

        return [
            Stat::make('🚨 Total Alerts', $totalAlerts)
                ->description($criticalAlerts > 0 ? $criticalAlerts . ' require immediate attention' : 'All systems normal')
                ->descriptionIcon($criticalAlerts > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($criticalAlerts > 0 ? 'danger' : 'success'),

            Stat::make('📦 Pending Pickups', $pendingPickups)
                ->description('Awaiting courier pickup')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPickups > 0 ? 'warning' : 'success'),

            Stat::make('⏱️ Delayed Shipments', $delayedShipments)
                ->description('Over 48 hours in transit')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->color($delayedShipments > 0 ? 'danger' : 'success'),

            Stat::make('🔄 Returned Today', $returnedToday)
                ->description('Shipments returned today')
                ->descriptionIcon('heroicon-m-arrow-uturn-left')
                ->color($returnedToday > 0 ? 'warning' : 'success'),

            Stat::make('👤 Pending Approvals', $pendingMerchants)
                ->description('Merchants awaiting verification')
                ->descriptionIcon('heroicon-m-user-plus')
                ->color($pendingMerchants > 0 ? 'warning' : 'success'),

            Stat::make('💰 High-Value Pending', $highValuePending)
                ->description('Shipments > PKR 50,000 not delivered')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($highValuePending > 0 ? 'danger' : 'success'),
        ];
    }
}