@php
    use App\Http\Controllers\OrderController;
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

    $queryBase = request()->except(['status_filter', 'page']);
@endphp

<x-app-layout>

    {{-- Main Container with state architecture --}}
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
        },

        // Bulk Print Labels
        showBulkModal: false,
        bulkOrders: [],
        selectedOrders: [],
        bulkDateRange: 'today',
        bulkSearch: '',
        loadingOrders: false,
        bulkDateDropdown: false,
        bulkSearchTimeout: null,
        bulkDateLabels: {
            all_time: 'All Time Results',
            today: 'Today Results',
            yesterday: 'Yesterday Results',
            last_7: 'Last 7 Days',
            last_30: 'Last 30 Days'
        },

        fetchBulkOrders() {
            this.loadingOrders = true;
            let params = `date_range=${this.bulkDateRange}`;
            if (this.bulkSearch.trim()) {
                params += `&search=${encodeURIComponent(this.bulkSearch.trim())}`;
            }
            fetch(`/api/bulk-print-orders?${params}`)
                .then(res => res.json())
                .then(data => {
                    this.bulkOrders = data.orders ?? data;
                    this.selectedOrders = [];
                    this.loadingOrders = false;
                })
                .catch(() => { this.loadingOrders = false; });
        },

        onBulkSearchInput() {
            clearTimeout(this.bulkSearchTimeout);
            this.bulkSearchTimeout = setTimeout(() => {
                this.fetchBulkOrders();
            }, 400);
        },

        get filteredOrders() {
            return this.bulkOrders;
        },

        toggleAll() {
            if (this.selectedOrders.length === this.bulkOrders.length && this.bulkOrders.length > 0) {
                this.selectedOrders = [];
            } else {
                this.selectedOrders = this.bulkOrders.map(o => o.id);
            }
        },

        // Shipper Detail
        shipperModalOpen: false,
        shipper: {
            id: '',
            name: '',
            phone: '',
            tracking: '',
            status: '',
            courier: '',
            destination: '',
            address: '',
            product: '',
            reference: '',
            weight: '',
            quantity: '1',
            cod: '0',
            service: '',
            date: '',
            printUrl: ''
        },

        printSelected() {
            if (this.selectedOrders.length === 0) return;
            const url = `{{ route('bookings.bulk-print') }}?ids=${this.selectedOrders.join(',')}`;
            window.open(url, '_blank');
        }

    }" @open-shipper-detail.window="
        shipper = {
            id: $event.detail.id,
            name: $event.detail.name,
            phone: $event.detail.phone,
            tracking: $event.detail.tracking,
            status: $event.detail.status,
            courier: $event.detail.courier,
            destination: $event.detail.destination,
            address: $event.detail.address,
            product: $event.detail.product,
            reference: $event.detail.reference,
            weight: $event.detail.weight,
            quantity: $event.detail.quantity,
            cod: $event.detail.cod,
            service: $event.detail.service,
            date: $event.detail.date,
            printUrl: '/bookings/' + $event.detail.id + '/slip'
        };
        shipperModalOpen = true;
    ">
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Top tabs: Dashboard + Finance only --}}
        <div class="flex flex-wrap gap-2">
            <span class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm">Dashboard</span>
            <a href="{{ route('finance') }}" class="rounded-lg bg-slate-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-600">Finance</a>
        </div>

        {{-- Courier integration counters --}}
        <div class="flex flex-wrap gap-3">
            @foreach($courierCounts as $courier)
                <a href="{{ route('bookings', array_merge($queryBase, ['courier' => $courier['name']])) }}"
                   class="flex min-w-[120px] items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm transition hover:ring-2 {{ $courier['ring'] }} {{ request('courier') === $courier['name'] ? 'ring-2 '.$courier['ring'] : '' }}">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg text-xs font-bold text-white {{ $courier['bg'] }}">
                        {{ Str::substr($courier['name'], 0, 2) }}
                    </span>
                    <span class="text-sm font-semibold text-slate-700">{{ $courier['name'] }} ({{ $courier['count'] }})</span>
                </a>
            @endforeach
            @if(request('courier'))
                <a href="{{ route('bookings', $queryBase) }}" class="self-center text-xs font-medium text-teal-600 hover:underline">Clear courier filter</a>
            @endif
        </div>

        {{-- Filters bar --}}
        <form method="GET" action="{{ route('bookings') }}" class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center">
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

            <div class="flex items-center gap-2" x-data="{ exportModal: false, exportType: 'excel', exportStatus: 'all' }">
                <button type="button" @click="exportType='excel'; exportModal=true" class="rounded-lg bg-emerald-600 p-2 text-white hover:bg-emerald-700 transition" title="Export Excel">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM8 13h2v5H8v-5zm4-3h2v8h-2v-8zm4 4h2v4h-2v-4z"/></svg>
                </button>
                <button type="button" @click="exportType='pdf'; exportModal=true" class="rounded-lg bg-rose-600 p-2 text-white hover:bg-rose-700 transition" title="Export PDF">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 2l5 5h-5V4zM7 13h2v5H7v-5zm3-4h2v9h-2V9zm4 2h2v7h-2v-7z"/></svg>
                </button>

                {{-- Export Status Selection Modal --}}
                <div x-show="exportModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
                    <div class="fixed inset-0 bg-slate-900/50" @click="exportModal = false"></div>
                    <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white shadow-2xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-slate-900">
                                Export <span x-text="exportType.toUpperCase()"></span>
                            </h3>
                            <button type="button" @click="exportModal = false" class="rounded-lg border border-slate-200 p-2 text-slate-400 hover:bg-slate-50 hover:text-slate-700 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <p class="text-sm text-slate-500 mb-4">Select which orders you want to export:</p>
                        <div class="space-y-2 mb-6">
                            <template x-for="(label, key) in { all: '📋 All Orders', pending: '📝 Booked (Pending)', dispatched: '🚚 In Progress (Dispatched)', in_transit: '🚛 In Transit', out_for_delivery: '📬 Out for Delivery', delivered: '✅ Delivered', cancelled: '❌ Cancelled', returned: '↩️ Returned', lost: '🔴 Lost' }">
                                <label class="flex items-center gap-3 rounded-lg border border-slate-200 p-3 cursor-pointer hover:bg-slate-50 transition">
                                    <input type="radio" name="exportStatus" :value="key" x-model="exportStatus" class="h-4 w-4 text-teal-600 border-slate-300">
                                    <span class="text-sm font-medium text-slate-700" x-text="label"></span>
                                </label>
                            </template>
                        </div>
                        <a :href="'{{ url('/export') }}/' + exportType + '?status=' + exportStatus" target="_blank"
                           class="block w-full rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 py-3 text-center text-sm font-bold text-white shadow-md hover:shadow-lg transition">
                            Download <span x-text="exportType.toUpperCase()"></span>
                        </a>
                    </div>
                </div>

                <select name="per_page" onchange="this.form.submit()" class="rounded-lg border border-slate-200 py-2 pl-2 pr-8 text-sm">
                    <option value="25" @selected($perPage == 25)>25 Per Page</option>
                    <option value="50" @selected($perPage == 50)>50 Per Page</option>
                    <option value="100" @selected($perPage == 100)>100 Per Page</option>
                </select>
            </div>
        </form>

        {{-- Status cards --}}
        <div class="grid grid-cols-2 gap-2 sm:grid-cols-4 lg:grid-cols-7 xl:grid-cols-14">
            @foreach($statusCards as $card)
                <a href="{{ route('bookings', array_merge($queryBase, ['status_filter' => $card['key']])) }}"
                   class="rounded-xl border bg-white p-2.5 text-center shadow-sm transition hover:shadow-md sm:p-3
                   {{ $statusFilter === $card['key'] ? 'border-orange-400 ring-2 ring-orange-200' : 'border-slate-200' }}">
                    @if($statusFilter === $card['key'])
                        <span class="mb-1 inline-block text-orange-500">✓</span>
                    @endif
                    <p class="text-[10px] font-medium text-slate-500 sm:text-xs">{{ $card['label'] }}</p>
                    <p class="text-lg font-bold text-slate-800">{{ $card['count'] }}</p>
                </a>
            @endforeach
        </div>

        {{-- Action buttons --}}
        <div class="flex flex-wrap gap-2" x-data="{ showCourierModal: false }">
            <a href="{{ route('bookings.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
                <span class="text-lg leading-none">+</span> Book New Packet
            </a>

            <button type="button" @click="showCourierModal = true" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">Generate Loadsheet</button>

            <button type="button" @click="showBulkModal = true; fetchBulkOrders();" class="inline-flex items-center gap-2 rounded-lg bg-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600">Bulk Print Labels</button>

            {{-- Courier Selection Modal --}}
            <div x-show="showCourierModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-slate-900/50" @click="showCourierModal = false"></div>
                <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl">
                    <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900">Select Courier</h1>
                                <p class="text-sm text-slate-500">Pick a partner to manage loadsheet</p>
                            </div>
                        </div>
                        <button type="button" @click="showCourierModal = false" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <ul class="max-h-[min(70vh,520px)] space-y-2 overflow-y-auto p-4">
                        @foreach(\App\Http\Controllers\BookingController::COURIERS as $slug => $courier)
                            <li>
                                <a href="{{ route('bookings.loadsheets', ['courier' => $slug]) }}"
                                   class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 transition hover:border-violet-300 hover:shadow-md">
                                    <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white {{ $courier['bg'] }}">
                                        {{ Str::substr($courier['name'], 0, 2) }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-semibold text-slate-800">{{ $courier['name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $courier['tagline'] }}</p>
                                    </div>
                                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-violet-600 text-white transition group-hover:bg-violet-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="flex items-center gap-2 border-t border-slate-100 px-5 py-3 text-xs text-slate-500">
                        <span class="h-2 w-2 rounded-full bg-violet-600"></span>
                        {{ count(\App\Http\Controllers\BookingController::COURIERS) }} couriers available
                    </div>
                </div>
            </div>

            {{-- Bulk Print Labels Modal --}}
            <div x-show="showBulkModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-slate-900/50" @click="showBulkModal = false"></div>
                <div class="relative w-full max-w-4xl rounded-2xl border border-slate-200 bg-white shadow-2xl">
                    
                    {{-- Header Controls Row as shown in Picture 2 --}}
                    <div class="flex items-center gap-2 border-b border-slate-200 bg-white p-2">
                        
                        {{-- Checkbox Toggle Column Master --}}
                        <div class="flex items-center justify-center border-r border-slate-200 pr-3 pl-2">
                            <input type="checkbox" @click="toggleAll()" :checked="filteredOrders.length > 0 && selectedOrders.length === filteredOrders.length" class="h-4 w-4 rounded border-slate-300 text-orange-500 focus:ring-orange-500">
                        </div>

                        {{-- Dropdown Filter Menu --}}
                        <div class="relative">
                            <button type="button" @click="bulkDateDropdown = !bulkDateDropdown" class="flex items-center gap-2 rounded border border-slate-300 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                                <span x-text="bulkDateLabels[bulkDateRange]">Today Results</span>
                                <svg class="h-3.5 w-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="bulkDateDropdown" @click.outside="bulkDateDropdown = false" x-cloak class="absolute left-0 top-full z-50 mt-1 w-48 rounded-lg border border-slate-200 bg-white py-1 shadow-xl">
                                <template x-for="(label, key) in bulkDateLabels">
                                    <button type="button" @click="bulkDateRange = key; bulkDateDropdown = false; fetchBulkOrders();" x-text="label" class="block w-full px-4 py-2 text-left text-xs text-slate-700 hover:bg-slate-100"></button>
                                </template>
                            </div>
                        </div>

                        {{-- Dynamic Client Search Bar Input (Sends search to backend on each keystroke) --}}
                        <div class="flex-1">
                            <input type="text" x-model="bulkSearch" @input="onBulkSearchInput()" placeholder="Search track #, reference, or customer name..." class="w-full rounded border border-slate-300 px-3 py-1.5 text-xs focus:border-orange-500 focus:ring-orange-500">
                        </div>

                        {{-- Action Control Options Group --}}
                        <button type="button" @click="fetchBulkOrders()" class="flex items-center gap-1 rounded bg-slate-700 px-3 py-1.5 text-xs font-bold text-white hover:bg-slate-800 transition">
                            🔄 Refresh
                        </button>
                        <button type="button" @click="showBulkModal = false" class="rounded border border-slate-200 p-1.5 text-slate-400 hover:bg-slate-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Central Content Area --}}
                    <div class="min-h-[250px] max-h-[50vh] overflow-y-auto bg-white p-4">
                        
                        {{-- Loading Indicator state --}}
                        <div x-show="loadingOrders" class="py-16 text-center text-xs font-medium text-slate-500">
                            Syncing database packages... Please hold on.
                        </div>

                        {{-- Picture 2 Empty Case Fallback handling --}}
                        <div x-show="!loadingOrders && filteredOrders.length === 0" class="py-12 text-center">
                            <div class="inline-block rounded-full bg-slate-50 p-3 mb-2 text-xl text-slate-400">📬</div>
                            <p class="text-xs font-bold text-slate-600">No orders found.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Adjust filter status to 'All Time Results' or refine search.</p>
                        </div>

                        {{-- Orders Data Stream Grid View --}}
                        <div x-show="!loadingOrders && filteredOrders.length > 0" class="overflow-hidden rounded-lg border border-slate-200">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-50 font-bold uppercase text-slate-600 border-b border-slate-200">
                                    <tr>
                                        <th class="p-2.5 w-10">Select</th>
                                        <th class="p-2.5">Tracking Reference</th>
                                        <th class="p-2.5">Consignee Name</th>
                                        <th class="p-2.5">Destination City</th>
                                        <th class="p-2.5 text-right">COD Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="order in filteredOrders" :key="order.id">
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="p-2.5"><input type="checkbox" :value="order.id" x-model="selectedOrders" class="rounded border-slate-300 text-orange-500 focus:ring-orange-500"></td>
                                            <td class="p-2.5 font-mono font-bold text-slate-900" x-text="order.tracking_number || 'Pending Assignment'"></td>
                                            <td class="p-2.5 text-slate-700 font-medium" x-text="order.customer_name"></td>
                                            <td class="p-2.5 text-slate-600" x-text="order.destination_city"></td>
                                            <td class="p-2.5 text-right font-bold text-sky-600" x-text="'Rs. ' + Number(order.cod_amount).toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Footer Process Tracking Actions Row --}}
                    <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-4 py-3">
                        <div class="text-xs font-semibold text-slate-500">
                            <span x-text="selectedOrders.length" class="text-orange-600 font-bold">0</span> of <span x-text="filteredOrders.length">0</span> selected · max 60
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="showBulkModal = false" class="rounded border border-slate-300 bg-white px-4 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-50">Cancel</button>
                            <button type="button" @click="printSelected()" :disabled="selectedOrders.length === 0" :class="selectedOrders.length === 0 ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-orange-500 hover:bg-orange-600 text-white shadow-sm'" class="rounded px-5 py-1.5 text-xs font-bold transition">↓ Download Labels</button>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Shipper Detail Modal --}}
            <div x-show="shipperModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                <div class="fixed inset-0 bg-slate-900/50" @click="shipperModalOpen = false"></div>
                <div class="relative w-full max-w-lg rounded-2xl border border-slate-200 bg-white shadow-2xl">
                    <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-sky-600 text-white shadow-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900" x-text="'Shipper Detail — ' + (shipper.tracking || '')"></h1>
                                <p class="text-sm text-slate-500">Live status from the system</p>
                            </div>
                        </div>
                        <button type="button" @click="shipperModalOpen = false" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-800">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="max-h-[min(70vh,520px)] overflow-y-auto p-5 space-y-4">
                        {{-- Status Badge --}}
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold uppercase text-slate-500 w-28 shrink-0">Status</span>
                            <span class="inline-block rounded px-3 py-1 text-xs font-bold text-white"
                                  :class="{
                                      'bg-emerald-500': shipper.status === 'delivered',
                                      'bg-amber-500': shipper.status === 'pending',
                                      'bg-violet-500': shipper.status === 'in_transit',
                                      'bg-indigo-500': shipper.status === 'out_for_delivery',
                                      'bg-blue-500': shipper.status === 'dispatched',
                                      'bg-red-500': shipper.status === 'cancelled' || shipper.status === 'returned',
                                      'bg-slate-500': !['delivered','pending','in_transit','out_for_delivery','dispatched','cancelled','returned'].includes(shipper.status)
                                  }"
                                  x-text="(shipper.status || 'unknown').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                        </div>

                        {{-- Shipper / Customer Details --}}
                        <div class="border-t border-slate-100 pt-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Shipper Information</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Consignee</span><span class="font-semibold text-slate-800" x-text="shipper.name"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Phone</span><span class="font-semibold text-slate-800" x-text="shipper.phone || '—'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Address</span><span class="text-slate-700" x-text="shipper.address || '—'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Destination</span><span class="font-semibold text-slate-800" x-text="shipper.destination"></span></div>
                            </div>
                        </div>

                        {{-- Order Details --}}
                        <div class="border-t border-slate-100 pt-3">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-2">Order Details</p>
                            <div class="space-y-2 text-xs">
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Tracking #</span><span class="font-mono font-bold text-sky-600" x-text="shipper.tracking || '—'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Reference</span><span class="text-slate-700" x-text="shipper.reference || '—'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Courier</span><span class="font-semibold capitalize text-slate-800" x-text="shipper.courier"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Service</span><span class="text-slate-700" x-text="shipper.service || '—'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Product</span><span class="text-slate-700" x-text="shipper.product || '—'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Weight / Qty</span><span class="text-slate-700" x-text="(shipper.weight || '0') + ' KG / ' + (shipper.quantity || '1') + ' Pcs'"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">COD Amount</span><span class="font-bold text-sky-600" x-text="'Rs. ' + Number(shipper.cod || 0).toLocaleString()"></span></div>
                                <div class="flex"><span class="text-slate-500 w-28 shrink-0">Date Booked</span><span class="text-slate-700" x-text="shipper.date || '—'"></span></div>
                            </div>
                        </div>

                        {{-- Print Label Button Inside Modal --}}
                        <div class="border-t border-slate-100 pt-3 flex justify-end">
                            <a :href="shipper.printUrl || '#'" target="_blank"
                               class="inline-flex items-center gap-2 rounded-lg bg-orange-500 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-orange-600 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m4-4v6m0 0l-3-3m3 3l3-3"/></svg>
                                Print Label
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders table --}}

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
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
                            @endphp
                            <tr class="border-b border-slate-100 align-top">
                                <td class="px-3 py-4">
                                    <p class="font-bold text-slate-800">{{ $sr }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->reference_no ?? 'N/A' }}</p>
                                </td>
                                <td class="px-3 py-4">
                                    <span class="inline-block rounded px-2 py-0.5 text-xs font-semibold {{ $meta['badge'] }}">{{ $meta['label'] }}</span>
                                    <p class="mt-1 text-xs text-rose-500">{{ $meta['shipment'] }}</p>
                                    @if($created)
                                        <p class="mt-1 text-[10px] text-slate-400">Picked: {{ $created->format('d M Y') }}</p>
                                        <p class="text-[10px] text-slate-400">Last: {{ $created->diffForHumans() }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <p class="text-xs capitalize text-slate-500">{{ Str::lower($courierLabel) }}</p>
                                    <p class="font-mono text-sm font-bold text-sky-600">{{ $order->tracking_number ?? 'N/A' }}</p>
                                    <span class="mt-1 inline-block rounded bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">{{ $order->service_type ?? 'Regular' }}</span>
                                </td>
                                <td class="px-3 py-4">
                                    <p class="font-semibold text-slate-800">{{ $order->customer_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $order->customer_phone ?? '—' }}</p>
                                </td>
                                <td class="px-3 py-4 text-slate-700">{{ $route }}</td>
                                <td class="px-3 py-4">
                                    @if($created)
                                        <p class="font-medium text-slate-800">{{ $created->format('d M Y') }}</p>
                                        <p class="text-xs text-slate-400">{{ $created->diffForHumans() }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-4">
                                    <p class="text-lg font-bold text-sky-600">{{ number_format($order->cod_amount) }}</p>
                                    <p class="text-xs text-orange-500">{{ $order->weight ?? '0.5' }}kg</p>
                                </td>
                                <td class="px-2 py-3 relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.outside="open = false" class="p-1 rounded hover:bg-slate-100 transition cursor-pointer">
                                        <svg class="h-5 w-5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                    </button>
                                    <div x-show="open" x-cloak @click.outside="open = false" class="absolute right-0 z-50 mt-1 w-36 rounded-lg border border-slate-200 bg-white py-0.5 shadow-lg text-xs">
                                        <button type="button" @click="open = false; $dispatch('open-shipper-detail', { id: '{{ $order->id }}', name: '{{ addslashes($order->customer_name) }}', phone: '{{ addslashes($order->customer_phone ?? '') }}', tracking: '{{ $order->tracking_number ?? '' }}', status: '{{ $order->status }}', courier: '{{ addslashes($courierLabel) }}', destination: '{{ addslashes($order->destination_city) }}', address: '{{ addslashes($order->consignee_address ?? '') }}', product: '{{ addslashes($order->product_name ?? '') }}', reference: '{{ addslashes($order->reference_no ?? '') }}', weight: '{{ $order->weight }}', quantity: '{{ $order->quantity ?? 1 }}', cod: '{{ $order->cod_amount }}', service: '{{ addslashes($order->service_type ?? '') }}', date: '{{ $created ? $created->format('d M Y h:i A') : '' }}' })" 
                                                class="flex w-full items-center gap-1.5 px-3 py-1.5 text-[11px] text-slate-700 hover:bg-slate-50">
                                            <svg class="h-3.5 w-3.5 text-sky-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            Details
                                        </button>
                                        @if($order->status === 'pending')
                                        <button type="button" onclick="cancelOrder({{ $order->id }})"
                                                class="flex w-full items-center gap-1.5 px-3 py-1.5 text-[11px] text-rose-600 hover:bg-rose-50">
                                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            Cancel
                                        </button>
                                        <a href="{{ route('bookings.edit', $order->id) }}"
                                           class="flex w-full items-center gap-1.5 px-3 py-1.5 text-[11px] text-amber-600 hover:bg-amber-50">
                                            <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        @endif
                                        <a href="{{ url('/track/'.$order->tracking_number) }}" target="_blank"
                                           class="flex w-full items-center gap-1.5 px-3 py-1.5 text-[11px] text-slate-700 hover:bg-slate-50">
                                            <svg class="h-3.5 w-3.5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            Tracking
                                        </a>
                                        <a href="{{ route('bookings.slip', $order->id) }}" target="_blank"
                                           class="flex w-full items-center gap-1.5 px-3 py-1.5 text-[11px] text-slate-700 hover:bg-slate-50">
                                            <svg class="h-3.5 w-3.5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m4-4v6m0 0l-3-3m3 3l3-3"/></svg>
                                            Print
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-slate-100 bg-slate-50/80">
                                <td colspan="8" class="px-3 py-2">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span class="rounded bg-slate-200 px-2 py-1 text-slate-700">Pickup: {{ $origin }}</span>
                                        <span class="rounded px-2 py-1 font-semibold {{ $meta['badge'] }}">{{ $meta['label'] }} ({{ strtoupper($courierLabel) }})</span>
                                        @if($order->status === 'pending')
                                            <span class="rounded bg-blue-100 px-2 py-1 text-blue-700">Booking (Shipment is booked.)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-12 text-center text-slate-500">No orders found for selected filters.</td>
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