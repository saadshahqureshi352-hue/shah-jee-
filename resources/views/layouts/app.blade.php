<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Shah Jee Courier') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN (Laragon اور VS Code کے لیے بغیر کسی کمانڈ کے ڈائریکٹ چلے گا) -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        
        /* Sidebar slide-in animations */
        .sidebar-link {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-link:hover {
            transform: translateX(4px);
            background: rgba(255,255,255,0.06);
        }
        .sidebar-link-active {
            background: linear-gradient(135deg, rgba(13,148,136,0.2), rgba(8,145,178,0.15));
            border-left: 3px solid #0d9488;
            color: #fff;
        }
        .sidebar-sub-link {
            transition: all 0.2s ease;
        }
        .sidebar-sub-link:hover {
            transform: translateX(6px);
            color: #fff;
        }
        .sidebar-sub-link-active {
            color: #2dd4bf !important;
            background: rgba(13,148,136,0.12);
            border-radius: 0.5rem;
        }
        .menu-chevron {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .menu-chevron.open {
            transform: rotate(180deg);
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes bounceIn {
            0% { opacity: 0; transform: scale(0.9); }
            50% { transform: scale(1.03); }
            100% { opacity: 1; transform: scale(1); }
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* WHATSAPP CUSTOM PULSE & GLOW ANIMATION */
        @keyframes whatsappGlowPulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
            }
            50% {
                transform: scale(1.06);
                box-shadow: 0 0 14px 6px rgba(16, 185, 129, 0.25);
            }
        }
        .animate-whatsapp {
            animation: whatsappGlowPulse 2s infinite ease-in-out;
        }
        
        .fade-in-up { animation: fadeInUp 0.5s ease-out both; }
        .fade-in-up-delay-1 { animation: fadeInUp 0.5s ease-out 0.1s both; }
        .fade-in-up-delay-2 { animation: fadeInUp 0.5s ease-out 0.2s both; }
        .fade-in-up-delay-3 { animation: fadeInUp 0.5s ease-out 0.3s both; }
        .slide-in-left { animation: slideInLeft 0.5s ease-out both; }
        .slide-in-right { animation: slideInRight 0.5s ease-out both; }
        .bounce-in { animation: bounceIn 0.4s ease-out both; }
        .nav-slide { animation: slideDown 0.25s ease-out; }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .card-hover-teal:hover {
            border-color: #0d9488;
            box-shadow: 0 4px 20px rgba(13,148,136,0.2);
        }
        .transition-all-300 {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-scale:hover { transform: scale(1.05); }
        .hover-scale-sm:hover { transform: scale(1.03); }
        
        .hover-shadow-glow:hover {
            box-shadow: 0 0 25px rgba(13,148,136,0.15);
        }
        
        /* Elegant Custom Scrollbar for Notification List */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <script>
        function copyToClipboard(text, btn) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopyFeedback(btn);
                }).catch(() => {
                    fallbackCopy(text, btn);
                });
            } else {
                fallbackCopy(text, btn);
            }
        }
        function fallbackCopy(text, btn) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();
            try {
                document.execCommand('copy');
                showCopyFeedback(btn);
            } catch (e) {}
            document.body.removeChild(textarea);
        }
        function showCopyFeedback(btn) {
            const original = btn.innerHTML;
            btn.innerHTML = '<span class="text-emerald-600">✓ Copied!</span>';
            setTimeout(() => { btn.innerHTML = original; }, 2000);
        }

        function cancelOrder(bookingId) {
            if (!confirm('Are you sure you want to cancel this order? This action cannot be undone.')) return;
            
            fetch('/bookings/' + bookingId + '/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ reason: 'Cancelled by shipper' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.ok) {
                    alert('✓ Order cancelled successfully.');
                    location.reload();
                } else {
                    alert('✗ ' + (data.message || 'Failed to cancel order.'));
                }
            })
            .catch(() => {
                alert('✗ Network error. Please try again.');
            });
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased" x-data="{ sidebarOpen: false }" @open-sidebar.window="sidebarOpen = true">

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-slate-900/60 lg:hidden"></div>

    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-gradient-to-b from-slate-950 via-slate-900 to-teal-950 text-slate-200 shadow-2xl transition-transform duration-300 lg:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-800/50 px-5 bg-gradient-to-r from-slate-900 to-slate-800">
            <div class="flex items-center">
                <div class="relative">
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-orange-400 to-rose-500 blur-lg opacity-40 animate-pulse"></div>
                    <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="relative h-14 w-14 rounded-2xl object-cover ring-2 ring-orange-300/60 shadow-xl hover:scale-110 transition-transform duration-300">
                </div>
            </div>
            <button type="button" @click="sidebarOpen = false" class="rounded-lg p-1 text-xl text-slate-400 hover:bg-slate-800 hover:text-white transition-all lg:hidden" aria-label="Close">&times;</button>
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
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-teal-600 to-cyan-600 text-white shadow-lg shadow-teal-500/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }} mb-1 flex items-center gap-3 rounded-lg px-3 py-2.5 font-medium transition-all-300 hover:scale-105">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Dashboard
            </a>

            <button type="button" @click="open.network = !open.network" class="{{ $menuButton }} mt-1 {{ $networkOpen ? 'bg-slate-800 text-white' : '' }} hover:scale-105">
                <span class="flex items-center gap-3"><svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10m0 0h10m-10 0l-2 8m12-8l2 8M5 16h14M17 16V8m0 8l2-8m-2 8H7"/></svg>Courier Network</span>
                <svg class="h-4 w-4 shrink-0 transition-all-300" :class="open.network && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="open.network" x-cloak class="mt-1 space-y-0.5">
                <a href="{{ route('pickup-addresses.index') }}" class="{{ request()->routeIs('pickup-addresses.*') ? $subLinkActive : $subLink }} transition-all-300 hover:translate-x-1">My Pickup Address</a>
                <a href="{{ route('bookings') }}" class="{{ request()->routeIs('bookings', 'bookings.*') ? $subLinkActive : $subLink }} transition-all-300 hover:translate-x-1">My All Orders</a>
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
        <header class="fixed top-0 right-0 left-0 z-30 flex h-16 items-center justify-between bg-gradient-to-r from-slate-800 via-slate-700 to-teal-600 px-4 text-white shadow-2xl lg:left-64">
            <div class="flex items-center gap-3">
                <button type="button" @click="sidebarOpen = true" class="rounded-lg bg-white/10 p-2 lg:hidden hover:bg-white/20 transition-all-300 hover:scale-110" aria-label="Open menu"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg></button>
                <h1 class="text-base font-bold sm:text-lg flex items-center gap-2">
                    <span class="hidden sm:inline">Welcome to</span> 
                    <span class="bg-gradient-to-r from-orange-300 to-rose-300 bg-clip-text text-transparent font-black">Shah Jee Courier</span>
                </h1>
            </div>
            <div class="relative flex items-center gap-4" x-data="{ 
                profileOpen: false, 
                notifyOpen: false, 
                sendNotifyOpen: false, 
                newNotifyMsg: '', 
                notifyRecipient: '',
                notifications: [
                    { id: 1, title: 'New booking received', message: 'Order #SJC0000000123 has been booked successfully.', time: '2 min ago', seen: false, icon: '📦' },
                    { id: 2, title: 'Delivery completed', message: 'Order #SJC0000000098 was delivered to Karachi.', time: '1 hour ago', seen: false, icon: '✅' },
                    { id: 3, title: 'Payment credited', message: 'Rs. 5,000 has been added to your wallet.', time: '3 hours ago', seen: true, icon: '💰' },
                    { id: 4, title: 'Return request', message: 'Order #SJC0000000076 marked as returned.', time: 'Yesterday', seen: true, icon: '↩️' },
                    { id: 5, title: 'New shipper joined', message: 'A new shipper has registered under your network.', time: '2 days ago', seen: true, icon: '👤' },
                ],
                unreadCount() { return this.notifications.filter(n => !n.seen).length; },
                markAllRead() { this.notifications.forEach(n => n.seen = true); },
                sendNotification() { 
                    if(this.newNotifyMsg.trim() === '') return; 
                    this.notifications.unshift({ id: Date.now(), title: 'Manual Notification', message: this.newNotifyMsg, time: 'Just now', seen: false, icon: '📢' }); 
                    this.newNotifyMsg = ''; 
                    this.sendNotifyOpen = false; 
                } 
            }">
                {{-- Animated WhatsApp Support Button --}}
                <a href="https://wa.me/923197290092" 
                   target="_blank" 
                   rel="noopener noreferrer" 
                   class="relative flex items-center justify-center w-9 h-9 rounded-full bg-emerald-500 text-white shadow-md transition-all duration-200 animate-whatsapp hover:scale-115 hover:bg-emerald-600"
                   title="Contact Support on WhatsApp">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.454 5.709 1.455h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                </a>

                {{-- Notification Bell --}}
                <button type="button" @click="notifyOpen = !notifyOpen; profileOpen = false" class="relative rounded-full p-2 hover:bg-white/10 transition-all-300 hover:scale-110" aria-label="Notifications">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span x-show="unreadCount() > 0" x-text="unreadCount()" class="absolute -top-0.5 -right-0.5 h-5 w-5 flex items-center justify-center rounded-full bg-rose-500 text-[10px] font-bold text-white animate-pulse"></span>
                </button>

                {{-- Notification Dropdown Popup (STRICT FIX FOR LARAGON) --}}
                <div x-show="notifyOpen" @click.outside="notifyOpen = false" x-cloak
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="height: 380px !important; max-height: 380px !important;"
                     class="absolute right-0 top-14 z-50 w-80 sm:w-96 flex flex-col rounded-2xl border border-slate-200 bg-white shadow-2xl overflow-hidden">
                    
                    {{-- Header --}}
                    <div class="flex h-14 shrink-0 items-center justify-between border-b border-slate-100 px-5 bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex items-center gap-2">
                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-rose-400 to-pink-500 text-white shadow-md">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </span>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Notifications</p>
                                <p class="text-[11px] text-slate-500"><span x-text="unreadCount()">0</span> unread</p>
                            </div>
                        </div>
                        <button @click="markAllRead()" class="text-[11px] font-semibold text-rose-500 hover:text-rose-700 transition">Mark all read</button>
                    </div>

                    {{-- Notification Scrollable Area --}}
                    <div class="flex-1 overflow-y-auto divide-y divide-slate-50 custom-scrollbar bg-white">
                        <template x-for="notif in notifications" :key="notif.id">
                            <div class="flex gap-3 px-5 py-3.5 transition hover:bg-slate-50 cursor-pointer"
                                 :class="notif.seen ? 'bg-white' : 'bg-rose-50/60'">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-lg"
                                      :class="notif.seen ? 'bg-slate-100' : 'bg-rose-100 shadow-sm'"
                                      x-text="notif.icon"></span>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <p class="text-sm font-semibold text-slate-800 truncate" x-text="notif.title"></p>
                                        <span x-show="!notif.seen" class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-rose-500"></span>
                                    </div>
                                    <p class="mt-0.5 text-xs text-slate-500 line-clamp-2" x-text="notif.message"></p>
                                    <p class="mt-1.5 text-[11px] font-medium text-slate-400" x-text="notif.time"></p>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="notifications.length === 0" class="flex flex-col items-center justify-center h-full text-center py-10">
                            <span class="text-4xl mb-2">🔔</span>
                            <p class="text-sm font-semibold text-slate-500">No notifications</p>
                            <p class="text-[11px] text-slate-400 mt-1">You're all caught up!</p>
                        </div>
                    </div>

                    {{-- Footer: Send Notification Button --}}
                    <div class="h-12 shrink-0 border-t border-slate-100 bg-slate-50 px-4 flex items-center gap-2">
                        <button @click="sendNotifyOpen = !sendNotifyOpen" 
                                class="flex-1 flex items-center justify-center gap-1.5 rounded-lg bg-gradient-to-r from-rose-500 to-pink-600 px-3 py-1.5 text-xs font-bold text-white shadow-md hover:from-rose-400 hover:to-pink-500 transition-all-300">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Send Notification
                        </button>
                    </div>

                    {{-- Send Notification Form Popup Layer --}}
                    <div x-show="sendNotifyOpen" x-cloak class="absolute inset-x-0 bottom-0 top-14 z-50 flex flex-col bg-white px-4 py-3 border-t border-slate-200">
                        <p class="text-xs font-bold text-slate-700 mb-2">Send Notification to Shipper</p>
                        <input type="text" x-model="notifyRecipient" placeholder="Recipient name or ID"
                               class="w-full mb-2 rounded-lg border border-slate-200 px-3 py-1.5 text-xs focus:border-rose-400 focus:ring-rose-400">
                        <textarea x-model="newNotifyMsg" rows="3" placeholder="Type your message..."
                                  class="w-full mb-2 flex-1 rounded-lg border border-slate-200 px-3 py-1.5 text-xs focus:border-rose-400 focus:ring-rose-400 resize-none"></textarea>
                        <div class="flex gap-2 shrink-0 mt-auto">
                            <button @click="sendNotification()" 
                                    class="flex-1 rounded-lg bg-gradient-to-r from-rose-500 to-pink-600 py-2 text-xs font-bold text-white hover:from-rose-400 hover:to-pink-500 transition">
                                Send Message
                            </button>
                            <button @click="sendNotifyOpen = false" 
                                    class="rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-500 hover:bg-slate-50 transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>

                {{-- User Profile Section --}}
                <div class="flex items-center gap-3">
                    <div class="hidden text-right text-sm leading-tight sm:block">
                        <p class="font-semibold">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-teal-100/80">{{ \Illuminate\Support\Str::before(auth()->user()->email ?? '', '@') }}</p>
                    </div>
                    <button type="button" @click="profileOpen = !profileOpen" class="flex items-center gap-2 rounded-full focus:outline-none focus:ring-2 focus:ring-teal-300 transition-all-300 hover:scale-105">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name ?? 'U') }}&background=0d9488&color=fff" alt="" class="h-10 w-10 rounded-full border-2 border-white/30 object-cover hover:border-white/60 transition-all">
                        <svg class="hidden h-4 w-4 sm:block transition-transform" :class="profileOpen && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                </div>

                {{-- Profile Dropdown --}}
                <div x-show="profileOpen" 
                     @click.outside="profileOpen = false" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     x-cloak class="absolute right-0 top-14 z-50 w-48 rounded-xl border border-slate-200 bg-white py-2 text-slate-700 shadow-2xl">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-sm hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 transition-all-300 hover:translate-x-1">Profile</a>
                    <a href="{{ route('settings') }}" class="block px-4 py-2.5 text-sm hover:bg-gradient-to-r hover:from-teal-50 hover:to-cyan-50 transition-all-300 hover:translate-x-1">Settings</a>
                    <div class="my-1 border-t border-slate-100"></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="block w-full px-4 py-2.5 text-left text-sm text-red-600 hover:bg-red-50 transition-all-300 hover:translate-x-1 font-semibold">Log out</button></form>
                </div>
            </div>
        </header>

        <main class="min-h-screen pt-16 bg-gradient-to-br from-slate-50 via-white to-teal-50">
            @if(session('success'))
                <div class="mx-4 mt-4 rounded-xl border-l-4 border-emerald-500 bg-gradient-to-r from-emerald-50 to-teal-50 px-5 py-4 text-sm font-semibold text-emerald-800 shadow-lg sm:mx-6 bounce-in flex items-center gap-3">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>
</body>
</html>