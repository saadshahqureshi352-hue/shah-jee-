<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0d9488 100%);">
    <div class="relative flex flex-col md:flex-row items-center justify-center md:justify-between min-h-screen w-full overflow-hidden p-4 md:p-12 gap-8">
        <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 opacity-15 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 opacity-15 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 opacity-10 blur-3xl float"></div>
        <div class="absolute -right-10 -top-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-80 w-80 rounded-full bg-black/10 blur-3xl"></div>

        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 left-1/4 h-2 w-2 rounded-full bg-teal-400 opacity-60 float" style="animation-delay: 0.5s;"></div>
            <div class="absolute top-3/4 right-1/4 h-3 w-3 rounded-full bg-orange-400 opacity-40 float" style="animation-delay: 1.5s;"></div>
            <div class="absolute top-1/2 right-1/3 h-2 w-2 rounded-full bg-blue-400 opacity-50 float" style="animation-delay: 2s;"></div>
        </div>

        <div class="w-full md:w-[38%] max-w-lg scale-in max-h-[92vh] overflow-auto z-10 md:ml-16">
            <div class="rounded-3xl border-2 border-white/20 bg-white/95 backdrop-blur-xl p-10 shadow-2xl overflow-hidden">
                <div class="mb-8 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="relative h-28 w-28 flex items-center justify-center">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-orange-400 to-rose-500 blur-xl opacity-45 animate-pulse"></div>
                            <div class="relative h-24 w-24 bg-white p-2 rounded-2xl ring-4 ring-white shadow-2xl overflow-hidden flex items-center justify-center transition-transform duration-300 hover:scale-105">
                                <img src="{{ asset('images/logo.png') }}" alt="Shah Jee Courier" class="max-h-full max-w-full h-auto w-auto object-contain">
                            </div>
                        </div>
                    </div>
                    
                    <h1 class="text-4xl font-extrabold tracking-tight mb-2">
                        <span class="bg-gradient-to-r from-slate-800 via-teal-700 to-slate-800 bg-clip-text text-transparent">Welcome to</span>
                    </h1>
                    <h2 class="text-5xl font-black mb-3">
                        <span class="bg-gradient-to-r from-orange-500 via-rose-500 to-orange-600 bg-clip-text text-transparent gradient-animate">Shah Jee Courier</span>
                    </h2>
                    <p class="text-sm text-slate-600 font-semibold uppercase tracking-wider">Clients Portal</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border-l-4 border-rose-500 bg-gradient-to-r from-rose-50 to-red-50 px-5 py-4 text-sm text-rose-700 shadow-lg bounce-in">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="font-semibold">{{ $errors->first() }}</span>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="fade-in-up">
                        <label for="login" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Email / Username / Phone</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-12 pr-4 py-3.5 text-sm font-medium transition-all duration-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 hover:border-slate-300" 
                                placeholder="Enter your email, username or phone">
                        </div>
                    </div>
                    
                    <div class="fade-in-up" style="animation-delay: 0.1s;">
                        <label for="password" class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-12 pr-4 py-3.5 text-sm font-medium transition-all duration-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20 hover:border-slate-300" 
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <button type="submit" class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-orange-500 via-rose-500 to-orange-600 py-4 text-base font-bold text-white shadow-xl transition-all duration-300 hover:shadow-2xl hover:scale-105 ripple fade-in-up" style="animation-delay: 0.2s;">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3 3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            Login
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-600 via-rose-600 to-orange-700 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-between text-sm fade-in-up" style="animation-delay: 0.3s;">
                    <a class="group flex items-center gap-1 font-semibold text-teal-600 hover:text-teal-700 transition-colors" href="{{ route('otp.forgot.form') }}">
                        <svg class="h-4 w-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Forgot password?
                    </a>
                    <a href="{{ route('register') }}" class="group flex items-center gap-1 font-bold text-slate-700 hover:text-orange-600 transition-colors">
                        Register Account
                        <svg class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 text-center fade-in-up" style="animation-delay: 0.4s;">
                    <p class="text-xs text-slate-500 font-medium">
                        Secure & Encrypted Connection
                        <span class="inline-flex items-center gap-1 ml-2 text-emerald-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            Protected
                        </span>
                    </p>
                </div>
            </div>

            <div class="mt-6 text-center text-xs text-white/80 font-medium fade-in-up" style="animation-delay: 0.5s; flex-shrink-0;">
                <p>© 2026 Shah Jee Courier. All rights reserved.</p>
            </div>
        </div>

        <div class="hidden md:flex flex-col justify-center items-center w-full md:w-[48%] z-10 md:mr-16">
            <div class="relative w-full rounded-3xl border-2 border-white/20 bg-white/5 backdrop-blur-md p-2 shadow-2xl overflow-hidden min-h-[520px] md:min-h-[620px]">
                <div class="absolute inset-0 w-full h-full" id="ad-slider">
                    <img src="{{ asset('images/ad1.png') }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-100" id="slide-0" alt="Courier Promo 1">
                    <img src="{{ asset('images/ad2.png') }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0" id="slide-1" alt="Courier Promo 2">
                    <img src="{{ asset('images/ad3.jfif') }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0" id="slide-2" alt="Courier Promo 3">
                    <img src="{{ asset('images/ad4.png') }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0" id="slide-3" alt="Courier Promo 4">
                    <img src="{{ asset('images/ad5.png') }}" class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0" id="slide-4" alt="Courier Promo 5">
                </div>
            </div>

            <div class="w-full mt-5 rounded-2xl bg-black/40 backdrop-blur-md border border-white/10 p-5 text-white shadow-xl">
                <h3 class="text-xl font-bold mb-1 tracking-wide" id="slide-title">Fast & Secure Delivery</h3>
                <p class="text-sm text-white/80" id="slide-desc">Shah Jee Courier provides real-time tracking, fast delivery network, and secure logistics management.</p>
            </div>

            <div class="flex gap-2.5 mt-4">
                <span class="h-2.5 w-8 rounded-full bg-orange-500 transition-all duration-300" id="dot-0"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/40 transition-all duration-300" id="dot-1"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/40 transition-all duration-300" id="dot-2"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/40 transition-all duration-300" id="dot-3"></span>
                <span class="h-2.5 w-2.5 rounded-full bg-white/40 transition-all duration-300" id="dot-4"></span>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slideCount = 5;
            let currentSlide = 0;

            const slides = [
                document.getElementById('slide-0'),
                document.getElementById('slide-1'),
                document.getElementById('slide-2'),
                document.getElementById('slide-3'),
                document.getElementById('slide-4')
            ];

            const dots = [
                document.getElementById('dot-0'),
                document.getElementById('dot-1'),
                document.getElementById('dot-2'),
                document.getElementById('dot-3'),
                document.getElementById('dot-4')
            ];

            const titles = [
                "Fast & Secure Delivery",
                "Wide Reliable Network",
                "Affordable COD Rates",
                "Advanced Merchant Dashboard",
                "24/7 Customer Support"
            ];

            const descriptions = [
                "Shah Jee Courier provides real-time tracking, fast delivery network, and secure logistics management.",
                "Delivering happiness across cities with top-notch security and verified tracking at every hub step.",
                "Optimize your e-commerce with our automated cash on delivery service and instant payments.",
                "Take control of your booking, tracking, pricing matrices, and payout schedules in one simple screen.",
                "Need help anytime? Our support team stays available 24/7 to assist with your shipments."
            ];

            const titleEl = document.getElementById('slide-title');
            const descEl = document.getElementById('slide-desc');

            const changeSlide = (index) => {
                // Remove active classes and hide previous
                slides[currentSlide].classList.remove('opacity-100');
                slides[currentSlide].classList.add('opacity-0');
                dots[currentSlide].className = 'h-2.5 w-2.5 rounded-full bg-white/40 transition-all duration-300';

                // Show new active
                currentSlide = index;
                slides[currentSlide].classList.remove('opacity-0');
                slides[currentSlide].classList.add('opacity-100');
                dots[currentSlide].className = 'h-2.5 w-8 rounded-full bg-orange-500 transition-all duration-300';

                // Update texts with a subtle fade-out/fade-in
                titleEl.style.opacity = 0;
                descEl.style.opacity = 0;
                setTimeout(() => {
                    titleEl.innerText = titles[currentSlide];
                    descEl.innerText = descriptions[currentSlide];
                    titleEl.style.opacity = 1;
                    descEl.style.opacity = 1;
                }, 300);
            };

            // Setup automated interval
            setInterval(() => {
                let nextSlide = (currentSlide + 1) % slideCount;
                changeSlide(nextSlide);
            }, 5000);

            // Add click events to dots
            dots.forEach((dot, idx) => {
                dot.style.cursor = 'pointer';
                dot.addEventListener('click', () => {
                    if (idx !== currentSlide) {
                        changeSlide(idx);
                    }
                });
            });

            // Set dynamic transition for title/desc elements
            titleEl.style.transition = 'opacity 0.3s ease-in-out';
            descEl.style.transition = 'opacity 0.3s ease-in-out';
        });
    </script>
</body>
</html>