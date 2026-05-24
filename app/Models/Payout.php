<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    protected $fillable = [
        'user_id',
        'payout_reference',
        'gross_amount',
        'commissions_deducted',
        'other_charges',
        'net_amount',
        'period_start',
        'period_end',
        'status',
        'payment_method',
        'paid_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'commissions_deducted' => 'decimal:2',
            'other_charges' => 'decimal:2',
            'net_amount' => 'decimal:2',
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateNetAmount(): void
    {
        $this->net_amount = $this->gross_amount - $this->commissions_deducted - $this->other_charges;
    }

    public static function generateReference(): string
    {
        $count = static::whereYear('created_at', now()->year)->count() + 1;
        return 'PAYOUT-' . now()->year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
    }
}
