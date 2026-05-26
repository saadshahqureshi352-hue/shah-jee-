<div class="space-y-6 p-2">
    <!-- Merchant Header Card -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($merchant->name, 0, 2)) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $merchant->name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $merchant->email }}</p>
                    <div class="flex gap-2 mt-2">
                        @if($merchant->is_approved)
                            <span class="inline-flex items-center gap-1 rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-200">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Approved
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-200">
                                Pending Approval
                            </span>
                        @endif
                        @if($merchant->is_vip)
                            <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-900 dark:text-amber-200">
                                ⭐ VIP
                            </span>
                        @endif
                        <span @class([
                            'inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium',
                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $merchant->status === 'active',
                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $merchant->status === 'suspended',
                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => $merchant->status === 'inactive',
                        ])>
                            {{ ucfirst($merchant->status) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900 dark:text-white">PKR {{ number_format($merchant->wallet_balance ?? 0, 0) }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Wallet Balance</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-4 text-center">
            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $merchant->bookings_count ?? $merchant->bookings()->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Orders</div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-4 text-center">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $merchant->bookings()->where('status', 'delivered')->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Delivered</div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-4 text-center">
            <div class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $merchant->bookings()->where('status', 'returned')->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Returned</div>
        </div>
        <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $merchant->bookings()->whereIn('status', ['pending', 'picked_up', 'dispatched', 'in_transit', 'out_for_delivery'])->count() }}</div>
            <div class="text-xs text-gray-500 dark:text-gray-400">Active</div>
        </div>
    </div>

    <!-- Business Details -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Business Details</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-500 dark:text-gray-400">Phone:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->phone ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Company:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->company_name ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">City:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->city ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Pricing Plan:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->pricingPlan?->name ?? 'Default' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">CNIC:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->cnic ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">NTN:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->ntn ?? 'N/A' }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Registered:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->created_at?->format('M d, Y') }}</span>
            </div>
            <div>
                <span class="text-gray-500 dark:text-gray-400">Last Login:</span>
                <span class="block font-medium text-gray-900 dark:text-white">{{ $merchant->last_login_at?->format('M d, Y H:i') ?? 'Never' }}</span>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Recent Orders (Last 5)</h3>
        @if($recentBookings->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-left">
                        <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">Consignment #</th>
                        <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">Customer</th>
                        <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">City</th>
                        <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">COD</th>
                        <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="pb-2 font-medium text-gray-500 dark:text-gray-400">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                    <tr class="border-b border-gray-100 dark:border-gray-700/50">
                        <td class="py-2 font-mono text-xs text-gray-900 dark:text-white">{{ $booking->consignment_no }}</td>
                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $booking->customer_name }}</td>
                        <td class="py-2 text-gray-700 dark:text-gray-300">{{ $booking->destination_city }}</td>
                        <td class="py-2 text-gray-700 dark:text-gray-300">PKR {{ number_format($booking->cod_amount, 0) }}</td>
                        <td class="py-2">
                            <span @class([
                                'inline-flex rounded-full px-2 py-0.5 text-xs font-medium',
                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' => $booking->status === 'delivered',
                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' => $booking->status === 'returned',
                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' => in_array($booking->status, ['pending', 'picked_up']),
                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' => in_array($booking->status, ['dispatched', 'in_transit', 'out_for_delivery']),
                                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' => !in_array($booking->status, ['delivered','returned','pending','picked_up','dispatched','in_transit','out_for_delivery']),
                            ])>
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                        <td class="py-2 text-gray-500 dark:text-gray-400 text-xs">{{ $booking->created_at?->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-sm text-gray-500 dark:text-gray-400 italic">No recent orders found.</p>
        @endif
    </div>
</div>