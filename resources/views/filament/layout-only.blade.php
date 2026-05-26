<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Shah Jee Courier — Admin Panel</title>
    <style>
        :root {
            --bg: #09090b;
            --surface: #18181b;
            --border: #27272a;
            --text: #f4f4f5;
            --muted: #a1a1aa;
            --amber: #f59e0b;
            --amber-bg: rgba(245, 158, 11, 0.1);
            --green: #22c55e;
            --green-bg: rgba(34, 197, 94, 0.1);
            --red: #ef4444;
            --red-bg: rgba(239, 68, 68, 0.1);
            --blue: #3b82f6;
            --blue-bg: rgba(59, 130, 246, 0.1);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            position: fixed;
            top: 0; bottom: 0; left: 0;
        }
        .sidebar-brand {
            padding: 8px 20px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 16px;
        }
        .sidebar-brand .logo {
            width: 36px;
            height: 36px;
            background: var(--amber);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #09090b;
        }
        .sidebar-brand .name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
        .nav-group {
            padding: 0 12px;
            margin-bottom: 8px;
        }
        .nav-group-title {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--muted);
            padding: 8px 8px 6px;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            color: var(--muted);
            font-size: 14px;
            text-decoration: none;
            transition: all 0.15s;
            cursor: default;
        }
        .nav-item.active {
            background: var(--amber-bg);
            color: var(--amber);
        }
        .nav-item .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }
        /* Main */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 32px 40px;
            max-width: calc(100vw - 260px);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        .topbar h1 {
            font-size: 24px;
            font-weight: 700;
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            background: var(--amber-bg);
            color: var(--amber);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .badge::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--amber);
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px 24px;
        }
        .stat-card .label {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 4px;
        }
        .stat-card .value {
            font-size: 28px;
            font-weight: 700;
        }
        .stat-card .sub {
            font-size: 12px;
            color: var(--muted);
            margin-top: 4px;
        }
        .stat-card .value.amber { color: var(--amber); }
        .stat-card .value.green { color: var(--green); }
        .stat-card .value.blue { color: var(--blue); }
        /* Panel */
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 28px 32px;
            margin-bottom: 20px;
        }
        .panel h2 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .panel .desc {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 16px;
            line-height: 1.6;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .info-box {
            background: #0f0f0f;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 16px;
        }
        .info-box strong {
            display: block;
            font-size: 13px;
            margin-bottom: 6px;
            color: var(--text);
        }
        .info-box span {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
        }
        .restore-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--amber);
            color: #09090b;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-top: 16px;
            transition: background 0.15s;
        }
        .restore-btn:hover { background: #e5a100; }
        /* Footer */
        .footer {
            margin-top: 32px;
            text-align: center;
            font-size: 12px;
            color: #52525b;
        }
        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; max-width: 100%; padding: 24px 16px; }
            .stats-grid { grid-template-columns: 1fr; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="logo">SJ</div>
            <div class="name">Shah Jee Courier</div>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Dashboard</div>
            <div class="nav-item active"><span class="dot"></span> Dashboard</div>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Shipment Management</div>
            <div class="nav-item"><span class="dot"></span> Bookings</div>
            <div class="nav-item"><span class="dot"></span> Tracking</div>
            <div class="nav-item"><span class="dot"></span> Rate Matrix</div>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Merchant & User Management</div>
            <div class="nav-item"><span class="dot"></span> Merchants</div>
            <div class="nav-item"><span class="dot"></span> Users</div>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Courier Management</div>
            <div class="nav-item"><span class="dot"></span> Courier Integrations</div>
            <div class="nav-item"><span class="dot"></span> API Keys</div>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Financials</div>
            <div class="nav-item"><span class="dot"></span> Financials</div>
            <div class="nav-item"><span class="dot"></span> COD Reconciliation</div>
            <div class="nav-item"><span class="dot"></span> Payouts</div>
            <div class="nav-item"><span class="dot"></span> Wallets</div>
            <div class="nav-item"><span class="dot"></span> Pricing Plans</div>
        </div>

        <div class="nav-group">
            <div class="nav-group-title">Reports & Logs</div>
            <div class="nav-item"><span class="dot"></span> Activity Logs</div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main">
        <div class="topbar">
            <h1>Dashboard</h1>
            <div class="badge">Layout-Only Mode</div>
        </div>

        <!-- Stats Row -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Today's Shipments</div>
                <div class="value blue">—</div>
                <div class="sub">Awaiting database connection</div>
            </div>
            <div class="stat-card">
                <div class="label">Today's Revenue</div>
                <div class="value amber">PKR —</div>
                <div class="sub">Awaiting database connection</div>
            </div>
            <div class="stat-card">
                <div class="label">Net Profit Today</div>
                <div class="value green">PKR —</div>
                <div class="sub">Awaiting database connection</div>
            </div>
        </div>

        <!-- Info Panel -->
        <div class="panel">
            <h2>Admin Panel Status</h2>
            <p class="desc">
                The admin dashboard is currently running in <strong>layout-only mode</strong>. 
                Database queries and live data are disabled. This allows you to preview the UI 
                structure and navigation without an active MySQL connection.
            </p>

            <div class="info-grid">
                <div class="info-box">
                    <strong>Tables & Resources</strong>
                    <span>All resource pages (Bookings, Merchants, Financials, etc.) are listed in the sidebar but data is not loaded.</span>
                </div>
                <div class="info-box">
                    <strong>Widgets</strong>
                    <span>Stats, charts, and alerts widgets are suspended. No database queries are executed in this mode.</span>
                </div>
                <div class="info-box">
                    <strong>Actions</strong>
                    <span>Create, Edit, and Delete operations are disabled to prevent data corruption when the database is offline.</span>
                </div>
                <div class="info-box">
                    <strong>Enable Full Dashboard</strong>
                    <span>Set <code style="background:#27272a;padding:1px 5px;border-radius:4px;">FILAMENT_LAYOUT_ONLY=false</code> in your <code style="background:#27272a;padding:1px 5px;border-radius:4px;">.env</code> file and ensure MySQL is running with the correct credentials.</span>
                </div>
            </div>
        </div>

        <!-- Courier Performance Placeholder -->
        <div class="panel">
            <h2>Courier Performance — Last 7 Days</h2>
            <p class="desc">Chart data will render once the database is connected.</p>
            <div style="background:#0f0f0f;border:1px dashed var(--border);border-radius:10px;padding:40px;text-align:center;color:var(--muted);font-size:14px;">
                📊 Chart Placeholder — No data available in layout-only mode
            </div>
        </div>

        <!-- Revenue vs Profit Placeholder -->
        <div class="panel">
            <h2>Revenue vs Profit</h2>
            <p class="desc">Chart data will render once the database is connected.</p>
            <div style="background:#0f0f0f;border:1px dashed var(--border);border-radius:10px;padding:40px;text-align:center;color:var(--muted);font-size:14px;">
                📈 Chart Placeholder — No data available in layout-only mode
            </div>
        </div>

        <div class="footer">
            Shah Jee Courier &copy; {{ date('Y') }} — Admin Panel UI Preview
        </div>
    </main>
</body>
</html>