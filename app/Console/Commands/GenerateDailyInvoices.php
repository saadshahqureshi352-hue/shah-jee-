<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateDailyInvoices extends Command
{
    protected $signature = 'invoices:generate-daily';
    protected $description = 'Generate daily invoices for each merchant based on delivered orders';

    public function handle()
    {
        $this->info('Starting daily invoice generation...');

        // Get delivered orders without an invoice
        $orders = Order::where('status', 'delivered')
            ->whereNull('invoice_id')
            ->with(['merchant.pricingPlan'])
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No delivered orders pending invoicing.');
            return 0;
        }

        // Group orders by merchant
        $grouped = $orders->groupBy('merchant_id');

        DB::transaction(function () use ($grouped) {
            foreach ($grouped as $merchantId => $merchantOrders) {
                /** @var User $merchant */
                $merchant = User::find($merchantId);
                $plan = $merchant->pricingPlan;

                $totalCod = $merchantOrders->sum('cod_amount');
                $deliveryCharges = $merchantOrders->sum('delivery_charges');
                $taxDeducted = round($totalCod * 0.04, 2); // 4% tax

                $netPayable = max(0, $totalCod - $deliveryCharges - $taxDeducted);

                $invoice = Invoice::create([
                    'merchant_id' => $merchantId,
                    'period_date' => Carbon::today(),
                    'total_cod' => $totalCod,
                    'delivery_charges_deducted' => $deliveryCharges,
                    'tax_deducted' => $taxDeducted,
                    'net_payable' => $netPayable,
                    'status' => 'pending',
                ]);

                // Attach orders to invoice
                $merchantOrders->each(function (Order $order) use ($invoice) {
                    $order->invoice_id = $invoice->id;
                    $order->save();
                });

                $this->info("Generated invoice #{$invoice->id} for merchant {$merchant->name}");
            }
        });

        $this->info('Daily invoice generation completed.');
        return 0;
    }
}