<div class="space-y-2">
    <p class="text-xs font-bold uppercase text-slate-500">Service Type</p>
    <div class="grid grid-cols-3 gap-2">
        @foreach(['Overnight', 'Detain', 'Overland'] as $service)
            <label class="cursor-pointer">
                <input type="radio" name="service_type" value="{{ $service }}" class="peer sr-only"
                       @checked(old('service_type', 'Overnight') === $service) x-model="serviceType">
                <span class="flex items-center gap-2 rounded-xl border px-2 py-3 text-xs font-semibold transition sm:px-3 sm:text-sm border-slate-200 bg-white text-slate-700"
                      :class="serviceType === '{{ $service }}' && 'border-orange-400 bg-slate-800 text-white'">
                    <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded border text-[10px]"
                          :class="serviceType === '{{ $service }}' ? 'border-orange-400 bg-orange-500 text-white' : 'border-slate-300'">
                        <span x-show="serviceType === '{{ $service }}'">✓</span>
                    </span>
                    {{ $service }}
                </span>
            </label>
        @endforeach
    </div>
</div>
