<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-6 p-4 sm:p-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('bookings') }}" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
                ← Back to Orders
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900">Edit Order</h1>
                <p class="text-sm text-slate-500">{{ $booking->tracking_number }} — Editing allowed before courier scans</p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl border-l-4 border-emerald-500 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="rounded-xl border-l-4 border-rose-500 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Current Order Summary --}}
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Tracking</p>
                    <p class="font-mono font-bold text-sky-600">{{ $booking->tracking_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Courier</p>
                    <p>{{ $booking->courier_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Destination</p>
                    <p>{{ $booking->destination_city }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase text-slate-500">Status</p>
                    <span class="inline-block rounded bg-amber-100 px-2 py-0.5 text-xs font-bold text-amber-700">Pending</span>
                </div>
            </div>
        </div>

        {{-- Edit Form --}}
        <form method="POST" action="{{ route('bookings.update', $booking->id) }}" class="space-y-6 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Customer Name *</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', $booking->customer_name) }}" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Customer Phone *</label>
                    <input type="text" name="customer_phone" value="{{ old('customer_phone', $booking->customer_phone) }}" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Delivery Address *</label>
                    <textarea name="complete_address" rows="2" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">{{ old('complete_address', $booking->consignee_address) }}</textarea>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Product Name *</label>
                    <input type="text" name="product_name" value="{{ old('product_name', $booking->product_name) }}" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Reference No.</label>
                    <input type="text" name="reference_no" value="{{ old('reference_no', $booking->reference_no) }}"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Quantity *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $booking->quantity ?? 1) }}" min="1" max="99" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Weight (KG) *</label>
                    <input type="number" step="0.1" name="weight" value="{{ old('weight', $booking->weight) }}" min="0.1" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">COD Amount *</label>
                    <input type="number" step="0.01" name="cod_amount" value="{{ old('cod_amount', $booking->cod_amount) }}" min="0" required
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">
                </div>
                <div class="sm:col-span-2">
                    <label class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-600">Special Instructions</label>
                    <textarea name="special_instructions" rows="2"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20">{{ old('special_instructions', $booking->special_instructions) }}</textarea>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-4 flex items-center justify-between">
                <p class="text-xs text-slate-400">
                    ⚠️ City (<strong>{{ $booking->destination_city }}</strong>) and courier cannot be changed after booking.
                </p>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 px-8 py-3 text-sm font-bold text-white shadow-md hover:shadow-lg transition">
                    ✅ Update Order
                </button>
            </div>
        </form>
    </div>
</x-app-layout>