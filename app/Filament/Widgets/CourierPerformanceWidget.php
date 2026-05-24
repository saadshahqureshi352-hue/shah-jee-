<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class CourierPerformanceWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    protected ?string $heading = 'Courier Performance (Deliveries)';

    protected function getData(): array
    {
        $data = Booking::select('courier_integrations.courier_name', DB::raw('count(*) as total'))
            ->join('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->where('bookings.status', 'delivered')
            ->groupBy('courier_integrations.courier_name')
            ->pluck('total', 'courier_name');

        return [
            'datasets' => [
                [
                    'label' => 'Deliveries',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => ['#36A2EB', '#FF6384', '#4BC0C0', '#FF9F40', '#9966FF'],
                ],
            ],
            'labels' => $data->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}