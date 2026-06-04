<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get existing plans
        $existingPlans = DB::table('pricing_plans')->get();
        $existingNames = $existingPlans->pluck('name')->map(fn($n) => strtolower(trim($n)))->toArray();

        $plansToCreate = [
            ['name' => 'Basic Plan',   'description' => 'Basic pricing plan for new merchants'],
            ['name' => 'Standard Plan', 'description' => 'Standard pricing plan'],
            ['name' => 'VIP Plan',      'description' => 'VIP pricing plan for premium merchants'],
        ];

        $defaults = [
            'base_delivery_charge' => 240,
            'different_city_delivery' => 240,
            'same_city_delivery' => 200,
            'return_charge' => 200,
            'additional_kg_rate' => 140,
            'cod_commission_percent' => 0,
            'weight_charge_per_kg' => 0,
            'fuel_surcharge_percent' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        foreach ($plansToCreate as $plan) {
            $slug = strtolower(str_replace(' ', '_', $plan['name']));
            // Check if a plan with similar name exists
            $exists = false;
            foreach ($existingNames as $en) {
                $enSlug = strtolower(str_replace(' ', '_', $en));
                if ($enSlug === $slug || str_contains($enSlug, explode('_', $slug)[0])) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                DB::table('pricing_plans')->insert(array_merge($plan, $defaults));
            }
        }

        // Update existing plans with the specified rates if they don't have them
        $allPlans = DB::table('pricing_plans')->get();
        foreach ($allPlans as $p) {
            $update = [];
            $nameLower = strtolower(trim($p->name));

            // Set different_city_delivery to 240 if 0 or null
            if (floatval($p->different_city_delivery ?? 0) === 0) {
                $update['different_city_delivery'] = 240;
            }
            // Set same_city_delivery to 200 if 0 or null
            if (floatval($p->same_city_delivery ?? 0) === 0) {
                $update['same_city_delivery'] = 200;
            }
            // Set return_charge to 200 if 0 or null
            if (floatval($p->return_charge ?? 0) === 0) {
                $update['return_charge'] = 200;
            }
            // Set additional_kg_rate to 140 if 0 or null
            if (floatval($p->additional_kg_rate ?? 0) === 0) {
                $update['additional_kg_rate'] = 140;
            }
            // Set base_delivery_charge to 240 if 0 or null
            if (floatval($p->base_delivery_charge ?? 0) === 0) {
                $update['base_delivery_charge'] = 240;
            }

            if (!empty($update)) {
                DB::table('pricing_plans')->where('id', $p->id)->update($update);
            }

            // Rename any plan to match Basic/Standard/VIP
            $isBasic = in_array($nameLower, ['basic', 'basic plan', 'base', 'default']);
            $isStandard = in_array($nameLower, ['standard', 'standard plan', 'normal', 'regular']);
            $isVip = in_array($nameLower, ['vip', 'vip plan', 'premium', 'gold']);

            if (!$isBasic && !$isStandard && !$isVip) {
                // Try to map to closest
                if (str_contains($nameLower, 'basic') || str_contains($nameLower, 'base') || str_contains($nameLower, 'default')) {
                    DB::table('pricing_plans')->where('id', $p->id)->update(['name' => 'Basic Plan']);
                } elseif (str_contains($nameLower, 'standard') || str_contains($nameLower, 'normal') || str_contains($nameLower, 'regular')) {
                    DB::table('pricing_plans')->where('id', $p->id)->update(['name' => 'Standard Plan']);
                } elseif (str_contains($nameLower, 'vip') || str_contains($nameLower, 'premium') || str_contains($nameLower, 'gold')) {
                    DB::table('pricing_plans')->where('id', $p->id)->update(['name' => 'VIP Plan']);
                }
            }
        }
    }

    public function down(): void
    {
        // No rollback - data migration
    }
};