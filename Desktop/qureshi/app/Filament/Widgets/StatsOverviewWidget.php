<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Aapki orders/shipments table ka naam yahan likhein
        $table = 'bookings'; // ← agar shipments hai to 'shipments' likh dein

        $todayCount = DB::table($table)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $yesterdayCount = DB::table($table)
            ->whereDate('created_at', Carbon::yesterday())
            ->count();

        $diff = $todayCount - $yesterdayCount;

        $totalRevenue = DB::table($table)
            ->whereDate('created_at', Carbon::today())
            ->sum('charged_amount') ?? 0;

        $totalProfit = DB::table($table)
            ->whereDate('created_at', Carbon::today())
            ->sum('profit') ?? 0;

        $pending = DB::table($table)
            ->where('status', 'pending')
            ->count();

        $merchants = DB::table('users')
            ->where('status', 'active')
            ->count();

        return [
            Stat::make('Aaj ke Shipments', $todayCount)
                ->description($diff >= 0 ? '+' . $diff . ' kal se zyada' : $diff . ' kal se kam')
                ->descriptionIcon($diff >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($diff >= 0 ? 'success' : 'danger'),

            Stat::make('Total Revenue (Aaj)', 'Rs ' . number_format($totalRevenue))
                ->description('Tamam couriers ka total')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('info'),

            Stat::make('Net Profit (Aaj)', 'Rs ' . number_format($totalProfit))
                ->description('Commission ke baad')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning'),

            Stat::make('Pending Pickups', $pending)
                ->description($pending > 0 ? 'Action zaroor karein!' : 'Sab clear hai')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pending > 0 ? 'danger' : 'success'),

            Stat::make('Active Users', $merchants)
                ->description('Registered shippers')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}