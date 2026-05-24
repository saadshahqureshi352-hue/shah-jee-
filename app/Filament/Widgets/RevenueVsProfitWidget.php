<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueVsProfitWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();

        // Total COD (Revenue)
        $todayCOD = Booking::where('created_at', '>=', $today)->sum('cod_amount');
        $monthlyCOD = Booking::where('created_at', '>=', $thisMonth)->sum('cod_amount');
        $totalCOD = Booking::sum('cod_amount');

        // Total Delivery Charges (Revenue from shipping)
        $todayCharges = Booking::where('created_at', '>=', $today)->sum('delivery_charges');
        $monthlyCharges = Booking::where('created_at', '>=', $thisMonth)->sum('delivery_charges');
        $totalCharges = Booking::sum('delivery_charges');

        // Calculate profit (delivery charges minus 2% COD processing)
        $todayProfit = $todayCharges - ($todayCOD * 0.02);
        $monthlyProfit = $monthlyCharges - ($monthlyCOD * 0.02);
        $totalProfit = $totalCharges - ($totalCOD * 0.02);

        // Count successful deliveries
        $todayDelivered = Booking::where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();
        $monthlyDelivered = Booking::where('status', 'delivered')
            ->where('delivered_at', '>=', $thisMonth)
            ->count();

        // Count registered merchants
        $totalMerchants = User::where('is_approved', true)->count();
        $newMerchantsThisMonth = User::where('is_approved', true)
            ->where('created_at', '>=', $thisMonth)
            ->count();

        // Average order value
        $avgOrderValue = Booking::where('cod_amount', '>', 0)->avg('cod_amount') ?? 0;

        return [
            Stat::make('💰 Monthly Revenue (COD)', 'PKR ' . number_format($monthlyCOD, 0))
                ->description('This month total COD value')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning')
                ->chart([$todayCOD, $monthlyCOD, $totalCOD]),

            Stat::make('📊 Monthly Profit', 'PKR ' . number_format($monthlyProfit, 0))
                ->description('After 2% COD processing fee')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([$todayProfit, $monthlyProfit, $totalProfit]),

            Stat::make('📦 Delivered This Month', $monthlyDelivered)
                ->description($todayDelivered . ' delivered today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('info'),

            Stat::make('👥 Active Merchants', $totalMerchants)
                ->description($newMerchantsThisMonth . ' new this month')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('🎯 Avg. Order Value', 'PKR ' . number_format($avgOrderValue, 0))
                ->description('Per COD shipment')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('gray'),

            Stat::make('🏆 Profit Margin', round($totalCOD > 0 ? ($totalProfit / $totalCOD) * 100 : 0, 1) . '%')
                ->description('Overall profit on total COD')
                ->descriptionIcon('heroicon-m-percent-badge')
                ->color('success'),
        ];
    }
}