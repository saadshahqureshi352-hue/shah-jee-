<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Affiliate Login — Shah Jee Courier</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased flex items-center justify-center p-4" style="background: radial-gradient(1200px 800px at 20% 20%, rgba(255,122,0,0.20), transparent 55%), radial-gradient(900px 600px at 80% 30%, rgba(16,185,129,0.15), transparent 50%), linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0d9488 100%);">

    <div class="w-full max-w-md">
        <div class="bg-white/95 backdrop-blur-xl border border-white/20 rounded-2xl shadow-2xl p-6">

            <div class="text-center mb-6">
                <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-orange-500 to-rose-500 p-1 shadow-xl">
                    <div class="h-full w-full rounded-xl bg-white flex items-center justify-center text-3xl font-extrabold text-orange-600">SJC</div>
                </div>
                <h1 class="mt-3 text-2xl font-extrabold text-slate-800">Affiliate Dashboard</h1>
                <p class="text-sm text-slate-600 font-semibold mt-1">Login as an affiliate agent</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            @if ($errors->any())
                <div class="mb-5 rounded-xl border-l-4 border-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <div class="font-semibold">{{ $errors->first() }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('affiliate.login.submit') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-600">Email / Username / Phone</label>
                    <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                           class="mt-2 w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20" 
                           placeholder="Enter your email, username or phone" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600">Password</label>
                    <input id="password" name="password" type="password" required
                           class="mt-2 w-full rounded-xl border-2 border-slate-200 bg-white px-4 py-3 text-sm font-medium focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20" 
                           placeholder="Enter your password" />
                </div>

                <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-orange-500 via-rose-500 to-orange-600 py-4 text-base font-bold text-white shadow-xl transition hover:shadow-2xl hover:scale-[1.01]">
                    Login
                </button>

                <div class="text-center text-xs text-slate-500 font-medium pt-2">
                    Affiliate credentials are separate from the client portal.
                </div>
            </form>

        </div>
    </div>
</body>
</html>

