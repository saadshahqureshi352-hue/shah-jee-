@php
    $walletJson = collect($wallets)->map(fn ($w, $k) => array_merge(['key' => $k], $w))->values();
@endphp

<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6" x-data="nonCodDeposit({
        bank: @json($bank),
        wallets: @json($walletJson),
    })">
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.overall-sales') }}" class="rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Overall Sales</a>
            <a href="{{ route('payments.invoices') }}" class="rounded-lg bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-violet-700">My Invoices</a>
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-orange-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm">
                <span class="text-white">✓</span> Add Payment for Non-COD
            </span>
        </div>

        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl border border-rose-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-rose-600">Total Balance</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">—</p>
            </div>
            <div class="rounded-xl border border-amber-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-amber-700">Not Approved</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">{{ $pendingCount }}</p>
            </div>
            <div class="rounded-xl border border-violet-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-violet-700">Used Balance</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">—</p>
            </div>
            <div class="rounded-xl border border-emerald-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-emerald-700">Remaining</p>
                <p class="mt-1 text-2xl font-bold text-slate-800">—</p>
            </div>
        </div>

        <div class="flex flex-col gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <input type="search" placeholder="Search by amount or payment method…" class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm" disabled>
            <button type="button" @click="open = true" class="rounded-xl bg-orange-500 px-6 py-3 text-sm font-bold text-white shadow-md hover:bg-orange-600">Add Balance</button>
        </div>

        {{-- Modal --}}
        <div x-show="open" x-cloak @keydown.escape.window="open = false" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/60" @click="open = false"></div>
            <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                    <h2 class="text-lg font-bold text-slate-900">Add Payment for Non-COD</h2>
                    <button type="button" @click.stop.prevent="open = false" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">&times;</button>
                </div>
                <form method="POST" action="{{ route('payments.non-cod.store') }}" enctype="multipart/form-data" class="space-y-4 p-4">
                    @csrf
                    <div class="flex rounded-lg bg-slate-100 p-1">
                        <button type="button" @click="tab = 'bank'" :class="tab === 'bank' ? 'bg-orange-500 text-white shadow' : 'text-slate-600'" class="flex-1 rounded-md py-2 text-sm font-semibold">Bank</button>
                        <button type="button" @click="tab = 'other'" :class="tab === 'other' ? 'bg-orange-500 text-white shadow' : 'text-slate-600'" class="flex-1 rounded-md py-2 text-sm font-semibold">Other</button>
                    </div>
                    <p class="rounded-lg bg-emerald-50 px-3 py-2 text-center text-xs font-medium text-emerald-800">Send amount and upload screenshot</p>

                    <input type="hidden" name="channel" :value="tab === 'bank' ? 'bank' : walletKey">

                    <div x-show="tab === 'bank'" x-cloak class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm">
                            <p><span class="text-slate-500">Bank:</span> <span class="font-semibold" x-text="bank.bank_name"></span></p>
                            <p><span class="text-slate-500">Account Holder:</span> <span class="font-semibold" x-text="bank.account_holder"></span></p>
                            <p><span class="text-slate-500">Account No:</span> <span class="font-mono font-semibold" x-text="bank.account_no"></span></p>
                            <p><span class="text-slate-500">IBAN:</span> <span class="font-mono text-xs font-semibold" x-text="bank.iban"></span></p>
                    </div>
                    <div x-show="tab === 'other'" x-cloak class="space-y-3">
                            <p class="text-xs font-semibold uppercase text-slate-500">Select wallet first</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="w in wallets" :key="w.key">
                                    <button type="button" @click="walletKey = w.key"
                                            :class="walletKey === w.key ? 'ring-2 ring-orange-500 bg-orange-50' : 'bg-white'"
                                            class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-bold text-slate-800" x-text="w.label"></button>
                                </template>
                            </div>
                            <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-sm">
                                <p><span class="text-slate-500">Account Holder:</span> <span class="font-semibold" x-text="activeWallet.account_holder"></span></p>
                                <p><span class="text-slate-500">Account No:</span> <span class="font-mono font-semibold" x-text="activeWallet.account_no"></span></p>
                            </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Amount</label>
                        <input type="number" name="amount" step="0.01" min="1" required class="w-full rounded-lg border border-slate-200 px-3 py-2.5 text-sm">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase text-slate-600">Upload Screenshot</label>
                        <input type="file" name="screenshot" accept="image/*" required class="w-full text-sm">
                    </div>
                    <button type="submit" class="w-full rounded-xl bg-orange-500 py-3 text-sm font-bold text-white hover:bg-orange-600">Deposit Now</button>
                </form>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-4 py-2 text-sm font-semibold text-slate-700">Deposit requests</div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-700 text-xs uppercase text-white">
                        <tr>
                            <th class="px-3 py-2">Date</th>
                            <th class="px-3 py-2">Channel</th>
                            <th class="px-3 py-2">Amount</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Proof</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3">{{ $r->created_at ? \Carbon\Carbon::parse($r->created_at)->format('d M Y') : '—' }}</td>
                                <td class="px-3 py-3 font-medium capitalize">{{ str_replace('_', ' ', $r->channel) }}</td>
                                <td class="px-3 py-3 font-bold text-sky-600">Rs. {{ number_format((float) $r->amount) }}</td>
                                <td class="px-3 py-3">
                                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold
                                        {{ $r->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : ($r->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">{{ $r->status }}</span>
                                </td>
                                <td class="px-3 py-3">
                                    @if($r->screenshot_path)
                                        <a href="{{ asset('storage/'.$r->screenshot_path) }}" target="_blank" class="text-xs font-medium text-teal-600 hover:underline">View</a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No deposit requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">
                @if($requests->total() > 0)
                    {{ $requests->links() }}
                @endif
            </div>
        </div>
    </div>

    <script>
        function nonCodDeposit({ bank, wallets }) {
            return {
                open: false,
                tab: 'bank',
                walletKey: wallets[0]?.key || 'jazzcash',
                bank,
                wallets,
                get activeWallet() {
                    return this.wallets.find(w => w.key === this.walletKey) || this.wallets[0] || { account_holder: '', account_no: '' };
                },
            };
        }
    </script>
</x-app-layout>
