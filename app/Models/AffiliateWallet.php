<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateWallet extends Model
{
    protected $table = 'affiliate_wallets';

    protected $fillable = [
        'affiliate_id',
        'available_balance',
        'pending_balance',
        'lifetime_earnings',
    ];

    protected $casts = [
        'available_balance' => 'decimal:2',
        'pending_balance' => 'decimal:2',
        'lifetime_earnings' => 'decimal:2',
    ];

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class, 'affiliate_id');
    }
}

