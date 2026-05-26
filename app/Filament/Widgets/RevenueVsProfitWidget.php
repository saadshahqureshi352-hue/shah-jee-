<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RevenueVsProfitWidget extends ChartWidget
{
    protected ?string $heading = 'Revenue vs Profit — Last 7 Days';
    protected static ?int $sort = 3;
    protected ?string $maxHeight = '250px';


    protected function getData(): array
    {
        $labels  = [];
        $revenue = [];
        $profit  = [];

        for ($i = 6; $i >= 0; $i--) {
            $date     = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');

            try {
                $revenue[] = DB::table('bookings')
                    ->whereDate('created_at', $date)
                    ->sum('charged_amount') ?? 0;

                $profit[] = DB::table('bookings')
                    ->whereDate('created_at', $date)
                    ->sum('profit') ?? 0;
            } catch (\Exception $e) {
                $revenue[] = 0;
                $profit[]  = 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Revenue (Rs)',
                    'data'            => $revenue,
                    'borderColor'     => '#3b82f6',
                    'backgroundColor' => 'rgba(59,130,246,0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
                [
                    'label'           => 'Profit (Rs)',
                    'data'            => $profit,
                    'borderColor'     => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,0.1)',
                    'fill'            => true,
                    'tension'         => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}