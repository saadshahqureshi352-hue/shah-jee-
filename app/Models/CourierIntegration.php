<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourierIntegration extends Model
{
    protected $fillable = [
        'courier_name',
        'api_key',
        'api_secret',
        'account_number',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function rateMatrices(): HasMany
    {
        return $this->hasMany(CourierRateMatrix::class);
    }

    public function apiKeys(): HasMany
    {
        return $this->hasMany(APIKey::class);
    }

    public function newRateMatrices(): HasMany
    {
        return $this->hasMany(RateMatrix::class);
    }

    public function codReconciliations(): HasMany
    {
        return $this->hasMany(CODReconciliation::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'courier_integration_id');
    }
}
