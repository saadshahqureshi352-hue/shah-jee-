<x-app-layout>
    <div x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }">
        <div class="mx-auto max-w-2xl space-y-5 p-4 sm:p-6">
            @if(session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <h1 class="text-xl font-bold text-slate-800 sm:text-2xl">My Pickup Addresses</h1>

            <button type="button" @click="showModal = true"
                    class="w-full rounded-xl bg-teal-600 py-3 text-center text-sm font-semibold text-white shadow-md transition hover:bg-teal-700">
                Add Pickup
            </button>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border-2 border-rose-300 bg-white p-3 shadow-sm sm:p-4">
                    <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-rose-500 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-slate-500">Total Pickups</p>
                    <p class="text-xl font-bold text-slate-800">{{ str_pad((string) $stats['total'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="relative rounded-xl border-2 border-amber-300 bg-white p-3 shadow-sm sm:p-4">
                    <span class="absolute right-2 top-2 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-[10px] text-white">✓</span>
                    <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <p class="text-xs font-medium text-slate-500">Active Pickup</p>
                    <p class="text-xl font-bold text-slate-800">{{ str_pad((string) $stats['active'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="rounded-xl border-2 border-sky-300 bg-white p-3 shadow-sm sm:p-4">
                    <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-sky-500 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-xs font-medium text-slate-500">Blocked Pickups</p>
                    <p class="text-xl font-bold text-slate-800">{{ str_pad((string) $stats['blocked'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="rounded-xl border-2 border-emerald-300 bg-white p-3 shadow-sm sm:p-4">
                    <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <p class="text-xs font-medium text-slate-500">Deleted</p>
                    <p class="text-xl font-bold text-slate-800">{{ str_pad((string) $stats['deleted'], 2, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <div class="space-y-4">
                @forelse($addresses as $index => $address)
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 px-4 py-2.5">
                            <span class="text-sm font-semibold text-slate-600">Sr {{ str_pad((string) ($addresses->count() - $index), 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="space-y-3 p-4">
                            <div>
                                <p class="text-xs text-slate-500">Booking ID</p>
                                <p class="text-lg font-bold text-slate-800">{{ $address->booking_id }}</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-xs text-slate-500">Name</p>
                                    <p class="font-semibold text-slate-800">{{ $address->brand_name }}</p>
                                    <p class="text-slate-600">{{ $address->phone }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Address</p>
                                    <p class="font-medium text-slate-800">{{ Str::limit($address->address, 35) }}</p>
                                    <p class="text-slate-600">{{ $address->city }}</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between border-t border-slate-100 pt-3">
                                <div>
                                    <p class="text-xs text-slate-500">Status</p>
                                    <span class="mt-0.5 inline-block rounded px-2 py-0.5 text-xs font-semibold uppercase text-white
                                        {{ $address->status === 'active' ? 'bg-emerald-500' : ($address->status === 'blocked' ? 'bg-sky-500' : 'bg-slate-400') }}">
                                        {{ $address->status }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-500">Date</p>
                                    <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($address->created_at)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">
                        No pickup address yet. Tap <strong>Add Pickup</strong> to create one.
                    </div>
                @endforelse
            </div>
        </div>

        <div
            x-show="showModal"
            x-cloak
            @keydown.escape.window="showModal = false"
            style="display: none;"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4"
        >
            <div class="absolute inset-0 bg-slate-900/60" @click="showModal = false"></div>
            <div class="relative z-10 w-full max-w-md rounded-2xl bg-white shadow-2xl" @click.stop>
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <h2 class="text-base font-bold text-slate-800">Add Pickup Address</h2>
                    <button type="button" @click="showModal = false" class="text-xl leading-none text-slate-400 hover:text-slate-600">&times;</button>
                </div>

                <form method="POST" action="{{ route('pickup-addresses.store') }}" class="space-y-3 p-4">
                    @csrf
                    <div>
                        <label for="brand_name" class="mb-1 block text-sm font-semibold text-slate-800">Brand Name <span class="text-rose-500">*</span></label>
                        <input id="brand_name" type="text" name="brand_name" value="{{ old('brand_name') }}" placeholder="Enter brand name" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                        @error('brand_name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="mb-1 block text-sm font-semibold text-slate-800">Phone Number <span class="text-rose-500">*</span></label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter shipper phone" required
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                        @error('phone')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="city" class="mb-1 block text-sm font-semibold text-slate-800">Select City <span class="text-rose-500">*</span></label>
                        <select id="city" name="city" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">
                            <option value="">Select city...</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" @selected(old('city') === $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        @error('city')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="address" class="mb-1 block text-sm font-semibold text-slate-800">Address <span class="text-rose-500">*</span></label>
                        <textarea id="address" name="address" rows="3" placeholder="Enter full address" required
                                  class="w-full resize-none rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-teal-500 focus:outline-none focus:ring-1 focus:ring-teal-500">{{ old('address') }}</textarea>
                        @error('address')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="button" @click="showModal = false"
                                class="flex-1 rounded-lg bg-slate-800 py-2.5 text-sm font-semibold text-white hover:bg-slate-900">
                            Cancel
                        </button>
                        <button type="submit"
                                class="flex-1 rounded-lg bg-teal-600 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
