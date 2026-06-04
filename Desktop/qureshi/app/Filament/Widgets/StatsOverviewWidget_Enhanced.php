<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget_Enhanced extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $table = 'bookings';

        $todayCount = DB::table($table)->whereDate('created_at', Carbon::today())->count();
        $yesterdayCount = DB::table($table)->whereDate('created_at', Carbon::yesterday())->count();
        $diff = $todayCount - $yesterdayCount;

        $todayRevenue = DB::table($table)->whereDate('created_at', Carbon::today())->sum('charged_amount') ?? 0;
        $yesterdayRevenue = DB::table($table)->whereDate('created_at', Carbon::yesterday())->sum('charged_amount') ?? 0;
        $revenueDiff = $yesterdayRevenue > 0 ? round((($todayRevenue - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) : 0;

        $todayProfit = DB::table($table)->whereDate('created_at', Carbon::today())->sum('profit') ?? 0;
        
        $pendingPickups = DB::table($table)->where('status', 'pending')->count();
        
        $deliveredToday = DB::table($table)->whereDate('created_at', Carbon::today())->where('status', 'delivered')->count();
        $totalToday = $todayCount > 0 ? round(($deliveredToday / $todayCount) * 100, 1) : 0;

        $activeMerchants = DB::table('users')->where('status', 'active')->count();
        $totalCouriers = DB::table('courier_integrations')->where('is_active', 1)->count();
        $pendingApprovals = DB::table('users')->where('is_approved', 0)->count();

        $delayedShipments = DB::table($table)
            ->whereIn('status', ['pending', 'picked_up'])
            ->where('created_at', '<', Carbon::now()->subDays(2))
            ->count();

        return [
            Stat::make('📦 Today\'s Shipments', $todayCount)
                ->description($diff >= 0 ? "+{$diff} vs yesterday ↑" : "{$diff} vs yesterday ↓")
                ->descriptionIcon($diff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($diff >= 0 ? 'success' : 'danger')
                ->url(fn () => url('/admin/bookings')),

            Stat::make('💰 Today\'s Revenue', 'PKR ' . number_format($todayRevenue, 0))
                ->description($revenueDiff >= 0 ? "+{$revenueDiff}% vs yesterday" : "{$revenueDiff}% vs yesterday")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($revenueDiff >= 0 ? 'success' : 'warning'),

            Stat::make('📈 Net Profit Today', 'PKR ' . number_format($todayProfit, 0))
                ->description('After courier & COD costs')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('🚚 Delivery Rate', $totalToday . '%')
                ->description($deliveredToday . ' of ' . $todayCount . ' delivered')
                ->descriptionIcon('heroicon-m-truck')
                ->color($totalToday >= 80 ? 'success' : ($totalToday >= 50 ? 'warning' : 'danger')),

            Stat::make('⏰ Pending Pickups', $pendingPickups)
                ->description($delayedShipments > 0 ? "{$delayedShipments} delayed > 2 days!" : 'All on schedule')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingPickups > 10 ? 'danger' : ($pendingPickups > 5 ? 'warning' : 'success'))
                ->url($pendingPickups > 0 ? '/admin/bookings?tableFilters[status][values][0]=pending' : null),

            Stat::make('👥 Active Merchants', $activeMerchants)
                ->description($totalCouriers . ' active couriers | ' . $pendingApprovals . ' pending approvals')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return true;
    }
}