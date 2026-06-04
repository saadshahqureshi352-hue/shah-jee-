<x-app-layout>
    <div class="min-h-[70vh] flex items-center justify-center p-4 sm:p-6">
        <div class="max-w-md w-full text-center">
            {{-- Clock Icon --}}
            <div class="flex justify-center mb-6">
                <div class="flex h-24 w-24 items-center justify-center rounded-full bg-amber-100 shadow-lg">
                    <svg class="h-12 w-12 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-black text-slate-800 mb-3">
                Account Pending Approval
            </h1>

            {{-- Message --}}
            <p class="text-slate-600 text-sm sm:text-base mb-2 leading-relaxed">
                Your account is currently under review by the admin team.
            </p>
            <p class="text-slate-500 text-xs sm:text-sm mb-8 leading-relaxed">
                You will be able to access the dashboard and book shipments once your account is approved.
            </p>

            {{-- Info Card --}}
            <div class="rounded-2xl border-2 border-amber-200 bg-amber-50 p-5 shadow-sm mb-8">
                <div class="flex items-center gap-2 justify-center mb-2">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm font-bold text-amber-700">What's Next?</span>
                </div>
                <ul class="text-xs text-amber-700 space-y-1.5 text-left max-w-xs mx-auto">
                    <li class="flex items-start gap-2">
                        <span class="text-amber-500 mt-0.5">•</span>
                        <span>Admin will review your registration details</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-amber-500 mt-0.5">•</span>
                        <span>You will be notified once approved</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-amber-500 mt-0.5">•</span>
                        <span>After approval, you can book shipments and manage orders</span>
                    </li>
                </ul>
            </div>

            {{-- Contact Info --}}
            <div class="text-xs text-slate-400">
                If you have any questions, please contact support:
                <span class="block mt-1 font-semibold text-slate-600">shahjeecourier@gmail.com</span>
            </div>

            {{-- Logout (optional, already in layout) --}}
        </div>
    </div>
</x-app-layout>