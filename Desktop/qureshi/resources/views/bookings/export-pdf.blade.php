<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $statusLabel }} - Export</title>
    <style>
        @page { margin: 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier', monospace; }
        body { color: #1e293b; font-size: 10px; }
        h1 { font-size: 16px; margin-bottom: 4px; }
        .sub { color: #64748b; margin-bottom: 15px; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1e293b; color: #fff; padding: 6px 4px; text-align: left; font-size: 9px; text-transform: uppercase; }
        td { padding: 5px 4px; border-bottom: 1px solid #e2e8f0; font-size: 9px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .page-break { page-break-after: always; }
        .footer { position: fixed; bottom: 10mm; text-align: center; font-size: 8px; color: #94a3b8; width: 100%; }
        .badge { display: inline-block; padding: 1px 5px; border-radius: 3px; font-size: 8px; font-weight: bold; }
        .badge-delivered { background: #d1fae5; color: #059669; }
        .badge-pending { background: #f1f5f9; color: #475569; }
        .badge-cancelled { background: #fee2e2; color: #dc2626; }
        .badge-returned { background: #ffedd5; color: #c2410c; }
        .badge-transit { background: #ede9fe; color: #6d28d9; }
        .badge-dispatched { background: #dbeafe; color: #2563eb; }
    </style>
</head>
<body>
    <h1>Shah Jee Courier — {{ $statusLabel }}</h1>
    <p class="sub">Generated: {{ date('d M Y h:i A') }} · Total Orders: {{ count($orders) }}</p>

    @if(count($orders) > 0)
        <table>
            <thead>
                <tr>
                    <th width="30">#</th>
                    <th width="100">Tracking</th>
                    <th width="80">Reference</th>
                    <th>Customer</th>
                    <th width="60">Phone</th>
                    <th width="60">Courier</th>
                    <th width="70">City</th>
                    <th width="60">COD</th>
                    <th width="60">Status</th>
                    <th width="70">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $i => $order)
                    @php
                        $statusClass = match($order->status) {
                            'delivered' => 'badge-delivered',
                            'cancelled' => 'badge-cancelled',
                            'returned' => 'badge-returned',
                            'in_transit' => 'badge-transit',
                            'dispatched' => 'badge-dispatched',
                            default => 'badge-pending',
                        };
                    @endphp
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td style="font-weight: bold;">{{ $order->tracking_number ?? 'N/A' }}</td>
                        <td>{{ $order->reference_no ?? '—' }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->customer_phone ?? '—' }}</td>
                        <td>{{ $order->courier_name ?? 'Standard' }}</td>
                        <td>{{ $order->destination_city ?? '' }}</td>
                        <td class="text-right">Rs. {{ number_format((float)($order->cod_amount ?? 0)) }}</td>
                        <td><span class="badge {{ $statusClass }}">{{ ucfirst(str_replace('_', ' ', $order->status ?? 'pending')) }}</span></td>
                        <td>{{ $order->created_at ? date('d M Y', strtotime($order->created_at)) : '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Shah Jee Courier · {{ $statusLabel }} · Page {PAGE_NUM} of {PAGE_COUNT}
        </div>
    @else
        <p style="text-align: center; color: #94a3b8; margin-top: 40px;">No orders found for selected status.</p>
    @endif>

    <script>
        window.print();
    </script>
</body>
</html>