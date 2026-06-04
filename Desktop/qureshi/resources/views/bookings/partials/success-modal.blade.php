<div x-show="successOpen" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4" style="display: none;">
    <div class="absolute inset-0 bg-slate-900/70"></div>
    <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-2xl">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600">✓</div>
        <h2 class="text-xl font-bold text-slate-900">Order Booked Successfully</h2>
        <p class="mt-2 text-sm text-slate-500">Tracking: <span class="font-mono font-bold text-teal-600" x-text="booked?.tracking_number"></span></p>
        <div class="mt-6 space-y-2">
            <button type="button" @click="printSlip()"
                    class="w-full rounded-lg bg-slate-800 py-3 text-sm font-semibold text-white hover:bg-slate-900">
                Print Slip
            </button>
            <button type="button" @click="newBooking()"
                    class="w-full rounded-lg bg-orange-500 py-3 text-sm font-semibold text-white hover:bg-orange-600">
                New Booking
            </button>
            <button type="button" @click="newBooking()"
                    class="w-full rounded-lg bg-violet-600 py-3 text-sm font-semibold text-white hover:bg-violet-700">
                Generate Loadsheet
            </button>
            <button type="button" @click="goHome()"
                    class="w-full rounded-lg border border-slate-200 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Return to Home Page
            </button>
        </div>
    </div>
</div>
