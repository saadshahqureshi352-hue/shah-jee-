{{-- resources/views/filament/pages/invoices.blade.php --}}
{{-- Custom invoice page layout --}}
@extends('filament::page')

@section('content')
    @if(isset($invoice) && $invoice instanceof \App\Models\Invoice)
        <div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-4">Invoice #{{ $invoice->id }}</h1>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="font-medium">Merchant:</p>
                    <p>{{ $invoice->merchant->business_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Period:</p>
                    <p>{{ $invoice->period_date ? $invoice->period_date->format('F Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="font-medium">Status:</p>
                    <p class="capitalize">{{ ucfirst($invoice->status) }}</p>
                </div>
                <div>
                    <p class="font-medium">Total COD:</p>
                    <p>{{ number_format($invoice->total_cod, 2) }} PKR</p>
                </div>
                <div>
                    <p class="font-medium">Delivery Charges:</p>
                    <p>{{ number_format($invoice->delivery_charges_deducted, 2) }} PKR</p>
                </div>
                <div>
                    <p class="font-medium">Tax (4%):</p>
                    <p>{{ number_format($invoice->tax_deducted, 2) }} PKR</p>
                </div>
                <div class="col-span-2">
                    <p class="font-medium">Net Payable:</p>
                    <p class="text-lg font-semibold">{{ number_format($invoice->net_payable, 2) }} PKR</p>
                </div>
            </div>

            @if($invoice->orders && $invoice->orders->count())
                <h2 class="text-xl font-semibold mb-3">Orders</h2>
                <table class="min-w-full bg-white border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 text-left">#</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->orders as $order)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $order->id }}</td>
                                <td class="px-4 py-2">{{ $order->customer_name ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ number_format($order->amount, 2) }} PKR</td>
                                <td class="px-4 py-2 capitalize">{{ $order->status }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No orders associated with this invoice.</p>
            @endif
        </div>
    @else
        {{-- Fallback to the default Filament table view --}}
        {{ $this->table($table) }}
    @endif
@endsection