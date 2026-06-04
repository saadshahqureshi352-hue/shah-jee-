<aside class="mt-4 space-y-4 lg:col-span-1 lg:mt-0">
    <div class="hidden rounded-lg bg-slate-800 px-3 py-2 text-center text-xs font-semibold text-white lg:block">
        ✦ {{ $courier['name'] }} — Ready to Book
    </div>

    @include('bookings.partials.courier-card')

    <section class="hidden overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm lg:block">
        @include('bookings.partials.pickup-block')
    </section>

    <div class="hidden lg:block">
        @include('bookings.partials.service-type')
    </div>

    <div class="hidden rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm lg:block">
        <p class="text-xs font-semibold uppercase text-amber-800">Delivery Charges (Shipper only)</p>
        <p class="mt-1 text-2xl font-bold text-amber-900">Rs. <span x-text="deliveryCharges.toLocaleString()"></span></p>
        <p class="mt-1 text-xs text-amber-700">Based on weight & service type</p>
    </div>

    <button type="submit"
            class="hidden w-full rounded-xl bg-orange-500 py-3.5 text-center text-base font-bold text-white shadow-md transition hover:bg-orange-600 lg:block">
        Confirm Booking
    </button>
</aside>

<button type="submit"
        class="fixed bottom-0 left-0 right-0 z-20 border-t border-orange-400 bg-orange-500 py-4 text-center text-base font-bold text-white shadow-lg transition hover:bg-orange-600 lg:hidden">
    Confirm Booking
</button>
