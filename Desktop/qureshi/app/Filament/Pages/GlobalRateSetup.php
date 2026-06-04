<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\CourierIntegration;
use App\Models\CourierRateMatrix;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class GlobalRateSetup extends Page
{
    protected string $view = 'filament.pages.global-rate-setup';
    protected static ?string $navigationLabel = 'Global Rate Setup';
    protected static ?string $title = 'Global Rate Setup';
    protected static string | \UnitEnum | null $navigationGroup = 'Rate Management';
    protected static ?int $navigationSort = 5;

    public function getCouriers()
    {
        return CourierIntegration::where('is_active', true)->get();
    }

    public function getRateData(): array
    {
        return CourierRateMatrix::with('courierIntegration')
            ->orderBy('courier_integration_id')
            ->orderBy('weight_from')
            ->get()
            ->toArray();
    }

    public function getWeightCategories(): array
    {
        return [
            ['label' => '0 - 0.5 kg', 'from' => 0, 'to' => 0.5],
            ['label' => '0.5 - 1 kg', 'from' => 0.5, 'to' => 1],
            ['label' => '1 - 2 kg', 'from' => 1, 'to' => 2],
            ['label' => '2 - 5 kg', 'from' => 2, 'to' => 5],
            ['label' => '5 - 10 kg', 'from' => 5, 'to' => 10],
            ['label' => '10+ kg', 'from' => 10, 'to' => 999],
        ];
    }

    public function saveRate(array $data): void
    {
        try {
            DB::beginTransaction();

            foreach ($data['rates'] as $rate) {
                CourierRateMatrix::updateOrCreate(
                    [
                        'courier_integration_id' => $rate['courier_integration_id'],
                        'weight_category' => $rate['weight_category'],
                        'zone' => $rate['zone'] ?? 'all',
                    ],
                    [
                        'weight_from' => $rate['weight_from'],
                        'weight_to' => $rate['weight_to'],
                        'rate' => $rate['rate'],
                        'courier_cost' => $rate['courier_cost'],
                        'shipper_charge' => $rate['shipper_charge'],
                        'cod_commission_percent' => $rate['cod_commission_percent'] ?? 0,
                        'shipper_cod_percent' => $rate['shipper_cod_percent'] ?? 0,
                        'fuel_surcharge_percent' => $rate['fuel_surcharge_percent'] ?? 0,
                        'is_active' => $rate['is_active'] ?? true,
                    ]
                );
            }

            DB::commit();
            Notification::make()
                ->title('Global rates saved successfully!')
                ->success()
                ->send();
        } catch (\Exception $e) {
            DB::rollBack();
            Notification::make()
                ->title('Error saving rates: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}