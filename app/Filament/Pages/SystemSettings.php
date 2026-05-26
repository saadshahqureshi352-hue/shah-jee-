<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class SystemSettings extends Page
{
    protected string $view = 'filament.pages.system-settings';
    protected static ?string $navigationLabel = 'System Settings';
    protected static ?string $title = 'System Settings';
    protected static string | \UnitEnum | null $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 2;

    public bool $newRegistrations = true;
    public bool $autoApprove = false;
    public bool $codAvailable = true;
    public bool $maintenanceMode = false;
    public bool $whatsappNotifications = true;
    public bool $deliveredAlert = true;
    public bool $returnAlert = true;
    public bool $lowWalletAlert = true;
    public bool $payoutNotification = false;

    public string $defaultCharge = '150';
    public string $fuelSurcharge = '5';
    public string $remoteAreaCharge = '100';
    public string $codHandlingFee = '2';

    public function saveRates(): void
    {
        Notification::make()->title('Rates saved!')->success()->send();
    }

    public function saveToggles(): void
    {
        Notification::make()->title('Settings saved!')->success()->send();
    }

    public function getCouriers()
    {
        return DB::table('courier_integrations')->get();
    }

    public function toggleCourier(int $id, bool $status): void
    {
        DB::table('courier_integrations')->where('id', $id)->update(['is_active' => $status]);
        Notification::make()->title('Courier updated!')->success()->send();
    }
}