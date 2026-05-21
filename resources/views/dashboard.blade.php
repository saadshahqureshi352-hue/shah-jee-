<x-app-layout>
    @php
        $dashboardToOrdersFilter = [
            'booked' => 'booked',
            'in_progress' => 'rider_picked',
            'delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'issued' => 'issue_detected',
            'returned' => 'returned',
            'lost' => 'lost',
            'reattempt' => 're_attempt',
        ];

        $statusLabels = [
            'booked' => ['label' => 'Booked', 'bar' => 'bg-slate-400', 'icon' => 'text-slate-500'],
            'in_progress' => ['label' => 'In Progress', 'bar' => 'bg-amber-400', 'icon' => 'text-amber-500'],
            'delivered' => ['label' => 'Delivered', 'bar' => 'bg-violet-500', 'icon' => 'text-violet-500'],
            'cancelled' => ['label' => 'Cancelled', 'bar' => 'bg-rose-500', 'icon' => 'text-rose-500'],
            'issued' => ['label' => 'Issued', 'bar' => 'bg-sky-400', 'icon' => 'text-sky-500'],
            'returned' => ['label' => 'Returned', 'bar' => 'bg-orange-400', 'icon' => 'text-orange-500'],
            'lost' => ['label' => 'Lost', 'bar' => 'bg-red-400', 'icon' => 'text-red-500'],
            'reattempt' => ['label' => 'ReAttempt', 'bar' => 'bg-teal-400', 'icon' => 'text-teal-500'],
        ];

        $statusBadge = function (string $status): array {
            return match ($status) {
                'delivered' => ['Delivered', 'bg-emerald-100 text-emerald-700 border-emerald-200'],
                'returned' => ['Returned', 'bg-orange-100 text-orange-700 border-orange-200'],
                'dispatched' => ['In Progress', 'bg-amber-100 text-amber-700 border-amber-200'],
                default => [ucfirst($status), 'bg-slate-100 text-slate-600 border-slate-200'],
            };
        };

        $balanceCardStyles = [
            'balance_added' => ['label' => 'Balance Added', 'border' => 'border-sky-400', 'icon' => 'bg-sky-50 text-sky-600'],
            'not_approved' => ['label' => 'Not Approved', 'border' => 'border-rose-400', 'icon' => 'bg-rose-50 text-rose-600'],
            'deducted' => ['label' => 'Deducted Amount', 'border' => 'border-emerald-700', 'icon' => 'bg-emerald-50 text-emerald-800'],
            'balance' => ['label' => 'Balance', 'border' => 'border-emerald-400', 'icon' => 'bg-emerald-50 text-emerald-600'],
        ];
    @endphp

    <div class="space-y-4 p-4 sm:p-6" x-data="{ showPaymentAlert: true, showConsigneeAlert: true }">

        {{-- Alerts --}}
        <div x-show="showPaymentAlert" x-cloak class="flex items-start justify-between gap-4 rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-slate-600 shadow-sm">
            <p><span class="font-semibold text-slate-800">Payment Update:</span> Auto payments will now be received on Tuesday instead of Monday as part of our payment system improvement.</p>
            <button type="button" @click="showPaymentAlert = false" class="shrink-0 text-slate-400 hover:text-slate-600" aria-label="Dismiss">&times;</button>
        </div>

        <div x-show="showConsigneeAlert" x-cloak class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm shadow-sm">
            <p class="font-medium text-rose-700"><span class="font-bold">Consignee Alert</span> Feature Not Activated!</p>
            <div class="flex items-center gap-2">
                <button type="button" class="rounded-md bg-rose-600 px-4 py-1.5 text-xs font-semibold text-white shadow hover:bg-rose-700">Activate</button>
                <button type="button" @click="showConsigneeAlert = false" class="text-rose-400 hover:text-rose-600" aria-label="Dismiss">&times;</button>
            </div>
        </div>

        {{-- Filter bar --}}
        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center">
            <button type="button" @click="$dispatch('open-sidebar')" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-700 text-white lg:hidden" aria-label="Menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center justify-between">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-600">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>Last 60 days</span>
                    </div>
                    <div class="relative flex-1">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="search" placeholder="Search anything here" class="w-full rounded-lg border border-slate-200 py-2 pl-10 pr-4 text-sm focus:border-teal-500 focus:ring-teal-500">
                    </div>
                </div>
                {{-- Bulk Print Labels Button Trigger adjusted here --}}
                <div class="shrink-0 sm:ml-2">
                    <button id="bulkPrintBtn" type="button" class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm px-4 h-10 rounded-lg shadow transition">
                        <span>🖨️ Bulk Print Labels</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Top stat cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <a href="{{ route('bookings', ['status_filter' => 'delivered']) }}" class="relative block overflow-hidden rounded-2xl bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 p-6 shadow-sm transition hover:shadow-md">
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-amber-700">Total Sales</p>
                    <p class="mt-1 text-4xl font-bold text-amber-600">{{ number_format($totalRevenue) }}</p>
                </div>
                <svg class="absolute bottom-2 right-4 h-20 w-32 text-emerald-300/80" viewBox="0 0 120 60" fill="none" aria-hidden="true">
                    <path d="M5 45 L25 35 L45 40 L65 20 L85 25 L105 10" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    <rect x="20" y="38" width="8" height="18" fill="currentColor" opacity=".5" rx="1"/>
                    <rect x="40" y="32" width="8" height="24" fill="currentColor" opacity=".5" rx="1"/>
                    <rect x="60" y="22" width="8" height="34" fill="currentColor" opacity=".5" rx="1"/>
                    <rect x="80" y="28" width="8" height="28" fill="currentColor" opacity=".5" rx="1"/>
                </svg>
            </a>
            <a href="{{ route('bookings', ['status_filter' => 'all']) }}" class="relative block overflow-hidden rounded-2xl bg-gradient-to-br from-violet-50 via-purple-50 to-violet-100 p-6 shadow-sm transition hover:shadow-md">
                <div class="relative z-10">
                    <p class="text-sm font-semibold text-violet-700">Total Order</p>
                    <p class="mt-1 text-4xl font-bold text-violet-600">{{ number_format($totalBookings) }}</p>
                </div>
                <svg class="absolute bottom-4 right-6 h-24 w-24 text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 9h6v4H9z"/>
                </svg>
            </a>
        </div>

        {{-- Middle: table + order stats --}}
        <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="text-lg font-bold text-slate-800">Recent Orders</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs font-semibold uppercase tracking-wide text-slate-500">
                                <th class="px-5 py-3">Sr</th>
                                <th class="px-5 py-3">Time</th>
                                <th class="px-5 py-3">Customer</th>
                                <th class="px-5 py-3">City</th>
                                <th class="px-5 py-3">Amount</th>
                                <th class="px-5 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentShipments as $index => $shipment)
                                @php [$label, $badgeClass] = $statusBadge($shipment->status); @endphp
                                <tr class="{{ $index % 2 === 0 ? 'bg-orange-50/60' : 'bg-rose-50/40' }} border-b border-slate-50 last:border-0">
                                    <td class="px-5 py-3.5 font-medium text-slate-700">{{ $shipment->display_serial }}</td>
                                    <td class="px-5 py-3.5 text-slate-500">{{ $shipment->time_label }}</td>
                                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $shipment->customer_name }}</td>
                                    <td class="px-5 py-3.5 text-slate-600">{{ $shipment->destination_city }}</td>
                                    <td class="px-5 py-3.5 font-semibold text-slate-800">PKR {{ number_format($shipment->cod_amount) }}</td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="inline-flex items-center gap-1 rounded-full border px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                            {{ $label }}
                                            <span class="text-[10px] opacity-70">+</span>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-slate-500">No orders yet. Create your first booking to see data here.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-2 gap-3">
                    @foreach($statusLabels as $key => $meta)
                        @php $stat = $orderStats[$key] ?? ['count' => 0, 'percent' => 0]; @endphp
                        <a href="{{ route('bookings', ['status_filter' => $dashboardToOrdersFilter[$key] ?? 'all']) }}" class="block rounded-xl border border-slate-100 bg-slate-50/50 p-3 transition hover:shadow-sm">
                            <div class="flex items-start justify-between gap-1">
                                <svg class="h-5 w-5 {{ $meta['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span class="text-lg font-bold text-slate-800">{{ $stat['count'] }}</span>
                            </div>
                            <p class="mt-1 text-xs font-medium text-slate-600">{{ $meta['label'] }}</p>
                            <div class="mt-2 flex items-center gap-2">
                                <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-200">
                                    <div class="h-full rounded-full {{ $meta['bar'] }}" style="width: {{ max($stat['percent'], $stat['count'] > 0 ? 8 : 0) }}%"></div>
                                </div>
                                <span class="text-[10px] font-semibold text-slate-500">{{ $stat['percent'] }}%</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-4 flex items-center justify-between rounded-xl border border-slate-100 bg-gradient-to-r from-slate-50 to-teal-50/50 p-4">
                    <div>
                        <p class="text-sm font-bold text-slate-800">Your Progress</p>
                        <p class="mt-1 text-xs text-slate-600"><span class="font-bold text-teal-600">{{ $completedPercent }}%</span> Completed</p>
                        <p class="text-xs text-slate-600"><span class="font-bold text-slate-500">{{ $pendingPercent }}%</span> Pending</p>
                    </div>
                    <div class="relative flex h-16 w-16 items-center justify-center">
                        <svg class="h-16 w-16 -rotate-90" viewBox="0 0 36 36" aria-hidden="true">
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                            <circle cx="18" cy="18" r="15.5" fill="none" stroke="#0d9488" stroke-width="3" stroke-dasharray="{{ $completedPercent }}, 100" stroke-linecap="round"/>
                        </svg>
                        <span class="absolute text-xs font-bold text-teal-700">{{ $completedPercent }}%</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom balance cards --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach($balanceCardStyles as $key => $style)
                <div class="flex items-center gap-4 rounded-2xl border-2 {{ $style['border'] }} bg-white p-4 shadow-sm">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $style['icon'] }}">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500">{{ $style['label'] }}</p>
                        <p class="text-lg font-bold text-slate-800">{{ number_format($balanceCards[$key] ?? 0) }} <span class="text-sm font-semibold">PKR</span></p>
                    </div>
                </div>
            @endforeach
        </div>

        @if(session('success'))
            <div class="fixed bottom-6 right-6 z-50 rounded-lg bg-teal-600 px-4 py-3 text-sm font-medium text-white shadow-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>

    {{-- Bulk Print Modal Area (Picture 2 aur Picture 3 ke layout ke mutabiq) --}}
    <div id="bulkPrintModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center border-b pb-3">
                <div class="flex items-center space-x-2">
                    <button id="dateFilterBtn" class="flex items-center space-x-1 border px-3 py-1.5 rounded text-gray-700 bg-gray-50 hover:bg-gray-100">
                        <span id="selectedDateRange">Today Results</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </button>
                </div>
                
                <div class="flex-1 mx-4">
                    <input type="text" id="modalSearchInput" placeholder="Search track # or reference #..." class="w-full border rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex items-center space-x-2">
                    <button id="refreshModalBtn" class="bg-slate-700 text-white px-3 py-1.5 rounded text-sm hover:bg-slate-800 flex items-center space-x-1">
                        <span>🔄 Refresh</span>
                    </button>
                    <button type="button" onclick="closeBulkModal()" class="text-gray-400 hover:text-gray-600 border rounded px-2 py-1">✕</button>
                </div>
            </div>

            <div class="my-4 overflow-y-auto max-h-60" id="modalOrdersList">
                <div class="text-center py-10 text-gray-500" id="noOrdersMessage">
                    <div class="text-3xl mb-2">📬</div>
                    <p>No orders found.</p>
                </div>
                
                <table class="w-full text-left text-sm border-collapse hidden" id="modalOrdersTable">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-2"><input type="checkbox" id="selectAllModalOrders"></th>
                            <th class="p-2">Tracking #</th>
                            <th class="p-2">Consignee</th>
                            <th class="p-2">Destination</th>
                            <th class="p-2">Status</th>
                        </tr>
                    </thead>
                    <tbody id="modalOrdersTableBody"></tbody>
                </table>
            </div>

            <div class="flex justify-between items-center border-t pt-3 text-sm">
                <div class="text-gray-600">
                    <span id="selectedCount">0</span> of <span id="totalCount">0</span> selected · max 60
                </div>
                <div class="space-x-2">
                    <button type="button" onclick="closeBulkModal()" class="border px-4 py-2 rounded text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button id="downloadLabelsBtn" disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed font-medium">↓ Download Labels</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Controller Engine --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bulkBtn = document.getElementById('bulkPrintBtn');
        
        if(bulkBtn) {
            bulkBtn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('bulkPrintModal').classList.remove('hidden');
                fetchModalOrders(); 
            });
        }

        // Search bar input par live search triggers fixing
        const searchInput = document.getElementById('modalSearchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                fetchModalOrders();
            });
        }

        // Refresh Button link trigger configuration
        const refreshBtn = document.getElementById('refreshModalBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                fetchModalOrders();
            });
        }

        // Download Labels Form Execution Logic
        const downloadBtn = document.getElementById('downloadLabelsBtn');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
                const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
                
                if(selectedIds.length > 0) {
                    window.open(`/bookings/bulk-print?ids=${selectedIds.join(',')}`, '_blank');
                }
            });
        }

        // Master check select-all sync fixing
        const selectAllBox = document.getElementById('selectAllModalOrders');
        if (selectAllBox) {
            selectAllBox.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.order-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateSelectionCount();
            });
        }
    });

    function closeBulkModal() {
        document.getElementById('bulkPrintModal').classList.add('hidden');
    }

    function fetchModalOrders() {
        const search = document.getElementById('modalSearchInput').value;
        const tbody = document.getElementById('modalOrdersTableBody');
        const table = document.getElementById('modalOrdersTable');
        const msg = document.getElementById('noOrdersMessage');
        const selectAllBox = document.getElementById('selectAllModalOrders');
        
        // Reset count elements on load
        if(selectAllBox) selectAllBox.checked = false;
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-gray-400">Loading operational bookings...</td></tr>';
        
        fetch(`/api/bulk-print-orders?search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = '';
                
                if(!data.orders || data.orders.length === 0) {
                    table.classList.add('hidden');
                    msg.classList.remove('hidden');
                    document.getElementById('totalCount').innerText = '0';
                    updateSelectionCount();
                } else {
                    msg.classList.add('hidden');
                    table.classList.remove('hidden');
                    document.getElementById('totalCount').innerText = data.orders.length;
                    
                    data.orders.forEach(order => {
                        let tracking = order.tracking_number ? order.tracking_number : `SJC${String(order.id).padStart(10, '0')}`;
                        
                        tbody.innerHTML += `
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-2"><input type="checkbox" class="order-checkbox" value="${order.id}" onchange="updateSelectionCount()"></td>
                                <td class="p-2 text-blue-600 font-mono text-xs font-semibold">${tracking}</td>
                                <td class="p-2 text-xs font-medium text-gray-800">${order.customer_name}</td>
                                <td class="p-2 text-xs uppercase text-gray-600">${order.destination_city}</td>
                                <td class="p-2"><span class="bg-blue-100 text-blue-800 text-[11px] px-2 py-0.5 rounded font-semibold uppercase">${order.status}</span></td>
                            </tr>
                        `;
                    });
                    updateSelectionCount();
                }
            })
            .catch(err => {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-red-500">Failed to pull active orders.</td></tr>';
                console.error("Error loading orders:", err);
            });
    }

    function updateSelectionCount() {
        const checkboxes = document.querySelectorAll('.order-checkbox:checked');
        const btn = document.getElementById('downloadLabelsBtn');
        const countSpan = document.getElementById('selectedCount');
        
        if(countSpan) {
            countSpan.innerText = checkboxes.length;
        }
        
        if(btn) {
            if(checkboxes.length > 0) {
                btn.disabled = false;
                btn.className = "bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 font-medium transition cursor-pointer";
            } else {
                btn.disabled = true;
                btn.className = "bg-gray-300 text-gray-500 px-4 py-2 rounded cursor-not-allowed font-medium";
            }
        }
    }
    </script>
</x-app-layout>