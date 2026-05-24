<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify OTP - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .otp-input {
            width: 3.5rem; height: 4rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.2s;
            outline: none;
        }
        .otp-input:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 4px rgba(13,148,136,0.15);
        }
        .otp-input.filled {
            border-color: #0d9488;
            background: #f0fdfa;
        }
        @media (max-width: 480px) {
            .otp-input { width: 2.8rem; height: 3.5rem; font-size: 1.25rem; }
        }
    </style>
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
                        <svg class="h-16 w-16 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-slate-800">Verify OTP</h1>
                    <p class="text-sm text-slate-500 mt-1">Enter the 6-digit code sent to</p>
                    <p class="text-sm font-bold text-teal-600 mt-0.5">{{ $masked }}</p>
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

                {{-- OTP display on screen (works immediately, no API needed) --}}
                @if(!empty($displayOtp))
                    <div class="mb-6 rounded-xl border-2 border-dashed border-teal-300 bg-teal-50/80 px-5 py-4 text-center">
                        <p class="text-xs font-bold uppercase tracking-wider text-teal-600">🔑 Your OTP Code</p>
                        <p class="mt-2 font-mono text-3xl font-black tracking-[0.3em] text-teal-700 select-all">
                            {{ $displayOtp }}
                        </p>
                        <p class="mt-1 text-xs text-teal-500">Valid for 10 minutes · Copy and paste below</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.verify') }}" id="otpForm" class="space-y-5">
                    @csrf
                    <div>
                        <label class="mb-3 block text-center text-xs font-bold uppercase tracking-wider text-slate-500">6-Digit OTP</label>
                        <div class="flex justify-center gap-2 sm:gap-3" id="otpContainer">
                            @for($i = 0; $i < 6; $i++)
                                <input type="text" maxlength="1" inputmode="numeric" pattern="[0-9]"
                                    class="otp-input" data-index="{{ $i }}"
                                    id="otp_{{ $i }}" autocomplete="off">
                            @endfor
                        </div>
                        <input type="hidden" name="otp" id="otpComplete">
                    </div>

                    <button type="submit" class="group relative w-full overflow-hidden rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 py-4 text-base font-bold text-white shadow-xl transition-all-300 hover:shadow-2xl hover:scale-105">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Verify & Continue
                        </span>
                    </button>
                </form>

                <div class="mt-6 flex items-center justify-center gap-4 text-sm">
                    <button type="button" onclick="document.getElementById('resendForm').submit()"
                        class="font-semibold text-teal-600 hover:text-teal-700 transition-colors">
                        Resend OTP
                    </button>
                    <span class="text-slate-300">|</span>
                    <a href="{{ route('login') }}" class="font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        Back to Login
                    </a>
                </div>

                <form id="resendForm" method="POST" action="{{ route('otp.resend') }}" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');
            const hiddenOtp = document.getElementById('otpComplete');

            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value) {
                        this.classList.add('filled');
                        if (index < 5) inputs[index + 1].focus();
                    }
                    updateOtp();
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].classList.remove('filled');
                    }
                    if (e.key === 'Enter') {
                        document.getElementById('otpForm').submit();
                    }
                });

                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const nums = paste.replace(/[^0-9]/g, '').slice(0, 6);
                    nums.split('').forEach((num, i) => {
                        if (inputs[i]) {
                            inputs[i].value = num;
                            inputs[i].classList.add('filled');
                        }
                    });
                    updateOtp();
                    inputs[Math.min(nums.length, 5)].focus();
                });
            });

            function updateOtp() {
                let otp = '';
                inputs.forEach(inp => otp += inp.value);
                hiddenOtp.value = otp;
            }
        });
    </script>
</body>
</html>