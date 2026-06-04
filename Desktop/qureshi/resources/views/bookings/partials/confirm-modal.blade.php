<div x-show="summaryOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
    <div class="absolute inset-0 bg-slate-900/60" @click="summaryOpen = false"></div>
    <div class="relative w-full max-w-md rounded-2xl border border-slate-200 bg-white p-5 shadow-2xl">
        <h2 class="text-lg font-bold text-slate-900">Booking Summary</h2>
        <p class="mb-4 text-xs text-slate-500">Review details before final confirmation</p>
        <dl class="space-y-2 text-sm">
            <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                <dt class="text-slate-500">Customer Name</dt>
                <dd class="font-semibold text-slate-800 text-right" x-text="customerName"></dd>
            </div>
            <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                <dt class="text-slate-500">Phone Number</dt>
                <dd class="font-semibold text-slate-800 text-right" x-text="customerPhone"></dd>
            </div>
            <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                <dt class="text-slate-500">City</dt>
                <dd class="font-semibold text-slate-800 text-right" x-text="destination"></dd>
            </div>
            <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                <dt class="text-slate-500">Address</dt>
                <dd class="max-w-[55%] font-semibold text-slate-800 text-right" x-text="completeAddress"></dd>
            </div>
            <div class="flex justify-between gap-4 border-b border-slate-100 py-2">
                <dt class="text-slate-500">COD Amount</dt>
                <dd class="font-semibold text-sky-600 text-right">Rs. <span x-text="Number(codAmount).toLocaleString()"></span></dd>
            </div>
            <div class="flex justify-between gap-4 py-2">
                <dt class="text-slate-500">Delivery Charges</dt>
                <dd class="font-bold text-amber-700 text-right">Rs. <span x-text="deliveryCharges.toLocaleString()"></span></dd>
            </div>
        </dl>
        <p class="mt-2 text-[10px] text-slate-400">Delivery charges are visible only to you on this portal.</p>
        <div class="mt-5 flex gap-2">
            <button type="button" @click="summaryOpen = false"
                    class="flex-1 rounded-lg border border-slate-200 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Back
            </button>
            <button type="button" @click="finalSubmit()" :disabled="submitting"
                    class="flex-1 rounded-lg bg-orange-500 py-2.5 text-sm font-bold text-white hover:bg-orange-600 disabled:opacity-60">
                <span x-show="!submitting">Final Submit / Confirm</span>
                <span x-show="submitting">Processing...</span>
            </button>
        </div>
    </div>
</div>
