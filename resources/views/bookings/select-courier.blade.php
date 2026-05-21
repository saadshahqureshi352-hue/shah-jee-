@php
    $count = count($couriers);
@endphp

<x-app-layout>
    <div class="fixed inset-0 z-30 bg-slate-900/50 lg:left-64" aria-hidden="true"></div>

    <div class="relative z-40 mx-auto flex min-h-[calc(100vh-4rem)] max-w-lg items-center justify-center p-4 sm:p-6">
        <div class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
            <div class="flex items-start justify-between border-b border-slate-100 px-5 py-4">
                <div class="flex items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500 text-white shadow-sm">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <div>
                        <h1 class="text-lg font-bold text-slate-900">Select Courier</h1>
                        <p class="text-sm text-slate-500">Pick a partner to book a packet</p>
                    </div>
                </div>
                <a href="{{ route('bookings') }}" class="rounded-lg border border-slate-200 p-2 text-slate-500 transition hover:bg-slate-50 hover:text-slate-800" aria-label="Close">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>

            <ul class="max-h-[min(70vh,520px)] space-y-2 overflow-y-auto p-4">
                @foreach($couriers as $slug => $courier)
                    <li>
                        <a href="{{ route('bookings.create', ['courier' => $slug]) }}"
                           class="group flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3 transition hover:border-orange-300 hover:shadow-md">
                            <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white {{ $courier['bg'] }}">
                                {{ Str::substr($courier['name'], 0, 2) }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-slate-800">{{ $courier['name'] }}</p>
                                <p class="text-xs text-slate-500">{{ $courier['tagline'] }}</p>
                            </div>
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-500 text-white transition group-hover:bg-orange-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="flex items-center gap-2 border-t border-slate-100 px-5 py-3 text-xs text-slate-500">
                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                {{ $count }} couriers available
            </div>
        </div>
    </div>
</x-app-layout>
