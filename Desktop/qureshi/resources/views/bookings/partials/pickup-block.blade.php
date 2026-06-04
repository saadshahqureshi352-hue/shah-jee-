<button type="button" @click="pickupOpen = !pickupOpen"
        class="flex w-full items-center justify-between bg-slate-800 px-4 py-3 text-left text-sm font-semibold text-white">
    <span>Shipper Pickup Details</span>
    <svg class="h-5 w-5 transition" :class="pickupOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
</button>
<div x-show="pickupOpen" x-cloak class="space-y-3 border-t border-slate-100 p-4">
    @if($pickupAddresses->isEmpty())
        <p class="text-sm text-slate-500">No saved pickup address. <a href="{{ route('pickup-addresses.index') }}" class="font-medium text-teal-600 hover:underline">Add one</a> first.</p>
    @else
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Select Shipper</label>
            <select name="pickup_address_id" x-model="pickupId" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
                <option value="">— Select —</option>
                @foreach($pickupAddresses as $pickup)
                    <option value="{{ $pickup->id }}">{{ $pickup->brand_name }} — {{ $pickup->city }}</option>
                @endforeach
            </select>
        </div>
        <template x-if="selectedPickup">
            <div class="space-y-3">
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Phone Number</label>
                    <input type="text" readonly :value="selectedPickup.phone"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">
                </div>
                <div>
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Pickup Address</label>
                    <input type="text" readonly :value="selectedPickup.address + ', ' + selectedPickup.city"
                           class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-700">
                </div>
            </div>
        </template>
    @endif
</div>
