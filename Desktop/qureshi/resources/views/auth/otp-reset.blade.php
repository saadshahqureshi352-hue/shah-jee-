<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen antialiased" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 40%, #0d9488 100%);">
    <div class="relative flex min-h-screen items-center justify-center overflow-hidden p-4">
        <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-gradient-to-br from-orange-400 to-rose-500 opacity-15 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-20 -right-20 h-96 w-96 rounded-full bg-gradient-to-br from-cyan-400 to-blue-500 opacity-15 blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 opacity-10 blur-3xl float"></div>

        <div class="w-full max-w-lg scale-in">
            <div class="rounded-3xl border-2 border-white/20 bg-white/95 backdrop-blur-xl p-10 shadow-2xl">
                <div class="mb-8 text-center">
                    <div class="flex justify-center mb-4">
                        <svg class="h-16 w-16 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-800">Reset Password</h1>
                    <p class="text-sm text-slate-500 mt-1">OTP verified ✓ — Choose a new password</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 rounded-xl border-l-4 border-rose-500 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.reset') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input type="password" name="password" required minlength="8" autocomplete="new-password"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-12 pr-4 py-3.5 text-sm font-medium transition-all-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20"
                                placeholder="Enter new password (min 8 characters)">
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-bold uppercase tracking-wider text-slate-600">Confirm New Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <input type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                                class="w-full rounded-xl border-2 border-slate-200 bg-white pl-12 pr-4 py-3.5 text-sm font-medium transition-all-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/20"
                                placeholder="Confirm new password">
                        </div>
                    </div>

                    <button type="submit" class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 py-4 text-base font-bold text-white shadow-xl transition-all-300 hover:shadow-2xl hover:scale-105">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Reset Password
                        </span>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 transition-colors">← Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password match validation
        document.querySelector('form')?.addEventListener('submit', function(e) {
            const pwd = document.querySelector('input[name="password"]');
            const confirm = document.querySelector('input[name="password_confirmation"]');
            if (pwd.value !== confirm.value) {
                e.preventDefault();
                confirm.classList.add('border-rose-500', 'focus:border-rose-500');
                confirm.focus();
                alert('Passwords do not match. Please try again.');
            }
        });
    </script>
</body>
</html>