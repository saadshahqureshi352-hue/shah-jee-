<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Shipping Slips - Shah Jee Courier</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f8fafc;
            color: #1e293b;
        }
        .slip-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid #e2e8f0;
            padding: 24px 28px;
            margin-bottom: 24px;
            page-break-inside: avoid;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            position: relative;
            overflow: hidden;
        }
        .slip-card::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 100px; height: 100px;
            background: linear-gradient(135deg, rgba(13,148,136,0.05), rgba(8,145,178,0.05));
            border-radius: 50%;
            transform: translate(30px, -30px);
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0d9488;
            padding-bottom: 14px;
            margin-bottom: 16px;
            position: relative;
        }
        .brand-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand-row img {
            height: 38px; width: 38px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #0d9488;
        }
        .brand-name {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .tracking-box {
            text-align: right;
            font-family: 'Courier', monospace;
        }
        .tracking-box .num {
            font-size: 16px;
            font-weight: 800;
            color: #0284c7;
        }
        .tracking-box .date {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 20px;
        }
        .field { margin-bottom: 4px; }
        .field .label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
        }
        .field .value {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b;
        }
        .field .value.big {
            font-size: 16px;
            font-weight: 800;
            color: #0d9488;
        }
        .field.full {
            grid-column: 1 / -1;
        }
        .badge {
            display: inline-block;
            font-size: 9px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 100px;
            text-transform: uppercase;
        }
        .badge-status { background: #dbeafe; color: #1d4ed8; }
        .btn-row {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
            position: sticky;
            top: 20px;
            z-index: 10;
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
        .btn-print { background: linear-gradient(135deg, #0d9488, #0891b2); color: #fff; box-shadow: 0 2px 12px rgba(13,148,136,0.3); }
        .btn-print:hover { transform: scale(1.03); }
        .btn-close { background: #fff; color: #475569; border: 1px solid #e2e8f0; }
        .btn-close:hover { background: #f8fafc; }
        @media print {
            body { background: #fff; padding: 0; }
            .btn-row { display: none; }
            .slip-card { box-shadow: none; border: 2px dashed #000; border-radius: 0; margin-bottom: 30px; }
            .slip-card::before { display: none; }
        }
    </style>
</head>
<body>
    <div class="btn-row">
        <button class="btn btn-print" onclick="window.print()">🖨️ Print All Slips</button>
        <button class="btn btn-close" onclick="window.close()">✕ Close</button>
    </div>

    @foreach($bookings as $booking)
        <div class="slip-card">
            <div class="top-bar">
                <div class="brand-row">
                    <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier">
                    <div>
                        <div class="brand-name">SHAH JEE COURIER</div>
                    </div>
                </div>
                <div class="tracking-box">
                    <div class="num">{{ $booking->tracking_number ?? 'SJC'.str_pad($booking->id, 10, '0', STR_PAD_LEFT) }}</div>
                    <div class="date">{{ $booking->created_at ? date('d M Y', strtotime($booking->created_at)) : date('d M Y') }}</div>
                </div>
            </div>

            <div class="grid-2">
                <div class="field">
                    <div class="label">Consignee</div>
                    <div class="value">{{ $booking->customer_name }}</div>
                </div>
                <div class="field">
                    <div class="label">Phone</div>
                    <div class="value">{{ $booking->customer_phone }} @if($booking->second_phone) / {{ $booking->second_phone }} @endif</div>
                </div>
                <div class="field full">
                    <div class="label">Address</div>
                    <div class="value">{{ $booking->consignee_address }}</div>
                </div>
                <div class="field">
                    <div class="label">Destination</div>
                    <div class="value">{{ strtoupper($booking->destination_city) }}</div>
                </div>
                <div class="field">
                    <div class="label">Product</div>
                    <div class="value">{{ $booking->product_name ?? 'N/A' }}</div>
                </div>
                <div class="field">
                    <div class="label">COD Amount</div>
                    <div class="value big">Rs. {{ number_format($booking->cod_amount) }}</div>
                </div>
                <div class="field">
                    <div class="label">Weight / Qty</div>
                    <div class="value">{{ $booking->weight }} KG / {{ $booking->quantity }} Pcs</div>
                </div>
                <div class="field">
                    <div class="label">Status</div>
                    <div class="value"><span class="badge badge-status">{{ strtoupper($booking->status) }}</span></div>
                </div>
            </div>
        </div>
    @endforeach
</body>
</html>