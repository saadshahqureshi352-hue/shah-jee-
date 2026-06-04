{{-- Consignee --}}
<section class="space-y-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <h2 class="text-xs font-bold uppercase tracking-wide text-slate-500">Consignee Details</h2>

    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Destination <span class="text-rose-500">*</span></label>
        <div class="relative">
            <input type="text" name="destination_city" x-model="destination" @focus="cityOpen = true" @input="cityOpen = true" required
                   class="w-full rounded-lg border border-slate-200 px-3 py-2.5 pr-24 text-sm focus:border-teal-500 focus:ring-teal-500"
                   placeholder="Search city (A–Z)..." autocomplete="off">
            <button type="button" @click="cityOpen = !cityOpen"
                    class="absolute right-1 top-1/2 -translate-y-1/2 rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white">
                Find City
            </button>
            <div x-show="cityOpen && filteredCities.length" @click.outside="cityOpen = false" x-cloak
                 class="absolute left-0 right-0 top-full z-30 mt-1 max-h-52 overflow-y-auto rounded-lg border border-slate-200 bg-white shadow-lg">
                <template x-for="city in filteredCities" :key="city">
                    <button type="button" @click="selectCity(city)"
                            class="block w-full px-3 py-2 text-left text-sm hover:bg-teal-50" x-text="city"></button>
                </template>
            </div>
        </div>
    </div>

    <div class="grid gap-3 lg:grid-cols-2">
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Consignee Name <span class="text-rose-500">*</span></label>
            <input type="text" name="customer_name" x-model="customerName" value="{{ old('customer_name') }}" required
                   class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Consignee Phone <span class="text-rose-500">*</span></label>
            <input type="tel" name="customer_phone" x-model="customerPhone" value="{{ old('customer_phone') }}" required
                   class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
        </div>
    </div>

    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">2nd Phone (Optional)</label>
        <input type="tel" name="second_phone" value="{{ old('second_phone') }}"
               class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500 lg:max-w-md">
    </div>

    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Complete Address <span class="text-rose-500">*</span></label>
        <textarea name="complete_address" x-model="completeAddress" rows="3" required
                  class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">{{ old('complete_address') }}</textarea>
    </div>
</section>

{{-- Parcel --}}
<section class="space-y-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <h2 class="text-xs font-bold uppercase tracking-wide text-slate-500">Parcel Details</h2>

    <div class="grid gap-4 lg:grid-cols-2">
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Quantity <span class="text-rose-500">*</span></label>
            <input type="number" name="quantity" x-model="quantity" min="1" max="99" required
                   class="mb-2 w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
            <div class="flex flex-wrap gap-2">
                @foreach(['01', '02', '03', '04'] as $q)
                    <button type="button" @click="quantity = {{ (int) $q }}"
                            :class="quantity == {{ (int) $q }} ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-slate-700 border-slate-200'"
                            class="rounded-lg border px-3 py-1.5 text-xs font-semibold">{{ $q }}</button>
                @endforeach
            </div>
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Weight (KG) <span class="text-rose-500">*</span></label>
            <input type="number" name="weight" x-model="weight" step="0.1" min="0.1" required
                   class="mb-2 w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
            <div class="flex flex-wrap gap-2">
                @foreach([0.5, 1, 2, 3, 5] as $w)
                    <button type="button" @click="weight = {{ $w }}"
                            :class="weight == {{ $w }} ? 'bg-orange-500 text-white border-orange-500' : 'bg-white text-slate-700 border-slate-200'"
                            class="rounded-lg border px-3 py-1.5 text-xs font-semibold">{{ $w }}kg</button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid gap-3 lg:grid-cols-2">
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">COD Amount <span class="text-rose-500">*</span></label>
            <input type="number" name="cod_amount" x-model="codAmount" value="{{ old('cod_amount', 0) }}" min="0" step="1" required
                   class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
        </div>
        <div>
            <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Ref Order ID</label>
            <input type="text" name="reference_no" value="{{ old('reference_no') }}" placeholder="Shopify / WooCommerce ID"
                   class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
        </div>
    </div>

    <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
        <input type="checkbox" name="is_non_cod" value="1" class="mt-0.5 rounded border-slate-300 text-teal-600 focus:ring-teal-500" @checked(old('is_non_cod'))>
        <span>
            <span class="text-sm font-semibold text-slate-800">Non-COD (advance paid)</span>
            <span class="mt-0.5 block text-xs text-slate-500">Use when cash is not collected on delivery. Record wallet/bank top-ups under My Payments → Add Payment for Non-COD.</span>
        </span>
    </label>

    <div>
        <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Product Name <span class="text-rose-500">*</span></label>
        <input type="text" name="product_name" x-model="productName" required
               class="mb-2 w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
               placeholder="General Item">
        <div class="flex flex-wrap gap-2">
            @foreach([
                ['General Item', 'bg-orange-100 text-orange-800 border-orange-200'],
                ['Gift Item', 'bg-sky-100 text-sky-800 border-sky-200'],
                ['Flash Item', 'bg-pink-100 text-pink-800 border-pink-200'],
                ['Customised', 'bg-emerald-100 text-emerald-800 border-emerald-200'],
            ] as [$label, $chip])
                <button type="button" @click="productName = '{{ $label }}'"
                        class="rounded-lg border px-3 py-1.5 text-xs font-semibold {{ $chip }}">{{ $label }}</button>
            @endforeach
        </div>
    </div>
</section>

<section class="space-y-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
    <label class="block text-xs font-semibold uppercase text-slate-600">Special Instructions</label>
    <textarea name="special_instructions" x-model="instructions" rows="2"
              class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"></textarea>
    <div class="flex flex-wrap gap-2">
        @foreach([
            ['Handle Carefully', 'bg-sky-100 text-sky-800'],
            ['Allow to Open', 'bg-emerald-100 text-emerald-800'],
            ['Fragile Item', 'bg-orange-100 text-orange-800'],
            ['Call Before delivery', 'bg-pink-100 text-pink-800'],
        ] as [$tag, $cls])
            <button type="button" @click="instructions = '{{ $tag }}'"
                    class="rounded-lg px-3 py-1.5 text-xs font-semibold {{ $cls }}">{{ $tag }}</button>
        @endforeach
    </div>
</section>

{{-- Pickup: mobile stacked; desktop in left column --}}
<section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:hidden">
    @include('bookings.partials.pickup-block')
</section>

{{-- Mobile-only courier + service (stacked) --}}
<div class="space-y-3 lg:hidden">
    @include('bookings.partials.courier-card')
    @include('bookings.partials.service-type')

    <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm">
        <p class="text-xs font-semibold uppercase text-amber-800">Delivery Charges (Shipper only)</p>
        <p class="mt-1 text-xl font-bold text-amber-900">Rs. <span x-text="deliveryCharges.toLocaleString()"></span></p>
    </div>
</div>
