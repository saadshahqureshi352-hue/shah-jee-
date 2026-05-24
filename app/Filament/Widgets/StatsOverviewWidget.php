<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\User;
use App\Models\CourierIntegration;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();

        $todayShipments = Booking::where('created_at', '>=', $today)->count();
        $yesterdayShipments = Booking::whereBetween('created_at', [$yesterday, $today])->count();
        $shipmentChange = $yesterdayShipments > 0 ? round((($todayShipments - $yesterdayShipments) / $yesterdayShipments) * 100, 1) : 0;

        $totalRevenue = Booking::sum('cod_amount');
        $totalCharges = Booking::sum('delivery_charges');
        $totalProfit = $totalCharges;

        $pendingPickups = Booking::where('status', 'pending')->count();
        $delayedShipments = Booking::where('status', 'dispatched')
            ->where('created_at', '<', now()->subDays(2))
            ->count();

        $activeCouriers = CourierIntegration::where('is_active', true)->count();
        $totalMerchants = User::where('is_approved', true)->count();

        return [
            Stat::make('📦 Today\'s Shipments', $todayShipments)
                ->description($shipmentChange >= 0 ? "{$shipmentChange}% increase vs yesterday" : "{$shipmentChange}% decrease vs yesterday")
                ->descriptionIcon($shipmentChange >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($shipmentChange >= 0 ? 'success' : 'danger')
                ->chart([$yesterdayShipments, $todayShipments]),

            Stat::make('💰 Total Revenue (COD)', 'Rs. ' . number_format($totalRevenue, 0))
                ->description('Across all shipments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('📊 Total Profit (Charges)', 'Rs. ' . number_format($totalProfit, 0))
                ->description('Delivery charges collected')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('🚚 Active Couriers', $activeCouriers)
                ->description("{$totalMerchants} active merchants")
                ->descriptionIcon('heroicon-m-truck')
                ->color('info'),

            Stat::make('⏳ Pending Pickups', $pendingPickups)
                ->description("{$delayedShipments} delayed shipments")
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPickups > 0 ? 'warning' : 'success'),

            Stat::make('🚨 Alerts', $delayedShipments + ($pendingPickups > 0 ? 1 : 0))
                ->description("{$delayedShipments} shipments need attention")
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($delayedShipments > 0 ? 'danger' : 'success'),
        ];
    }
}