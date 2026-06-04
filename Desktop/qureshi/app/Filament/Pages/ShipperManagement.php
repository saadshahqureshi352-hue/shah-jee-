<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class ShipperManagement extends Page
{
    protected string $view = 'filament.pages.shipper-management';
    protected static ?string $navigationLabel = 'Merchants';
    protected static ?string $title = 'Merchant & User Management';
    protected static string | \UnitEnum | null $navigationGroup = 'Merchant & User Management';
    protected static ?int $navigationSort = 1;

    public string $search = '';
    public string $status = 'all';
    public array $walletAmounts = [];

    public function getShippers()
    {
        return User::where('role', 'shipper')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('username', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%")
                  ->orWhere('city', 'like', "%{$this->search}%")
            )
            ->when($this->status === 'pending',  fn($q) => $q->where('is_approved', false))
            ->when($this->status === 'approved', fn($q) => $q->where('is_approved', true))
            ->paginate(15);
    }

    public function approveShipper(int $id): void
    {
        User::findOrFail($id)->update(['is_approved' => true, 'account_status' => 'approved']);
        Notification::make()->title('✅ Merchant approved!')->success()->send();
    }

    public function rejectShipper(int $id): void
    {
        User::findOrFail($id)->update(['is_approved' => false, 'account_status' => 'pending']);
        Notification::make()->title('⏳ Merchant set to pending')->warning()->send();
    }

    public function addWalletBalance(int $id): void
    {
        $amount = $this->walletAmounts[$id] ?? 0;
        if ($amount > 0) {
            User::findOrFail($id)->increment('wallet_balance', $amount);
            $this->walletAmounts[$id] = '';
            Notification::make()->title("Rs {$amount} added to wallet")->success()->send();
        }
    }

    public function blockWallet(int $id): void
    {
        User::findOrFail($id)->update(['wallet_blocked' => true]);
        Notification::make()->title('🔒 Wallet blocked')->danger()->send();
    }

    public function unblockWallet(int $id): void
    {
        User::findOrFail($id)->update(['wallet_blocked' => false]);
        Notification::make()->title('🔓 Wallet unblocked')->success()->send();
    }

    public function exportToExcel(): void
    {
        $shippers = User::where('role', 'shipper')->get();
        $csv = "Name,Email,Phone,City,Status,Wallet Balance\n";
        foreach ($shippers as $s) {
            $csv .= "{$s->name},{$s->email},{$s->phone},{$s->city}," . ($s->is_approved ? 'Approved' : 'Pending') . ",{$s->wallet_balance}\n";
        }
        response()->streamDownload(function() use ($csv) { echo $csv; }, 'shippers_export_' . now()->format('Ymd') . '.csv')
            ->send();
        Notification::make()->title('📥 Export downloaded')->success()->send();
    }
}