<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-2xl font-bold text-slate-900">Admin - Shipper Approvals</h1>
            <a href="{{ route('admin.shippers.index', ['status' => 'pending']) }}" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white hover:bg-amber-600">Pending Only</a>
        </div>

        <form method="GET" class="flex flex-col gap-2 rounded-xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row">
            <input type="search" name="search" value="{{ $search }}" placeholder="Search name/email/username/phone/city" class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-sm">
            <select name="status" class="rounded-lg border border-slate-200 px-3 py-2 text-sm">
                <option value="all" @selected($status==='all')>All</option>
                <option value="pending" @selected($status==='pending')>Pending</option>
                <option value="approved" @selected($status==='approved')>Approved</option>
            </select>
            <button class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-semibold text-white">Filter</button>
        </form>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-slate-700 text-xs uppercase text-white">
                        <tr>
                            <th class="px-3 py-3">Name</th>
                            <th class="px-3 py-3">Username</th>
                            <th class="px-3 py-3">Email</th>
                            <th class="px-3 py-3">Phone</th>
                            <th class="px-3 py-3">City</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Created</th>
                            <th class="px-3 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shippers as $shipper)
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-3 font-semibold text-slate-800">{{ $shipper->name }}</td>
                                <td class="px-3 py-3 font-mono text-xs text-slate-700">{{ $shipper->username ?: '—' }}</td>
                                <td class="px-3 py-3 text-slate-700">{{ $shipper->email }}</td>
                                <td class="px-3 py-3 text-slate-700">{{ $shipper->phone ?: '—' }}</td>
                                <td class="px-3 py-3 text-slate-700">{{ $shipper->city ?: '—' }}</td>
                                <td class="px-3 py-3">
                                    @if($shipper->is_approved)
                                        <span class="rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-700">Approved</span>
                                    @else
                                        <span class="rounded-full bg-rose-100 px-2 py-1 text-xs font-semibold text-rose-700">Pending</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-slate-500">{{ $shipper->created_at?->format('d M Y') }}</td>
                                <td class="px-3 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('admin.shippers.edit', $shipper) }}" class="rounded bg-slate-200 px-2 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-300">Edit Data</a>
                                        @if(!$shipper->is_approved)
                                            <form method="POST" action="{{ route('admin.shippers.approve', $shipper) }}">@csrf
                                                <button class="rounded bg-emerald-600 px-2 py-1 text-xs font-semibold text-white hover:bg-emerald-700">Approve</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.shippers.reject', $shipper) }}">@csrf
                                                <button class="rounded bg-rose-600 px-2 py-1 text-xs font-semibold text-white hover:bg-rose-700">Set Pending</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-10 text-center text-slate-500">No shippers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3">{{ $shippers->links() }}</div>
        </div>
    </div>
</x-app-layout>