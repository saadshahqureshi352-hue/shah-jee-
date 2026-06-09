<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'period_date',
        'total_cod',
        'delivery_charges_deducted',
        'tax_deducted',
        'net_payable',
        'status',
    ];

    protected $dates = ['period_date'];

    /**
     * Merchant that this invoice belongs to.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    /**
     * Orders linked to this invoice.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(): void
    {
        $this->status = 'paid';
        $this->save();
    }
}