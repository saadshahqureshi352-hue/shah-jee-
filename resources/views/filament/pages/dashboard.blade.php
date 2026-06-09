<x-filament-panels::page> <meta name="csrf-token" content="{{ csrf_token() }}"> <form method="GET" class="date-filter-form" style="margin-bottom:12px;display:flex;gap:8px;align-items:center"> <select name="period" onchange="this.form.submit()" style="padding:6px 10px;border-radius:6px;border:1px solid var(--color-border);background:var(--color-background);"> <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Today</option> <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>Yesterday</option> <option value="3days" {{ $period === '3days' ? 'selected' : '' }}>3 Days</option> <option value="week" {{ $period === 'week' ? 'selected' : '' }}>This Week</option> <option value="month" {{ $period === 'month' ? 'selected' : '' }}>This Month</option> <option value="date_to_date" {{ $period === 'date_to_date' ? 'selected' : '' }}>Date to Date</option> </select> </form> 

<!-- TOP CARDS: OPERATIONAL -->
<div class="sc" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:16px">
    <div class="scard">
        <div class="scard-lbl">Booked Today</div>
        <div class="scard-val">{{ $operationalCards['bookedToday'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Dispatched</div>
        <div class="scard-val">{{ $operationalCards['dispatched'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Delivered</div>
        <div class="scard-val pos">{{ $operationalCards['delivered'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">In Progress</div>
        <div class="scard-val">{{ $operationalCards['inProgress'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Issue Orders</div>
        <div class="scard-val neg">{{ $operationalCards['issueOrders'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Ready to Return</div>
        <div class="scard-val">{{ $operationalCards['readyToReturn'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Return Confirmed</div>
        <div class="scard-val">{{ $operationalCards['returnConfirmed'] ?? 0 }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Total Returned</div>
        <div class="scard-val">{{ $operationalCards['totalReturned'] ?? 0 }}</div>
    </div>
</div>

<!-- TOP CARDS: FINANCIAL -->
<div class="sc" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:16px">
    <div class="scard">
        <div class="scard-lbl">Total COD</div>
        <div class="scard-val">Rs {{ number_format($financialCards['totalDeliveredCod'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Gross Profit</div>
        <div class="scard-val pos">Rs {{ number_format($financialCards['grossProfit'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Net Profit</div>
        <div class="scard-val pos">Rs {{ number_format($financialCards['netProfit'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Tax Collected (4%)</div>
        <div class="scard-val">Rs {{ number_format($financialCards['tax4Collected'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Courier Tax (2%)</div>
        <div class="scard-val">Rs {{ number_format($financialCards['courierTax2'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Our 2% Margin</div>
        <div class="scard-val pos">Rs {{ number_format($financialCards['ourTax2Balance'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Merchant Payables</div>
        <div class="scard-val neg">Rs {{ number_format($companyPosition['merchantPayables'] ?? 0) }}</div>
    </div>
    <div class="scard">
        <div class="scard-lbl">Courier Receivable</div>
        <div class="scard-val">Rs {{ number_format($companyPosition['courierReceivables'] ?? 0) }}</div>
    </div>
</div>

<!-- ORDERS SIDEBAR -->
<div class="card">
    <div class="card-hdr">
        <div class="card-hdr-title">Orders</div>
        <div style="display:flex;gap:8px;align-items:center">
            <!-- Status Tabs -->
            <div class="tabs" style="display:flex;gap:4px;flex-wrap:wrap;">
                <button class="btn {{ $activeTab === 'all' ? 'btn-b' : '' }}" onclick="loadOrders('all')">All</button>
                <button class="btn {{ $activeTab === 'booked' ? 'btn-b' : '' }}" onclick="loadOrders('booked')">Booked</button>
                <button class="btn {{ $activeTab === 'in_progress' ? 'btn-b' : '' }}" onclick="loadOrders('in_progress')">In Progress</button>
                <button class="btn {{ $activeTab === 'delivered' ? 'btn-b' : '' }}" onclick="loadOrders('delivered')">Delivered</button>
                <button class="btn {{ $activeTab === 'returned' ? 'btn-b' : '' }}" onclick="loadOrders('returned')">Returned</button>
                <button class="btn {{ $activeTab === 'issue' ? 'btn-b' : '' }}" onclick="loadOrders('issue')">Issue</button>
                <button class="btn {{ $activeTab === 'ready_to_return' ? 'btn-b' : '' }}" onclick="loadOrders('ready_to_return')">Ready to Return</button>
            </div>

            <!-- Search Box -->
            <div style="margin-top:8px;">
                <input type="text" id="orderSearch" placeholder="Search by name, tracking, or phone..." style="padding:6px;border-radius:4px;border:1px solid var(--color-border);background:var(--color-background);width:200px;" />
            </div>

            <!-- Calendar Filter -->
            <div style="margin-top:8px;">
                <input type="date" id="orderDateFrom" style="padding:6px;border-radius:4px;border:1px solid var(--color-border);background:var(--color-background);width:120px;" />
                <input type="date" id="orderDateTo" style="padding:6px;border-radius:4px;border:1px solid var(--color-border);background:var(--color-background);width:120px;" />
                <button type="button" id="applyOrderFilters" class="btn btn-sm btn-primary" style="padding:6px 10px;">Apply</button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div id="orders-table" style="margin-top:16px;">
        @if($orders->isEmpty())
            <p style="color:var(--color-text-secondary);text-align:center;padding:20px;">No orders found.</div>
        @else
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tracking Number</th>
                    <th>Merchant</th>
                    <th>COD Amount</th>
                    <th>Delivery Charges</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->tracking_number ?? '—' }}</td>
                    <td>{{ $order->merchant ?? '—' }}</td>
                    <td>Rs {{ number_format((float) $order->cod_amount, 0) }}</td>
                    <td>Rs {{ number_format((float) $order->delivery_charges, 0) }}</td>
                    <td class="pos"><b>{{ $order->status_label ?? $order->status }}</b></td>
                    <td>
                                        <div class="action-buttons">
                                            <button class="btn btn-sm" onclick="viewOrderDetails({{ $order->id }})">View</button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    async function loadOrders(status) {
        const formData = new FormData();
        formData.append('status', status);
        formData.append('from', document.getElementById('orderDateFrom')?.value || '');
        formData.append('to', document.getElementById('orderDateTo')?.value || '');
        formData.append('search', document.getElementById('orderSearch')?.value || '');
        const response = await fetch('/admin/api/admin/orders/filter', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        const data = await response.json();
        const ordersTable = document.getElementById('orders-table');
        if (data.success) {
            const orders = data.data;
            let tbody = ordersTable.querySelector('tbody');
            if (!tbody) {
                tbody = document.createElement('tbody');
                ordersTable.appendChild(tbody);
            }
            tbody.innerHTML = '';
            orders.forEach(order => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
<tr>
<td>${order.id}</td>
<td>${order.tracking_number || '—'}</td>
<td>${order.merchant}</td>
<td>Rs ${number.format(order.cod_amount, 0)}</td>
<td>Rs ${number.format(order.delivery_charges, 0)}</td>
<td class="pos"><b>${order.status_label || order.status}</b></td>
<td>
<div class="action-buttons">
<button class="btn btn-sm" onclick="viewOrderDetails(${order.id})">View</button>
</div>
</td>
</tr>`;
                tbody.appendChild(tr);
            });
        } else {
            ordersTable.innerHTML = '<tr><td colspan="6" style="text-align:center;color:var(--color-text-secondary);padding:20px;">' + (data.message || 'Failed to load orders') + '</td></tr>';
        }
    });

    // Load orders on page load (default: delivered)
    document.addEventListener('DOMContentLoaded', () => {
        loadOrders('delivered');
    });

    // Apply order filters on button click
    document.getElementById('applyOrderFilters')?.addEventListener('click', function() {
        const status = document.querySelector('input[name="status"]')?.value || 'all';
        const from = document.getElementById('orderDateFrom')?.value || '';
        const to = document.getElementById('orderDateTo')?.value || '';
        const search = document.getElementById('orderSearch')?.value || '';
        loadOrders(status);
    });

    // View order details (placeholder)
    window.viewOrderDetails = function(orderId) {
        alert('Viewing order details for order ID: ' + orderId);
    };
</script>
</x-filament-panels::page>
</parameter> </arg_key> </read_file>
