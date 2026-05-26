<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class PricingPlans extends Page
{
    protected string $view = 'filament.pages.pricing-plans';
    protected static ?string $navigationLabel = 'Pricing Plans';
    protected static ?string $title = 'Pricing Plans';
    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    protected static ?string $slug = 'plan-management';

    public array $planForm = [
        'name' => '',
        'description' => '',
        'base_delivery_charge' => '',
        'cod_commission_percent' => '',
        'weight_charge_per_kg' => '',
        'fuel_surcharge_percent' => '',
    ];

    public array $merchantRates = [];
    public bool $showAddPlan = false;

    public function getPlans()
    {
        return DB::table('pricing_plans')->orderBy('id')->get();
    }

    public function getMerchants()
    {
        return User::where('role', 'shipper')
            ->select('id', 'name', 'email', 'pricing_plan_id', 'fixed_delivery_charges')
            ->orderBy('name')
            ->get();
    }

    public function savePlan(): void
    {
        if (empty($this->planForm['name'])) {
            Notification::make()->title('Plan name required')->danger()->send();
            return;
        }

        DB::table('pricing_plans')->insert([
            'name' => $this->planForm['name'],
            'description' => $this->planForm['description'],
            'base_delivery_charge' => $this->planForm['base_delivery_charge'] ?: 0,
            'cod_commission_percent' => $this->planForm['cod_commission_percent'] ?: 0,
            'weight_charge_per_kg' => $this->planForm['weight_charge_per_kg'] ?: 0,
            'fuel_surcharge_percent' => $this->planForm['fuel_surcharge_percent'] ?: 0,
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->planForm = [
            'name' => '', 'description' => '',
            'base_delivery_charge' => '', 'cod_commission_percent' => '',
            'weight_charge_per_kg' => '', 'fuel_surcharge_percent' => '',
        ];
        $this->showAddPlan = false;
        Notification::make()->title('Plan saved!')->success()->send();
    }

    public function deletePlan(int $id): void
    {
        DB::table('pricing_plans')->where('id', $id)->delete();
        Notification::make()->title('Plan deleted')->warning()->send();
    }

    public function assignPlan(int $userId): void
    {
        $planId = $this->merchantRates[$userId]['plan_id'] ?? null;
        $customRate = $this->merchantRates[$userId]['custom_rate'] ?? null;

        User::where('id', $userId)->update([
            'pricing_plan_id' => $planId ?: null,
            'fixed_delivery_charges' => $customRate ?: null,
        ]);

        Notification::make()->title('Rate updated!')->success()->send();
    }
}