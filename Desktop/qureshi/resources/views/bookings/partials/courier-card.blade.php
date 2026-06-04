<div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
    <span class="flex h-11 w-11 items-center justify-center rounded-xl text-sm font-bold text-white {{ $courier['bg'] }}">
        {{ Str::substr($courier['name'], 0, 2) }}
    </span>
    <div class="flex-1">
        <p class="font-semibold text-slate-800">{{ $courier['name'] }}</p>
        <p class="text-xs text-slate-500" x-text="destination ? 'Ready to book to ' + destination : 'Select a city to continue'"></p>
    </div>
    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-orange-500 text-white text-sm">✓</span>
</div>
