<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class ShipmentManagement extends Page
{

    protected string $view = 'filament.pages.shipment-management';
    protected static ?string $navigationLabel = 'Shipments';
    protected static ?string $title = 'Shipment Management';
    protected static string | \UnitEnum | null $navigationGroup = 'Shipment Management';
    protected static ?int $navigationSort = 1;

    public string $search = '';
    public string $status = 'all';
    public string $dateFrom = '';
    public string $dateTo = '';
    public array $selected = [];

    public function getShipments()
    {
        return DB::table('bookings')
            ->leftJoin('courier_integrations', 'bookings.courier_integration_id', '=', 'courier_integrations.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->select(
                'bookings.*',
                'courier_integrations.courier_name as courier_name',
                'users.name as merchant_name'
            )
            ->when($this->search, fn($q) =>
                $q->where('bookings.customer_name', 'like', "%{$this->search}%")
                  ->orWhere('bookings.tracking_number', 'like', "%{$this->search}%")
                  ->orWhere('users.name', 'like', "%{$this->search}%")
            )
            ->when($this->status !== 'all', fn($q) => $q->where('bookings.status', $this->status))
            ->when($this->dateFrom, fn($q) => $q->whereDate('bookings.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('bookings.created_at', '<=', $this->dateTo))
            ->orderByDesc('bookings.created_at')
            ->paginate(20);
    }

    public function updateStatus(int $id, string $status): void
    {
        DB::table('bookings')->where('id', $id)->update(['status' => $status]);
        Notification::make()->title('Status updated!')->success()->send();
    }
}