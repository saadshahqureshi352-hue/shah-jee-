<?php

namespace App\Services;

use App\Models\Wallet;
use App\Models\User;
use App\Models\ActivityLog;

class WalletService
{
    public function getOrCreateWallet(User $user): Wallet
    {
        return Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_credited' => 0, 'total_debited' => 0]
        );
    }

    public function creditWallet(User $user, float $amount, string $description = ''): Wallet
    {
        $wallet = $this->getOrCreateWallet($user);
        $wallet->increment('balance', $amount);
        $wallet->increment('total_credited', $amount);
        $wallet->save();

        $this->logTransaction($wallet, 'credit', $amount, $description);

        return $wallet;
    }

    public function debitWallet(User $user, float $amount, string $description = ''): Wallet
    {
        $wallet = $this->getOrCreateWallet($user);
        
        if ($wallet->balance < $amount) {
            throw new \Exception('Insufficient wallet balance');
        }

        $wallet->decrement('balance', $amount);
        $wallet->increment('total_debited', $amount);
        $wallet->save();

        $this->logTransaction($wallet, 'debit', $amount, $description);

        return $wallet;
    }

    public function blockWallet(User $user, string $reason = ''): Wallet
    {
        $wallet = $this->getOrCreateWallet($user);
        $wallet->status = 'blocked';
        $wallet->notes = $reason ?: $wallet->notes;
        $wallet->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_type' => Wallet::class,
            'loggable_id' => $wallet->id,
            'event' => 'blocked',
            'description' => 'Wallet blocked: ' . $reason,
        ]);

        return $wallet;
    }

    public function unblockWallet(User $user): Wallet
    {
        $wallet = $this->getOrCreateWallet($user);
        $wallet->status = 'active';
        $wallet->save();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_type' => Wallet::class,
            'loggable_id' => $wallet->id,
            'event' => 'unblocked',
            'description' => 'Wallet unblocked',
        ]);

        return $wallet;
    }

    private function logTransaction(Wallet $wallet, string $type, float $amount, string $description): void
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_type' => Wallet::class,
            'loggable_id' => $wallet->id,
            'event' => $type,
            'description' => "$type of PKR $amount. " . $description,
            'new_values' => ['balance' => $wallet->balance],
        ]);
    }
}
