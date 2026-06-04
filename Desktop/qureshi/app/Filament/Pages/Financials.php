<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class Financials extends Page
{
    protected string $view = 'filament.pages.financials';
    protected static ?string $navigationLabel = 'Financials & Payouts';
    protected static ?string $title = 'Financials & Payouts';
    protected static string | \UnitEnum | null $navigationGroup = 'Financials';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'finance-overview';

    public function getStats(): array
    {
        $totalRevenue = DB::table('bookings')->sum('delivery_charges');
        $totalCOD = DB::table('bookings')->where('is_cod', 1)->sum('cod_amount');
        $totalPayouts = DB::table('payouts')->where('status', 'completed')->sum('net_amount') ?? 0;
        $pendingPayouts = DB::table('payouts')->where('status', 'pending')->sum('net_amount') ?? 0;

        return compact('totalRevenue', 'totalCOD', 'totalPayouts', 'pendingPayouts');
    }

    public function getReconciliations()
    {
        return DB::table('cod_reconciliations')
            ->leftJoin('courier_integrations', 'cod_reconciliations.courier_integration_id', '=', 'courier_integrations.id')
            ->select('cod_reconciliations.*', 'courier_integrations.courier_name as courier_name')
            ->orderByDesc('cod_reconciliations.created_at')
            ->limit(10)
            ->get();
    }

    public function getPayouts()
    {
        return DB::table('payouts')
            ->leftJoin('users', 'payouts.user_id', '=', 'users.id')
            ->select('payouts.*', 'users.name as merchant_name')
            ->orderByDesc('payouts.created_at')
            ->limit(10)
            ->get();
    }

    public function getMerchantProfits()
    {
        return DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->select(
                'users.name as merchant_name',
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(delivery_charges) as total_charges'),
                DB::raw('SUM(cod_amount) as total_cod')
            )
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_charges')
            ->limit(10)
            ->get();
    }
}