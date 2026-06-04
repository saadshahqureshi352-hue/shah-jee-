<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class APIKey extends Model
{
    protected $table = 'api_keys';

    protected $fillable = [
        'courier_integration_id',
        'key_name',
        'api_key',
        'api_secret',
        'account_id',
        'account_title',
        'environment',
        'is_active',
        'notes',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    public function courierIntegration(): BelongsTo
    {
        return $this->belongsTo(CourierIntegration::class);
    }

    // Encrypt sensitive fields
    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getApiKeyAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setApiSecretAttribute($value)
    {
        $this->attributes['api_secret'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getApiSecretAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setAccountIdAttribute($value)
    {
        $this->attributes['account_id'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getAccountIdAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setAccountTitleAttribute($value)
    {
        $this->attributes['account_title'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getAccountTitleAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function recordUsage(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}
