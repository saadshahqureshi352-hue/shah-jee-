<x-app-layout>
    @php
        // Aapke existing PHP variables...
        $dashboardToOrdersFilter = ['booked' => 'booked', 'in_progress' => 'rider_picked', 'delivered' => 'delivered', 'cancelled' => 'cancelled', 'issued' => 'issue_detected', 'returned' => 'returned', 'lost' => 'lost', 'reattempt' => 're_attempt'];
        $statusLabels = ['booked' => ['label' => 'Booked', 'bar' => 'bg-slate-400', 'icon' => 'text-slate-500'], 'in_progress' => ['label' => 'In Progress', 'bar' => 'bg-amber-400', 'icon' => 'text-amber-500'], 'delivered' => ['label' => 'Delivered', 'bar' => 'bg-violet-500', 'icon' => 'text-violet-500'], 'cancelled' => ['label' => 'Cancelled', 'bar' => 'bg-rose-500', 'icon' => 'text-rose-500'], 'issued' => ['label' => 'Issued', 'bar' => 'bg-sky-400', 'icon' => 'text-sky-500'], 'returned' => ['label' => 'Returned', 'bar' => 'bg-orange-400', 'icon' => 'text-orange-500'], 'lost' => ['label' => 'Lost', 'bar' => 'bg-red-400', 'icon' => 'text-red-500'], 'reattempt' => ['label' => 'ReAttempt', 'bar' => 'bg-teal-400', 'icon' => 'text-teal-500']];
        $statusBadge = function (string $status): array {
            return match ($status) { 'delivered' => ['Delivered', 'bg-emerald-100 text-emerald-700 border-emerald-200'], 'returned' => ['Returned', 'bg-orange-100 text-orange-700 border-orange-200'], 'dispatched' => ['In Progress', 'bg-amber-100 text-amber-700 border-amber-200'], default => [ucfirst($status), 'bg-slate-100 text-slate-600 border-slate-200'] };
        };
        $balanceCardStyles = ['balance_added' => ['label' => 'Balance Added', 'border' => 'border-sky-400'], 'not_approved' => ['label' => 'Not Approved', 'border' => 'border-rose-400'], 'deducted' => ['label' => 'Deducted', 'border' => 'border-emerald-700'], 'balance' => ['label' => 'Balance', 'border' => 'border-emerald-400']];

        // === DYNAMIC DATA EXTRACTION & CALCULATION FOR NEW CARDS ===
        $cBooked      = $orderStats['booked']['count'] ?? 0;
        $cInProgress  = $orderStats['in_progress']['count'] ?? 0;
        $cDelivered   = $orderStats['delivered']['count'] ?? 0;
        $cCancelled   = $orderStats['cancelled']['count'] ?? 0;
        $cIssued      = $orderStats['issued']['count'] ?? 0;
        $cReturned    = $orderStats['returned']['count'] ?? 0;
        $cLost        = $orderStats['lost']['count'] ?? 0;
        $cReattempt   = $orderStats['reattempt']['count'] ?? 0;

        // Total Booked Volume (Sum of all status counts)
        $totalCalculatedOrders = $cBooked + $cInProgress + $cDelivered + $cCancelled + $cIssued + $cReturned + $cLost + $cReattempt;

        // Percentage Calculations safely to avoid division by zero
        $pctInProgress = $totalCalculatedOrders > 0 ? round(($cInProgress / $totalCalculatedOrders) * 100) : 0;
        $pctDelivered  = $totalCalculatedOrders > 0 ? round(($cDelivered / $totalCalculatedOrders) * 100) : 0;
        $pctReturned   = $totalCalculatedOrders > 0 ? round(($cReturned / $totalCalculatedOrders) * 100) : 0;
    @endphp

    <div class="space-y-6 p-4 sm:p-6" x-data="{ showPaymentAlert: true, showConsigneeAlert: true }">
        
        {{-- Top Alerts with animation --}}
        <div x-show="showPaymentAlert" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="rounded-xl border-l-4 border-teal-500 bg-gradient-to-r from-teal-50 to-cyan-50 px-5 py-4 text-sm shadow-lg flex justify-between items-center fade-in-up">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-teal-500 text-white shadow-lg">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-slate-700"><span class="font-bold text-teal-700">CEO :</span> Saad Shah .</p>
            </div>
            <button @click="showPaymentAlert = false" class="text-slate-400 hover:text-slate-600 transition-colors text-2xl font-bold leading-none">&times;</button>
        </div>

        {{-- Top Stats Cards (Revenue/Orders) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-400 via-orange-400 to-rose-500 p-8 border-2 border-amber-300 shadow-2xl card-hover transition-all-300 slide-in-left">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-bold text-white/90 uppercase tracking-wider">Total Sales</p>
                    </div>
                    <p class="text-5xl font-black text-white drop-shadow-lg">{{ number_format($totalRevenue ?? 0) }}</p>
                    <p class="text-white/80 text-sm mt-2 font-medium">PKR Revenue Generated</p>
                </div>
            </a>
            
            <a href="#" class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-600 p-8 border-2 border-violet-300 shadow-2xl card-hover transition-all-300 slide-in-right">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12 group-hover:scale-150 transition-transform duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <p class="text-sm font-bold text-white/90 uppercase tracking-wider">Total Orders</p>
                    </div>
                    <p class="text-5xl font-black text-white drop-shadow-lg">{{ number_format($totalBookings ?? 0) }}</p>
                    <p class="text-white/80 text-sm mt-2 font-medium">Packages Delivered</p>
                </div>
            </a>
        </div>

        {{-- Status Grid Panel --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
            @foreach($statusLabels as $key => $meta)
                @php $filterValue = $dashboardToOrdersFilter[$key] ?? 'all'; @endphp
                <a href="{{ route('bookings', ['status_filter' => $filterValue]) }}" class="group bg-white p-4 rounded-2xl border-2 border-slate-200 text-center card-hover transition-all-300 hover:border-teal-400 hover:shadow-glow-teal bounce-in" style="animation-delay: {{ $loop->index * 0.05 }}s">
                    <div class="flex justify-center mb-2">
                        <div class="h-3 w-3 rounded-full {{ $meta['bar'] }} group-hover:scale-125 transition-transform"></div>
                    </div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wide mb-1">{{ $meta['label'] }}</p>
                    <p class="text-2xl font-black bg-gradient-to-br from-slate-700 to-slate-900 bg-clip-text text-transparent group-hover:scale-110 transition-transform">{{ $orderStats[$key]['count'] ?? 0 }}</p>
                    <img src="{{ asset('images/shah-jee-logo.png') }}" alt="SJC" class="mx-auto mt-1.5 h-5 w-5 rounded opacity-0 group-hover:opacity-30 transition-opacity duration-300">
                </a>
            @endforeach
        </div>

        {{-- === AUTOMATED & FIXED PERFORMANCE CARDS SECTION === --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mt-6 px-1">

            <!-- Card 1: All Orders (Base Volume) -->
            <div class="relative overflow-hidden p-5 flex flex-col justify-between card-hover transition-all-300 fade-in-up" 
                 style="border: 2px solid #e2e8f0; border-radius: 20px; background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); animation-delay: 0.1s;">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-blue-600 animate-pulse"></span>
                            <h3 class="text-xs font-black text-slate-700 tracking-tight uppercase" style="font-weight: 800;">All Orders</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Total booked parcels</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-slate-800" style="font-weight: 900;">{{ sprintf('%02d', $totalCalculatedOrders) }}</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Base Volume</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center mt-6">
                    <div class="relative flex items-center justify-center h-24 w-24">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-100" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-blue-500" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="100, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-black text-slate-800">{{ $totalCalculatedOrders > 0 ? '100%' : '0%' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <span class="h-2 w-2 rounded-full bg-blue-500"></span> Booked Data
                    </div>
                </div>
            </div>

            <!-- Card 2: In Progress Orders -->
            <div class="relative overflow-hidden p-5 flex flex-col justify-between card-hover transition-all-300 fade-in-up" 
                 style="border: 2px solid #fef08a; border-radius: 20px; background: rgba(254, 252, 232, 0.75); backdrop-filter: blur(8px); animation-delay: 0.2s;">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                            <h3 class="text-xs font-black text-slate-700 tracking-tight uppercase" style="font-weight: 800;">In Progress</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Parcels in transit</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-amber-600" style="font-weight: 900;">{{ sprintf('%02d', $cInProgress) }}</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">of {{ sprintf('%02d', $totalCalculatedOrders) }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center mt-6">
                    <div class="relative flex items-center justify-center h-24 w-24">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-200/60" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-amber-500" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="{{ $pctInProgress }}, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-black text-slate-800">{{ $pctInProgress }}%</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Transit</span>
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-slate-300"></span> Pending</span>
                    </div>
                </div>
            </div>

            <!-- Card 3: Delivered Orders -->
            <div class="relative overflow-hidden p-5 flex flex-col justify-between card-hover transition-all-300 fade-in-up" 
                 style="border: 2px solid #bbf7d0; border-radius: 20px; background: rgba(240, 253, 244, 0.75); backdrop-filter: blur(8px); animation-delay: 0.3s;">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                            <h3 class="text-xs font-black text-slate-700 tracking-tight uppercase" style="font-weight: 800;">Delivered</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Successful deliveries</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-emerald-600" style="font-weight: 900;">{{ sprintf('%02d', $cDelivered) }}</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">of {{ sprintf('%02d', $totalCalculatedOrders) }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center mt-6">
                    <div class="relative flex items-center justify-center h-24 w-24">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-200/60" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-emerald-500" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="{{ $pctDelivered }}, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-black text-slate-800">{{ $pctDelivered }}%</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Success</span>
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-slate-300"></span> Pending</span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Returned Orders -->
            <div class="relative overflow-hidden p-5 flex flex-col justify-between card-hover transition-all-300 fade-in-up" 
                 style="border: 2px solid #fecaca; border-radius: 20px; background: rgba(254, 242, 242, 0.75); backdrop-filter: blur(8px); animation-delay: 0.4s;">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-rose-600"></span>
                            <h3 class="text-xs font-black text-slate-700 tracking-tight uppercase" style="font-weight: 800;">Returned</h3>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-0.5">Product returns</p>
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-black text-rose-600" style="font-weight: 900;">{{ sprintf('%02d', $cReturned) }}</span>
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">of {{ sprintf('%02d', $totalCalculatedOrders) }}</p>
                    </div>
                </div>
                
                <div class="flex flex-col items-center justify-center mt-6">
                    <div class="relative flex items-center justify-center h-24 w-24">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path class="text-slate-200/60" stroke-width="3" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="text-rose-500" stroke-width="3.2" stroke-linecap="round" stroke-dasharray="{{ $pctReturned }}, 100" stroke="currentColor" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-black text-slate-800">{{ $pctReturned }}%</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-4 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-rose-500"></span> Returned</span>
                        <span class="flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-slate-300"></span> Pending</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>