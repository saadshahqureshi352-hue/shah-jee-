<x-app-layout>
    <!-- Forced Inline Styles for Guaranteed Look -->
    <style>
        @keyframes customShine {
            0% { transform: translateX(-100%) rotate(12deg); }
            100% { transform: translateX(100%) rotate(12deg); }
        }
        @keyframes customPulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.4); opacity: 0; }
        }
        .animate-shine-effect:hover .shine-bar {
            animation: customShine 1s ease-in-out forwards;
        }
        .live-dot-pulse {
            animation: customPulse 2s infinite ease-in-out;
        }
        .forced-card {
            border: 1px solid #e2e8f0 !important;
            border-radius: 16px !important;
            background-color: #ffffff !important;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05) !important;
            transition: all 0.2s ease-in-out !important;
            cursor: pointer;
        }
        .forced-card:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
        }
        .card-active-filter {
            border-width: 2px !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08) !important;
        }
        .forced-badge {
            background-color: #f1f5f9 !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px 12px !important;
        }
    </style>

    <!-- Alpine.js Main Wrapper (Manages modal and selected status filter) -->
    <div x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }}, 
        currentFilter: 'all' 
    }">
        <div class="mx-auto max-w-2xl space-y-6 p-4 sm:p-6 mt-2" style="background-color: #f8fafc; border-radius: 24px;">
            
            @if(session('success'))
                <div class="px-4 py-3 text-sm font-medium text-emerald-800" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Modern Header Section -->
            <div class="pb-3 flex flex-wrap gap-2 justify-between items-center" style="border-b: 1px solid #f1f5f9;">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-800 sm:text-2xl tracking-tight" style="font-weight: 800;">My Pickup Addresses</h1>
                    <p class="text-xs text-slate-400 mt-1">Click cards below to filter the list instantly.</p>
                </div>
                <!-- Reset Filter Badge -->
                <button type="button" x-show="currentFilter !== 'all'" @click="currentFilter = 'all'" 
                        class="text-xs font-bold text-teal-600 bg-teal-50 px-2.5 py-1 rounded-full hover:bg-teal-100 transition-colors" x-cloak>
                    Clear Filter ✕
                </button>
            </div>

            <!-- Animated Add Pickup Button -->
            <button type="button" @click="showModal = true"
                    class="animate-shine-effect relative w-full overflow-hidden bg-teal-600 py-3.5 text-center text-sm font-semibold text-white transition-all duration-300 hover:bg-teal-700 active:scale-[0.99] group"
                    style="border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(13, 148, 136, 0.2);">
                <span class="shine-bar absolute inset-0 -translate-x-full rotate-12 bg-gradient-to-r from-transparent via-white/25 to-transparent w-1/2 h-full top-0"></span>
                <span class="relative inline-flex items-center justify-center gap-2">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:rotate-90" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Add Pickup
                </span>
            </button>

            <!-- Status Cards Grid (Fully functional with click events) -->
            <div class="grid grid-cols-2 gap-4 select-none">
                <!-- Total Pickups Card (Shows all) -->
                <div @click="currentFilter = 'all'" 
                     class="forced-card group relative overflow-hidden p-4"
                     :class="currentFilter === 'all' ? 'card-active-filter !border-rose-400' : 'hover:border-rose-200'">
                    <div class="absolute -right-4 -top-4 h-12 w-12 rounded-full bg-rose-50 transition-all duration-500 group-hover:scale-150"></div>
                    <div class="relative mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-rose-500 text-white shadow-sm shadow-rose-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">Total Pickups</p>
                    <p class="text-2xl font-black text-slate-800 mt-0.5 tracking-tight group-hover:text-rose-600 transition-colors">{{ str_pad((string) $stats['total'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Active Pickup Card -->
                <div @click="currentFilter = 'active'" 
                     class="forced-card group relative overflow-hidden p-4"
                     :class="currentFilter === 'active' ? 'card-active-filter !border-amber-400' : 'hover:border-amber-200'">
                    <div class="absolute -right-4 -top-4 h-12 w-12 rounded-full bg-amber-50 transition-all duration-500 group-hover:scale-150"></div>
                    <div class="absolute top-3 right-3 flex h-2 w-2">
                        <span class="live-dot-pulse absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </div>
                    <div class="relative mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm shadow-amber-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">Active Pickup</p>
                    <p class="text-2xl font-black text-slate-800 mt-0.5 tracking-tight group-hover:text-amber-600 transition-colors">{{ str_pad((string) $stats['active'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Blocked Pickups Card -->
                <div @click="currentFilter = 'blocked'" 
                     class="forced-card group relative overflow-hidden p-4"
                     :class="currentFilter === 'blocked' ? 'card-active-filter !border-sky-400' : 'hover:border-sky-200'">
                    <div class="absolute -right-4 -top-4 h-12 w-12 rounded-full bg-sky-50 transition-all duration-500 group-hover:scale-150"></div>
                    <div class="relative mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-sky-500 text-white shadow-sm shadow-sky-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">Blocked Pickups</p>
                    <p class="text-2xl font-black text-slate-800 mt-0.5 tracking-tight group-hover:text-sky-600 transition-colors">{{ str_pad((string) $stats['blocked'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>

                <!-- Deleted Pickups Card -->
                <div @click="currentFilter = 'deleted'" 
                     class="forced-card group relative overflow-hidden p-4"
                     :class="currentFilter === 'deleted' ? 'card-active-filter !border-emerald-400' : 'hover:border-emerald-200'">
                    <div class="absolute -right-4 -top-4 h-12 w-12 rounded-full bg-emerald-50 transition-all duration-500 group-hover:scale-150"></div>
                    <div class="relative mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-500 text-white shadow-sm shadow-emerald-500/20">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-slate-400">Deleted</p>
                    <p class="text-2xl font-black text-slate-800 mt-0.5 tracking-tight group-hover:text-emerald-600 transition-colors">{{ str_pad((string) $stats['deleted'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <!-- Address Cards List Section -->
            <div class="space-y-4 pt-2">
                @php $hasAddresses = $addresses->count() > 0; @endphp
                
                @if($hasAddresses)
                    @foreach($addresses as $index => $address)
                        <!-- Each card decides its visibility based on the 'currentFilter' state -->
                        <article 
                            x-show="currentFilter === 'all' || currentFilter === '{{ $address->status }}'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            class="overflow-hidden bg-white group" 
                            style="border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);"
                        >
                            <!-- Top Bar -->
                            <div class="flex items-center justify-between px-4 py-2.5" style="border-b: 1px solid #f1f5f9; background-color: #f8fafc;">
                                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Sr {{ str_pad((string) ($addresses->count() - $index), 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            
                            <!-- Data Body -->
                            <div class="space-y-4 p-4">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Booking ID</p>
                                    <p class="text-base font-extrabold text-slate-800 tracking-tight mt-0.5" style="font-weight: 800;">{{ $address->booking_id }}</p>
                                </div>
                                
                                <!-- Data Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                    <div class="forced-badge">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Name</p>
                                        <p class="text-slate-800 mt-1" style="font-weight: 800;">{{ $address->brand_name }}</p>
                                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $address->phone }}</p>
                                    </div>
                                    <div class="forced-badge">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Address</p>
                                        <p class="text-slate-800 mt-1" style="font-weight: 700; line-height: 1.25;">{{ Str::limit($address->address, 35) }}</p>
                                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $address->city }}</p>
                                    </div>
                                </div>

                                <!-- Status, Actions, and Date Footer -->
                                <div class="flex flex-wrap items-center justify-between gap-4 pt-3" style="border-t: 1px solid #f1f5f9;">
                                    <div class="flex items-center gap-4 flex-wrap">
                                        <div>
                                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Status</p>
                                            <span class="inline-block rounded-lg px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider text-white
                                                {{ $address->status === 'active' ? 'bg-emerald-500' : ($address->status === 'blocked' ? 'bg-sky-500' : 'bg-slate-400') }}">
                                                {{ $address->status }}
                                            </span>
                                        </div>

                                        <!-- Edit and Delete Action Group -->
                                        <div class="flex items-center gap-2 mt-4">
                                            <!-- Edit Button -->
                                            <a href="{{ route('pickup-addresses.edit', $address->id) }}" 
                                               class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition-colors hover:bg-slate-50 hover:text-teal-600 shadow-2xs"
                                               title="Edit Address">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                                </svg>
                                            </a>

                                            <!-- Delete Button via Form -->
                                            <form action="{{ route('pickup-addresses.destroy', $address->id) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this address?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-rose-100 bg-rose-50 text-rose-500 transition-colors hover:bg-rose-100 hover:text-rose-600 shadow-2xs"
                                                        title="Delete Address">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.34 9m-4.72 0l-.34-9m9.43-4.28l-1.05 12.002a2 2 0 01-1.995 1.858H7.84a2 2 0 01-1.995-1.858L4.82 5.07m12.117-1.07a9.745 9.745 0 00-1.545-.103m-5.093 0C9.33 4.182 8 4.613 7.02 5.312m12.156 0c-1.018-.7-2.352-1.136-3.712-1.136m-5.517 0c1.36 0 2.694.436 3.712 1.136m0 0a9.754 9.754 0 013.712 0M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Date</p>
                                        <p class="text-xs font-bold text-slate-600 mt-1 bg-slate-100 px-2 py-0.5 rounded-md inline-block">{{ \Carbon\Carbon::parse($address->created_at)->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                @else
                    <!-- Database Empty State -->
                    <div class="p-8 text-center text-sm text-slate-400" style="border: 2px dashed #e2e8f0; border-radius: 16px; background-color: #ffffff;">
                        No pickup address found. Tap <span class="font-bold text-teal-600">Add Pickup</span> to create one.
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Modal Section -->
        <div
            x-show="showModal"
            x-cloak
            @keydown.escape.window="showModal = false"
            style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        >
            <div class="absolute inset-0 bg-slate-900/60" @click="showModal = false"></div>
            <div class="relative z-10 w-full max-w-md rounded-2xl bg-white shadow-2xl" @click.stop style="border-radius: 16px;">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h2 class="text-base font-bold text-slate-800">Add Pickup Address</h2>
                    <button type="button" @click="showModal = false" class="text-xl leading-none text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form method="POST" action="{{ route('pickup-addresses.store') }}" class="space-y-3 p-4">
                    @csrf
                    <div>
                        <label for="brand_name" class="mb-1 block text-sm font-semibold text-slate-800">Brand Name <span class="text-rose-500">*</span></label>
                        <input id="brand_name" type="text" name="brand_name" value="{{ old('brand_name') }}" placeholder="Enter brand name" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                        @error('brand_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1 block text-sm font-semibold text-slate-800">Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter shipper phone" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <!-- Pakistan All Cities Datalist (Searchable Input) -->
                    <div>
                        <label for="city" class="mb-1 block text-sm font-semibold text-slate-800">Select City <span class="text-rose-500">*</span></label>
                        <input list="pakistan-cities" id="city" name="city" value="{{ old('city') }}" placeholder="Type to search city (e.g. Sialkot, Lahore, Multan)..." required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">
                        
                        <datalist id="pakistan-cities">
                            @if(isset($cities) && count($cities) > 0)
                                @foreach($cities as $city)
                                    <option value="{{ $city }}">
                                @endforeach
                            @endif
                            <option value="Sialkot"><option value="Lahore"><option value="Karachi"><option value="Islamabad"><option value="Rawalpindi"><option value="Faisalabad"><option value="Multan"><option value="Peshawar"><option value="Quetta"><option value="Gujranwala"><option value="Hyderabad"><option value="Sargodha"><option value="Bahawalpur"><option value="Sukkur"><option value="Kandhkot"><option value="Sheikhupura"><option value="Muzaffargarh"><option value="Rahim Yar Khan"><option value="Jhang"><option value="Dera Ghazi Khan"><option value="Gujrat"><option value="Sahiwal"><option value="Wah Cantonment"><option value="Mardan"><option value="Kasur"><option value="Okara"><option value="Mingora"><option value="Nawabshah"><option value="Chiniot"><option value="Kotri"><option value="Kāmoke"><option value="Hafizabad"><option value="Sadiqabad"><option value="Mirpur Khas"><option value="Burewala"><option value="Kohat"><option value="Khanewal"><option value="Dera Ismail Khan"><option value="Turbat"><option value="Muzaffarabad"><option value="Abbottabad"><option value="Mandi Bahauddin"><option value="Shikarpur"><option value="Jacobabad"><option value="Jhelum"><option value="Khanpur"><option value="Khairpur"><option value="Khuzdar"><option value="Pakpattan"><option value="Hub"><option value="Daska"><option value="Gojra"><option value="Muridke"><option value="Bahawalnagar"><option value="Samundri"><option value="Tando Allahyar"><option value="Tando Adam"><option value="Jaranwala"><option value="Chishtian"><option value="Attock"><option value="Vehari"><option value="Kot Abdul Malik"><option value="Ferozwala"><option value="Chakwal"><option value="Kamalia"><option value="Umerkot"><option value="Ahmedpur East"><option value="Kot Addu"><option value="Wazirabad"><option value="Mansehra"><option value="Layyah"><option value="Swabi"><option value="Chaman"><option value="Taxila"><option value="Nowshera"><option value="Khushab"><option value="Mianwali"><option value="Lodhran"><option value="Badin"><option value="Taunsa"><option value="Rajanpur"><option value="Narowal"><option value="Toba Tek Singh"><option value="Shangla"><option value="Ziarat"><option value="Gwadar"><option value="Ghotki"><option value="Thatta"><option value="Gilgit"><option value="Skardu"><option value="Mirpur (AJK)"><option value="Bhimber"><option value="Kotli">
                        </datalist>
                        @error('city')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="address" class="mb-1 block text-sm font-semibold text-slate-800">Address <span class="text-rose-500">*</span></label>
                        <textarea id="address" name="address" rows="3" placeholder="Enter full address" required
                                  class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none">{{ old('address') }}</textarea>
                        @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 pt-1">
                        <button type="button" @click="showModal = false"
                                class="flex-1 rounded-lg bg-slate-800 py-2.5 text-sm font-semibold text-white hover:bg-slate-900 order-2 sm:order-1">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 rounded-lg bg-teal-600 py-2.5 text-sm font-semibold text-white hover:bg-teal-700 order-1 sm:order-2">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>