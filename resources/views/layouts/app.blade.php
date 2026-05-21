<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shah Jee Courier') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>[x-cloak] { display: none !important; } body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }" @open-sidebar.window="sidebarOpen = true">

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"></div>

    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-gradient-to-b from-slate-950 via-slate-900 to-blue-950 text-slate-200 shadow-xl transition-transform duration-200 lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-800 px-5">
            <div class="flex items-center">
                <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="h-14 w-14 rounded-2xl object-cover ring-2 ring-orange-300/60 shadow-lg shadow-orange-500/20">
            </div>
            <button type="button" @click="sidebarOpen = false" class="rounded-lg p-1 text-xl text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden" aria-label="Close">&times;</button>
        </div>

        @php
            $networkOpen = request()->routeIs('pickup-addresses.*', 'bookings', 'bookings.*');
            $paymentsOpen = request()->routeIs('payments', 'payments.*');
            $smartOpen = request()->routeIs('smart-tools', 'smart-tools.*');
            $adminEmails = config('finance.admin_emails', []);
            $isAdmin = in_array((string) auth()->user()?->email, $adminEmails, true) || (int) auth()->id() === 1;
            $menuButton = 'flex w-full items-center justify-between rounded-lg px-3 py-2.5 text-slate-300 transition hover:bg-slate-800 hover:text-white';
            $subLink = 'block rounded-lg py-2 pl-11 pr-3 text-slate-400 hover:bg-slate-800 hover:text-white';
            $subLinkActive = 'block rounded-lg py-2 pl-11 pr-3 font-medium text-teal-400 bg-slate-800/80';
            $topLink = 'flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition';
        @endphp

        <nav class="flex-1 overflow-y-auto px-3 py-4 text-sm" x-data="{ open: { network: {{ $networkOpen ? 'true' : 'false' }}, payments: {{ $paymentsOpen ? 'true' : 'false' }}, smart: {{ $smartOpen ? 'true' : 'false' }} } }">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <button type="button" @click="open.network = !open.network" class="{{ $menuButton }} mt-1 {{ $networkOpen ? 'bg-slate-800 text-white' : '' }}">
                <span class="flex items-center gap-3"><svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0h10m-10 0l-2 8m12-8l2 8M5 16h14M17 16V8m0 8l2-8m-2 8H7"/></svg>Courier Network</span>
                <svg class="h-4 w-4 shrink-0 transition" :class="open.network && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open.network" x-cloak class="mt-1 space-y-0.5">
                <a href="{{ route('pickup-addresses.index') }}" class="{{ request()->routeIs('pickup-addresses.*') ? $subLinkActive : $subLink }}">My Pickup Address</a>
                <a href="{{ route('bookings') }}" class="{{ request()->routeIs('bookings', 'bookings.*') ? $subLinkActive : $subLink }}">My All Orders</a>
            </div>

            <button type="button" @click="open.payments = !open.payments" class="{{ $menuButton }} mt-1 {{ $paymentsOpen ? 'bg-slate-800 text-white' : '' }}">
                <span class="flex items-center gap-3"><svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>My Payments</span>
                <svg class="h-4 w-4 shrink-0 transition" :class="open.payments && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open.payments" x-cloak class="mt-1 space-y-0.5">
                <a href="{{ route('payments.overall-sales') }}" class="{{ request()->routeIs('payments.overall-sales') ? $subLinkActive : $subLink }}">Overall Sales</a>
                <a href="{{ route('payments.invoices') }}" class="{{ request()->routeIs('payments.invoices') ? $subLinkActive : $subLink }}">My Invoices</a>
                <a href="{{ route('payments.non-cod') }}" class="{{ request()->routeIs('payments.non-cod') ? $subLinkActive : $subLink }}">Add Payment for Non-COD</a>
            </div>

            <button type="button" @click="open.smart = !open.smart" class="{{ $menuButton }} mt-1 {{ $smartOpen ? 'bg-slate-800 text-white' : '' }}">
                <span class="flex items-center gap-3"><svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>Smart Tools</span>
                <svg class="h-4 w-4 shrink-0 transition" :class="open.smart && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open.smart" x-cloak class="mt-1 space-y-0.5">
                <a href="{{ route('smart-tools.tracking') }}" class="{{ request()->routeIs('smart-tools.tracking') ? $subLinkActive : $subLink }}"> Tracking System</a>
                <a href="{{ route('smart-tools.whatsapp-gateway') }}" class="{{ request()->routeIs('smart-tools.whatsapp-gateway') ? $subLinkActive : $subLink }}">WhatsApp API Gateway Setup</a>
                <a href="{{ route('smart-tools.alert-templates') }}" class="{{ request()->routeIs('smart-tools.alert-templates') ? $subLinkActive : $subLink }}">Alert Templates Settings</a>
            </div>

            @if($isAdmin)
                <a href="{{ route('admin.shippers.index') }}" class="{{ request()->routeIs('admin.shippers.*') ? 'bg-white text-teal-700 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} {{ $topLink }} mt-1">
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Admin Approvals
                </a>
            @endif
        </nav>
    </aside>

    <div class="lg:pl-64">
        <header class="fixed top-0 right-0 left-0 z-30 flex h-16 items-center justify-between bg-gradient-to-r from-slate-800 via-slate-700 to-teal-600 px-4 text-white shadow-lg lg:left-64">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="rounded-lg bg-white/10 p-2 lg:hidden" aria-label="Open menu"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                <h1 class="text-base font-semibold sm:text-lg">Welcome to Shah Jee Courier</h1>
            </div>
            <div class="relative flex items-center gap-4" x-data="{ profileOpen: false }">
                <button type="button" class="rounded-full p-2 hover:bg-white/10" aria-label="Notifications"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg></button>
                <div class="flex items-center gap-3">
                    <div class="hidden text-right text-sm leading-tight sm:block">
                        <p class="font-semibold">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-teal-100/80">{{ \Illuminate\Support\Str::before(auth()->user()->email ?? '', '@') }}</p>
                    </div>
                    <button type="button" @click="profileOpen = !profileOpen" class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-300">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=0d9488&color=fff" alt="" class="h-10 w-10 rounded-full border-2 border-white/30 object-cover">
                        <svg class="hidden h-4 w-4 sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>
                <div x-show="profileOpen" @click.outside="profileOpen = false" x-cloak class="absolute right-0 top-14 z-50 w-48 rounded-lg border border-slate-200 bg-white py-1 text-slate-700 shadow-xl">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Profile</a>
                    <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm hover:bg-slate-50">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-slate-50">Log out</button></form>
                </div>
            </div>
        </header>

        <main class="min-h-screen pt-16">
            @if(session('success'))
                <div class="mx-4 mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 sm:mx-6">{{ session('success') }}</div>
            @endif
            {{ $slot }}
        </main>
    </div>
</body>
</html>