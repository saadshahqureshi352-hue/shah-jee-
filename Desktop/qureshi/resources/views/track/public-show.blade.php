<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Parcel — {{ config('app.name', 'Shah Jee Courier') }}</title>
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            color: #1e293b;
        }

        /* ===== HEADER ===== */
        .track-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0d9488 100%);
            padding: 2rem 1rem 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .track-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 50%, rgba(13,148,136,0.15) 0%, transparent 60%),
                        radial-gradient(circle at 70% 20%, rgba(56,189,248,0.1) 0%, transparent 50%);
            animation: float 8s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(20px, -20px) rotate(1deg); }
            66% { transform: translate(-10px, 10px) rotate(-1deg); }
        }
        .track-header-content { position: relative; z-index: 1; }
        .track-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .track-header p {
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        .track-header .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }
        .track-header .brand-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }
        .track-header .brand-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #fff;
        }

        /* ===== SEARCH BAR ===== */
        .search-section {
            max-width: 600px;
            margin: -1.5rem auto 0;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }
        .search-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            padding: 0.5rem;
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .search-box input {
            flex: 1;
            border: none;
            outline: none;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            background: transparent;
            color: #1e293b;
        }
        .search-box input::placeholder { color: #94a3b8; }
        .search-box button {
            background: linear-gradient(135deg, #0d9488, #0891b2);
            color: #fff;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }
        .search-box button:hover { transform: scale(1.02); box-shadow: 0 4px 16px rgba(13,148,136,0.4); }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            max-width: 720px;
            margin: 2rem auto;
            padding: 0 1rem 3rem;
        }

        /* ===== ORDER CARD ===== */
        .order-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 24px rgba(0,0,0,0.04);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .order-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .order-card-header .tracking-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #64748b;
        }
        .order-card-header .tracking-number {
            font-family: 'Inter', monospace;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.01em;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .status-badge .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.3); }
        }
        .status-badge.booked { background: #f1f5f9; color: #475569; }
        .status-badge.booked .dot { background: #94a3b8; }
        .status-badge.in_transit { background: #ede9fe; color: #6d28d9; }
        .status-badge.in_transit .dot { background: #7c3aed; }
        .status-badge.out_for_delivery { background: #fef3c7; color: #b45309; }
        .status-badge.out_for_delivery .dot { background: #f59e0b; }
        .status-badge.delivered { background: #d1fae5; color: #059669; }
        .status-badge.delivered .dot { background: #10b981; }
        .status-badge.cancelled { background: #fee2e2; color: #dc2626; }
        .status-badge.cancelled .dot { background: #ef4444; }
        .status-badge.returned { background: #ffedd5; color: #c2410c; }
        .status-badge.returned .dot { background: #ea580c; }
        .status-badge.dispatched { background: #dbeafe; color: #2563eb; }
        .status-badge.dispatched .dot { background: #3b82f6; }
        .status-badge.lost { background: #fce7f3; color: #be185d; }
        .status-badge.lost .dot { background: #ec4899; }

        /* ===== ORDER DETAILS GRID ===== */
        .order-details {
            padding: 1.25rem 1.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .detail-item {}
        .detail-item .label {
            font-size: 0.675rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }
        .detail-item .value {
            font-size: 0.9rem;
            font-weight: 600;
            color: #1e293b;
        }
        .detail-item .value.highlight {
            color: #0d9488;
        }
        .detail-full {
            grid-column: 1 / -1;
        }

        /* ===== LIVE INDICATOR ===== */
        .live-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #f0fdfa;
            border-top: 1px solid #ccfbf1;
            font-size: 0.75rem;
            font-weight: 500;
            color: #0d9488;
        }
        .live-indicator .live-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #10b981;
            animation: live-pulse 1.2s ease-in-out infinite;
        }
        @keyframes live-pulse {
            0% { box-shadow: 0 0 0 0 rgba(16,185,129,0.5); }
            70% { box-shadow: 0 0 0 8px rgba(16,185,129,0); }
            100% { box-shadow: 0 0 0 0 rgba(16,185,129,0); }
        }
        .live-indicator .time-ago {
            color: #64748b;
            font-weight: 400;
        }

        /* ===== TIMELINE ===== */
        .timeline-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 24px rgba(0,0,0,0.04);
            padding: 1.5rem;
        }
        .timeline-card .timeline-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
            margin-bottom: 1.5rem;
        }
        .timeline {
            position: relative;
        }
        .timeline-step {
            display: flex;
            gap: 1rem;
            padding-bottom: 1.75rem;
            position: relative;
        }
        .timeline-step:last-child { padding-bottom: 0; }
        .timeline-step .step-indicator {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            width: 28px;
        }
        .timeline-step .step-dot {
            width: 16px; height: 16px;
            border-radius: 50%;
            border: 2.5px solid;
            background: #fff;
            z-index: 2;
            position: relative;
            transition: all 0.3s;
        }
        .timeline-step .step-line {
            width: 2px;
            flex: 1;
            margin-top: 2px;
        }
        .timeline-step:last-child .step-line { display: none; }

        /* Step states */
        .timeline-step.done .step-dot {
            background: #10b981;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16,185,129,0.15);
        }
        .timeline-step.done .step-line { background: #10b981; }

        .timeline-step.current .step-dot {
            background: #0d9488;
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13,148,136,0.2);
            animation: current-pulse 2s ease-in-out infinite;
        }
        .timeline-step.current .step-line { background: #0d9488; }

        @keyframes current-pulse {
            0%, 100% { box-shadow: 0 0 0 4px rgba(13,148,136,0.2); }
            50% { box-shadow: 0 0 0 8px rgba(13,148,136,0.08); }
        }

        .timeline-step.pending .step-dot {
            background: #fff;
            border-color: #e2e8f0;
        }
        .timeline-step.pending .step-line { background: #e2e8f0; }

        .timeline-step .step-content {
            flex: 1;
            padding-top: 0.125rem;
        }
        .timeline-step .step-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1e293b;
        }
        .timeline-step.pending .step-title { color: #94a3b8; }
        .timeline-step .step-detail {
            font-size: 0.8rem;
            color: #64748b;
            margin-top: 0.125rem;
        }
        .timeline-step .step-time {
            font-size: 0.7rem;
            font-weight: 600;
            color: #94a3b8;
            margin-top: 0.25rem;
        }
        .timeline-step.current .step-time { color: #0d9488; }

        /* Current step label */
        .now-label {
            display: inline-block;
            background: #0d9488;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.125rem 0.5rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            animation: now-flash 1.5s ease-in-out infinite;
        }
        @keyframes now-flash {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* ===== NOT FOUND ===== */
        .not-found {
            text-align: center;
            padding: 4rem 1.5rem;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 6px 24px rgba(0,0,0,0.04);
        }
        .not-found .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.6;
        }
        .not-found h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #475569;
        }
        .not-found p {
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        /* ===== LOADING ===== */
        .loading-state {
            text-align: center;
            padding: 3rem;
        }
        .spinner {
            width: 32px; height: 32px;
            border: 3px solid #e2e8f0;
            border-top-color: #0d9488;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 0.75rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 480px) {
            .track-header { padding: 1.5rem 1rem 2.5rem; }
            .track-header h1 { font-size: 1.25rem; }
            .order-details { grid-template-columns: 1fr; }
            .order-card-header { flex-direction: column; align-items: flex-start; }
            .search-box { flex-direction: column; }
            .search-box button { width: 100%; text-align: center; }
        }
        @media print {
            .search-section, .live-indicator { display: none; }
        }
    </style>
</head>
<body>

    <!-- ===== HEADER ===== -->
    <header class="track-header">
        <div class="track-header-content">
            <div class="brand">
                <div class="brand-icon">📦</div>
                <span class="brand-name">{{ config('app.name', 'Shah Jee Courier') }}</span>
            </div>
            <h1>Live Parcel Tracking</h1>
            <p>Real-time updates · No login required</p>
        </div>
    </header>

    <!-- ===== SEARCH BAR ===== -->
    <div class="search-section">
        <form class="search-box" action="{{ url('/track') }}" method="GET" id="searchForm">
            <input
                type="text"
                name="q"
                id="trackingInput"
                placeholder="Enter tracking number (e.g. SJC0000000001)"
                value="{{ request('q', $tracking_number ?? '') }}"
                autocomplete="off"
            >
            <button type="submit">Track</button>
        </form>
    </div>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content" id="app">

        @if($order)
            <div id="trackingContainer">
                @include('track._partials.tracking_data', ['order' => $order, 'events' => $events, 'meta' => $meta])
            </div>
        @elseif(request('q') || $tracking_number)
            <div class="not-found">
                <div class="icon">🔍</div>
                <h2>Tracking number not found</h2>
                <p>We couldn't find any parcel with <strong>{{ request('q', $tracking_number) }}</strong>.<br>Please check the number and try again.</p>
            </div>
        @else
            <div class="not-found">
                <div class="icon">📮</div>
                <h2>Enter a tracking number</h2>
                <p>Type your tracking number above to see real-time updates for your parcel.</p>
            </div>
        @endif
    </div>

    <!-- ===== REAL-TIME POLLING ===== -->
    @if($order)
    <script>
        const TRACKING_NUMBER = "{{ $order->tracking_number }}";
        const API_URL = "{{ url('/track/api') }}/" + TRACKING_NUMBER;
        const POLL_INTERVAL = 15000; // 15 seconds

        let currentEvents = {};

        function fetchTracking() {
            fetch(API_URL, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (!data.ok) return;

                const container = document.getElementById('trackingContainer');
                if (!container) return;

                // Update live time
                const liveTime = document.querySelector('.time-ago');
                if (liveTime) liveTime.textContent = 'Just now';

                // Update status badge if changed
                const newStatus = data.order?.status || '';
                const oldBadge = document.querySelector('.status-badge');
                if (oldBadge && data.meta) {
                    const label = data.meta.label || newStatus.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
                    oldBadge.textContent = label;
                    // Update badge class
                    oldBadge.className = 'status-badge ' + (newStatus || 'booked');
                    // Add dot
                    const dot = oldBadge.querySelector('.dot');
                    if (!dot) {
                        oldBadge.innerHTML = '<span class="dot"></span> ' + label;
                    }
                }

                // Check for event changes
                const newEvents = data.events || [];
                const eventsChanged = JSON.stringify(newEvents) !== JSON.stringify(currentEvents);

                if (eventsChanged && newEvents.length > 0) {
                    currentEvents = newEvents;
                    // Reload the full tracking data partial
                    // For now we do a full page partial reload via a hidden iframe trick or just reload the section
                    // Simpler: just reload the whole tracking container using a fetch to the HTML page itself
                    refreshFullTracking();
                }
            })
            .catch(() => {
                // Silently fail - don't disrupt user
            });
        }

        function refreshFullTracking() {
            const container = document.getElementById('trackingContainer');
            if (!container) return;

            fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html, */*' }
            })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.getElementById('trackingContainer');
                if (newContainer) {
                    container.innerHTML = newContainer.innerHTML;
                }
            })
            .catch(() => {});
        }

        // Initial load
        if (document.getElementById('trackingContainer')) {
            // Start polling
            setInterval(fetchTracking, POLL_INTERVAL);

            // Update "Just now" every 30s
            setInterval(() => {
                const liveTime = document.querySelector('.time-ago');
                if (liveTime) {
                    const secs = Math.floor((Date.now() - liveTime._lastUpdate || Date.now()) / 1000);
                    if (secs < 60) liveTime.textContent = secs + 's ago';
                    else liveTime.textContent = Math.floor(secs/60) + 'm ago';
                }
            }, 10000);
        }

        // Smooth scroll to results on form submit
        document.getElementById('searchForm')?.addEventListener('submit', function(e) {
            const input = document.getElementById('trackingInput');
            if (!input.value.trim()) {
                e.preventDefault();
                return;
            }
            // Let the form submit naturally
        });
    </script>
    @endif

</body>
</html>