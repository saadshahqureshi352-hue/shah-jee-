<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourierPerformanceWidget_Enhanced extends ChartWidget
{
    protected ?string $heading = 'Courier Performance — Last 7 Days';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        try {
            $couriers = DB::table('courier_integrations')
                ->where('is_active', 1)
                ->get();
        } catch (\Exception $e) {
            $couriers = collect();
        }

        if (count($couriers) === 0) {
            // Fallback placeholder couriers
            $courierNames = ['Trax', 'Leopards', 'TCS', 'M&P'];
        } else {
            $courierNames = $couriers->pluck('courier_name')->toArray();
        }

        $labels = $courierNames;
        $delivered = [];
        $returned = [];
        $inTransit = [];

        foreach ($couriers as $courier) {
            $courierName = $courier->courier_name ?? '';
            $delivered[] = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                ->where('status', 'delivered')
                ->count();

            $returned[] = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                ->where('status', 'returned')
                ->count();

            $inTransit[] = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
                ->whereIn('status', ['dispatched', 'in_transit', 'out_for_delivery'])
                ->count();
        }

        // If no real data, use fallback
        if (count($couriers) === 0) {
            $labels = ['Trax', 'Leopards', 'TCS', 'M&P'];
            $delivered = [0, 0, 0, 0];
            $returned = [0, 0, 0, 0];
            $inTransit = [0, 0, 0, 0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Delivered',
                    'data' => $delivered,
                    'backgroundColor' => '#10b981',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'In Transit',
                    'data' => $inTransit,
                    'backgroundColor' => '#3b82f6',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Returned',
                    'data' => $returned,
                    'backgroundColor' => '#ef4444',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}