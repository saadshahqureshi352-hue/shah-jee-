<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6">
        <div>
            <h1 class="text-xl font-bold text-slate-900 sm:text-2xl">Universal Tracking System</h1>
            <p class="mt-1 text-sm text-slate-600">Enter a tracking or reference number. We match your bookings first; courier APIs can be wired per partner (TCS, Leopards, Trax, M&amp;P).</p>
        </div>

        <form method="GET" action="{{ route('smart-tools.tracking') }}" class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-end">
            <div class="flex-1">
                <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Tracking / Ref</label>
                <input type="text" name="q" value="{{ $trackingInput }}" placeholder="SJC… or reference"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500">
            </div>
            <div class="sm:w-44">
                <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Courier (optional)</label>
                <select name="courier" class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm">
                    <option value="">Auto / any</option>
                    @foreach($couriers as $c)
                        <option value="{{ $c }}" @selected($courier === $c)>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-lg bg-teal-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-teal-700">Track</button>
        </form>

        @if($apiNote)
            <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">{{ $apiNote }}</p>
        @endif

        @if($result)
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 bg-slate-700 px-4 py-2 text-sm font-semibold text-white">Result</div>
                <div class="p-4 text-sm">
                    <p><strong>Tracking:</strong> <span class="font-mono text-teal-700">{{ $result->tracking_number }}</span></p>
                    <p class="mt-2"><strong>Status:</strong>
                        @php $m = \App\Http\Controllers\OrderController::statusMeta($result->status); @endphp
                        <span class="rounded px-2 py-0.5 text-xs font-bold {{ $m['badge'] }}">{{ $m['label'] }}</span>
                    </p>
                    <p class="mt-2"><strong>Courier:</strong> {{ $result->courier_name ?? '—' }}</p>
                    <p class="mt-2"><strong>Consignee:</strong> {{ $result->customer_name }}</p>
                    <p class="mt-2"><strong>Destination:</strong> {{ $result->destination_city }}</p>
                    <p class="mt-2"><strong>COD:</strong> Rs. {{ number_format((float) $result->cod_amount) }}</p>
                    <p class="mt-4 text-xs text-slate-400">Courier API enrichment (dispatched / out-for-delivery / delivered timeline) attaches here once credentials are configured.</p>
                    <p class="mt-2 text-xs"><strong>Customer link:</strong>
                        <a class="break-all text-teal-600 hover:underline" href="{{ route('track.show', $result->tracking_number) }}" target="_blank">{{ route('track.show', $result->tracking_number) }}</a>
                    </p>
                </div>
            </div>
        @elseif($trackingInput)
            <p class="rounded-lg border border-slate-200 bg-white p-6 text-center text-slate-500">No shipment found with that search.</p>
        @endif
    </div>
</x-app-layout>
