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
        'courier_cost',
        'shipper_charge',
        'profit',
        'cod_commission',
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
            'courier_cost' => 'decimal:2',
            'shipper_charge' => 'decimal:2',
            'profit' => 'decimal:2',
            'cod_commission' => 'decimal:2',
        ];
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_PICKED_UP = 'picked_up';
    public const STATUS_DISPATCHED = 'dispatched';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_ISSUE = 'issue';

    public static function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_DELIVERED => 'bg-s',
            self::STATUS_PICKED_UP, self::STATUS_DISPATCHED, self::STATUS_IN_TRANSIT => 'bg-i',
            self::STATUS_OUT_FOR_DELIVERY => 'bg-w',
            self::STATUS_RETURNED, self::STATUS_ISSUE => 'bg-d',
            self::STATUS_CANCELLED => 'bg-n',
            default => 'bg-n',
        };
    }

    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'Booked',
            self::STATUS_PICKED_UP => 'Picked Up',
            self::STATUS_DISPATCHED => 'Dispatched',
            self::STATUS_IN_TRANSIT => 'In Transit',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_RETURNED => 'Returned',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_ISSUE => 'Issue',
            default => ucfirst(str_replace('_', ' ', $status)),
        };
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

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}