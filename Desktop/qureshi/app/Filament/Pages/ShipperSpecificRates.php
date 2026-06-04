<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\CourierIntegration;
use App\Models\CourierRateMatrix;
use App\Models\ShipperSpecificRate;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ShipperSpecificRates extends Page
{
    protected string $view = 'filament.pages.shipper-specific-rates';
    protected static ?string $navigationLabel = 'Shipper-Specific Rates';
    protected static ?string $title = 'Shipper-Specific Rates';
    protected static string | \UnitEnum | null $navigationGroup = 'Rate Management';
    protected static ?int $navigationSort = 6;

    public function getShippers()
    {
        return User::where('role', 'shipper')
            ->where('is_approved', true)
            ->withCount('bookings')
            ->orderByDesc('bookings_count')
            ->get()
            ->map(function ($shipper) {
                $shipper->monthly_orders = $shipper->bookings_count;
                return $shipper;
            });
    }

    public function getCouriers()
    {
        return CourierIntegration::where('is_active', true)->get();
    }

    public function getGlobalRates()
    {
        return CourierRateMatrix::with('courierIntegration')
            ->where('is_active', true)
            ->orderBy('courier_integration_id')
            ->orderBy('weight_from')
            ->get();
    }

    public function getShipperRates(int $shipperId): array
    {
        return ShipperSpecificRate::where('user_id', $shipperId)
            ->where('is_active', true)
            ->pluck('custom_rate', 'courier_rate_matrix_id')
            ->toArray();
    }

    public function getShipperMonthlyOrders(int $shipperId): int
    {
        return DB::table('bookings')
            ->where('user_id', $shipperId)
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function saveShipperRate(array $data): void
    {
        try {
            DB::beginTransaction();

            $shipperId = $data['user_id'];
            $courierIntegrationId = $data['courier_integration_id'] ?? null;

            foreach ($data['rates'] as $rate) {
                ShipperSpecificRate::updateOrCreate(
                    [
                        'user_id' => $shipperId,
                        'courier_rate_matrix_id' => $rate['courier_rate_matrix_id'],
                    ],
                    [
                        'courier_integration_id' => $courierIntegrationId,
                        'custom_rate' => $rate['custom_rate'],
                        'custom_cod_percent' => $rate['custom_cod_percent'] ?? null,
                        'is_active' => $rate['is_active'] ?? true,
                        'notes' => $rate['notes'] ?? null,
                    ]
                );
            }

            DB::commit();
            Notification::make()
                ->title('Shipper-specific rates saved!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}