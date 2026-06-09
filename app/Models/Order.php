<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'merchant_id',
        'courier_id',
        'cod_amount',
        'delivery_charges',
        'tax_amount',
        'status',
        'invoice_id',
        'customer_address',
    ];

    protected $casts = [
        'customer_address' => 'array',
    ];

    /**
     * Relationships
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(Courier::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Boot method to hook into model events.
     */
    protected static function booted()
    {
        // When an order is being created, calculate delivery charges and tax.
        static::creating(function (self $order) {
            $order->applyPricingPlanRates();
            $order->calculateTax();
        });

        // When status changes to delivered, we may want to update courier receivable etc.
        static::updating(function (self $order) {
            if ($order->isDirty('status') && $order->status === 'delivered') {
                $order->calculateTax(); // ensure tax is set
            }
        });
    }

    /**
     * Apply pricing plan rates based on same city detection.
     */
    public function applyPricingPlanRates(): void
    {
        $merchant = $this->merchant;
        if (! $merchant) {
            Log::warning('Order created without merchant relation.');
            return;
        }

        $plan = $merchant->pricingPlan;
        if (! $plan) {
            Log::warning('Merchant has no pricing plan.');
            return;
        }

        // Expect customer_address['city'] to be present.
        $deliveryCity = $this->customer_address['city'] ?? null;
        $merchantCity = $merchant->city;

        $isSameCity = $deliveryCity && $merchantCity && strtolower($deliveryCity) === strtolower($merchantCity);

        $orderRate = $isSameCity ? $plan->same_city_rate : $plan->diff_city_rate;

        $this->delivery_charges = $orderRate;
    }

    /**
     * Calculate tax (4% of COD for merchant side, 2% for courier side stored separately if needed).
     */
    public function calculateTax(): void
    {
        // 4% tax on COD (merchant side)
        $this->tax_amount = round($this->cod_amount * 0.04, 2);
    }

    /**
     * Helper to get courier receivable amount (COD - courier_rate - 2% tax)
     */
    public function courierReceivable(): float
    {
        $courierRate = $this->courier?->courier_rate ?? 0;
        $courierTax = round($this->cod_amount * 0.02, 2);
        return max(0, $this->cod_amount - $courierRate - $courierTax);
    }

    /**
     * Helper to get merchant net payable (COD - delivery_charges - 4% tax)
     */
    public function merchantNetPayable(): float
    {
        return max(0, $this->cod_amount - $this->delivery_charges - $this->tax_amount);
    }
}