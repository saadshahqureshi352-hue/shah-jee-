<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Slip — {{ $order->tracking_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 24px;
            color: #1e293b;
            background: #f8fafc;
        }
        .slip-wrapper {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }
        .top-bar {
            background: linear-gradient(135deg, #0f172a, #1e3a5f);
            padding: 20px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #fff;
        }
        .brand-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-row img {
            height: 42px;
            width: 42px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.3);
        }
        .brand-name {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .brand-sub {
            font-size: 10px;
            color: rgba(255,255,255,0.65);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .tracking-tag {
            text-align: right;
        }
        .tracking-tag .label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.6);
        }
        .tracking-tag .number {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.04em;
            font-family: 'Courier', monospace;
        }
        .content {
            padding: 24px 28px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 24px;
        }
        .grid-full {
            grid-column: 1 / -1;
        }
        .field {}
        .field .label {
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 2px;
        }
        .field .value {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
        .field .value.big {
            font-size: 20px;
            font-weight: 800;
            color: #0d9488;
        }
        .field .value.code {
            font-family: 'Courier', monospace;
            color: #0284c7;
        }
        .badge {
            display: inline-block;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 10px;
            border-radius: 100px;
            text-transform: uppercase;
        }
        .badge-teal { background: #ccfbf1; color: #0f766e; }
        .divider {
            border-top: 1px solid #e2e8f0;
            margin: 20px 0;
        }
        .footer-bar {
            background: #f8fafc;
            padding: 16px 28px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
            font-size: 11px;
            color: #94a3b8;
        }
        .btn-row {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-print { background: linear-gradient(135deg, #f97316, #ef4444); color: #fff; }
        .btn-print:hover { transform: scale(1.03); box-shadow: 0 4px 16px rgba(249,115,22,0.3); }
        .btn-close { background: #fff; color: #475569; border: 1px solid #e2e8f0; }
        .btn-close:hover { background: #f8fafc; }
        @media print {
            body { background: #fff; padding: 12px; }
            .btn-row { display: none; }
            .slip-wrapper { box-shadow: none; border: 2px dashed #000; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="btn-row">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print Slip</button>
        <button class="btn btn-close" onclick="window.close()">✕ Close</button>
    </div>

    <div class="slip-wrapper">
        <div class="top-bar">
            <div class="brand-row">
                <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier">
                <div>
                    <div class="brand-name">Shah Jee Courier</div>
                    <div class="brand-sub">Airway Bill / Shipping Label</div>
                </div>
            </div>
            <div class="tracking-tag">
                <div class="label">Tracking #</div>
                <div class="number">{{ $order->tracking_number }}</div>
            </div>
        </div>

        <div class="content">
            <div class="grid-2">
                <div class="field">
                    <div class="label">Courier</div>
                    <div class="value">{{ $order->courier_name ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="label">Service</div>
                    <div class="value"><span class="badge badge-teal">{{ $order->service_type ?? 'Regular' }}</span></div>
                </div>
                <div class="field">
                    <div class="label">Consignee</div>
                    <div class="value">{{ $order->customer_name }}</div>
                </div>
                <div class="field">
                    <div class="label">Phone</div>
                    <div class="value">{{ $order->customer_phone }}</div>
                </div>
                <div class="field">
                    <div class="label">Destination</div>
                    <div class="value">{{ $order->destination_city }}</div>
                </div>
                <div class="field">
                    <div class="label">COD Amount</div>
                    <div class="value big">Rs. {{ number_format((float) $order->cod_amount) }}</div>
                </div>
                <div class="field grid-full">
                    <div class="label">Delivery Address</div>
                    <div class="value">{{ $order->consignee_address }}</div>
                </div>
                <div class="field">
                    <div class="label">Weight / Qty</div>
                    <div class="value">{{ $order->weight }} kg × {{ $order->quantity ?? 1 }} pcs</div>
                </div>
                <div class="field">
                    <div class="label">Delivery Charges</div>
                    <div class="value">Rs. {{ number_format((float) ($order->delivery_charges ?? 0)) }}</div>
                </div>
                @if($order->product_name)
                <div class="field">
                    <div class="label">Product</div>
                    <div class="value">{{ $order->product_name }}</div>
                </div>
                @endif
                @if($order->reference_no)
                <div class="field">
                    <div class="label">Reference</div>
                    <div class="value code">{{ $order->reference_no }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="footer-bar">
            Thank you for choosing <strong>Shah Jee Courier</strong> · {{ now()->format('d M Y, h:i A') }}
        </div>
    </div>
</body>
</html>