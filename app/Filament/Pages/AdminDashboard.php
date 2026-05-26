<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Page
{
    protected string $view = 'filament.pages.admin-dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?string $title = 'Admin Dashboard';
    protected static ?int $navigationSort = 1;
    protected static string | \UnitEnum | null $navigationGroup = 'Dashboard';

    public function getDashboardData(): array
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $todayBookings = DB::table('bookings')
            ->whereDate('created_at', $today)->count();
        $yesterdayBookings = DB::table('bookings')
            ->whereDate('created_at', $yesterday)->count();

        $todayRevenue = DB::table('bookings')
            ->whereDate('created_at', $today)->sum('delivery_charges');
        $totalRevenue = DB::table('bookings')->sum('delivery_charges');

        $totalCOD = DB::table('bookings')
            ->where('is_cod', 1)->sum('cod_amount');

        $pendingBookings = DB::table('bookings')
            ->where('status', 'pending')->count();
        $deliveredBookings = DB::table('bookings')
            ->where('status', 'delivered')->count();
        $returnedBookings = DB::table('bookings')
            ->where('status', 'returned')->count();
        $inTransitBookings = DB::table('bookings')
            ->where('status', 'in_transit')->count();
        $totalShippers = User::where('role', 'shipper')->count();
        $pendingShippers = User::where('role', 'shipper')
            ->where('is_approved', false)->count();

        $courierStats = DB::table('bookings')
            ->join('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->select('courier_integrations.courier_name as name', DB::raw('count(*) as total'))
            ->groupBy('courier_integrations.courier_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return compact(
            'todayBookings', 'yesterdayBookings',
            'todayRevenue', 'totalRevenue', 'totalCOD',
            'pendingBookings', 'deliveredBookings', 'returnedBookings', 'inTransitBookings',
            'totalShippers', 'pendingShippers', 'courierStats'
        );
    }
}