<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0d9488 100%);">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden p-4">
        <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 opacity-15 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 opacity-15 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 opacity-10 blur-3xl float"></div>
        <div class="absolute -right-10 -top-10 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-80 w-80 rounded-full bg-black/10 blur-3xl"></div>

        <div class="w-full max-w-lg scale-in">
            <div class="rounded-3xl border-2 border-white/20 bg-white/95 backdrop-blur-xl p-10 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="flex justify-center mb-6">
                        <div class="relative">
                            <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-orange-400 to-rose-500 blur-xl opacity-50 animate-pulse"></div>
                            <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="relative h-20 w-20 rounded-2xl object-cover ring-4 ring-white shadow-2xl">
                        </div>
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight mb-1">
                        <span class="bg-gradient-to-r from-slate-800 via-teal-700 to-slate-800 bg-clip-text text-transparent">Forgot Password?</span>
                    </h1>
                    <p class="text-sm text-slate-500 mt-1">Enter your email to receive OTP</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 rounded-xl border-l-4 border-emerald-500 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="mb-6 rounded-xl border-l-4 border-rose-500 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.send') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input type="email" name="identifier" value="{{ old('identifier') }}" required autofocus
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-12 pr-4 py-3.5 text-sm font-medium transition-all-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20"
                                placeholder="your.email@example.com">
                        </div>
                    </div>
                    <button type="submit" class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 py-4 text-base font-bold text-white shadow-xl transition-all-300 hover:shadow-2xl hover:scale-105">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Send OTP
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors">← Back to Login</a>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-200 text-center">
                    <p class="text-xs text-slate-400">OTP will be sent to your email address</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>