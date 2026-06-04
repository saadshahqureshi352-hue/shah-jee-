<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-6 p-4 sm:p-6">
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('smart-tools.tracking') }}" class="rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white">Universal Tracking</a>
            <a href="{{ route('smart-tools.whatsapp-gateway') }}" class="rounded-lg bg-slate-600 px-4 py-2 text-sm font-semibold text-white">WhatsApp Gateway</a>
            <span class="rounded-lg bg-teal-600 px-4 py-2 text-sm font-semibold text-white">Alert Templates</span>
        </div>

        <div>
            <h1 class="text-xl font-bold text-slate-900">Alert Templates</h1>
            <p class="mt-2 text-sm text-slate-600">
                Placeholders:
                <code class="rounded bg-slate-100 px-1">{customer_name}</code>,
                <code class="rounded bg-slate-100 px-1">{tracking_id}</code>,
                <code class="rounded bg-slate-100 px-1">{cod_amount}</code>,
                <code class="rounded bg-slate-100 px-1">{track_url}</code>,
                <code class="rounded bg-slate-100 px-1">{consignee_phone}</code>,
                <code class="rounded bg-slate-100 px-1">{seller_name}</code>
            </p>
        </div>

        <form method="POST" action="{{ route('smart-tools.alert-templates.save') }}" class="space-y-6">
            @csrf
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <label class="mb-2 block text-sm font-bold text-slate-800">Route A — Shipper (official portal number)</label>
                <textarea name="shipper_alert" rows="5" placeholder="Dear {seller_name}, your order is booked. Ref {tracking_id}."
                          class="w-full rounded-lg border border-slate-200 px-3 py-2.5 font-mono text-sm">{{ old('shipper_alert', $shipper_alert) }}</textarea>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
                <label class="mb-2 block text-sm font-bold text-slate-800">Route B — Consignee (via your linked WhatsApp)</label>
                <textarea name="consignee_alert" rows="6" placeholder="Hi {customer_name}, your shipment total Rs.{cod_amount}. Track: {track_url}"
                          class="w-full rounded-lg border border-slate-200 px-3 py-2.5 font-mono text-sm">{{ old('consignee_alert', $consignee_alert) }}</textarea>
                <p class="mt-2 text-xs text-slate-500">
                    <code>{track_url}</code> resolves to <code>{{ url('/track') }}/{tracking}</code> — customer clicks and sees live tracking page automatically with their tracking number loaded.
                </p>
            </div>
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 shadow-sm">
                <div class="flex items-start gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-200 text-emerald-700 text-sm font-bold">✓</span>
                    <div>
                        <p class="text-sm font-bold text-emerald-800">How tracking links work:</p>
                        <ul class="mt-1 space-y-1 text-xs text-emerald-700">
                            <li>1. When a shipper books a parcel, a WhatsApp alert is sent to the <strong>official portal number</strong>.</li>
                            <li>2. The shipper clicks a <strong>Connect WhatsApp</strong> link and links their number to the portal.</li>
                            <li>3. After connecting, every new booking automatically sends a WhatsApp message to the <strong>consignee (customer)</strong>.</li>
                            <li>4. The message contains a tracking link: <code class="bg-emerald-100 px-1 rounded">{{ url('/track') }}/SJC0000000001</code></li>
                            <li>5. The customer clicks the link — <strong>no login required</strong> — and sees real-time tracking updates.</li>
                            <li>6. The <strong>shipper</strong> can also copy the tracking link from their "My All Orders" page by clicking the three dots menu > <strong>"Copy Tracking Link"</strong>.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="notify_me_consent" value="1" @checked(old('notify_me_consent', $notify_me_consent)) class="rounded border-slate-300 text-teal-600">
                I consent to Notify Me alerts on my registered number (seller).
            </label>
            <button type="submit" class="rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white hover:bg-orange-600">Save templates</button>
        </form>
    </div>
</x-app-layout>
