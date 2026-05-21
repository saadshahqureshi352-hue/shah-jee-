<x-app-layout>
    @php
        $connected = $profiles->where('connected', true)->count();
        $active = $connected > 0;
        $trackingBase = url('/track');
        $gatewayInit = [
            'openCreate' => false,
            'openQr' => (bool) session('new_pair_stub'),
            'pairText' => (string) session('new_pair_stub', ''),
        ];
    @endphp

    <div class="space-y-4 p-4 sm:p-6" x-data='whGateway(@json($gatewayInit))'>
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif

        @if(!$active)
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-rose-900">
                <p class="text-sm font-medium">Consignee Alert Feature Not Activated!</p>
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('smart-tools.profiles.activate') }}"> @csrf
                        <button type="submit" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-bold text-white hover:bg-rose-700">+ Activate</button>
                    </form>
                    <small class="self-center text-xs text-rose-700">Creates demo link step — connect a profile first.</small>
                </div>
            </div>
        @else
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-900">
                <p class="text-sm font-medium">Notify Me / Consignee Alert active — Connected profile(s): {{ $connected }}</p>
                <form method="POST" action="{{ route('smart-tools.profiles.disconnect') }}" onsubmit="return confirm('Disconnect all linked profiles?');"> @csrf
                    <button type="submit" class="rounded-lg border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Activated ✓ · Disconnect</button>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-3 gap-3">
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm text-center">
                <p class="text-xs uppercase text-slate-500">Total Msgs</p>
                <p class="mt-2 text-2xl font-bold">—</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-center">
                <p class="text-xs uppercase text-emerald-700">Delivered</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700">—</p>
            </div>
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-center">
                <p class="text-xs uppercase text-rose-700">Failed</p>
                <p class="mt-2 text-2xl font-bold text-rose-600">0</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <div>
                <p class="text-sm font-bold text-slate-800">Connected Profiles</p>
                <p class="text-xs text-slate-500"><span class="font-semibold text-rose-500">{{ $connected }}</span> / {{ $profiles->count() }} connected</p>
            </div>
            <div class="flex gap-2">
                <button type="button" @click="openCreate = true" class="rounded-lg bg-rose-600 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-700">+ Add Profile</button>
                <a href="{{ route('smart-tools.alert-templates') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Settings</a>
            </div>
        </div>

        @if($profiles->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 bg-white py-14 text-center text-slate-500">
                <p class="text-4xl mb-2">:(</p>
                <p>No profiles yet. Click <strong>Add Profile</strong> to connect WhatsApp (demo QR flow).</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-700 text-xs uppercase text-white">
                        <tr>
                            <th class="px-3 py-2">Name</th>
                            <th class="px-3 py-2">Stub</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Linked</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($profiles as $p)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3 font-semibold">{{ $p->name }}</td>
                                <td class="px-3 py-3 font-mono text-xs">{{ \Illuminate\Support\Str::limit($p->pair_stub, 28) }}</td>
                                <td class="px-3 py-3">
                                    @if($p->connected)
                                        <span class="text-emerald-600 font-semibold">✓ Connected</span>
                                    @else
                                        <span class="text-amber-600">Pending scan</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-slate-500">{{ $p->linked_at ? \Carbon\Carbon::parse($p->linked_at)->format('d M Y H:i') : '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-600">
            <p class="font-semibold text-slate-800">Dual-route messaging (when APIs are configured)</p>
            <ul class="mt-2 list-inside list-disc space-y-1 text-xs">
                <li><strong>Route A — Shah Jee official:</strong> on new booking, confirmation to shipper.</li>
                <li><strong>Route B — Your linked WhatsApp:</strong> consignee message with name, COD, short link → <code class="rounded bg-slate-100 px-1">{{ $trackingBase }}/{"{tracking}"}</code>.</li>
            </ul>
        </div>

        {{-- Modal: Create Profile --}}
        <div x-show="openCreate" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60" @click="openCreate = false"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                <div class="bg-slate-900 px-4 py-3 text-white">
                    <h2 class="font-bold">Create New Profile</h2>
                    <p class="text-xs text-slate-400">Add a new WhatsApp connection</p>
                </div>
                <form method="POST" action="{{ route('smart-tools.profiles.store') }}" class="p-4 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-semibold text-slate-700">Profile Name</label>
                        <input type="text" name="name" required placeholder="Enter profile name…" class="w-full rounded-lg border border-teal-500 px-3 py-2.5 text-sm focus:ring-teal-500">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openCreate = false" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700">Cancel</button>
                        <button type="submit" class="rounded-lg bg-sky-500 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-600">Create</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: QR (Picture 4) --}}
        <div x-show="openQr" x-cloak class="fixed inset-0 z-[200] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60" @click="openQr = false"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                <div class="bg-slate-900 px-4 py-3 text-white">
                    <h2 class="font-bold">WhatsApp Connection</h2>
                    <p class="text-xs text-slate-400">Scan QR to link your account</p>
                </div>
                <div class="p-6 text-center">
                    <p class="text-sm text-slate-600">Scan this QR code with your phone</p>
                    <div class="mx-auto mt-4 flex h-52 w-52 items-center justify-center border-2 border-slate-200 bg-white p-2">
                        <img :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(pairText || 'shah-jee-whatsapp-link')" alt="QR" class="h-48 w-48">
                    </div>
                    <p class="mt-4 text-xs text-slate-500">Open WhatsApp → Linked devices → Link a device</p>
                    <p class="mt-2 font-mono text-[10px] text-slate-400 break-all" x-text="pairText"></p>
                    <button type="button" @click="openQr = false" class="mt-6 w-full rounded-lg border border-slate-200 py-2.5 text-sm font-semibold text-slate-700">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function whGateway(initial) {
            return {
                openCreate: initial.openCreate,
                openQr: initial.openQr,
                pairText: initial.pairText || 'SHAHJEE-WHATSAPP-DEMO',
            };
        }
    </script>
</x-app-layout>
