<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>3PL Admin</title>
    @vite([])
    <link rel="stylesheet" href="{{ asset('css/micro-erp.css') }}"/>
    <link rel="stylesheet" href="{{ asset('filament/../css/micro-erp.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/custom-erp.css') }}"/>
    <link rel="stylesheet" href="{{ asset('css/filament/micro-erp.css') }}"/>
    <style>
        /* fallback when asset path not found */
        {{ file_exists(public_path('css/micro-erp.css')) ? '' : '' }}
    </style>
</head>
<body>
<div class="layout">
    <div class="sidebar">
        <div class="logo">
            <div class="logo-title"><i class="ti ti-package" style="font-size:13px;margin-right:4px" aria-hidden="true"></i>3PL Admin</div>
            <div class="logo-sub">Logistics ERP</div>
        </div>
        <div class="nav-group">
            <div class="nav-lbl">Main</div>
            <div class="nav-item {{ request()->routeIs('filament.admin.pages.admin-dashboard') ? 'active' : '' }}" onclick="go('dashboard',this)"><i class="ti ti-layout-dashboard" aria-hidden="true"></i>Dashboard</div>
            <div class="nav-item" onclick="go('orders',this)"><i class="ti ti-package" aria-hidden="true"></i>Orders</div>
            <div class="nav-item" onclick="go('cod',this)"><i class="ti ti-cash" aria-hidden="true"></i>COD &amp; Settlement</div>
            <div class="nav-item" onclick="go('invoices',this)"><i class="ti ti-file-invoice" aria-hidden="true"></i>Invoices</div>
        </div>
        <div class="nav-group">
            <div class="nav-lbl">People</div>
            <div class="nav-item" onclick="go('merchants',this)"><i class="ti ti-building-store" aria-hidden="true"></i>Merchants</div>
            <div class="nav-item" onclick="go('couriers',this)"><i class="ti ti-truck" aria-hidden="true"></i>Couriers</div>
        </div>
        <div class="nav-group">
            <div class="nav-lbl">Finance</div>
            <div class="nav-item" onclick="go('pricing',this)"><i class="ti ti-tag" aria-hidden="true"></i>Pricing Plans</div>
            <div class="nav-item" onclick="go('profit',this)"><i class="ti ti-chart-bar" aria-hidden="true"></i>Profit Report</div>
            <div class="nav-item" onclick="go('tax',this)"><i class="ti ti-percentage" aria-hidden="true"></i>Tax Engine</div>
        </div>
        <div class="nav-group">
            <div class="nav-lbl">Tools</div>
            <div class="nav-item" onclick="go('notif',this)"><i class="ti ti-bell" aria-hidden="true"></i>Notifications</div>
        </div>
    </div>

    <div class="main">
        <div class="topbar" style="position:relative">
            <div class="topbar-title" id="ptitle">{{ $title ?? 'Dashboard' }}</div>
            <div class="toast" id="main-toast"></div>
            <div style="width:28px;height:28px;border-radius:50%;background:var(--color-background-info);color:var(--color-text-info);font-size:11px;font-weight:500;display:flex;align-items:center;justify-content:center;flex-shrink:0">SA</div>
        </div>

        <div class="content">
            <div class="page active" id="page-dashboard">
                {!! $slot ?? '' !!}
            </div>
        </div>
    </div>
</div>

<script>
    const pages = {dashboard:'Dashboard',orders:'Orders',cod:'COD & Settlement',invoices:'Invoices',merchants:'Merchants',couriers:'Couriers',pricing:'Pricing Plans',profit:'Profit Report',tax:'Tax Engine',notif:'Notifications'};
    function go(id,el){
        document.querySelectorAll('.page').forEach(p=>p.classList.remove('active'));
        if(document.getElementById('page-'+id)) document.getElementById('page-'+id).classList.add('active');
        document.querySelectorAll('.nav-item').forEach(n=>n.classList.remove('active'));
        if(el) el.classList.add('active');
        document.getElementById('ptitle').textContent=pages[id] || 'Dashboard';
    }
    function showToast(msg){
        const t=document.getElementById('main-toast');
        if(!t) return;
        t.textContent=msg;t.style.display='block';
        setTimeout(()=>t.style.display='none',2200);
    }
</script>
</body>
</html>

