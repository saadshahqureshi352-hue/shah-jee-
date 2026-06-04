<x-app-layout>
    <div class="space-y-6 p-4 sm:p-6" 
         x-data="{
        showModal: false,
        loadSheetData: [],
        selectedOrderIds: [],
        dateRange: 'today',
        searchQuery: '',
        loadingOrders: false,
        dateDropdownOpen: false,
        searchTimeout: null,
        dateFilterOpen: false,
        dateFilterRange: 'today',

        dateLabels: {
            all_time: 'All Time Results',
            today: 'Today Results',
            yesterday: 'Yesterday Results',
            last_7: 'Last 7 Days',
            last_30: 'Last 30 Days'
        },

        dateFilterLabels: {
            all_time: 'All Time Results',
            today: 'Today Results',
            yesterday: 'Yesterday Results',
            last_7: 'Last 7 Days',
            last_30: 'Last 30 Days',
            this_month: 'This Month',
            last_month: 'Last Month'
        },

        openModal() {
            this.showModal = true;
            this.fetchLoadSheetOrders();
        },

        closeModal() {
            this.showModal = false;
        },

        fetchLoadSheetOrders() {
            this.loadingOrders = true;
            let params = `courier={{ $courierSlug }}&date_range=${this.dateRange}`;
            if (this.searchQuery.trim()) {
                params += `&search=${encodeURIComponent(this.searchQuery.trim())}`;
            }
            fetch(`/api/load-sheet-orders?${params}`)
                .then(res => res.json())
                .then(data => {
                    this.loadSheetData = data.orders ?? data;
                    this.selectedOrderIds = [];
                    this.loadingOrders = false;
                })
                .catch(() => { this.loadingOrders = false; });
        },

        onSearchInput() {
            clearTimeout(this.searchTimeout);
            this.searchTimeout = setTimeout(() => {
                this.fetchLoadSheetOrders();
            }, 400);
        },

        toggleAllOrders() {
            if (this.selectedOrderIds.length === this.loadSheetData.length && this.loadSheetData.length > 0) {
                this.selectedOrderIds = [];
            } else {
                this.selectedOrderIds = this.loadSheetData.map(o => o.id);
            }
        },

        generateLoadSheet() {
            if (this.selectedOrderIds.length === 0) return;
            const ids = this.selectedOrderIds.join(',');
            const url = `/bookings/bulk-print?ids=${ids}`;
            window.open(url, '_blank');
            this.closeModal();
        },

        downloadLoadSheet(id) {
            if (id) {
                window.open('/bookings/' + id + '/slip', '_blank');
            }
        }
    }">

        {{-- HERO HEADER - Professional Light Cyan Theme --}}
        <div class="fade-in-up relative overflow-hidden rounded-2xl bg-gradient-to-br from-cyan-500 via-teal-500 to-emerald-600 p-6 sm:p-8 shadow-2xl border border-cyan-400/30">
            <div class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-white/15 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-16 h-48 w-48 rounded-full bg-cyan-300/20 blur-2xl"></div>
            <div class="relative flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                <div class="flex items-center gap-3">
                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-white/25 backdrop-blur-sm text-white shadow-lg">
                        <img src="{{ asset($courier['logo']) }}" alt="{{ $courier['name'] }}" class="h-8 w-8 object-contain">
                    </span>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight drop-shadow-sm">
                            {{ $courier['name'] }} <span class="text-cyan-200 font-light">Load Sheets</span>
                        </h1>
                        <p class="text-cyan-100/90 text-sm font-medium">Track, generate & manage your loadsheets in one place.</p>
                    </div>
                </div>
                <button @click="openModal()"
                    class="inline-flex items-center gap-2 rounded-xl bg-white/20 backdrop-blur-sm border border-white/30 px-6 py-3 font-bold text-white shadow-xl transition-all-300 hover:scale-105 hover:bg-white/30 hover:shadow-2xl card-hover">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Generate New
                </button>
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeModal()"></div>
            <div class="relative w-full max-w-5xl rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden bounce-in"
                @click.outside="closeModal()">
                <div class="flex items-center gap-3 border-b border-slate-200 bg-cyan-50 px-4 py-3">
                    <div class="flex items-center border-r border-slate-200 pr-3">
                        <input type="checkbox" @click="toggleAllOrders()"
                               :checked="loadSheetData.length > 0 && selectedOrderIds.length === loadSheetData.length"
                               class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500 cursor-pointer">
                    </div>
                    <div class="relative">
                        <button type="button" @click="dateDropdownOpen = !dateDropdownOpen"
                                class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:border-cyan-400 transition-all-300">
                            <svg class="h-4 w-4 text-cyan-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span x-text="dateLabels[dateRange]" class="min-w-[100px] text-left text-slate-700">Today Results</span>
                            <svg class="h-3.5 w-3.5 text-slate-400 transition-all-300 shrink-0" :class="dateDropdownOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="dateDropdownOpen" @click.outside="dateDropdownOpen = false" x-cloak
                             class="absolute left-0 top-full z-50 mt-1.5 w-52 rounded-xl border border-slate-200 bg-white py-1.5 shadow-xl nav-slide">
                            <template x-for="(label, key) in dateLabels">
                                <button type="button" @click="dateRange = key; dateDropdownOpen = false; fetchLoadSheetOrders();"
                                        x-text="label"
                                        class="flex w-full items-center px-4 py-2 text-left text-xs font-medium text-slate-700 hover:bg-cyan-50 hover:text-cyan-700"
                                        :class="dateRange === key ? 'bg-cyan-100 text-cyan-700 font-semibold' : ''">
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" x-model="searchQuery" @input="onSearchInput()"
                                   placeholder="Search by tracking, reference or customer..."
                                   class="w-full rounded-lg border border-slate-200 bg-white py-2 pl-9 pr-3 text-xs text-slate-700 placeholder:text-slate-400 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all-300">
                        </div>
                    </div>
                    <button type="button" @click="fetchLoadSheetOrders()"
                            class="flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-cyan-500 to-teal-600 px-3.5 py-2 text-xs font-bold text-white hover:from-cyan-400 hover:to-teal-500 transition-all-300 shadow-md">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Refresh
                    </button>
                    <button type="button" @click="closeModal()"
                            class="rounded-lg border border-slate-200 bg-white p-2 text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-all-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="min-h-[300px] max-h-[55vh] overflow-y-auto bg-white">
                    <div x-show="loadingOrders" class="flex items-center justify-center py-20">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="animate-spin h-8 w-8 text-cyan-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span class="text-sm font-medium text-slate-500">Loading orders...</span>
                        </div>
                    </div>
                    <div x-show="!loadingOrders && loadSheetData.length === 0"
                         class="flex flex-col items-center justify-center py-20 text-center">
                        <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-100 text-3xl">📦</div>
                        <h3 class="text-sm font-bold text-slate-700">No orders found</h3>
                        <p class="mt-1 text-xs text-slate-400">Try adjusting your date range or search terms to find pending orders.</p>
                    </div>
                    <div x-show="!loadingOrders && loadSheetData.length > 0" class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="bg-cyan-600 text-xs font-bold uppercase tracking-wider text-white">
                                    <th class="px-4 py-3 w-12">Sel.</th>
                                    <th class="px-4 py-3 w-10">#</th>
                                    <th class="px-4 py-3">Tracking</th>
                                    <th class="px-4 py-3">Consignee</th>
                                    <th class="px-4 py-3 hidden sm:table-cell">Phone</th>
                                    <th class="px-4 py-3">Destination</th>
                                    <th class="px-4 py-3 text-right">COD</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <template x-for="(order, index) in loadSheetData" :key="order.id">
                                    <tr @click="selectedOrderIds.includes(order.id) ? selectedOrderIds = selectedOrderIds.filter(id => id !== order.id) : selectedOrderIds.push(order.id)"
                                        class="group transition-all-300 cursor-pointer"
                                        :class="selectedOrderIds.includes(order.id) ? 'bg-cyan-50' : 'hover:bg-slate-50'">
                                        <td class="px-4 py-3" @click.stop>
                                            <input type="checkbox" :value="order.id"
                                                   :checked="selectedOrderIds.includes(order.id)"
                                                   @change="selectedOrderIds.includes(order.id) ? selectedOrderIds = selectedOrderIds.filter(id => id !== order.id) : selectedOrderIds.push(order.id)"
                                                   class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500 cursor-pointer">
                                        </td>
                                        <td class="px-4 py-3 font-bold text-slate-800" x-text="index + 1"></td>
                                        <td class="px-4 py-3"><span class="font-mono text-sm font-bold text-cyan-700" x-text="order.tracking_number || '—'"></span></td>
                                        <td class="px-4 py-3"><span class="font-semibold text-slate-800" x-text="order.customer_name"></span></td>
                                        <td class="px-4 py-3 text-slate-500 hidden sm:table-cell" x-text="order.customer_phone || '—'"></td>
                                        <td class="px-4 py-3"><span class="inline-flex items-center rounded-md bg-teal-100 px-2 py-0.5 text-xs font-semibold text-teal-700" x-text="order.destination_city"></span></td>
                                        <td class="px-4 py-3 text-right"><span class="font-bold text-amber-600" x-text="'Rs ' + Number(order.cod_amount).toLocaleString()"></span></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex items-center justify-between border-t border-slate-200 bg-cyan-50 px-5 py-3.5">
                    <div class="flex items-center gap-2 text-xs text-slate-600">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-br from-cyan-500 to-teal-500 text-xs font-bold text-white" x-text="selectedOrderIds.length"></span>
                        <span>of <strong class="text-slate-800" x-text="loadSheetData.length"></strong> selected</span>
                    </div>
                    <div class="flex gap-2.5">
                        <button @click="closeModal()" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all-300">Cancel</button>
                        <button @click="generateLoadSheet()" :disabled="selectedOrderIds.length === 0" :class="selectedOrderIds.length === 0 ? 'bg-slate-300 text-slate-500 cursor-not-allowed' : 'bg-gradient-to-r from-cyan-500 to-teal-600 hover:from-cyan-400 hover:to-teal-500 text-white shadow-md'" class="inline-flex items-center gap-2 rounded-lg px-5 py-2 text-xs font-bold transition-all-300">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Generate Load Sheet
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTERS BAR - Light Cyan Theme --}}
        <div class="fade-in-up-delay-1 rounded-xl border border-cyan-200 bg-gradient-to-r from-cyan-50 to-teal-50 p-4 shadow-sm flex flex-col md:flex-row gap-3 card-hover items-center">
            <div class="relative shrink-0">
                <button @click="dateFilterOpen = !dateFilterOpen" type="button"
                        class="flex items-center gap-2.5 rounded-lg border border-cyan-200 bg-white px-4 py-2.5 text-sm text-slate-700 hover:bg-cyan-50 hover:border-cyan-400 transition-all-300 shadow-sm">
                    <svg class="h-5 w-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span x-text="dateFilterLabels[dateFilterRange]" class="font-medium">Today Results</span>
                    <svg class="h-4 w-4 text-slate-400 transition-all-300" :class="dateFilterOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="dateFilterOpen" @click.outside="dateFilterOpen = false" x-cloak
                     class="absolute left-0 top-full z-40 mt-1.5 w-56 rounded-xl border border-cyan-200 bg-white py-1.5 shadow-xl nav-slide">
                    <template x-for="(label, key) in dateFilterLabels">
                        <button type="button" @click="dateFilterRange = key; dateFilterOpen = false" x-text="label"
                                class="flex w-full items-center px-4 py-2 text-left text-sm text-slate-700 hover:bg-cyan-50 hover:text-cyan-700"
                                :class="dateFilterRange === key ? 'bg-cyan-100 text-cyan-700 font-semibold' : ''">
                        </button>
                    </template>
                </div>
            </div>
            <div class="relative flex-1">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" placeholder="Search anything here" class="w-full rounded-lg border border-cyan-200 bg-white pl-11 pr-3 py-2.5 text-sm text-slate-700 placeholder:text-slate-400 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all-300 shadow-sm">
            </div>
            <select class="rounded-lg border border-cyan-200 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all-300 shrink-0 shadow-sm">
                <option>25 Per Page</option>
                <option>50 Per Page</option>
                <option>100 Per Page</option>
            </select>
        </div>

        {{-- ACTION BUTTONS - Professional Dark Colors --}}
        <div class="fade-in-up-delay-2 flex gap-3 flex-wrap">
            <a href="{{ route('bookings.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-slate-700 to-slate-800 px-5 py-2.5 text-sm font-bold text-white shadow-md transition-all-300 hover:scale-105 hover:shadow-lg card-hover border border-slate-600/30">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Book Packet
            </a>
            <button class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-600 to-teal-700 px-5 py-2.5 text-sm font-bold text-white shadow-md transition-all-300 hover:scale-105 hover:shadow-lg card-hover border border-cyan-500/30">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m4-4v6m0 0l-3-3m3 3l3-3"/></svg>
                Bulk Print Labels
            </button>
        </div>

        {{-- MAIN TABLE --}}
        <div class="fade-in-up-delay-3 overflow-hidden rounded-xl border border-cyan-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px] text-left text-sm">
                    <thead>
                        <tr class="bg-gradient-to-r from-cyan-600 to-teal-600 text-xs font-bold uppercase tracking-wider text-white">
                            <th class="px-4 py-3">Sr No</th>
                            <th class="px-4 py-3">Courier</th>
                            <th class="px-4 py-3">Created Date</th>
                            <th class="px-4 py-3">Packets</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="group transition-all-300 hover:bg-cyan-50 cursor-pointer">
                            <td class="px-4 py-4">
                                <p class="font-bold text-slate-800">1</p>
                                <p class="text-xs text-slate-400 font-mono">#7328125</p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-cyan-100 px-3 py-1.5 text-xs font-bold text-cyan-700">
                                    <img src="{{ asset($courier['logo']) }}" alt="{{ $courier['name'] }}" class="h-4 w-4 object-contain">
                                    {{ $courier['name'] }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-semibold text-slate-800">11 Apr 2026</p>
                                <p class="text-xs text-slate-400">1 month ago</p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="text-lg font-bold text-slate-800">1</p>
                                <p class="text-xs text-amber-600 font-medium">CN count</p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <button @click="downloadLoadSheet(1)"
                                        class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-cyan-500 to-teal-600 px-4 py-2 text-xs font-bold text-white shadow-md transition-all-300 hover:from-cyan-400 hover:to-teal-500 hover:shadow-lg hover:scale-105"
                                        title="Download Load Sheet">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="py-16 text-center border-t border-slate-100">
                <div class="mb-3 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-3xl">📋</div>
                <p class="text-sm font-bold text-slate-600">No load sheets generated yet</p>
                <p class="text-xs text-slate-400 mt-1">Click "Generate New" to create your first load sheet</p>
            </div>
        </div>
    </div>
</x-app-layout>