<x-app-layout>
    <div class="space-y-4 p-4 sm:p-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold text-slate-900">Edit Shipper Data</h1>
            <a href="{{ route('admin.shippers.index') }}" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Back</a>
        </div>

        <form method="POST" action="{{ route('admin.shippers.update', $user) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-slate-800">Personal Information</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Name</label><input name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5" required></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Brand Name</label><input name="brand_name" value="{{ old('brand_name', $user->brand_name) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Father Name</label><input name="father_name" value="{{ old('father_name', $user->father_name) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">CNIC / Passport</label><input name="cnic_or_passport" value="{{ old('cnic_or_passport', $user->cnic_or_passport) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">City</label><input name="city" value="{{ old('city', $user->city) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Phone</label><input name="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div class="md:col-span-2"><label class="text-xs font-semibold uppercase text-slate-500">Home Address</label><textarea name="home_address" class="mt-1 w-full rounded-lg border px-3 py-2.5">{{ old('home_address', $user->home_address) }}</textarea></div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-slate-800">Authentication</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5" required></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Username</label><input name="username" value="{{ old('username', $user->username) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5" required></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">New Password (optional)</label><input type="password" name="password" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Confirm Password</label><input type="password" name="password_confirmation" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-xl font-bold text-slate-800">Bank / Payment</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Account Holder Name</label><input name="account_holder_name" value="{{ old('account_holder_name', $user->account_holder_name) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Account Number</label><input name="account_number" value="{{ old('account_number', $user->account_number) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">IBAN Number</label><input name="iban_number" value="{{ old('iban_number', $user->iban_number) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Bank Name</label>
                        <select name="bank_name" class="mt-1 w-full rounded-lg border px-3 py-2.5">
                            <option value="">Select</option>
                            @foreach($banks as $bank)
                                <option value="{{ $bank }}" @selected(old('bank_name', $user->bank_name) === $bank)>{{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="text-xs font-semibold uppercase text-slate-500">Payment Cycle</label>
                        <select name="payment_cycle" class="mt-1 w-full rounded-lg border px-3 py-2.5">
                            <option value="weekly" @selected(old('payment_cycle', $user->payment_cycle) === 'weekly')>Once in a week</option>
                            <option value="twice_weekly" @selected(old('payment_cycle', $user->payment_cycle) === 'twice_weekly')>Twice in a week</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2.5">
                            <input type="checkbox" name="is_approved" value="1" @checked(old('is_approved', $user->is_approved))>
                            <span class="text-sm font-semibold">Approved</span>
                        </label>
                    </div>
                </div>
            </div>

            <div>
                <button class="rounded-lg bg-emerald-600 px-6 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Save Changes</button>
            </div>
        </form>
    </div>
</x-app-layout>