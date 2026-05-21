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
                <p class="mt-2 text-xs text-slate-500"><code>{track_url}</code> resolves to {{ url('/track') }}/{{ '{tracking}' }}</p>
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="notify_me_consent" value="1" @checked(old('notify_me_consent', $notify_me_consent)) class="rounded border-slate-300 text-teal-600">
                I consent to Notify Me alerts on my registered number (seller).
            </label>
            <button type="submit" class="rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white hover:bg-orange-600">Save templates</button>
        </form>
    </div>
</x-app-layout>
