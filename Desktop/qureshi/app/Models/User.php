<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'username', 'phone', 'city', 'alternate_email', 'alternate_city',
    'company_name', 'owner_name', 'brand_name', 'father_name', 'business_type', 'fixed_delivery_charges',
    'wallet_method', 'wallet_account_no', 'wallet_account_title', 'account_holder_name', 'account_number',
    'iban_number', 'bank_name', 'payment_cycle', 'cheque_photo_path', 'profile_photo_path',
    'selfie_photo_path', 'cnic_front_path', 'cnic_back_path', 'business_photo_paths', 'home_address',
    'cnic_or_passport', 'date_of_birth', 'gender', 'is_approved', 'approved_at', 'has_seen_welcome', 'is_profile_locked',
    'pricing_plan_id', 'role', 'wallet_balance', 'wallet_blocked', 'account_status',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'approved_at'       => 'datetime',
            'date_of_birth'     => 'date',
            'is_approved'       => 'boolean',
            'has_seen_welcome'  => 'boolean',
            'is_profile_locked' => 'boolean',
            'business_photo_paths' => 'array',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }

    public function isAdmin(): bool
    {
        $adminEmails = config('finance.admin_emails', []);
        return in_array((string) $this->email, $adminEmails, true)
            || $this->id === 1
            || $this->email === 'shahjeecourier@gmail.com';
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function pricingPlan(): BelongsTo
    {
        return $this->belongsTo(PricingPlan::class);
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function payouts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payout::class);
    }
}