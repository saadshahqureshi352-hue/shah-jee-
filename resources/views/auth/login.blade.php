<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 antialiased">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden p-4">
        <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-orange-200/60 blur-3xl"></div>
        <div class="absolute -bottom-20 -right-20 h-72 w-72 rounded-full bg-blue-200/60 blur-3xl"></div>

        <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white/95 p-8 shadow-2xl backdrop-blur">
            <div class="mb-6 text-center">
                <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="mx-auto h-24 w-24 rounded-2xl object-cover ring-2 ring-orange-200 animate-pulse">
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-800">Welcome to <span class="text-orange-500">Shah Jee Courier</span></h1>
                <p class="mt-2 text-sm text-slate-500">Clients Portal</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />
            @if ($errors->any())
                <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="login" class="mb-1 block text-xs font-semibold uppercase text-slate-500">Email / Username / Phone</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-orange-400 focus:ring-orange-400" placeholder="email, username or phone">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-xs font-semibold uppercase text-slate-500">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm focus:border-orange-400 focus:ring-orange-400" placeholder="Password">
                </div>

                <button type="submit" class="w-full rounded-xl bg-orange-500 py-3 text-base font-bold text-white transition hover:bg-orange-600">Log In</button>
            </form>

            <div class="mt-4 flex items-center justify-between text-sm">
                @if (Route::has('password.request'))
                    <a class="text-orange-500 hover:underline" href="{{ route('password.request') }}">Forgot password?</a>
                @endif
                <a href="{{ route('register') }}" class="font-semibold text-slate-700 hover:text-orange-500">Register New Account</a>
            </div>
        </div>
    </div>
</body>
</html>