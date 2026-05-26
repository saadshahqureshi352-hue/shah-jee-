<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourierPerformanceWidget extends ChartWidget
{
    protected ?string $heading = 'Courier Performance — Aaj';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '250px';


    protected function getData(): array
    {
        // couriers table se data
        try {
            $couriers = DB::table('courier_services')->get();
        } catch (\Exception $e) {
            $couriers = collect();
        }

        $labels    = [];
        $delivered = [];
        $returned  = [];

        foreach ($couriers as $courier) {
            $labels[] = $courier->name;

            $delivered[] = DB::table('bookings')
                ->where('courier_id', $courier->id)
                ->whereDate('created_at', Carbon::today())
                ->where('status', 'delivered')
                ->count();

            $returned[] = DB::table('orders')
                ->where('courier_id', $courier->id)
                ->whereDate('created_at', Carbon::today())
                ->where('status', 'returned')
                ->count();
        }

        // Agar couriers table nahi hai
        if (count($labels) === 0) {
            $labels    = ['Trax', 'Leopards', 'TCS', 'M&P'];
            $delivered = [0, 0, 0, 0];
            $returned  = [0, 0, 0, 0];
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Delivered',
                    'data'            => $delivered,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label'           => 'Returned',
                    'data'            => $returned,
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}