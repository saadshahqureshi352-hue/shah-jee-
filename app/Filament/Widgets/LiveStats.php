<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Courier;
use App\Models\User;
use Carbon\Carbon;

class LiveStats extends Widget
{
    protected static string $view = 'filament.widgets.live-stats';

    public $dateRange; // ['from' => Carbon, 'to' => Carbon]

    public function mount()
    {
        // Default to today
        $this->dateRange = [
            'from' => Carbon::today(),
            'to'   => Carbon::today(),
        ];
    }

    public function getStats()
    {
        $from = $this->dateRange['from']->startOfDay();
        $to   = $this->dateRange['to']->endOfDay();

        $orders = Order::whereBetween('created_at', [$from, $to]);

        $delivered = (clone $orders)->where('status', 'delivered');

        $totalCod = $delivered->sum('cod_amount');
        $grossProfit = $delivered->sum(function ($order) {
            $courierRate = $order->courier?->courier_rate ?? 0;
            $merchantRate = $order->courier?->merchant_rate ?? 0;
            return $merchantRate - $courierRate;
        });

        $taxCollected = $delivered->sum('tax_amount'); // 4% tax

        $courierReceivable = $delivered->sum(function ($order) {
            $courierRate = $order->courier?->courier_rate ?? 0;
            $courierTax = round($order->cod_amount * 0.02, 2);
            return max(0, $order->cod_amount - $courierRate - $courierTax);
        });

        $merchantPayable = $delivered->sum(function ($order) {
            return $order->merchantNetPayable();
        });

        $availableCash = $grossProfit; // profit without tax

        $booked = (clone $orders)->where('status', 'booked')->count();
        $dispatched = (clone $orders)->where('status', 'dispatched')->count();
        $inProgress = (clone $orders)->whereIn('status', ['dispatched', 'in_transit'])->count();
        $deliveredCount = $delivered->count();
        $issue = (clone $orders)->where('status', 'issue_detected')->count();
        $readyToReturn = (clone $orders)->where('status', 'ready_to_return')->count();
        $returnConfirmed = (clone $orders)->where('status', 'return_confirmed')->count();
        $returned = (clone $orders)->where('status', 'returned')->count();

        $netProfit = $availableCash - $taxCollected; // simplified

        return [
            'company_live_balance' => $totalCod,
            'merchant_payables' => $merchantPayable,
            'courier_receivable' => $courierReceivable,
            'tax_collected' => $taxCollected,
            'available_cash' => $availableCash,
            'book_today' => $booked,
            'dispatched' => $dispatched,
            'delivered' => $deliveredCount,
            'in_progress' => $inProgress,
            'issue_orders' => $issue,
            'ready_to_return' => $readyToReturn,
            'return_confirmed' => $returnConfirmed,
            'total_returned' => $returned,
            'gross_profit' => $grossProfit,
            'net_profit' => $netProfit,
        ];
    }
}