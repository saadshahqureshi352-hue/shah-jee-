@php
    $fmt = fn ($n) => 'Rs. '.number_format((float) $n);
@endphp

<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6">
        @if(session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
        @endif

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.overall-sales') }}" class="rounded-lg bg-sky-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-700">Overall Sales</a>
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 px-6 py-2.5 text-sm font-semibold text-white shadow-sm">
                <span class="text-emerald-400">✓</span> My Invoices
            </span>
            <a href="{{ route('payments.non-cod') }}" class="rounded-lg bg-orange-500 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-600">Add Payment for Non-COD</a>
        </div>

        <div class="grid gap-3 sm:grid-cols-3">
            <div class="rounded-xl border-2 border-emerald-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-emerald-700">Paid Invoices</p>
                <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $fmt($paidSum) }}</p>
                <p class="text-xs text-slate-500">Transferred to seller</p>
            </div>
            <div class="rounded-xl border-2 border-rose-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-rose-700">Pending / Unpaid</p>
                <p class="mt-2 text-2xl font-bold text-rose-600">{{ $fmt($unpaidSum) }}</p>
                <p class="text-xs text-slate-500">Awaiting transfer</p>
            </div>
            <div class="rounded-xl border-2 border-sky-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-semibold uppercase text-sky-700">Overall DC + Govt Tax</p>
                <p class="mt-2 text-2xl font-bold text-sky-700">{{ $fmt($overallDeductions) }}</p>
                <p class="text-xs text-slate-500">Across all invoices</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('payments.invoices', ['invoice_status' => 'all']) }}"
               class="rounded-lg px-4 py-2 text-sm font-semibold {{ $filter === 'all' ? 'bg-slate-800 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">All</a>
            <a href="{{ route('payments.invoices', ['invoice_status' => 'paid']) }}"
               class="rounded-lg px-4 py-2 text-sm font-semibold {{ $filter === 'paid' ? 'bg-emerald-600 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">Paid Invoices</a>
            <a href="{{ route('payments.invoices', ['invoice_status' => 'unpaid']) }}"
               class="rounded-lg px-4 py-2 text-sm font-semibold {{ $filter === 'unpaid' ? 'bg-rose-600 text-white' : 'border border-slate-200 bg-white text-slate-700' }}">Unpaid Invoices</a>
        </div>

        @if($canMarkPaid)
            <p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs text-amber-900">
                You can mark invoices as paid (finance admin). Set <code class="rounded bg-white px-1">FINANCE_ADMIN_EMAILS</code> in <code class="rounded bg-white px-1">.env</code> to your login email.
            </p>
        @endif

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-slate-700 text-xs font-semibold uppercase text-white">
                        <tr>
                            <th class="px-3 py-3">Sr</th>
                            <th class="px-3 py-3">Invoice ID</th>
                            <th class="px-3 py-3">Date</th>
                            <th class="px-3 py-3">Total COD</th>
                            <th class="px-3 py-3">DC + Tax</th>
                            <th class="px-3 py-3">Net Transferable</th>
                            <th class="px-3 py-3">Payment Method</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $i => $inv)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3 font-semibold text-slate-800">{{ $invoices->firstItem() + $i }}</td>
                                <td class="px-3 py-3 font-mono font-bold text-sky-600">{{ $inv->invoice_number }}</td>
                                <td class="px-3 py-3 text-slate-600">
                                    {{ \Carbon\Carbon::parse($inv->period_end)->format('d M Y') }}
                                    <p class="text-xs text-slate-400">Week ending</p>
                                </td>
                                <td class="px-3 py-3 font-semibold text-slate-800">{{ $fmt($inv->total_cod) }}</td>
                                <td class="px-3 py-3 text-orange-600">{{ $fmt($inv->total_deductions) }}</td>
                                <td class="px-3 py-3 font-bold text-teal-700">{{ $fmt($inv->net_amount) }}</td>
                                <td class="px-3 py-3 text-slate-700">{{ $inv->payment_method ?? '—' }}</td>
                                <td class="px-3 py-3">
                                    @if($inv->status === 'paid')
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800">Paid</span>
                                    @else
                                        <span class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-800">Unpaid</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    @if($canMarkPaid && $inv->status === 'unpaid')
                                        <form method="POST" action="{{ route('payments.invoices.mark-paid', $inv->id) }}" class="flex flex-col gap-1 sm:flex-row sm:items-center">
                                            @csrf
                                            <select name="payment_method" required class="rounded border border-slate-200 px-2 py-1 text-xs">
                                                <option value="">Method…</option>
                                                <option value="Bank">Bank</option>
                                                <option value="JazzCash">JazzCash</option>
                                                <option value="Easypaisa">Easypaisa</option>
                                                <option value="NayaPay">NayaPay</option>
                                                <option value="UPaisa">UPaisa</option>
                                            </select>
                                            <button type="submit" class="rounded bg-emerald-600 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-700">Mark Paid</button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-slate-500">No invoices yet. Delivered parcels are grouped into weekly invoices automatically.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($invoices->hasPages())
                <div class="border-t border-slate-100 px-4 py-3">{{ $invoices->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
