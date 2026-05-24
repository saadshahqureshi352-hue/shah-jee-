<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PricingPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'base_delivery_charge',
        'cod_commission_percent',
        'weight_charge_per_kg',
        'fuel_surcharge_percent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_delivery_charge' => 'decimal:2',
            'cod_commission_percent' => 'decimal:2',
            'weight_charge_per_kg' => 'decimal:2',
            'fuel_surcharge_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}