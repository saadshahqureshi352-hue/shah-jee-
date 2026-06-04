<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\CourierIntegration;
use App\Models\CourierRateMatrix;
use App\Models\APIKey;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CourierHub extends Page
{
    protected string $view = 'filament.pages.courier-hub';
    protected static ?string $navigationLabel = 'Courier Hub';
    protected static ?string $title = 'Courier Hub';
    protected static string | \UnitEnum | null $navigationGroup = 'Courier Management';
    protected static ?int $navigationSort = 4;

    public function getCouriers()
    {
        return CourierIntegration::with('apiKeys')->get()->map(function ($courier) {
            $totalOrders = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)->count();
            $deliveredOrders = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->where('status', 'delivered')->count();
            $deliveryRate = $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100) : 0;

            $totalShipperCharge = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->sum(DB::raw('COALESCE(shipper_charge, delivery_charges)'));
            $totalCourierCost = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->sum('courier_cost');
            $profit = $totalShipperCharge - $totalCourierCost;

            $avgShipperCharge = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->avg(DB::raw('COALESCE(shipper_charge, delivery_charges)')) ?? 0;
            $avgCourierCost = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->avg('courier_cost') ?? 0;
            $perOrderProfit = round($avgShipperCharge - $avgCourierCost);

            $courier->total_orders = $totalOrders;
            $courier->delivery_rate = $deliveryRate;
            $courier->total_shipper_charge = $totalShipperCharge;
            $courier->total_courier_cost = $totalCourierCost;
            $courier->total_profit = $profit;
            $courier->avg_shipper_charge = round($avgShipperCharge);
            $courier->avg_courier_cost = round($avgCourierCost);
            $courier->per_order_profit = $perOrderProfit;
            $courier->today_orders = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->whereDate('created_at', now()->toDateString())->count();
            $courier->today_profit = DB::table('bookings')
                ->where('courier_integration_id', $courier->id)
                ->whereDate('created_at', now()->toDateString())
                ->sum('profit');

            return $courier;
        });
    }

    public function toggleCourier(int $id, bool $status): void
    {
        CourierIntegration::where('id', $id)->update(['is_active' => $status]);
        Notification::make()
            ->title('Courier status updated!')
            ->success()
            ->send();
    }

    public function saveApiKey(int $courierId, array $data): void
    {
        APIKey::updateOrCreate(
            [
                'courier_integration_id' => $courierId,
                'key_type' => $data['key_type'] ?? 'api_key',
            ],
            [
                'api_key' => $data['api_key'] ?? '',
                'api_secret' => $data['api_secret'] ?? '',
                'account_number' => $data['account_number'] ?? '',
                'is_active' => $data['is_active'] ?? true,
            ]
        );

        // Also update the courier's own api fields for backward compatibility
        if (!empty($data['api_key'])) {
            CourierIntegration::where('id', $courierId)->update([
                'api_key' => $data['api_key'],
                'api_secret' => $data['api_secret'] ?? '',
                'account_number' => $data['account_number'] ?? '',
            ]);
        }

        Notification::make()
            ->title('API key saved successfully!')
            ->success()
            ->send();
    }

    public function addCourier(array $data): void
    {
        $courier = CourierIntegration::create([
            'courier_name' => $data['courier_name'],
            'api_key' => $data['api_key'] ?? '',
            'api_secret' => $data['api_secret'] ?? '',
            'account_number' => $data['account_number'] ?? '',
            'is_active' => true,
        ]);

        Notification::make()
            ->title("{$courier->courier_name} added successfully!")
            ->success()
            ->send();
    }
}