<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Courier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo_path',
        'courier_rate',
        'merchant_rate',
        'profit_per_order',
        'status',
    ];

    /**
     * Orders that were assigned to this courier.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scope to get only active couriers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}