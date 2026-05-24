<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourierRateMatrix extends Model
{
    protected $fillable = [
        'courier_integration_id',
        'weight_category',
        'weight_from',
        'weight_to',
        'zone',
        'rate',
        'cod_commission_percent',
        'fuel_surcharge_percent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weight_from' => 'decimal:2',
            'weight_to' => 'decimal:2',
            'rate' => 'decimal:2',
            'cod_commission_percent' => 'decimal:2',
            'fuel_surcharge_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function courierIntegration(): BelongsTo
    {
        return $this->belongsTo(CourierIntegration::class);
    }
}