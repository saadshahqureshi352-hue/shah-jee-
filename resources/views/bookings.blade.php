<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-bold text-slate-800">All Consignments</h2>
    </x-slot>

    <div class="py-6 px-6 space-y-6">
        <form action="/bookings" method="GET" class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="text-xs text-slate-500 block mb-1">Search Booking</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="CN, Name or Phone..." class="w-full border border-slate-300 rounded-xl p-2.5 text-sm focus:border-orange-500 focus:ring-orange-500">
            </div>
            <div>
                <label class="text-xs text-slate-500 block mb-1">Filter by Courier</label>
                <select name="courier" class="w-full border border-slate-300 rounded-xl p-2.5 text-sm focus:border-orange-500">
                    <option value="All Couriers" {{ request('courier') == 'All Couriers' ? 'selected' : '' }}>All Couriers</option>
                    <option value="TCS" {{ request('courier') == 'TCS' ? 'selected' : '' }}>TCS</option>
                    <option value="Leopards" {{ request('courier') == 'Leopards' ? 'selected' : '' }}>Leopards</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-500 block mb-1">Filter by Status</label>
                <select name="status" class="w-full border border-slate-300 rounded-xl p-2.5 text-sm focus:border-orange-500">
                    <option value="All Statuses" {{ request('status') == 'All Statuses' ? 'selected' : '' }}>All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                </select>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2.5 rounded-xl text-sm transition-all">
                🔍 Apply Filters
            </button>
        </form>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-md font-bold text-slate-800">Shipment Logs</h3>
                <button type="button" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-all">
                    📥 Export CSV
                </button>
            </div>

            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-slate-500 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="p-4">CN Number</th>
                        <th class="p-4">Customer</th>
                        <th class="p-4">Destination</th>
                        <th class="p-4">Courier</th>
                        <th class="p-4">COD Amount</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($allBookings as $booking)
                    <tr class="hover:bg-slate-50 transition-all">
                        <td class="p-4 font-mono font-bold text-orange-600">{{ $booking->consignment_no ?? 'N/A' }}</td>
                        <td class="p-4">
                            <div class="font-semibold text-slate-900">{{ $booking->customer_name }}</div>
                            <div class="text-xs text-slate-400">{{ $booking->customer_phone }}</div>
                        </td>
                        <td class="p-4">{{ $booking->destination_city }}</td>
                        <td class="p-4">{{ $booking->courier_name ?? 'N/A' }}</td>
                        <td class="p-4 font-bold text-emerald-600">Rs. {{ number_format($booking->cod_amount) }}</td>
                        <td class="p-4">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold {{ $booking->status == 'delivered' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-slate-400">No shipments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>