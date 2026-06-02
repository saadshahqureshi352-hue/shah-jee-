@props(['title', 'icon' => 'ti-table'])

<div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-panel">
    <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4">
        <div class="flex items-center gap-2">
            <span class="grid h-9 w-9 place-items-center rounded-xl bg-teal-50 text-teal-700">
                <i class="ti {{ $icon }} text-lg"></i>
            </span>
            <div class="text-sm font-black text-slate-950">{{ $title }}</div>
        </div>
        <button type="button" onclick="showToast('Data refreshed')" class="rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-50">
            Refresh
        </button>
    </div>
    <div class="scrollbar overflow-x-auto">
        {{ $slot }}
    </div>
</div>
