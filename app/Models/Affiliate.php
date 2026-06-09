<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliate extends Model
{
    protected $table = 'affiliates';

    protected $fillable = [
        'user_id',
        'referral_code',
        'available_wallet',
        'pending_balance',
        'lifetime_earnings',
        'total_paid_out',
        'jazzcash_number',
        'easypaisa_number',
        'bank_name',
        'iban',
        'status',
    ];

    protected $casts = [
        'available_wallet' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'lifetime_earnings' => 'decimal:2',
        'total_paid_out' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(AffiliateCommissionLedger::class, 'affiliate_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(AffiliateTransaction::class, 'affiliate_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(AffiliatePayout::class, 'affiliate_id');
    }
}

