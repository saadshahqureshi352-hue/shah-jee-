<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlertsWidget extends Widget
{
    protected static ?int $sort = 4;
    protected string $view = 'filament.widgets.alerts-widget';
    protected int | string | array $columnSpan = 'full';

    public function getAlerts(): array
    {
        $alerts = [];

        // Pending orders
        $pending = DB::table('bookings')->where('status', 'pending')->count();
        if ($pending > 0) {
            $alerts[] = [
                'type'    => 'danger',
                'message' => $pending . ' orders pending pickup hain — schedule nahi hua',
                'icon'    => 'heroicon-o-clock',
            ];
        }

        // Low wallet merchants - agar aapke paas wallets table hai
        try {
            $lowWallets = DB::table('users')
                ->where('wallet_balance', '<', 1000)
                ->where('status', 'active')
                ->get();

            foreach ($lowWallets as $user) {
                $alerts[] = [
                    'type'    => 'warning',
                    'message' => '"' . $user->name . '" ka wallet low hai — Rs ' . $user->wallet_balance,
                    'icon'    => 'heroicon-o-exclamation-triangle',
                ];
            }
        } catch (\Exception $e) {
            // wallet_balance column nahi hai to skip
        }

        if (count($alerts) === 0) {
            $alerts[] = [
                'type'    => 'success',
                'message' => 'Sab theek hai — koi alert nahi!',
                'icon'    => 'heroicon-o-check-circle',
            ];
        }

        return $alerts;
    }
}