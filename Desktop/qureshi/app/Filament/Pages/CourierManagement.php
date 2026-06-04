<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class CourierManagement extends Page
{
    protected string $view = 'filament.pages.courier-management';
    protected static ?string $navigationLabel = 'Courier & API';
    protected static ?string $title = 'Courier & API Management';
    protected static string | \UnitEnum | null $navigationGroup = 'Courier Management';
    protected static ?int $navigationSort = 1;

    public function getCouriers()
    {
        return DB::table('courier_integrations')->orderBy('courier_name')->get();
    }

    public function getRateMatrix()
    {
        return DB::table('rate_matrices')
            ->leftJoin('courier_integrations', 'rate_matrices.courier_integration_id', '=', 'courier_integrations.id')
            ->select('rate_matrices.*', 'courier_integrations.courier_name')
            ->orderBy('courier_integrations.courier_name')
            ->orderBy('rate_matrices.zone_type')
            ->orderBy('rate_matrices.weight_from')
            ->get();
    }

    public function toggleCourier(int $id, bool $status): void
    {
        DB::table('courier_integrations')->where('id', $id)->update(['is_active' => $status]);
        Notification::make()->title($status ? 'Courier activated!' : 'Courier disabled!')->success()->send();
    }

    public function regenerateApiKey(int $id): void
    {
        $newKey = 'auto_' . bin2hex(random_bytes(16));
        $newSecret = bin2hex(random_bytes(16));
        DB::table('courier_integrations')->where('id', $id)->update([
            'api_key' => $newKey,
            'api_secret' => $newSecret,
        ]);
        Notification::make()->title('API Key regenerated!')->success()->send();
    }
}