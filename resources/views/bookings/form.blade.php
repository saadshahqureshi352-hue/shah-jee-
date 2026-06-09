@php
    $pickupJson = $pickupAddresses->map(fn ($p) => [
        'id' => $p->id,
        'brand_name' => $p->brand_name,
        'phone' => $p->phone,
        'city' => $p->city,
        'address' => $p->address,
        'booking_id' => $p->booking_id,
    ])->values();
@endphp

<x-app-layout>
    <div class="mx-auto max-w-7xl p-4 pb-28 lg:pb-8 lg:p-6" x-data="bookPacketForm()" x-ref="root">
        @if($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-inside list-disc space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
            <div>
                <a href="{{ route('bookings.create') }}" class="mb-2 inline-flex items-center gap-1 text-xs font-medium text-teal-600 hover:underline">
                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Change courier
                </a>
                <h1 class="text-xl font-bold text-slate-900 lg:text-2xl flex items-center gap-2">Book Packet — 
                    <span class="inline-flex items-center gap-1.5">
                        <img src="{{ asset($courier['logo']) }}" alt="{{ $courier['name'] }}" class="h-6 w-6 object-contain">
                        {{ $courier['name'] }}
                    </span>
                </h1>
                <p class="mt-0.5 flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="h-1.5 w-1.5 rounded-full bg-teal-500"></span>
                    Shah Jee Courier
                </p>
            </div>
            <div class="hidden rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white lg:flex items-center gap-2">
                <img src="{{ asset($courier['logo']) }}" alt="{{ $courier['name'] }}" class="h-5 w-5 object-contain">
                {{ $courier['name'] }} Courier — Ready to Book
            </div>
        </div>

        <form x-ref="bookingForm" @submit.prevent="openSummary()" class="lg:grid lg:grid-cols-3 lg:items-start lg:gap-6">
            @csrf
            <input type="hidden" name="courier_slug" value="{{ $courier['slug'] }}">
            <input type="hidden" name="delivery_charges" :value="deliveryCharges">

            <div class="space-y-4 lg:col-span-2">
                @include('bookings.partials.form-fields')
            </div>

            @include('bookings.partials.form-sidebar')
        </form>

        @include('bookings.partials.confirm-modal')
        @include('bookings.partials.success-modal')
    </div>

    <script>
        function bookPacketForm() {
            return {
                destination: @json(old('destination_city', '')),
                customerName: @json(old('customer_name', '')),
                customerPhone: @json(old('customer_phone', '')),
                completeAddress: @json(old('complete_address', '')),
                codAmount: @json(old('cod_amount', 0)),
                quantity: {{ old('quantity', 1) }},
                weight: {{ old('weight', 1) }},
                productName: @json(old('product_name', 'General Item')),
                instructions: @json(old('special_instructions', 'Handle with Care')),
                serviceType: @json(old('service_type', 'Overnight')),
                pickupId: @json(old('pickup_address_id', '')),
                pickupOpen: true,
                cityOpen: false,
                summaryOpen: false,
                successOpen: false,
                submitting: false,
                booked: null,
                cities: @json($cities),
                pickups: @json($pickupJson),
                courierSlug: @json($courier['slug']),
                get filteredCities() {
                    const q = (this.destination || '').trim().toLowerCase();
                    if (!q) return this.cities.slice(0, 15);
                    return this.cities.filter(c => c.toLowerCase().includes(q)).slice(0, 20);
                },
                get selectedPickup() {
                    return this.pickups.find(p => String(p.id) === String(this.pickupId)) || null;
                },
                // Pricing plan rates (merchant tier): fetched dynamically from DB
                overnightBase: @json($pricingPlan?->overnight_base_rate ?? 0),
                overnightAdditional: @json($pricingPlan?->overnight_additional_rate ?? 0),
                detainBase: @json($pricingPlan?->detain_base_rate ?? 0),
                detainAdditional: @json($pricingPlan?->detain_additional_rate ?? 0),
                overlandBase: @json($pricingPlan?->overland_base_rate ?? 0),
                overlandAdditional: @json($pricingPlan?->overland_additional_rate ?? 0),

                get deliveryCharges() {
                    const weightG = parseFloat(this.weight) || 0;

                    const ratesByService = {
                        Overnight: { base: parseFloat(this.overnightBase) || 0, additional: parseFloat(this.overnightAdditional) || 0 },
                        Detain: { base: parseFloat(this.detainBase) || 0, additional: parseFloat(this.detainAdditional) || 0 },
                        Overland: { base: parseFloat(this.overlandBase) || 0, additional: parseFloat(this.overlandAdditional) || 0 },
                    };

                    const rates = ratesByService[this.serviceType] ?? { base: 0, additional: 0 };
                    const baseRate = rates.base;
                    const additionalRate = rates.additional;

                    let totalDeliveryCharges = 0;

                    if (weightG >= 1 && weightG <= 1000) {
                        totalDeliveryCharges = baseRate;
                    } else if (weightG >= 1001 && weightG <= 1999) {
                        totalDeliveryCharges = baseRate + additionalRate;
                    } else if (weightG >= 2000 && weightG <= 2999) {
                        totalDeliveryCharges = baseRate * 2;
                    } else if (weightG === 3000) {
                        totalDeliveryCharges = baseRate * 3;
                    } else {
                        // Safe fallback for weights scaling beyond 3000g based on the established sequence
                        totalDeliveryCharges = (Math.floor(weightG / 1000) * baseRate);
                    }

                    return Math.round(totalDeliveryCharges);
                },
                selectCity(city) {
                    this.destination = city;
                    this.cityOpen = false;
                },
                openSummary() {
                    const form = this.$refs.bookingForm;
                    if (!form.reportValidity()) return;
                    this.customerName = form.customer_name.value;
                    this.customerPhone = form.customer_phone.value;
                    this.completeAddress = form.complete_address.value;
                    this.destination = form.destination_city.value;
                    this.codAmount = form.cod_amount.value;
                    this.summaryOpen = true;
                },
                async finalSubmit() {
                    this.submitting = true;
                    const form = this.$refs.bookingForm;
                    const data = new FormData(form);
                    data.set('delivery_charges', this.deliveryCharges);
                    try {
                        const res = await fetch(@json(route('shipment.book')), {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: data,
                        });
                        const json = await res.json();
                        if (!res.ok) {
                            const msgs = json.errors ? Object.values(json.errors).flat().join('\n') : (json.message || 'Booking failed.');
                            alert(msgs);
                            return;
                        }
                        this.summaryOpen = false;
                        this.booked = json;
                        this.successOpen = true;
                    } catch (e) {
                        alert(e.message || 'Booking failed. Please check all fields.');
                    } finally {
                        this.submitting = false;
                    }
                },
                printSlip() {
                    if (this.booked?.slip_url) window.open(this.booked.slip_url, '_blank');
                },
                newBooking() {
                    window.location.href = this.booked?.new_booking_url || @json(route('bookings.create', ['courier' => $courier['slug']]));
                },
                goHome() {
                    window.location.href = @json(route('bookings'));
                },
            };
        }
    </script>
</x-app-layout>
