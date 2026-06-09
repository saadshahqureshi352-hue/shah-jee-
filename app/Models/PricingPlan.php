<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'diff_city_rate',
        'same_city_rate',
        'additional_kg_rate',
        'return_rate',

        // Service-type specific rates
        'overnight_base_rate',
        'overnight_additional_rate',
        'detain_base_rate',
        'detain_additional_rate',
        'overland_base_rate',
        'overland_additional_rate',
    ];

    protected $casts = [
        'diff_city_rate' => 'decimal:2',
        'same_city_rate' => 'decimal:2',
        'additional_kg_rate' => 'decimal:2',
        'return_rate' => 'decimal:2',

        // Service-type specific rates
        'overnight_base_rate' => 'decimal:2',
        'overnight_additional_rate' => 'decimal:2',
        'detain_base_rate' => 'decimal:2',
        'detain_additional_rate' => 'decimal:2',
        'overland_base_rate' => 'decimal:2',
        'overland_additional_rate' => 'decimal:2',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getDeliveryRateAttribute()
    {
        return $this->diff_city_rate;
    }

    public function getSameCityRateAttribute()
    {
        return $this->same_city_rate;
    }

    public function getAdditionalKgRateAttribute()
    {
        return $this->attributes['additional_kg_rate'] 
            ?? $this->overnight_additional_rate 
            ?? $this->detain_additional_rate 
            ?? $this->overland_additional_rate 
            ?? 0;
    }

    public function getReturnRateAttribute()
    {
        return $this->attributes['return_rate'] ?? 0;
    }
}