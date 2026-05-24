<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RateMatrix extends Model
{
    protected $fillable = [
        'courier_integration_id',
        'city_zone',
        'weight_category',
        'rate',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function courierIntegration(): BelongsTo
    {
        return $this->belongsTo(CourierIntegration::class);
    }

    public static function getRate(int $courierId, string $cityZone, string $weightCategory): ?float
    {
        return static::query()
            ->where('courier_integration_id', $courierId)
            ->where('city_zone', $cityZone)
            ->where('weight_category', $weightCategory)
            ->where('is_active', true)
            ->value('rate');
    }
}
