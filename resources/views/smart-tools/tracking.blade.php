<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-gradient-to-br from-teal-600 via-cyan-600 to-indigo-600 p-5 sm:p-6 shadow-sm">
            <div class="absolute -right-20 -top-16 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -left-20 -bottom-20 h-56 w-56 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative">
                <p class="text-xs font-semibold uppercase tracking-wide text-white/90">Shah Jee Courier</p>
                <h1 class="mt-1 text-xl font-extrabold text-white sm:text-2xl">Live Parcel Tracking</h1>
                <p class="mt-2 text-sm text-white/90">
                    Check Your Order Status Here.
                </p>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex-1">
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Tracking / Ref</label>
                    <input
                        id="tracking-q"
                        type="text"
                        name="q"
                        value="{{ $trackingInput ?? '' }}"
                        placeholder="SJC… or reference"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-500 focus:ring-teal-500"
                    >
                    <p class="mt-2 text-xs text-slate-500">
                        Tip: Enter Your Tracking Number 
                    </p>
                </div>

                <div class="sm:w-44">
                    <label class="mb-1 block text-xs font-semibold uppercase text-slate-500">Courier (optional)</label>
                    <select
                        id="tracking-courier"
                        class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm"
                    >
                        <option value="">Auto / any</option>
                        @foreach(($couriers ?? []) as $c)
                            <option value="{{ $c }}" @selected(($courier ?? '') === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>

                <button
                    id="tracking-submit"
                    type="button"
                    class="rounded-lg bg-teal-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-teal-700"
                >
                    Track
                </button>
            </div>
        </div>

        <div id="tracking-status" class="hidden rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 h-2.5 w-2.5 animate-pulse rounded-full bg-teal-600"></div>
                <div class="text-sm">
                    <p class="font-semibold text-slate-900">Fetching courier updates…</p>
                    <p class="mt-1 text-xs text-slate-500">Please wait a second.</p>
                </div>
            </div>
        </div>

        @if(!empty($apiNote))
            <p class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                {{ $apiNote }}
            </p>
        @endif

        <div id="tracking-result">
            @if($result)
                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 bg-slate-700 px-4 py-2 text-sm font-semibold text-white">
                        Result
                    </div>
                    <div class="p-4 text-sm">
                        <p>
                            <strong>Tracking:</strong>
                            <span class="font-mono text-teal-700">{{ $result->tracking_number }}</span>
                        </p>

                        <p class="mt-2">
                            <strong>Status:</strong>
                            @php $m = \App\Http\Controllers\OrderController::statusMeta($result->status); @endphp
                            <span class="rounded px-2 py-0.5 text-xs font-bold {{ $m['badge'] }}">{{ $m['label'] }}</span>
                        </p>

                        <p class="mt-2"><strong>Courier:</strong> {{ $result->courier_name ?? '—' }}</p>
                        <p class="mt-2"><strong>Consignee:</strong> {{ $result->customer_name }}</p>
                        <p class="mt-2"><strong>Destination:</strong> {{ $result->destination_city }}</p>
                        <p class="mt-2"><strong>COD:</strong> Rs. {{ number_format((float) $result->cod_amount) }}</p>

                        <p class="mt-4 text-xs text-slate-400">
                            Courier API enrichment attaches here once integrations are enabled.
                        </p>

                        <p class="mt-2 text-xs">
                            <strong>Customer link:</strong>
                            <a
                                class="break-all text-teal-600 hover:underline"
                                href="{{ route('track.show', $result->tracking_number) }}"
                                target="_blank"
                            >
                                {{ route('track.show', $result->tracking_number) }}
                            </a>
                        </p>
                    </div>
                </div>
            @elseif(!empty($trackingInput))
                <p class="rounded-lg border border-slate-200 bg-white p-6 text-center text-slate-500">
                    No shipment found with that search.
                </p>
            @else
                <!-- اپڈیٹڈ واٹس ایپ باکس (ٹیکسٹ کے سامنے اینیمیٹڈ واٹس ایپ آئیکن کے ساتھ) -->
                <div class="flex items-center justify-center gap-3 rounded-lg border border-slate-200 bg-white p-6 shadow-sm text-center">
                    <span class="text-sm font-medium text-slate-500">
                        For Any Issues/Query Contact Us On Whatsapp
                    </span>
                    <a href="https://wa.me/923197290092" 
                       target="_blank" 
                       rel="noopener noreferrer" 
                       class="relative flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-white shadow-md transition-all duration-200 animate-whatsapp hover:scale-110 hover:bg-emerald-600"
                       title="Contact Support on WhatsApp">
                        <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.454 5.709 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('tracking-q');
            const statusBox = document.getElementById('tracking-status');
            const resultBox = document.getElementById('tracking-result');
            const submitBtn = document.getElementById('tracking-submit');
            const courierSelect = document.getElementById('tracking-courier');

            let timer = null;
            let lastRequestId = 0;

            function escapeHtml(str) {
                return String(str ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function renderTimeline(events) {
                if (!Array.isArray(events) || events.length === 0) {
                    return `
                        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                            <p class="text-sm font-semibold text-slate-700">No timeline events found.</p>
                        </div>
                    `;
                }

                const items = events.map((e, idx) => {
                    const st = e?.status ?? '';
                    const isCurrent = st === 'current';
                    const isDone = st === 'done' || isCurrent;

                    const dot = isCurrent
                        ? 'bg-teal-600 ring-4 ring-teal-100'
                        : (isDone ? 'bg-emerald-600 ring-4 ring-emerald-100' : 'bg-slate-300 ring-4 ring-slate-100');

                    const line = (isCurrent || isDone) ? 'bg-emerald-200' : 'bg-slate-200';

                    return `
                        <li class="relative pl-8">
                            <span class="absolute left-2 top-1 h-4 w-4 rounded-full ${dot}"></span>
                            <span class="absolute left-[15px] top-4 h-[calc(100%-10px)] w-px ${line}"></span>
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-sm font-extrabold text-slate-900">${escapeHtml(e?.title ?? ('Step ' + (idx + 1)))}</p>
                                    ${isCurrent ? `<span class="rounded-lg bg-teal-600 px-2 py-0.5 text-[11px] font-bold text-white">Now</span>` : ``}
                                </div>
                                ${e?.detail ? `<p class="mt-1 text-sm text-slate-600">${escapeHtml(e?.detail)}</p>` : ``}
                                ${e?.time ? `<p class="mt-2 text-xs font-semibold text-slate-500">Updated: ${escapeHtml(e?.time)}</p>` : ``}
                            </div>
                        </li>
                    `;
                }).join('');

                return `
                    <div class="mt-4 flow-root">
                        <ol class="relative ml-1 space-y-5">${items}</ol>
                    </div>
                `;
            }

            async function fetchTracking(trackingNumber) {
                const requestId = ++lastRequestId;

                resultBox.innerHTML = '';
                statusBox.classList.remove('hidden');

                try {
                    const tn = String(trackingNumber ?? '').trim();

                    // Placeholder route to avoid Blade inside JS template issues.
                    const apiBaseUrl = @json(route('track.api', ['tracking_number' => 'TN_PLACEHOLDER']));
                    const url = apiBaseUrl.replace('TN_PLACEHOLDER', encodeURIComponent(tn));

                    const res = await fetch(url, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });

                    const data = await res.json().catch(() => null);

                    if (requestId !== lastRequestId) return;

                    statusBox.classList.add('hidden');

                    if (!res.ok || !data?.ok) {
                        const msg = data?.message ?? 'Tracking number not found.';
                        resultBox.innerHTML = `
                            <div class="mt-1 rounded-lg border border-rose-200 bg-rose-50 p-6 text-center text-rose-800">
                                <p class="font-semibold">No shipment</p>
                                <p class="mt-2 text-sm">${escapeHtml(msg)}</p>
                                <code class="mt-4 inline-block rounded bg-white px-3 py-1 text-xs font-mono text-rose-900 shadow-sm">${escapeHtml(tn)}</code>
                            </div>
                        `;
                        return;
                    }

                    const order = data.order ?? {};
                    const meta = data.meta ?? {};
                    const events = data.events ?? [];

                    const courierBadge = meta?.badge ?? 'bg-teal-100 text-teal-800 border border-teal-200';
                    const statusLabel = meta?.label ?? 'In Transit';
                    const shipment = meta?.shipment ?? '';

                    const customerLink = @json(route('track.show', ['tracking_number' => 'TN_PLACEHOLDER']));
                    const showUrl = customerLink.replace('TN_PLACEHOLDER', encodeURIComponent(order?.tracking_number ?? tn));

                    resultBox.innerHTML = `
                        <div class="space-y-4">
                            <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs font-semibold uppercase text-slate-500">Tracking Number</p>
                                        <p class="mt-1 font-mono text-2xl font-bold text-slate-900">${escapeHtml(order?.tracking_number ?? tn)}</p>
                                        <p class="mt-2 text-sm text-slate-600">
                                            Courier:
                                            <span class="font-semibold text-slate-900">${escapeHtml(order?.courier_name ?? 'Standard')}</span>
                                        </p>
                                    </div>

                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                                        <p class="text-xs uppercase text-slate-500">Current status</p>
                                        <div class="mt-2 inline-flex items-center gap-2">
                                            <span class="rounded-lg px-3 py-1 text-sm font-bold ${courierBadge}">${escapeHtml(statusLabel)}</span>
                                        </div>
                                        ${shipment ? `<p class="mt-2 text-sm text-slate-600">${escapeHtml(shipment)}</p>` : ``}
                                    </div>
                                </div>

                                <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase text-slate-500">Consignee</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">${escapeHtml(order?.customer_name ?? '—')}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase text-slate-500">Destination</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">${escapeHtml(order?.destination_city ?? '—')}</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-3">
                                        <p class="text-xs font-semibold uppercase text-slate-500">COD</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-900">
                                            ${order?.cod_amount != null ? `Rs. ${escapeHtml(order.cod_amount)}` : '—'}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 text-xs">
                                    <strong>Customer link:</strong>
                                    <a class="break-all text-teal-600 hover:underline" href="${escapeHtml(showUrl)}" target="_blank">${escapeHtml(showUrl)}</a>
                                </div>
                            </div>

                            ${renderTimeline(events)}
                        </div>
                    `;
                } catch (e) {
                    if (requestId !== lastRequestId) return;
                    statusBox.classList.add('hidden');
                    resultBox.innerHTML = `
                        <div class="mt-1 rounded-lg border border-rose-200 bg-rose-50 p-6 text-center text-rose-800">
                            <p class="font-semibold">Something went wrong</p>
                            <p class="mt-2 text-sm">${escapeHtml(e?.message ?? 'Please try again later.')}</p>
                        </div>
                    `;
                }
            }

            function scheduleFetch() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    const tn = input.value;
                    if (!tn || String(tn).trim().length === 0) return;
                    fetchTracking(tn);
                }, 500);
            }

            // Debounced typing (auto-fetch)
            input?.addEventListener('input', scheduleFetch);

            // Manual button
            submitBtn?.addEventListener('click', () => {
                const tn = input.value;
                if (!tn || String(tn).trim().length === 0) return;
                fetchTracking(tn);
            });
        })();
    </script>
</x-app-layout>