<x-app-layout>
    <div class="space-y-6 p-4 sm:p-6">
        <h1 class="text-3xl font-extrabold text-slate-900">Seller Profile</h1>

        @if(session('status') === 'profile-updated')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">Profile updated successfully.</div>
        @endif
        @if(session('status') === 'password-updated')
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">Password updated successfully.</div>
        @endif

        <div class="grid gap-4 lg:grid-cols-3">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-1">
                <h2 class="text-xl font-bold text-slate-800">Profile</h2>
                <div class="mt-4 flex items-center gap-3">
                    <img src="{{ $user->profile_photo_path ? asset('storage/'.$user->profile_photo_path) : asset('images/shah-jee-logo.png') }}" class="h-16 w-16 rounded-full object-cover ring-2 ring-slate-200" alt="Profile">
                    <div>
                        <p class="font-bold text-slate-800">{{ $user->name }}</p>
                        <p class="text-sm text-slate-500">{{ $user->brand_name ?: $user->company_name }}</p>
                        <p class="text-xs text-slate-500">{{ $user->city ?: 'Pakistan' }}</p>
                    </div>
                </div>

                <div class="mt-4 space-y-2 text-sm text-slate-700">
                    <p><strong>Phone:</strong> {{ $user->phone ?: '—' }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Address:</strong> {{ $user->home_address ?: '—' }}</p>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                <h2 class="text-xl font-bold text-slate-800">Profile Settings</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="mt-4 grid gap-3 md:grid-cols-2">
                    @csrf
                    @method('patch')
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Name</label>
                        <input name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Brand</label>
                        <input name="company_name" value="{{ old('company_name', $user->company_name) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Primary Email (locked)</label>
                        <input value="{{ $user->email }}" readonly class="mt-1 w-full rounded-lg border bg-slate-100 px-3 py-2.5 text-slate-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Primary City (locked)</label>
                        <input value="{{ $user->city }}" readonly class="mt-1 w-full rounded-lg border bg-slate-100 px-3 py-2.5 text-slate-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Add New Email</label>
                        <input name="alternate_email" value="{{ old('alternate_email', $user->alternate_email) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5" placeholder="optional">
                    </div>
                    <div>
                        <label class="text-xs font-semibold uppercase text-slate-500">Add New City</label>
                        <input name="alternate_city" value="{{ old('alternate_city', $user->alternate_city) }}" class="mt-1 w-full rounded-lg border px-3 py-2.5" placeholder="optional">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs font-semibold uppercase text-slate-500">Address</label>
                        <textarea name="home_address" class="mt-1 w-full rounded-lg border px-3 py-2.5">{{ old('home_address', $user->home_address) }}</textarea>
                    </div>
                    <div class="md:col-span-2">
                        <button class="rounded-lg bg-slate-700 px-6 py-2.5 text-sm font-bold text-white hover:bg-slate-800">Edit Profile</button>
                    </div>
                </form>
            </section>
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xl font-bold text-slate-800">Payment Method</h3>
                <div class="mt-3 space-y-2 text-sm text-slate-700">
                    <p><strong>Bank:</strong> {{ $user->bank_name ?: '—' }}</p>
                    <p><strong>Account #:</strong> {{ $user->account_number ?: '—' }}</p>
                    <p><strong>IBAN:</strong> {{ $user->iban_number ?: '—' }}</p>
                    <p><strong>Cycle:</strong> {{ $user->payment_cycle === 'weekly' ? 'Once in a week' : 'Twice in a week' }}</p>
                </div>
                <p class="mt-3 text-xs text-rose-500">Bank details are locked and cannot be changed from profile.</p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xl font-bold text-slate-800">Notification Preferences</h3>
                <ul class="mt-3 list-inside list-disc text-sm text-slate-700">
                    <li>Activity Notifications</li>
                    <li>Email Preferences</li>
                </ul>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-xl font-bold text-slate-800">Authentication Details</h3>
                <p class="mt-3 text-sm"><strong>Username:</strong> {{ $user->username }}</p>
                <p class="text-sm"><strong>Email:</strong> {{ $user->email }}</p>
                <form method="POST" action="{{ route('password.update') }}" class="mt-4 space-y-2">
                    @csrf
                    @method('put')
                    <input type="password" name="current_password" placeholder="Current password" class="w-full rounded-lg border px-3 py-2.5">
                    <input type="password" name="password" placeholder="New password" class="w-full rounded-lg border px-3 py-2.5">
                    <input type="password" name="password_confirmation" placeholder="Confirm password" class="w-full rounded-lg border px-3 py-2.5">
                    <button class="rounded-lg bg-orange-500 px-5 py-2.5 text-sm font-bold text-white hover:bg-orange-600">Edit Authentication</button>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>