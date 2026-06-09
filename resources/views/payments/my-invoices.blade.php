@php
    $fmt = fn ($n) => 'Rs. '.number_format((float) $n, 2);

    $badge = function (string $status): array {
        return match ($status) {
            'paid' => ['label' => 'Paid', 'classes' => 'bg-emerald-100 text-emerald-800'],
            'pending' => ['label' => 'Pending', 'classes' => 'bg-orange-100 text-orange-800'],
            default => ['label' => 'Unpaid', 'classes' => 'bg-rose-100 text-rose-800'],
        };
    };
@endphp

<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6">
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.overall-sales') }}" class="rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">
                Overall Sales
            </a>
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-6 py-2.5 text-sm font-semibold text-white shadow-sm">
                <span class="text-emerald-400">✓</span> My Invoices
            </span>
            <a href="{{ route('payments.non-cod') }}" class="rounded-lg bg-orange-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600">
                Add Payment for Non-COD
            </a>
        </div>

        {{-- TOP STATS CARDS --}}
        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border-2 border-emerald-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-black uppercase text-emerald-700 tracking-wide">PAID INVOICES</p>
                <p class="mt-2 text-2xl font-black text-emerald-700 tabular-nums">{{ $fmt($paidSum) }}</p>
                <p class="text-xs text-slate-500">Transferred to seller</p>
            </div>

            <div class="rounded-xl border-2 border-rose-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-black uppercase text-rose-700 tracking-wide">PENDING / UNPAID</p>
                <p class="mt-2 text-2xl font-black text-rose-600 tabular-nums">{{ $fmt($unpaidSum) }}</p>
                <p class="text-xs text-slate-500">Awaiting transfer</p>
            </div>

            <div class="rounded-xl border-2 border-sky-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-black uppercase text-sky-700 tracking-wide">OVERALL DC + GOVT TAX</p>
                <p class="mt-2 text-2xl font-black text-sky-700 tabular-nums">{{ $fmt($overallDeductions) }}</p>
                <p class="text-xs text-slate-500">Across all invoices</p>
            </div>
        </div>

        {{-- FILTER TABS (page-refresh) --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.my-invoices') }}"
               class="rounded-lg px-4 py-2 text-sm font-black tabular-nums {{ $filter === 'all' ? 'bg-slate-800 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">
                [All]
            </a>

            <a href="{{ route('payments.my-invoices', ['invoice_status' => 'paid']) }}"
               class="rounded-lg px-4 py-2 text-sm font-black tabular-nums {{ $filter === 'paid' ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">
                [Paid Invoices]
            </a>

            <a href="{{ route('payments.my-invoices', ['invoice_status' => 'unpaid']) }}"
               class="rounded-lg px-4 py-2 text-sm font-black tabular-nums {{ $filter === 'unpaid' ? 'bg-rose-600 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">
                [Unpaid Invoices]
            </a>
        </div>

        @if($canMarkPaid)
            <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-900">
                You can mark invoices as paid (finance admin).
            </p>
        @endif

        {{-- TABLE --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[1100px] text-left text-sm">
                    <thead class="bg-slate-700 text-xs font-black uppercase text-white">
                        <tr>
                            <th class="px-3 py-3">SR</th>
                            <th class="px-3 py-3">INVOICE ID</th>
                            <th class="px-3 py-3">DATE</th>
                            <th class="px-3 py-3">TOTAL COD</th>
                            <th class="px-3 py-3">DC + TAX</th>
                            <th class="px-3 py-3">NET TRANSFERABLE</th>
                            <th class="px-3 py-3">PAYMENT METHOD</th>
                            <th class="px-3 py-3">STATUS</th>
                            <th class="px-3 py-3 text-right">ACTIONS</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($invoices as $inv)
                        @php
                            $b = $badge((string)$inv->status);
                            $date = optional($inv->period_end)->format('d M Y');
                        @endphp

                        <tr class="border-b border-slate-100">
                            <td class="px-3 py-3 font-black text-slate-800 tabular-nums">{{ $loop->iteration }}</td>

                            <td class="px-3 py-3 font-black text-sky-600 tabular-nums">
                                <span>#{{ ltrim((string)$inv->invoice_number, '#') }}</span>
                            </td>

                            <td class="px-3 py-3 font-black text-slate-700 tabular-nums">{{ $date ?? '—' }}</td>

                            <td class="px-3 py-3 font-black text-slate-800 tabular-nums">{{ $fmt($inv->total_cod) }}</td>

                            <td class="px-3 py-3 font-black text-orange-600 tabular-nums">{{ $fmt($inv->total_deductions) }}</td>

                            <td class="px-3 py-3 font-black text-teal-700 tabular-nums">{{ $fmt($inv->net_amount) }}</td>

                            <td class="px-3 py-3 font-black text-slate-700 tabular-nums">{{ $inv->payment_method ?? '—' }}</td>

                            <td class="px-3 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-black tabular-nums {{ $b['classes'] }}">
                                    {{ $b['label'] }}
                                </span>
                            </td>

                            <td class="px-3 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="#" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-black text-slate-700">
                                        View
                                    </a>

                                    <a href="{{ route('payments.invoices.download-pdf', $inv->id) }}" class="rounded-lg bg-slate-800 px-3 py-1.5 text-xs font-black text-white">
                                        Download PDF
                                    </a>

                                    <button onclick="window.print()" class="rounded-lg bg-white border border-slate-200 px-3 py-1.5 text-xs font-black text-slate-700">
                                        Print
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-slate-500">
                                No invoices yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

