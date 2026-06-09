<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @php
        $stats = $this->getStats();
    @endphp

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Company Live Balance</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['company_live_balance'], 2) }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Merchant Payables</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['merchant_payables'], 2) }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Courier Receivable</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['courier_receivable'], 2) }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Tax Collected (4%)</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['tax_collected'], 2) }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Available Cash (Profit w/o Tax)</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['available_cash'], 2) }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Booked Today</h3>
        <p class="text-xl font-semibold">{{ $stats['book_today'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Dispatched</h3>
        <p class="text-xl font-semibold">{{ $stats['dispatched'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Delivered</h3>
        <p class="text-xl font-semibold">{{ $stats['delivered'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">In Progress</h3>
        <p class="text-xl font-semibold">{{ $stats['in_progress'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Issue Orders</h3>
        <p class="text-xl font-semibold">{{ $stats['issue_orders'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Ready to Return</h3>
        <p class="text-xl font-semibold">{{ $stats['ready_to_return'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Return Confirmed</h3>
        <p class="text-xl font-semibold">{{ $stats['return_confirmed'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Total Returned</h3>
        <p class="text-xl font-semibold">{{ $stats['total_returned'] }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Gross Profit</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['gross_profit'], 2) }}</p>
    </x-filament::card>

    <x-filament::card>
        <h3 class="text-sm font-medium text-gray-500">Net Profit</h3>
        <p class="text-xl font-semibold">{{ number_format($stats['net_profit'], 2) }}</p>
    </x-filament::card>
</div>