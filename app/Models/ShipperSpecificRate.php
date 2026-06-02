<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipperSpecificRate extends Model
{
    protected $fillable = [
        'user_id',
        'courier_integration_id',
        'courier_rate_matrix_id',
        'custom_rate',
        'custom_cod_percent',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'custom_rate' => 'decimal:2',
            'custom_cod_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courierIntegration(): BelongsTo
    {
        return $this->belongsTo(CourierIntegration::class);
    }

    public function courierRateMatrix(): BelongsTo
    {
        return $this->belongsTo(CourierRateMatrix::class);
    }
}