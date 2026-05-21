@php
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
            today: 'Today', yesterday: 'Yesterday', last_7_days: 'Last 7 days', last_30_days: 'Last 30 days',
            last_60_days: 'Last 60 days', this_month: 'This Month', last_month: 'Last Month'
        }
    }">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.overall-sales') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-6 py-2.5 text-sm font-semibold text-white shadow-sm">
                <span class="text-emerald-400">✓</span> Overall Sales
            </a>
            <a href="{{ route('payments.invoices') }}" class="rounded-lg bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">My Invoices</a>
            <a href="{{ route('payments.non-cod') }}" class="rounded-lg bg-orange-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600">Add Payment for Non-COD</a>
        </div>

        <div class="flex flex-wrap gap-3">
            @foreach($courierCounts as $courier)
                <a href="{{ route('payments.overall-sales', array_merge($queryBase, ['courier' => $courier['name']])) }}"
                   class="flex min-w-[120px] items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm transition hover:ring-2 {{ $courier['ring'] }} {{ request('courier') === $courier['name'] ? 'ring-2 '.$courier['ring'] : '' }}">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg text-xs font-bold text-white {{ $courier['bg'] }}">
                        {{ Str::substr($courier['name'], 0, 2) }}
                    </span>
                    <span class="text-sm font-semibold text-slate-700">{{ $courier['name'] }} ({{ $courier['count'] }})</span>
                </a>
            @endforeach
            @if(request('courier'))
                <a href="{{ route('payments.overall-sales', $queryBase) }}" class="self-center text-xs font-medium text-teal-600 hover:underline">Clear courier filter</a>
            @endif
        </div>

        <form method="GET" action="{{ route('payments.overall-sales') }}" class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center">
            @foreach(request()->except(['date_range', 'date_from', 'date_to', 'page']) as $key => $val)
                @if(is_string($val) || is_numeric($val))
                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                @endif
            @endforeach
            <div class="relative">
                <button type="button" @click="dateOpen = !dateOpen" class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span x-text="presets[dateRange] || 'Last 60 days'">{{ $dateLabels[$dateRange] ?? 'Last 60 days' }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="dateOpen" @click.outside="dateOpen = false" x-cloak class="absolute left-0 top-full z-50 mt-1 w-72 rounded-xl border border-slate-200 bg-white p-3 shadow-xl sm:w-80">
                    <p class="mb-2 text-xs font-bold uppercase text-slate-500">Quick Select</p>
                    <div class="space-y-1">
                        @foreach($dateLabels as $key => $label)
                            <button type="submit" name="date_range" value="{{ $key }}"
                                    class="block w-full rounded-lg px-3 py-2 text-left text-sm hover:bg-slate-50 {{ $dateRange === $key ? 'bg-teal-50 font-semibold text-teal-700' : 'text-slate-700' }}">{{ $label }}</button>
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
        </form>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border-2 border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-slate-500">Total Delivered Orders</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">{{ $summary['delivered_count'] }}</p>
                <p class="mt-1 text-xs text-slate-500">orders in selected period</p>
            </div>
            <div class="rounded-xl border-2 border-orange-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-orange-700">Total Deductions (DC + Govt Tax)</p>
                <p class="mt-2 text-2xl font-bold text-orange-600">{{ $fmt($summary['total_deductions']) }}</p>
            </div>
            <div class="rounded-xl border-2 border-teal-400 bg-teal-50 p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase text-teal-800">Net Payable Amount</p>
                <p class="mt-2 text-2xl font-bold text-teal-900">{{ $fmt($summary['net_payable']) }}</p>
                <p class="mt-1 text-[10px] text-teal-700">Total COD − deductions</p>
            </div>
        </div>

        <p class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
            Parcel-wise breakdown is available on <a href="{{ route('finance') }}" class="font-semibold text-teal-600 hover:underline">Finance</a> (from My All Orders) and weekly statements on <a href="{{ route('payments.invoices') }}" class="font-semibold text-teal-600 hover:underline">My Invoices</a>.
        </p>
    </div>
</x-app-layout>
