<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Shipping Slips - Shah Jee Courier</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background-color: #fff;
        }
        .slip-card { 
            border: 2px dashed #000; 
            padding: 20px; 
            margin-bottom: 30px; 
            page-break-inside: avoid; 
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            border-bottom: 2px solid #000; 
            padding-bottom: 10px; 
        }
        .header h2 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
        }
        .tracking-box { 
            text-align: right; 
            font-weight: bold; 
            font-size: 14px;
            line-height: 1.5;
        }
        .details { 
            margin-top: 15px; 
            font-size: 15px; 
            line-height: 1.6;
        }
        .details p {
            margin: 6px 0;
        }
        .cod-text {
            font-size: 18px;
            font-weight: bold;
        }
        /* Print styling to automatically handle margins */
        @media print {
            body { padding: 0; }
            .slip-card { margin-bottom: 40px; border: 2px dashed #000; }
        }
    </style>
</head>
<body onload="window.print()">

    @foreach($bookings as $booking)
        <div class="slip-card">
            <div class="header">
                <h2>SHAH JEE COURIER</h2>
                <div class="tracking-box">
                    <div>TRACKING #: {{ $booking->tracking_number ?? 'SJC'.str_pad($booking->id, 10, '0', STR_PAD_LEFT) }}</div>
                    <div>DATE: {{ $booking->created_at ? date('d M Y', strtotime($booking->created_at)) : date('d M Y') }}</div>
                </div>
            </div>
            
            <div class="details">
                <p><strong>Shipper:</strong> Shah Jee Courier Aggregator</p>
                <p><strong>Product:</strong> {{ $booking->product_name ?? 'N/A' }}</p>
                <p><strong>Consignee Name:</strong> {{ $booking->customer_name }}</p>
                <p><strong>Phone:</strong> {{ $booking->customer_phone }} @if($booking->second_phone) / {{ $booking->second_phone }} @endif</p>
                <p><strong>Address:</strong> {{ $booking->consignee_address }}</p>
                <p><strong>Destination:</strong> {{ strtoupper($booking->destination_city) }}</p>
                <p class="cod-text"><strong>COD Amount:</strong> Rs. {{ number_format($booking->cod_amount) }} /-</p>
                <p><strong>Weight / Qty:</strong> {{ $booking->weight }} KG / {{ $booking->quantity }} Pcs</p>
                <p><strong>Status:</strong> {{ strtoupper($booking->status) }}</p>
            </div>
        </div>
    @endforeach

</body>
</html>