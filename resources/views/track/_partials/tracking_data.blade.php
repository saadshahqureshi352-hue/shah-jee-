{{-- Tracking Data Partial - used by both initial render and AJAX polling --}}
<div class="order-card">
    <div class="order-card-header">
        <div>
            <p class="tracking-label">Tracking Number</p>
            <p class="tracking-number">{{ $order->tracking_number }}</p>
        </div>
        @php
            $status = $order->status ?? 'pending';
            $statusLabel = $meta['label'] ?? match($status) {
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
                'returned' => 'Returned',
                'in_transit' => 'In Transit',
                'out_for_delivery' => 'Out for Delivery',
                'dispatched' => 'Rider Picked',
                'lost' => 'Lost',
                default => 'Booked',
            };
            $statusSlug = match($status) {
                'pending' => 'booked',
                'delivered' => 'delivered',
                'cancelled' => 'cancelled',
                'returned' => 'returned',
                'in_transit' => 'in_transit',
                'out_for_delivery' => 'out_for_delivery',
                'dispatched' => 'dispatched',
                'lost' => 'lost',
                default => 'booked',
            };
        @endphp
        <span class="status-badge {{ $statusSlug }}">
            <span class="dot"></span>
            {{ $statusLabel }}
        </span>
    </div>

    <div class="order-details">
        @if(!empty($order->customer_name))
            <div class="detail-item">
                <p class="label">Consignee</p>
                <p class="value">{{ $order->customer_name }}</p>
            </div>
        @endif

        @if(!empty($order->customer_phone))
            <div class="detail-item">
                <p class="label">Phone</p>
                <p class="value">{{ $order->customer_phone }}</p>
            </div>
        @endif

        @if(!empty($order->courier_name))
            <div class="detail-item">
                <p class="label">Courier</p>
                <p class="value">{{ $order->courier_name }}</p>
            </div>
        @endif

        @if(!empty($order->destination_city))
            <div class="detail-item">
                <p class="label">Destination</p>
                <p class="value">{{ $order->destination_city }}</p>
            </div>
        @endif

        @if(!empty($order->cod_amount) && (float)$order->cod_amount > 0)
            <div class="detail-item">
                <p class="label">COD Amount</p>
                <p class="value highlight">Rs. {{ number_format((float) $order->cod_amount) }}</p>
            </div>
        @endif

        @if(!empty($order->product_name))
            <div class="detail-item">
                <p class="label">Product</p>
                <p class="value">{{ $order->product_name }}</p>
            </div>
        @endif

        @if(!empty($order->reference_no))
            <div class="detail-item detail-full">
                <p class="label">Reference No.</p>
                <p class="value">{{ $order->reference_no }}</p>
            </div>
        @endif

        @if(!empty($order->consignee_address))
            <div class="detail-item detail-full">
                <p class="label">Delivery Address</p>
                <p class="value">{{ $order->consignee_address }}</p>
            </div>
        @endif

        @if(!empty($order->special_instructions))
            <div class="detail-item detail-full">
                <p class="label">Special Instructions</p>
                <p class="value">{{ $order->special_instructions }}</p>
            </div>
        @endif
    </div>

    <div class="live-indicator">
        <span class="live-dot"></span>
        <span>Live</span>
        <span class="time-ago">Just now</span>
        @if(!empty($order->updated_at))
            <span class="time-ago">· Last update: {{ \Carbon\Carbon::parse($order->updated_at)->diffForHumans() }}</span>
        @endif
    </div>
</div>

<div class="timeline-card">
    <p class="timeline-title">Shipment Timeline</p>

    @if(count($events) > 0)
        <div class="timeline">
            @foreach($events as $idx => $event)
                @php
                    $stepClass = $event['status'] ?? 'pending';
                @endphp
                <div class="timeline-step {{ $stepClass }}">
                    <div class="step-indicator">
                        <div class="step-dot"></div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-content">
                        <div class="step-title">
                            {{ $event['title'] ?? ('Step '.($idx+1)) }}
                            @if($stepClass === 'current')
                                <span class="now-label">Now</span>
                            @endif
                        </div>
                        @if(!empty($event['detail']))
                            <p class="step-detail">{{ $event['detail'] }}</p>
                        @endif
                        @if(!empty($event['time']))
                            <p class="step-time">{{ $event['time'] }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-slate-500">No timeline events available yet. Tracking updates will appear here.</p>
    @endif
</div>