@php
    use App\Http\Controllers\OrderController;
    use App\Services\OrderFinanceService;
    use Illuminate\Support\Str;

    $dateLabels = [
        'last_60_days' => 'Last 60 days',
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'last_7_days' => 'Last 7 days',
        'last_30_days' => 'Last 30 days',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
    ];

    $queryBase = request()->except(['page']);
    $fmt = fn ($n) => 'Rs. '.number_format((float) $n);
@endphp

<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6" x-data="{
        dateOpen: false,
        dateRange: '{{ $dateRange }}',
        dateFrom: '{{ request('date_from', '') }}',
        dateTo: '{{ request('date_to', '') }}',
        presets: {
            today: 'Today',
            yesterday: 'Yesterday',
            last_7_days: 'Last 7 days',
            last_30_days: 'Last 30 days',
            last_60_days: 'Last 60 days',
            this_month: 'This Month',
            last_month: 'Last Month'
        }
    }">
        {{-- Tabs --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('bookings') }}" class="rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-700">Dashboard</a>
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-6 py-2.5 text-sm font-semibold text-white shadow-sm">
                <span class="text-emerald-400">✓</span> Finance
            </span>
            <a href="{{ route('payments.overall-sales') }}" class="rounded-lg bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">My Payments</a>
        </div>

        {{-- Courier counters --}}
        <div class="flex flex-wrap gap-3">
            @foreach($courierCounts as $courier)
                <a href="{{ route('finance', array_merge($queryBase, ['courier' => $courier['name']])) }}"
                   class="flex min-w-[120px] items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm transition hover:ring-2 {{ $courier['ring'] }} {{ request('courier') === $courier['name'] ? 'ring-2 '.$courier['ring'] : '' }}">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg text-xs font-bold text-white {{ $courier['bg'] }}">
                        {{ Str::substr($courier['name'], 0, 2) }}
                    </span>
                    <span class="text-sm font-semibold text-slate-700">{{ $courier['name'] }} ({{ $courier['count'] }})</span>
                </a>
            @endforeach
            @if(request('courier'))
                <a href="{{ route('finance', $queryBase) }}" class="self-center text-xs font-medium text-teal-600 hover:underline">Clear courier filter</a>
            @endif
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('finance') }}" class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center">
            @foreach(request()->except(['date_range', 'date_from', 'date_to', 'search', 'per_page', 'page']) as $key => $val)
                @if(is_string($val) || is_numeric($val))
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach

            <div class="relative">
                <button type="button" @click="dateOpen = !dateOpen"
                        class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span x-text="presets[dateRange] || 'Last 60 days'">{{ $dateLabels[$dateRange] ?? 'Last 60 days' }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="dateOpen" @click.outside="dateOpen = false" x-cloak
                     class="absolute left-0 top-full z-50 mt-1 w-72 rounded-xl border border-slate-200 bg-white p-3 shadow-xl sm:w-80">
                    <p class="mb-2 text-xs font-bold uppercase text-slate-500">Quick Select</p>
                    <div class="space-y-1">
                        @foreach($dateLabels as $key => $label)
                            <button type="submit" name="date_range" value="{{ $key }}"
                                    class="block w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-slate-50 {{ $dateRange === $key ? 'bg-teal-50 font-semibold text-teal-700' : 'text-slate-700' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                    <div class="mt-3 border-t border-slate-100 pt-3">
                        <p class="mb-2 text-xs font-bold uppercase text-slate-500">Custom Range</p>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="date" name="date_from" x-model="dateFrom" class="rounded-lg border border-slate-200 px-2 py-1.5 text-xs">
                            <input type="date" name="date_to" x-model="dateTo" class="rounded-lg border border-slate-200 px-2 py-1.5 text-xs">
                        </div>
                        <button type="submit" class="mt-2 w-full rounded-lg bg-teal-600 py-2 text-xs font-semibold text-white">Apply</button>
                    </div>
                </div>
            </div>

            <input type="search" name="search" value="{{ request('search') }}"
                   placeholder="Search track no, name, city, reference..."
                   class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">

            <div class="flex items-center gap-2">
                <button type="button" class="rounded-lg bg-emerald-600 p-2 text-white" title="Export Excel">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM8 13h2v5H8v-5zm4-3h2v8h-2v-8zm4 4h2v4h-2v-4z"/></svg>
                </button>
                <button type="button" class="rounded-lg bg-rose-600 p-2 text-white" title="Export PDF">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM7 13h2v5H7v-5zm3-4h2v9h-2V9zm4 2h2v7h-2v-7z"/></svg>
                </button>
                <select name="per_page" onchange="this.form.submit()" class="rounded-lg border border-slate-200 py-2 pl-2 pr-8 text-sm">
                    <option value="25" @selected($perPage == 25)>25 Per Page</option>
                    <option value="50" @selected($perPage == 50)>50 Per Page</option>
                    <option value="100" @selected($perPage == 100)>100 Per Page</option>
                </select>
            </div>
        </form>

        {{-- Financial summary boxes --}}
        <div class="grid grid-cols-2 gap-3 lg:grid-cols-5">
            <div class="rounded-xl border-2 border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-slate-500">Total Delivered Orders</p>
                <p class="mt-2 text-2xl font-bold text-slate-800">{{ $financeSummary['delivered_count'] }}</p>
                <p class="mt-1 text-xs text-slate-500">parcels in payout</p>
            </div>
            <div class="rounded-xl border-2 border-emerald-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-emerald-700">Delivered (Total COD)</p>
                <p class="mt-2 text-xl font-bold text-emerald-700">{{ $fmt($financeSummary['delivered_amount']) }}</p>
            </div>
            <div class="rounded-xl border-2 border-orange-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-orange-700">Total Delivery Charges</p>
                <p class="mt-2 text-xl font-bold text-orange-600">{{ $fmt($financeSummary['delivery_charges']) }}</p>
            </div>
            <div class="rounded-xl border-2 border-violet-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-violet-700">Govt Tax (4%)</p>
                <p class="mt-2 text-xl font-bold text-violet-700">{{ $fmt($financeSummary['govt_tax']) }}</p>
            </div>
            <div class="col-span-2 rounded-xl border-2 border-teal-400 bg-teal-50 p-4 shadow-sm lg:col-span-1">
                <p class="text-xs font-semibold uppercase text-teal-800">Net Payable to Seller</p>
                <p class="mt-2 text-xl font-bold text-teal-900">{{ $fmt($financeSummary['net_payable']) }}</p>
                <p class="mt-1 text-[10px] text-teal-700">COD − Delivery − Tax</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-2">
            <div class="rounded-xl border-2 border-amber-200 bg-amber-50 p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-amber-800">In Progress Orders</p>
                <p class="mt-2 text-2xl font-bold text-amber-900">{{ $inProgressCount }}</p>
                <p class="mt-1 text-xs text-amber-800">Booked on portal, excluding returned / cancelled / lost / delivered</p>
            </div>
            <div class="rounded-xl border-2 border-indigo-200 bg-indigo-50 p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-indigo-800">In Progress (Total COD)</p>
                <p class="mt-2 text-2xl font-bold text-indigo-900">{{ $fmt($inProgressCodAmount) }}</p>
                <p class="mt-1 text-xs text-indigo-800">Exposure for parcels not yet settled as delivered</p>
            </div>
        </div>

        {{-- Delivered orders table (no booking action buttons) --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50 px-4 py-2">
                <p class="text-sm font-semibold text-slate-700">Delivered Order Details</p>
                <p class="text-xs text-slate-500">Per-parcel COD, delivery charges, 4% tax & net payout</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1000px] text-left text-sm">
                    <thead class="bg-slate-700 text-xs font-semibold uppercase text-white">
                        <tr>
                            <th class="px-3 py-3">Sr No</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Tracking</th>
                            <th class="px-3 py-3">Consignee</th>
                            <th class="px-3 py-3">Destination</th>
                            <th class="px-3 py-3">Date</th>
                            <th class="px-3 py-3">COD</th>
                            <th class="px-3 py-3">Act.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            @php
                                $meta = OrderController::statusMeta($order->status);
                                $sr = $orders->firstItem() + $index;
                                $created = $order->created_at ? \Carbon\Carbon::parse($order->created_at) : null;
                                $courierLabel = $order->courier_name ?? 'Standard';
                                $origin = $order->origin_city ?? 'Origin';
                                $route = $origin.' → '.$order->destination_city;
                                $fin = OrderFinanceService::parcelBreakdown($order);
                                $address = $order->consignee_address ?? '';
                            @endphp
                            <tr class="border-b border-slate-100 align-top">
                                <td class="px-3 py-4">
                                    <p class="font-bold text-slate-800">{{ $sr }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->reference_no ?? 'N/A' }}</p>
                                </td>
                                <td class="px-3 py-4">
                                    <span class="inline-block rounded px-2 py-0.5 text-xs font-semibold {{ $meta['badge'] }}">{{ $meta['label'] }}</span>
                                    <p class="mt-1 text-xs text-emerald-600">{{ $meta['shipment'] }}</p>
                                </td>
                                <td class="px-3 py-4">
                                    <p class="text-xs capitalize text-slate-500">{{ Str::lower($courierLabel) }}</p>
                                    <p class="font-mono text-sm font-bold text-sky-600">{{ $order->tracking_number ?? 'N/A' }}</p>
                                </td>
                                <td class="px-3 py-4">
                                    <p class="font-semibold text-slate-800">{{ $order->customer_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $order->customer_phone ?? '—' }}</p>
                                </td>
                                <td class="px-3 py-4 text-slate-700">{{ $route }}</td>
                                <td class="px-3 py-4">
                                    @if($created)
                                        <p class="font-medium text-slate-800">{{ $created->format('d M Y') }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <p class="text-lg font-bold text-sky-600">{{ number_format($fin['cod']) }}</p>
                                </td>
                                <td class="px-3 py-4">
                                    <a href="{{ route('bookings.slip', $order->id) }}" target="_blank" class="text-xs font-medium text-teal-600 hover:underline">Slip</a>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-100 bg-slate-50/90">
                                <td colspan="8" class="px-3 py-3">
                                    @if($address)
                                        <p class="mb-2 flex items-start gap-1 text-xs text-slate-600">
                                            <span class="text-rose-500">📍</span>
                                            <span>{{ $address }}</span>
                                        </p>
                                    @endif
                                    <div class="mb-2 flex flex-wrap gap-2 text-xs">
                                        <span class="rounded bg-slate-200 px-2 py-1 text-slate-700">
                                            <strong>Product:</strong> {{ $order->product_name ?? 'General Item' }}
                                        </span>
                                        <span class="rounded bg-slate-200 px-2 py-1 text-slate-700">
                                            <strong>Weight:</strong> {{ $order->weight ?? '0.5' }} kg
                                        </span>
                                        @if($order->reference_no)
                                            <span class="rounded bg-slate-200 px-2 py-1 text-slate-700">
                                                <strong>Ref:</strong> {{ $order->reference_no }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                                        <div class="rounded-lg border border-sky-200 bg-sky-50 px-3 py-2">
                                            <p class="text-[10px] uppercase text-sky-600">COD Amount</p>
                                            <p class="font-bold text-sky-700">{{ $fmt($fin['cod']) }}</p>
                                        </div>
                                        <div class="rounded-lg border border-orange-200 bg-orange-50 px-3 py-2">
                                            <p class="text-[10px] uppercase text-orange-600">Delivery Charges</p>
                                            <p class="font-bold text-orange-700">{{ $fmt($fin['delivery_charges']) }}</p>
                                        </div>
                                        <div class="rounded-lg border border-violet-200 bg-violet-50 px-3 py-2">
                                            <p class="text-[10px] uppercase text-violet-600">Govt Tax (4%)</p>
                                            <p class="font-bold text-violet-700">{{ $fmt($fin['govt_tax']) }}</p>
                                        </div>
                                        <div class="rounded-lg border border-teal-300 bg-teal-50 px-3 py-2">
                                            <p class="text-[10px] uppercase text-teal-700">Net to Seller</p>
                                            <p class="font-bold text-teal-800">{{ $fmt($fin['net_amount']) }}</p>
                                        </div>
                                    </div>
                                    <span class="mt-2 inline-block rounded bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">
                                        Delivered ({{ strtoupper($courierLabel) }})
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-500">
                                    No delivered orders found for selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($orders->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
