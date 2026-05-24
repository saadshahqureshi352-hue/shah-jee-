<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'courier_integration_id',
        'consignment_no',
        'tracking_number',
        'reference_no',
        'customer_name',
        'customer_phone',
        'second_phone',
        'customer_address',
        'consignee_address',
        'destination_city',
        'origin_city',
        'weight',
        'quantity',
        'product_name',
        'description',
        'special_instructions',
        'cod_amount',
        'delivery_charges',
        'is_cod',
        'service_type',
        'status',
        'pickup_address_id',
        'pickup_date',
        'delivered_at',
        'remarks',
        'invoice_id',
    ];

    protected function casts(): array
    {
        return [
            'weight' => 'decimal:2',
            'cod_amount' => 'decimal:2',
            'delivery_charges' => 'decimal:2',
            'quantity' => 'integer',
            'is_cod' => 'boolean',
            'pickup_date' => 'date',
            'delivered_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courier_integration(): BelongsTo
    {
        return $this->belongsTo(CourierIntegration::class, 'courier_integration_id');
    }

    public function trackingHistory(): HasMany
    {
        return $this->hasMany(TrackingHistory::class);
    }

    public function pickupAddress(): BelongsTo
    {
        return $this->belongsTo(PickupAddress::class);
    }
}