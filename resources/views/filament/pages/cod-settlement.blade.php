<div x-data="codSettlementController()" x-init="init()" class="space-y-8 p-1 text-gray-800">
    
    <div class="flex items-center justify-between border-b border-gray-200 pb-4">
        <h1 class="text-xl font-bold tracking-tight text-gray-900">COD & Settlement</h1>
        <div class="flex items-center gap-6 text-sm font-medium text-gray-500">
            <button @click="setPeriod('today')" :class="filters.period === 'today' ? 'text-purple-600 font-bold border-b-2 border-purple-600 pb-1' : 'hover:text-gray-700'">Today</button>
            <button @click="setPeriod('yesterday')" :class="filters.period === 'yesterday' ? 'text-purple-600 font-bold border-b-2 border-purple-600 pb-1' : 'hover:text-gray-700'">Yesterday</button>
            <button @click="setPeriod('3_days')" :class="filters.period === '3_days' ? 'text-purple-600 font-bold border-b-2 border-purple-600 pb-1' : 'hover:text-gray-700'">3 Days</button>
            <button @click="setPeriod('this_week')" :class="filters.period === 'this_week' ? 'text-purple-600 font-bold border-b-2 border-purple-600 pb-1' : 'hover:text-gray-700'">This Week</button>
            <button @click="setPeriod('this_month')" :class="filters.period === 'this_month' ? 'text-purple-600 font-bold border-b-2 border-purple-600 pb-1' : 'hover:text-gray-700'">This Month</button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 py-2">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total COD to pay</p>
            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="'Rs ' + Number(summary.total_cod_to_pay || 0).toLocaleString()"></p>
            <span class="text-xs text-gray-400 block mt-0.5">To merchants</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Courier receivable</p>
            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="'Rs ' + Number(summary.courier_receivable || 0).toLocaleString()"></p>
            <span class="text-xs text-gray-400 block mt-0.5">COD from couriers</span>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Pending settlements</p>
            <p class="text-3xl font-bold text-gray-900 mt-1" x-text="summary.pending_settlements_count || 0"></p>
            <span class="text-xs text-gray-400 block mt-0.5">Merchants</span>
        </div>
    </div>

    <div class="space-y-4 pt-4">
        <div>
            <h2 class="text-md font-bold text-gray-900 tracking-wide">Merchant COD settlement</h2>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-tight mt-1">Per merchant — COD payable</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700 border-collapse">
                <thead class="text-xs text-gray-400 font-semibold uppercase border-b border-gray-100">
                    <tr>
                        <th class="py-3 pr-4 font-bold text-gray-900">Merchant</th>
                        <th class="py-3 px-4">Delivered orders</th>
                        <th class="py-3 px-4">Total COD</th>
                        <th class="py-3 px-4">Delivery charges</th>
                        <th class="py-3 px-4">4% Tax on COD</th>
                        <th class="py-3 px-4">Net payable to merchant</th>
                        <th class="py-3 px-4">Courier paid us</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 pl-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    <template x-for="merchant in merchants" :key="merchant.id">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 pr-4 font-bold text-gray-900" x-text="merchant.name"></td>
                            <td class="py-3 px-4" x-text="merchant.delivered_orders"></td>
                            <td class="py-3 px-4 font-semibold" x-text="'Rs ' + Number(merchant.total_cod).toLocaleString()"></td>
                            <td class="py-3 px-4" x-text="'Rs ' + Number(merchant.delivery_charges).toLocaleString()"></td>
                            <td class="py-3 px-4 text-gray-500" x-text="'Rs ' + Number(merchant.tax_held).toLocaleString()"></td>
                            <td class="py-3 px-4 font-bold text-gray-900" x-text="'Rs ' + Number(merchant.net_payable).toLocaleString()"></td>
                            <td class="py-3 px-4 text-gray-600" x-text="'Rs ' + Number(merchant.courier_paid).toLocaleString()"></td>
                            <td class="py-3 px-4">
                                <span :class="merchant.status === 'Paid' ? 'text-emerald-600' : 'text-amber-600'" class="text-xs font-bold" x-text="merchant.status"></span>
                            </td>
                            <td class="py-3 pl-4 text-right">
                                <button @click="triggerMerchantAction(merchant)" 
                                        :class="merchant.status === 'Paid' ? 'text-gray-400 hover:text-gray-600' : 'text-purple-600 hover:text-purple-800 font-bold'"
                                        class="text-xs tracking-wide uppercase" x-text="merchant.status === 'Paid' ? 'Receipt' : 'Pay now'"></button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4 pt-6">
        <h2 class="text-md font-bold text-gray-900 tracking-wide">Courier COD received</h2>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700 border-collapse">
                <thead class="text-xs text-gray-400 font-semibold uppercase border-b border-gray-100">
                    <tr>
                        <th class="py-3 pr-4 font-bold text-gray-900">Courier</th>
                        <th class="py-3 px-4">Delivered orders</th>
                        <th class="py-3 px-4">Total COD collected</th>
                        <th class="py-3 px-4">Courier charges deducted</th>
                        <th class="py-3 px-4">2% Tax deducted</th>
                        <th class="py-3 px-4">Amount remitted to us</th>
                        <th class="py-3 pl-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 font-medium">
                    <template x-for="courier in couriers" :key="courier.id">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 pr-4 font-bold text-gray-900" x-text="courier.name"></td>
                            <td class="py-3 px-4" x-text="courier.delivered_orders"></td>
                            <td class="py-3 px-4 font-semibold" x-text="'Rs ' + Number(courier.total_cod_collected).toLocaleString()"></td>
                            <td class="py-3 px-4 text-rose-600" x-text="'- Rs ' + Number(courier.charges_deducted).toLocaleString()"></td>
                            <td class="py-3 px-4 text-gray-400" x-text="'- Rs ' + Number(courier.tax_deducted).toLocaleString()"></td>
                            <td class="py-3 px-4 font-bold text-emerald-600" x-text="'Rs ' + Number(courier.amount_remitted).toLocaleString()"></td>
                            <td class="py-3 pl-4 text-right">
                                <span :class="courier.status === 'Received' ? 'text-gray-500 bg-gray-100 px-2 py-0.5 rounded text-xs' : 'text-purple-600 font-bold text-xs'" x-text="courier.status"></span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</div>