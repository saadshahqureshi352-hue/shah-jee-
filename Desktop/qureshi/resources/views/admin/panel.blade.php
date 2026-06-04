<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Console | Shah Jee Courier</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
                    colors: {
                        ink: '#102033',
                        brand: {
                            50: '#ecfdf8',
                            100: '#d1faee',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            900: '#134e4a'
                        },
                        ember: {
                            50: '#fff7ed',
                            500: '#f97316',
                            600: '#ea580c'
                        }
                    },
                    boxShadow: {
                        soft: '0 14px 40px rgba(15, 23, 42, 0.08)',
                        panel: '0 1px 2px rgba(15, 23, 42, 0.04), 0 12px 30px rgba(15, 23, 42, 0.06)'
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        .page { display: none; }
        .page.active { display: block; }
        .nav-item { color: rgb(203 213 225); border-left: 3px solid transparent; }
        .nav-item:hover { background: rgba(255,255,255,.07); color: white; }
        .nav-item.active { background: linear-gradient(90deg, rgba(20,184,166,.22), rgba(249,115,22,.08)); color: white; border-left-color: #2dd4bf; }
        .filter-btn.active { background: #0d9488; color: white; border-color: #0d9488; box-shadow: 0 8px 18px rgba(13,148,136,.22); }
        .data-table th { padding: .78rem 1rem; text-align: left; font-size: .68rem; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: .055em; background: #f8fafc; white-space: nowrap; }
        .data-table td { padding: .78rem 1rem; border-top: 1px solid #eef2f7; color: #334155; white-space: nowrap; }
        .data-table tbody tr:hover td { background: #fbfefd; }
        .scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .toggle { width: 38px; height: 21px; border-radius: 999px; position: relative; transition: .2s ease; }
        .toggle:after { content: ''; position: absolute; top: 3px; width: 15px; height: 15px; border-radius: 999px; background: white; box-shadow: 0 1px 4px rgba(15,23,42,.3); transition: .2s ease; }
        .toggle.on { background: #0d9488; }
        .toggle.off { background: #cbd5e1; }
        .toggle.on:after { left: 20px; }
        .toggle.off:after { left: 3px; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
@php
    $user = Auth::user();
    $navGroups = [
        'Main' => [
            ['dashboard', 'Dashboard', 'ti-layout-dashboard'],
            ['orders', 'Orders', 'ti-package'],
            ['cod', 'COD & Settlement', 'ti-cash'],
            ['invoices', 'Invoices', 'ti-file-invoice'],
        ],
        'People' => [
            ['merchants', 'Merchants', 'ti-building-store', '4'],
            ['couriers', 'Couriers', 'ti-truck'],
        ],
        'Finance' => [
            ['pricing', 'Pricing Plans', 'ti-tag'],
            ['profit', 'Profit Report', 'ti-chart-bar'],
            ['tax', 'Tax Engine', 'ti-percentage'],
        ],
        'Tools' => [
            ['notif', 'Notifications', 'ti-bell'],
        ],
    ];

    $stats = [
        ['Booked today', '125', 'Rs 280,000 COD', 'ti-plus', 'text-slate-900', 'bg-slate-100'],
        ['Dispatched', '98', 'Ready and in transit', 'ti-send', 'text-sky-700', 'bg-sky-50'],
        ['Delivered', '87', 'Rs 192,000 COD', 'ti-circle-check', 'text-emerald-700', 'bg-emerald-50'],
        ['In progress', '29', 'Live courier movement', 'ti-loader-2', 'text-amber-700', 'bg-amber-50'],
        ['Returned', '9', 'Rs 18,000 COD', 'ti-arrow-back-up', 'text-rose-700', 'bg-rose-50'],
        ['Issue orders', '3', 'Action required', 'ti-alert-triangle', 'text-red-700', 'bg-red-50'],
    ];

    $financeStats = [
        ['Gross profit', 'Rs 45,000', 'Dispatched orders', 'ti-coin', 'text-emerald-700'],
        ['Net profit', 'Rs 37,200', 'After tax difference', 'ti-chart-line', 'text-emerald-700'],
        ['Tax collected 4%', 'Rs 7,680', 'Delivered COD', 'ti-percentage', 'text-slate-900'],
        ['Courier tax 2%', 'Rs 3,840', 'Deducted by courier', 'ti-receipt-tax', 'text-rose-700'],
    ];

    $orders = [
        ['#8821', 'ABC Store', 'Karachi', 'Leopards', 'Rs 1,000', 'Rs 40', 'Rs 20', 'Rs 180', 'Rs 55', 'Delivered', 'green'],
        ['#8820', 'XYZ Shop', 'Lahore', 'TCS', 'Rs 2,500', 'Rs 100', 'Rs 50', 'Rs 240', 'Rs 40', 'In transit', 'blue'],
        ['#8819', 'Fast Deals', 'Karachi', 'M&P', 'Rs 3,200', 'Rs 128', 'Rs 64', 'Rs 220', 'Rs 70', 'Delivered', 'green'],
        ['#8818', 'Tech Zone', 'Islamabad', 'Trax', 'Rs 5,000', 'Rs 200', 'Rs 100', 'Rs 220', '-', 'Returned', 'red'],
        ['#8817', 'Style Hub', 'Karachi', 'BlueEx', 'Rs 1,500', 'Rs 60', 'Rs 30', 'Rs 210', 'Rs 50', 'Delivered', 'green'],
    ];

    $merchants = [
        ['ABC Store', 'VIP', 842, 785, 'Rs 980,000', 'Rs 126,300', 'Rs 814,500', 'Active'],
        ['XYZ Shop', 'Basic', 312, 266, 'Rs 420,000', 'Rs 68,640', 'Rs 334,560', 'Active'],
        ['Fast Deals', 'Gold', 1204, 980, 'Rs 1,850,000', 'Rs 264,880', 'Rs 1,511,120', 'Suspended'],
        ['Tech Zone', 'Basic', 198, 160, 'Rs 280,000', 'Rs 43,560', 'Rs 225,240', 'Active'],
    ];

    $couriers = [
        ['Leopards', 'Active', 'Rs 165', 'Rs 220', 'Rs 55', 52, 'Rs 2,860'],
        ['TCS', 'Active', 'Rs 200', 'Rs 240', 'Rs 40', 38, 'Rs 1,520'],
        ['M&P', 'Active', 'Rs 150', 'Rs 220', 'Rs 70', 25, 'Rs 1,750'],
        ['Call Courier', 'Active', 'Rs 140', 'Rs 200', 'Rs 60', 18, 'Rs 1,080'],
        ['Trax', 'Off', 'Rs 180', 'Rs 220', 'Rs 40', 0, '-'],
    ];

    $badge = [
        'green' => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        'blue' => 'bg-sky-50 text-sky-700 ring-sky-200',
        'red' => 'bg-rose-50 text-rose-700 ring-rose-200',
        'amber' => 'bg-amber-50 text-amber-700 ring-amber-200',
        'slate' => 'bg-slate-100 text-slate-600 ring-slate-200',
    ];
@endphp

<div id="toast" class="fixed right-5 top-5 z-50 hidden rounded-full border border-emerald-200 bg-white px-4 py-2 text-xs font-bold text-emerald-700 shadow-soft"></div>

<div class="flex min-h-screen">
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full bg-slate-950 text-white shadow-2xl transition-transform duration-300 lg:static lg:translate-x-0">
        <div class="flex h-full flex-col">
            <div class="border-b border-white/10 px-5 py-5">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="h-12 w-12 rounded-2xl object-cover ring-2 ring-orange-300/60">
                    <div class="min-w-0">
                        <div class="truncate text-base font-extrabold tracking-tight">Shah Jee Courier</div>
                        <div class="text-[11px] font-semibold uppercase tracking-[.22em] text-teal-200">Admin ERP</div>
                    </div>
                </div>
            </div>

            <nav class="scrollbar flex-1 overflow-y-auto px-3 py-4">
                @foreach($navGroups as $group => $items)
                    <div class="px-3 pb-2 pt-4 text-[10px] font-extrabold uppercase tracking-[.18em] text-slate-500">{{ $group }}</div>
                    <div class="space-y-1">
                        @foreach($items as $item)
                            <button type="button" data-page="{{ $item[0] }}" class="nav-item {{ $item[0] === 'dashboard' ? 'active' : '' }} flex w-full items-center gap-3 rounded-r-xl px-3 py-2.5 text-left text-sm font-semibold transition">
                                <i class="ti {{ $item[2] }} text-lg"></i>
                                <span class="flex-1">{{ $item[1] }}</span>
                                @if(isset($item[3]))
                                    <span class="rounded-full bg-amber-400 px-2 py-0.5 text-[10px] font-black text-slate-950">{{ $item[3] }}</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endforeach
            </nav>

            <div class="border-t border-white/10 p-4">
                <div class="rounded-2xl bg-white/7 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-teal-400 text-sm font-black text-slate-950">
                            {{ strtoupper(substr($user?->name ?? 'Admin', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="truncate text-sm font-bold">{{ $user?->name ?? 'Admin' }}</div>
                            <div class="truncate text-[11px] text-slate-400">{{ $user?->email ?? 'admin@shahjee.test' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <div id="overlay" class="fixed inset-0 z-30 hidden bg-slate-950/60 lg:hidden"></div>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 px-4 py-3 shadow-sm backdrop-blur lg:px-6">
            <div class="flex flex-wrap items-center gap-3">
                <button id="menuBtn" type="button" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-200 text-slate-700 lg:hidden">
                    <i class="ti ti-menu-2 text-xl"></i>
                </button>
                <div class="min-w-0 flex-1">
                    <div id="pageTitle" class="text-lg font-extrabold tracking-tight text-slate-950">Dashboard</div>
                    <div class="text-xs font-medium text-slate-500">Operations, COD, courier rates, tax and profit controls</div>
                </div>
                <div class="flex items-center gap-2 overflow-x-auto">
                    @foreach(['Today', 'Yesterday', '3 Days', 'This Week', 'This Month'] as $i => $filter)
                        <button type="button" class="filter-btn {{ $i === 0 ? 'active' : '' }} shrink-0 rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-600 transition">{{ $filter }}</button>
                    @endforeach
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-extrabold text-rose-700 transition hover:bg-rose-100">
                        <i class="ti ti-logout text-base"></i>
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="scrollbar flex-1 overflow-y-auto p-4 lg:p-6">
            <section id="page-dashboard" class="page active space-y-5">
                <div class="grid gap-5 xl:grid-cols-[1.25fr_.75fr]">
                    <div class="overflow-hidden rounded-2xl bg-slate-950 text-white shadow-soft">
                        <div class="grid gap-0 lg:grid-cols-[1fr_320px]">
                            <div class="p-6">
                                <div class="mb-5 flex items-center justify-between gap-4">
                                    <div>
                                        <div class="text-xs font-extrabold uppercase tracking-[.18em] text-teal-200">Company live position</div>
                                        <div class="mt-1 text-sm text-slate-400">Bank, payable, receivable and held tax</div>
                                    </div>
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-400/10 px-3 py-1 text-xs font-bold text-emerald-200">
                                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span> Live
                                    </span>
                                </div>
                                <div class="text-4xl font-black tracking-tight">Rs 1,245,000</div>
                                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-white/8 p-4">
                                        <div class="text-xs text-slate-400">Merchant payables</div>
                                        <div class="mt-1 text-xl font-extrabold text-rose-200">Rs 870,000</div>
                                    </div>
                                    <div class="rounded-2xl bg-white/8 p-4">
                                        <div class="text-xs text-slate-400">Courier receivables</div>
                                        <div class="mt-1 text-xl font-extrabold text-emerald-200">Rs 325,000</div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gradient-to-br from-teal-500 to-orange-500 p-6 text-slate-950">
                                <div class="text-xs font-black uppercase tracking-[.18em] opacity-75">Available cash</div>
                                <div class="mt-2 text-3xl font-black">Rs 872,000</div>
                                <div class="mt-5 space-y-3 text-sm font-bold">
                                    <div class="flex justify-between border-b border-slate-950/10 pb-3"><span>Bank balance</span><span>Rs 1,245,000</span></div>
                                    <div class="flex justify-between border-b border-slate-950/10 pb-3"><span>Tax held 4%</span><span>Rs 28,000</span></div>
                                    <div class="flex justify-between"><span>Net health</span><span>Stable</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-panel">
                        <div class="mb-4 flex items-center justify-between">
                            <div>
                                <div class="text-sm font-extrabold text-slate-950">Quick actions</div>
                                <div class="text-xs text-slate-500">Common admin work</div>
                            </div>
                            <i class="ti ti-bolt text-2xl text-orange-500"></i>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <button onclick="showToast('Invoice generated')" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 text-left transition hover:border-teal-300 hover:bg-teal-50">
                                <i class="ti ti-file-invoice text-xl text-teal-700"></i><span class="text-sm font-bold">Generate invoices</span>
                            </button>
                            <button onclick="showToast('Courier rates saved')" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 text-left transition hover:border-teal-300 hover:bg-teal-50">
                                <i class="ti ti-truck-delivery text-xl text-teal-700"></i><span class="text-sm font-bold">Update courier rates</span>
                            </button>
                            <button onclick="showToast('Notification queued')" class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 text-left transition hover:border-teal-300 hover:bg-teal-50">
                                <i class="ti ti-brand-whatsapp text-xl text-emerald-700"></i><span class="text-sm font-bold">Send WhatsApp alert</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-6">
                    @foreach($stats as $stat)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-panel">
                            <div class="mb-3 flex items-center justify-between gap-2">
                                <span class="text-xs font-bold text-slate-500">{{ $stat[0] }}</span>
                                <span class="grid h-9 w-9 place-items-center rounded-xl {{ $stat[5] }}"><i class="ti {{ $stat[3] }} {{ $stat[4] }}"></i></span>
                            </div>
                            <div class="text-2xl font-black {{ $stat[4] }}">{{ $stat[1] }}</div>
                            <div class="mt-1 text-[11px] font-medium text-slate-400">{{ $stat[2] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($financeStats as $stat)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-panel">
                            <div class="mb-2 flex items-center gap-2 text-xs font-bold text-slate-500"><i class="ti {{ $stat[3] }} text-base"></i>{{ $stat[0] }}</div>
                            <div class="text-xl font-black {{ $stat[4] }}">{{ $stat[1] }}</div>
                            <div class="mt-1 text-[11px] text-slate-400">{{ $stat[2] }}</div>
                        </div>
                    @endforeach
                </div>

                <x-admin.table-card title="Recent orders" icon="ti-list-details">
                    @include('admin.partials.orders-table', ['orders' => $orders, 'badge' => $badge])
                </x-admin.table-card>
            </section>

            <section id="page-orders" class="page space-y-5">
                <div class="grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl bg-white p-4 shadow-panel"><div class="text-xs font-bold text-slate-500">Order value</div><div class="mt-1 text-2xl font-black">Rs 2,800,000</div></div>
                    <div class="rounded-2xl bg-white p-4 shadow-panel"><div class="text-xs font-bold text-slate-500">Delivery success</div><div class="mt-1 text-2xl font-black text-emerald-700">89.4%</div></div>
                    <div class="rounded-2xl bg-white p-4 shadow-panel"><div class="text-xs font-bold text-slate-500">Pending issues</div><div class="mt-1 text-2xl font-black text-rose-700">3</div></div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach(['All', 'Booked', 'Dispatched', 'Delivered', 'In transit', 'Returned', 'Issue'] as $i => $filter)
                        <button class="filter-btn {{ $i === 0 ? 'active' : '' }} rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600">{{ $filter }}</button>
                    @endforeach
                </div>
                <x-admin.table-card title="Shipment ledger" icon="ti-package">
                    @include('admin.partials.orders-table', ['orders' => $orders, 'badge' => $badge])
                </x-admin.table-card>
            </section>

            <section id="page-cod" class="page space-y-5">
                <div class="grid gap-3 md:grid-cols-3">
                    <div class="rounded-2xl border border-rose-100 bg-white p-5 shadow-panel"><div class="text-xs font-bold text-slate-500">Total COD to pay</div><div class="mt-1 text-2xl font-black text-rose-700">Rs 870,000</div><div class="mt-1 text-xs text-slate-400">To merchants</div></div>
                    <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-panel"><div class="text-xs font-bold text-slate-500">Courier receivable</div><div class="mt-1 text-2xl font-black text-emerald-700">Rs 325,000</div><div class="mt-1 text-xs text-slate-400">COD from couriers</div></div>
                    <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-panel"><div class="text-xs font-bold text-slate-500">Pending settlements</div><div class="mt-1 text-2xl font-black text-amber-700">12</div><div class="mt-1 text-xs text-slate-400">Merchants waiting</div></div>
                </div>
                <x-admin.table-card title="Merchant COD settlement" icon="ti-cash">
                    <table class="data-table w-full text-sm">
                        <thead><tr><th>Merchant</th><th>Delivered</th><th>Total COD</th><th>Charges</th><th>4% Tax</th><th>Net payable</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach([['ABC Store',35,'Rs 85,000','Rs 7,200','Rs 3,400','Rs 74,400','Pending'],['XYZ Shop',22,'Rs 52,000','Rs 5,280','Rs 2,080','Rs 44,640','Paid'],['Fast Deals',58,'Rs 135,000','Rs 12,760','Rs 5,400','Rs 116,840','Pending']] as $row)
                                <tr><td class="font-bold">{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td>{{ $row[3] }}</td><td>{{ $row[4] }}</td><td class="font-black text-emerald-700">{{ $row[5] }}</td><td><span class="rounded-full px-2 py-1 text-[11px] font-bold ring-1 {{ $row[6] === 'Paid' ? $badge['green'] : $badge['amber'] }}">{{ $row[6] }}</span></td><td><button onclick="showToast('Settlement updated')" class="rounded-lg bg-teal-600 px-3 py-1.5 text-xs font-bold text-white">Process</button></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-admin.table-card>
            </section>

            <section id="page-invoices" class="page space-y-5">
                <div class="grid gap-3 sm:grid-cols-4">
                    @foreach([['Total invoices','48','text-slate-950'],['Pending','12','text-amber-700'],['Paid','33','text-emerald-700'],['Overdue','3','text-rose-700']] as $item)
                        <div class="rounded-2xl bg-white p-4 shadow-panel"><div class="text-xs font-bold text-slate-500">{{ $item[0] }}</div><div class="mt-1 text-2xl font-black {{ $item[2] }}">{{ $item[1] }}</div></div>
                    @endforeach
                </div>
                <x-admin.table-card title="Invoice cycle" icon="ti-file-invoice">
                    <table class="data-table w-full text-sm">
                        <thead><tr><th>Invoice</th><th>Merchant</th><th>Period</th><th>Delivered</th><th>COD</th><th>Charges</th><th>Tax</th><th>Net payable</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach([['INV-00021','ABC Store','1-3 Jun',35,'Rs 85,000','Rs 7,200','Rs 3,400','Rs 74,400','Pending'],['INV-00020','XYZ Shop','29-31 May',22,'Rs 52,000','Rs 5,280','Rs 2,080','Rs 44,640','Paid'],['INV-00019','Fast Deals','26-28 May',58,'Rs 135,000','Rs 12,760','Rs 5,400','Rs 116,840','Overdue']] as $row)
                                <tr><td class="font-black">{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td>{{ $row[3] }}</td><td>{{ $row[4] }}</td><td>{{ $row[5] }}</td><td>{{ $row[6] }}</td><td class="font-black text-emerald-700">{{ $row[7] }}</td><td><span class="rounded-full px-2 py-1 text-[11px] font-bold ring-1 {{ $row[8] === 'Paid' ? $badge['green'] : ($row[8] === 'Overdue' ? $badge['red'] : $badge['amber']) }}">{{ $row[8] }}</span></td><td><button onclick="showToast('PDF ready')" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold">PDF</button></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-admin.table-card>
            </section>

            <section id="page-merchants" class="page space-y-5">
                <x-admin.table-card title="Pending approvals" icon="ti-user-plus">
                    <table class="data-table w-full text-sm">
                        <thead><tr><th>Merchant</th><th>Business</th><th>City</th><th>Plan</th><th>Joined</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach([['Style Hub PK','Fashion','Karachi','VIP','28 May'],['Tech Galaxy','Electronics','Lahore','Basic','30 May'],['Ghar Ghar','Grocery','Islamabad','Basic','31 May'],['Desi Crafts','Handicrafts','Multan','Gold','31 May']] as $row)
                                <tr><td class="font-black">{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td><span class="rounded-full px-2 py-1 text-[11px] font-bold ring-1 {{ $badge['amber'] }}">{{ $row[3] }}</span></td><td>{{ $row[4] }}</td><td><div class="flex gap-2"><button onclick="showToast('Merchant approved')" class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white">Approve</button><button onclick="showToast('Merchant rejected')" class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700">Reject</button></div></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-admin.table-card>
                <x-admin.table-card title="Active merchant finance" icon="ti-building-store">
                    <table class="data-table w-full text-sm">
                        <thead><tr><th>Merchant</th><th>Plan</th><th>Dispatched</th><th>Delivered</th><th>Total COD</th><th>Charges</th><th>Net payable</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @foreach($merchants as $row)
                                <tr><td class="font-black">{{ $row[0] }}</td><td>{{ $row[1] }}</td><td>{{ $row[2] }}</td><td class="font-bold text-emerald-700">{{ $row[3] }}</td><td>{{ $row[4] }}</td><td>{{ $row[5] }}</td><td class="font-black text-emerald-700">{{ $row[6] }}</td><td><span class="rounded-full px-2 py-1 text-[11px] font-bold ring-1 {{ $row[7] === 'Active' ? $badge['green'] : $badge['red'] }}">{{ $row[7] }}</span></td><td><button onclick="showToast('Merchant record opened')" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-bold">View</button></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-admin.table-card>
            </section>

            <section id="page-couriers" class="page space-y-5">
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-900">Profit = merchant rate - courier rate. Profit is counted only on dispatched orders.</div>
                <x-admin.table-card title="Courier rate and profit controls" icon="ti-truck">
                    <table class="data-table w-full text-sm">
                        <thead><tr><th>Courier</th><th>Status</th><th>Courier rate</th><th>Merchant rate</th><th>Profit/order</th><th>Dispatched</th><th>Total profit</th><th>Toggle</th><th>Save</th></tr></thead>
                        <tbody>
                            @foreach($couriers as $row)
                                <tr><td class="font-black">{{ $row[0] }}</td><td><span class="rounded-full px-2 py-1 text-[11px] font-bold ring-1 {{ $row[1] === 'Active' ? $badge['green'] : $badge['slate'] }}">{{ $row[1] }}</span></td><td><input class="w-24 rounded-lg border border-slate-200 px-2 py-1 text-right text-xs font-bold" value="{{ $row[2] }}"></td><td><input class="w-24 rounded-lg border border-slate-200 px-2 py-1 text-right text-xs font-bold" value="{{ $row[3] }}"></td><td class="font-bold text-emerald-700">{{ $row[4] }}</td><td>{{ $row[5] }}</td><td class="font-black text-emerald-700">{{ $row[6] }}</td><td><button class="toggle {{ $row[1] === 'Active' ? 'on' : 'off' }}" onclick="this.classList.toggle('on');this.classList.toggle('off')"></button></td><td><button onclick="showToast('Courier saved')" class="rounded-lg bg-teal-600 px-3 py-1.5 text-xs font-bold text-white">Save</button></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </x-admin.table-card>
            </section>

            <section id="page-pricing" class="page space-y-5">
                <div class="grid gap-4 lg:grid-cols-3">
                    @foreach([['Basic plan','Default','180','120'],['VIP plan','Premium','150','90'],['Custom plan','Per merchant','160','100']] as $plan)
                        <div class="rounded-2xl border {{ $plan[0] === 'VIP plan' ? 'border-teal-300 ring-2 ring-teal-100' : 'border-slate-200' }} bg-white p-5 shadow-panel">
                            <div class="mb-4 flex items-center justify-between"><div class="text-lg font-black text-slate-950">{{ $plan[0] }}</div><span class="rounded-full bg-teal-50 px-2 py-1 text-[11px] font-bold text-teal-700">{{ $plan[1] }}</span></div>
                            <table class="w-full text-sm">
                                <thead class="text-left text-xs text-slate-500"><tr><th class="pb-2">City</th><th class="pb-2">Forward</th><th class="pb-2">Return</th></tr></thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach(['Karachi','Lahore','Islamabad','Other'] as $city)
                                        <tr><td class="py-2 font-bold">{{ $city }}</td><td><input class="w-20 rounded-lg border border-slate-200 px-2 py-1 text-right text-xs font-bold" value="{{ $plan[2] }}"></td><td><input class="w-20 rounded-lg border border-slate-200 px-2 py-1 text-right text-xs font-bold" value="{{ $plan[3] }}"></td></tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button onclick="showToast('{{ $plan[0] }} saved')" class="mt-4 w-full rounded-xl bg-teal-600 px-4 py-2 text-sm font-black text-white">Save plan</button>
                        </div>
                    @endforeach
                </div>
            </section>

            <section id="page-profit" class="page space-y-5">
                <div class="grid gap-5 lg:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-panel">
                        <div class="text-sm font-black text-slate-950">Profit engine</div>
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between"><span class="text-slate-500">Merchant delivery revenue</span><span class="font-bold">Rs 485,000</span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Courier cost actual</span><span class="font-bold text-rose-700">Rs 360,000</span></div>
                            <div class="flex justify-between border-t border-slate-100 pt-3"><span class="font-black">Gross profit</span><span class="font-black text-emerald-700">Rs 125,000</span></div>
                            <div class="flex justify-between"><span class="text-slate-500">Our 2% tax remaining</span><span class="font-bold text-rose-700">Rs 7,680</span></div>
                            <div class="flex justify-between border-t border-slate-100 pt-3 text-base"><span class="font-black">Net profit</span><span class="font-black text-emerald-700">Rs 117,320</span></div>
                        </div>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-panel">
                        <div class="text-sm font-black text-slate-950">Per courier profit</div>
                        <div class="mt-4 space-y-3 text-sm">
                            @foreach([['Leopards','Rs 28,600'],['TCS','Rs 15,200'],['M&P','Rs 17,500'],['Call Courier','Rs 10,800'],['BlueEx','Rs 7,000']] as $row)
                                <div class="flex justify-between"><span class="text-slate-500">{{ $row[0] }}</span><span class="font-bold text-emerald-700">{{ $row[1] }}</span></div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>

            <section id="page-tax" class="page space-y-5">
                <div class="grid gap-5 lg:grid-cols-[.8fr_1.2fr]">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-panel">
                        <div class="text-lg font-black text-slate-950">Tax calculator</div>
                        <label class="mt-4 block text-xs font-bold uppercase tracking-wide text-slate-500">COD amount</label>
                        <input id="codInput" type="number" value="5000" class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-lg font-black" oninput="calcTax()">
                        <button onclick="calcTax();showToast('Tax calculated')" class="mt-4 w-full rounded-xl bg-teal-600 py-3 text-sm font-black text-white">Calculate</button>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-panel">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs font-bold text-slate-500">Tax collected 4%</div><div id="tax4" class="mt-1 text-2xl font-black">Rs 200</div></div>
                            <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs font-bold text-slate-500">Courier tax 2%</div><div id="tax2c" class="mt-1 text-2xl font-black text-rose-700">Rs 100</div></div>
                            <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs font-bold text-slate-500">Our tax 2%</div><div id="tax2o" class="mt-1 text-2xl font-black text-amber-700">Rs 100</div></div>
                            <div class="rounded-xl bg-slate-50 p-4"><div class="text-xs font-bold text-slate-500">Merchant net after tax</div><div id="taxNet" class="mt-1 text-2xl font-black text-emerald-700">Rs 4,800</div></div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="page-notif" class="page space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-panel">
                    <div class="mb-4 text-lg font-black text-slate-950">Send notification</div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-slate-500">Audience</label>
                            <select class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold">
                                <option>All merchants</option>
                                <option>Pending merchants</option>
                                <option>COD settlement pending</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wide text-slate-500">Channel</label>
                            <select class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold">
                                <option>WhatsApp and portal</option>
                                <option>Portal only</option>
                                <option>Email</option>
                            </select>
                        </div>
                    </div>
                    <label class="mt-4 block text-xs font-bold uppercase tracking-wide text-slate-500">Message</label>
                    <textarea class="mt-2 min-h-32 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm" placeholder="Write notification message...">Your COD settlement has been processed. Please check your invoice section.</textarea>
                    <button onclick="showToast('Notification sent')" class="mt-4 rounded-xl bg-teal-600 px-5 py-3 text-sm font-black text-white">Send notification</button>
                </div>
            </section>
        </main>
    </div>
</div>

<script>
    const pages = {
        dashboard: 'Dashboard',
        orders: 'Orders',
        cod: 'COD & Settlement',
        invoices: 'Invoices',
        merchants: 'Merchants',
        couriers: 'Couriers',
        pricing: 'Pricing Plans',
        profit: 'Profit Report',
        tax: 'Tax Engine',
        notif: 'Notifications'
    };

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    document.querySelectorAll('[data-page]').forEach((button) => {
        button.addEventListener('click', () => {
            const id = button.dataset.page;
            document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            document.getElementById('page-' + id).classList.add('active');
            button.classList.add('active');
            document.getElementById('pageTitle').textContent = pages[id];
            closeMobileNav();
        });
    });

    document.querySelectorAll('.filter-btn').forEach((button) => {
        button.addEventListener('click', () => {
            const group = button.parentElement;
            group.querySelectorAll('.filter-btn').forEach(item => item.classList.remove('active'));
            button.classList.add('active');
            showToast(button.textContent.trim() + ' filter applied');
        });
    });

    document.getElementById('menuBtn').addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
    });
    overlay.addEventListener('click', closeMobileNav);

    function closeMobileNav() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.remove('hidden');
        clearTimeout(window.toastTimer);
        window.toastTimer = setTimeout(() => toast.classList.add('hidden'), 2200);
    }

    function calcTax() {
        const cod = Number(document.getElementById('codInput').value || 0);
        const tax4 = Math.round(cod * 0.04);
        const tax2 = Math.round(cod * 0.02);
        document.getElementById('tax4').textContent = 'Rs ' + tax4.toLocaleString();
        document.getElementById('tax2c').textContent = 'Rs ' + tax2.toLocaleString();
        document.getElementById('tax2o').textContent = 'Rs ' + tax2.toLocaleString();
        document.getElementById('taxNet').textContent = 'Rs ' + Math.round(cod - tax4).toLocaleString();
    }
</script>
</body>
</html>
