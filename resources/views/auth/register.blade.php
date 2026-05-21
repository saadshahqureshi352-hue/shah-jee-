<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="min-h-screen bg-slate-100 antialiased" x-data="{ step: 1 }">
    <div class="mx-auto max-w-5xl p-4 sm:p-8">
        <div class="mb-6 text-center">
            <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="mx-auto h-20 w-20 rounded-xl object-cover shadow">
            <h1 class="mt-3 text-3xl font-extrabold text-slate-800">Register New Account</h1>
            <p class="mt-1 text-sm text-slate-500">Shipper onboarding</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <section x-show="step === 1" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800">Personal Info</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-sm font-semibold">Your Name *</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Brand Name *</label><input name="brand_name" value="{{ old('brand_name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Father Name *</label><input name="father_name" value="{{ old('father_name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">CNIC or Passport *</label><input name="cnic_or_passport" value="{{ old('cnic_or_passport') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">City *</label><input name="city" value="{{ old('city') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Phone Number *</label><input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Date of Birth *</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Gender *</label><select name="gender" required class="mt-1 w-full rounded-lg border px-3 py-2.5"><option>Male</option><option>Female</option><option>Other</option></select></div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Complete Home Address *</label><textarea name="home_address" required class="mt-1 w-full rounded-lg border px-3 py-2.5">{{ old('home_address') }}</textarea></div>
                </div>
                <div class="mt-4 flex justify-end"><button type="button" @click="step = 2" class="rounded-lg bg-orange-500 px-6 py-2.5 font-bold text-white">Next Step</button></div>
            </section>

            <section x-show="step === 2" x-cloak style="display:none" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800">Bank Details & Payable Days</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-sm font-semibold">Account Holder Name *</label><input name="account_holder_name" value="{{ old('account_holder_name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Account Number *</label><input name="account_number" value="{{ old('account_number') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">IBAN Number *</label><input name="iban_number" value="{{ old('iban_number') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div>
                        <label class="text-sm font-semibold">Bank / Wallet *</label>
                        <select name="bank_name" required class="mt-1 w-full rounded-lg border px-3 py-2.5">
                            <option value="">Select</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank }}" @selected(old('bank_name') === $bank)>{{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-semibold">Payment Cycle *</label>
                        <div class="mt-2 grid gap-2 sm:grid-cols-2">
                            <label class="rounded-lg border p-3"><input type="radio" name="payment_cycle" value="weekly" @checked(old('payment_cycle')==='weekly')> Once in a week</label>
                            <label class="rounded-lg border p-3"><input type="radio" name="payment_cycle" value="twice_weekly" @checked(old('payment_cycle','twice_weekly')==='twice_weekly')> Twice in a week</label>
                        </div>
                    </div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Add Cheque Photo (Optional)</label><input type="file" name="cheque_photo" accept="image/*" class="mt-1 w-full"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" @click="step = 1" class="rounded-lg bg-slate-500 px-6 py-2.5 font-bold text-white">Previous</button>
                    <button type="button" @click="step = 3" class="rounded-lg bg-orange-500 px-6 py-2.5 font-bold text-white">Next Step</button>
                </div>
            </section>

            <section x-show="step === 3" x-cloak style="display:none" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800">Verifications</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-sm font-semibold">Profile Image *</label><input type="file" name="profile_photo" accept="image/*" required class="mt-1 w-full"></div>
                    <div><label class="text-sm font-semibold">Selfie Live Face Image *</label><input type="file" name="selfie_photo" accept="image/*" required class="mt-1 w-full"></div>
                    <div><label class="text-sm font-semibold">CNIC Front *</label><input type="file" name="cnic_front" accept="image/*" required class="mt-1 w-full"></div>
                    <div><label class="text-sm font-semibold">CNIC Back *</label><input type="file" name="cnic_back" accept="image/*" required class="mt-1 w-full"></div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Business Photos (Max 5) *</label><input type="file" name="business_photos[]" accept="image/*" multiple required class="mt-1 w-full"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" @click="step = 2" class="rounded-lg bg-slate-500 px-6 py-2.5 font-bold text-white">Previous</button>
                    <button type="button" @click="step = 4" class="rounded-lg bg-orange-500 px-6 py-2.5 font-bold text-white">Next Step</button>
                </div>
            </section>

            <section x-show="step === 4" x-cloak style="display:none" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800">Authentication Details</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-sm font-semibold">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Phone (Login) *</label><input name="login_phone" value="{{ old('login_phone', old('phone')) }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Username *</label><input name="username" value="{{ old('username') }}" required pattern="[a-z0-9_]+" class="mt-1 w-full rounded-lg border px-3 py-2.5" placeholder="small letters no spaces"></div>
                    <div><label class="text-sm font-semibold">Password *</label><input type="password" name="password" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Confirm Password *</label><input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" @click="step = 3" class="rounded-lg bg-slate-500 px-6 py-2.5 font-bold text-white">Previous</button>
                    <button type="submit" class="rounded-lg bg-emerald-600 px-6 py-2.5 font-bold text-white">Submit Registration</button>
                </div>
            </section>
        </form>
    </div>
</body>
</html>