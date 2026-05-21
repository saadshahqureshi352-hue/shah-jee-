<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shipping Slip — {{ $order->tracking_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; margin: 24px; color: #1e293b; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #0d9488; padding-bottom: 12px; margin-bottom: 20px; }
        .brand { font-size: 20px; font-weight: bold; color: #0d9488; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; font-size: 13px; }
        th { background: #f1f5f9; width: 35%; }
        .tracking { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        @media print {
            body { margin: 12px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <p class="no-print" style="margin-bottom:16px;">
        <button type="button" onclick="window.print()" style="padding:8px 16px;background:#f97316;color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:bold;">Print</button>
        <button type="button" onclick="window.close()" style="padding:8px 16px;margin-left:8px;border:1px solid #cbd5e1;border-radius:6px;cursor:pointer;">Close</button>
    </p>

    <div class="header">
        <div>
            <div class="brand">Shah Jee Courier</div>
            <p style="margin:4px 0 0;font-size:12px;color:#64748b;">Airway Bill / Shipping Label</p>
        </div>
        <div style="text-align:right;">
            <p class="tracking">{{ $order->tracking_number }}</p>
            <p style="font-size:12px;color:#64748b;">{{ now()->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    <table>
        <tr><th>Courier</th><td>{{ $order->courier_name ?? '—' }}</td></tr>
        <tr><th>Service</th><td>{{ $order->service_type ?? '—' }}</td></tr>
        <tr><th>Consignee</th><td>{{ $order->customer_name }}</td></tr>
        <tr><th>Phone</th><td>{{ $order->customer_phone }}</td></tr>
        <tr><th>Destination</th><td>{{ $order->destination_city }}</td></tr>
        <tr><th>Address</th><td>{{ $order->consignee_address }}</td></tr>
        <tr><th>Weight</th><td>{{ $order->weight }} kg × {{ $order->quantity ?? 1 }}</td></tr>
        <tr><th>COD</th><td>Rs. {{ number_format((float) $order->cod_amount) }}</td></tr>
        <tr><th>Delivery Charges</th><td>Rs. {{ number_format((float) ($order->delivery_charges ?? 0)) }}</td></tr>
        <tr><th>Product</th><td>{{ $order->product_name ?? '—' }}</td></tr>
        <tr><th>Reference</th><td>{{ $order->reference_no ?? '—' }}</td></tr>
        <tr><th>Instructions</th><td>{{ $order->special_instructions ?? '—' }}</td></tr>
    </table>
</body>
</html>
