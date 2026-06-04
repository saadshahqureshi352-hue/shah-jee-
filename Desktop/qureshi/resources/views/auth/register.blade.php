<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Shah Jee Courier</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <style>
        [x-cloak]{display:none!important;}
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        
        @keyframes whatsappGlowPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5); }
            50% { transform: scale(1.08); box-shadow: 0 0 18px 6px rgba(16, 185, 129, 0.3); }
        }
        .animate-whatsapp {
            animation: whatsappGlowPulse 2s infinite ease-in-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up { animation: fadeInUp 0.5s ease-out both; }
        .fade-in-up-delay-1 { animation: fadeInUp 0.5s ease-out 0.1s both; }
        .fade-in-up-delay-2 { animation: fadeInUp 0.5s ease-out 0.2s both; }
    </style>
</head>
<body class="min-h-screen bg-slate-100 antialiased" x-data="{ step: 1, acceptedRules: false }">
    <div class="mx-auto max-w-5xl p-4 sm:p-8">
        <div class="mb-6 text-center">
            <div class="relative inline-block">
                <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-orange-400 to-rose-500 blur-lg opacity-30 animate-pulse"></div>
                <img src="{{ asset('images/shah-jee-logo.png') }}" alt="Shah Jee Courier" class="relative mx-auto h-20 w-20 rounded-2xl object-cover ring-2 ring-orange-300/50 shadow-xl transition-transform duration-300 hover:scale-110">
            </div>
            <h1 class="mt-3 text-3xl font-extrabold text-slate-800">Register New Account</h1>
            <p class="mt-1 text-sm text-slate-500">Shipper onboarding — Join the fastest growing courier network</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- STEP 1: Personal Information + Rules Sidebar --}}
            <div x-show="step === 1" class="grid gap-6 lg:grid-cols-3">
                {{-- Main Form: Personal Information --}}
                <section class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <h2 class="mb-4 text-2xl font-bold text-slate-800 flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-orange-400 to-rose-500 text-white text-sm">1</span>
                        Personal Information
                    </h2>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div><label class="text-sm font-semibold">Your Name *</label><input name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">Brand Name *</label><input name="brand_name" value="{{ old('brand_name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">Father Name *</label><input name="father_name" value="{{ old('father_name') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">CNIC Number *</label><input name="cnic_or_passport" value="{{ old('cnic_or_passport') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">City *</label><input name="city" value="{{ old('city') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">Phone Number *</label><input name="phone" value="{{ old('phone') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">Date of Birth *</label><input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                        <div><label class="text-sm font-semibold">Gender *</label><select name="gender" required class="mt-1 w-full rounded-lg border px-3 py-2.5"><option>Male</option><option>Female</option><option>Other</option></select></div>
                        <div class="md:col-span-2"><label class="text-sm font-semibold">Complete Home/Shop Address *</label><textarea name="home_address" required class="mt-1 w-full rounded-lg border px-3 py-2.5">{{ old('home_address') }}</textarea></div>
                    </div>
                    <div class="mt-6 border-t pt-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" x-model="acceptedRules" class="mt-1 h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-sm text-slate-700">I have read and agree to the <strong>Shipper Registration Guidelines</strong> and <strong>Terms of Service</strong>.</span>
                        </label>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" @click="step = 2" :disabled="!acceptedRules"
                                :class="acceptedRules ? 'bg-gradient-to-r from-orange-400 to-red-500 hover:from-orange-500 hover:to-rose-600' : 'bg-slate-300 cursor-not-allowed'"
                                class="rounded-lg px-6 py-2.5 font-bold text-red shadow-md transition-all duration-300">
                            Next Step
                        </button>
                    </div>
                </section>

                {{-- Sidebar: Rules & WhatsApp Help --}}
                <aside class="space-y-4">
                    {{-- Rules Card --}}
                    <div class="rounded-2xl border-2 border-emerald-200 bg-gradient-to-b from-emerald-50 to-white p-5 shadow-sm">
                        <h3 class="flex items-center gap-2 text-lg font-extrabold text-emerald-800 mb-3">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            شپر رجسٹریشن کی ہدایات
                        </h3>
                        <p class="text-xs font-semibold text-emerald-700 mb-3">Shipper Registration Guidelines</p>
                        
                        <div class="space-y-3 text-xs text-slate-700 max-h-[380px] overflow-y-auto pr-2 custom-scrollbar">
                            <p class="text-slate-500 italic">خوش آمدید! ہمارے پلیٹ فارم پر بطور شپر رجسٹر کرنے کے لیے درج ذیل قوانین کا خاص خیال رکھیں:</p>
                            
                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">1۔ درست معلومات (Accurate Information)</p>
                                <ul class="list-disc list-inside space-y-1 text-slate-600">
                                    <li>رجسٹریشن فارم میں اپنا مکمل نام، موبائل نمبر اور ای میل ایڈریس بالکل صحیح درج کریں۔</li>
                                    <li>بینک اکاؤنٹ کی تفصیلات (اکاؤنٹ نمبر، اکاؤنٹ ٹائٹل اور IBAN) بالکل صحیح اور دھیان سے درج کریں۔</li>
                                </ul>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">2۔ کوریئر کمپنیاں، پارسل گمشدگی اور کلیم پالیسی</p>
                                <ul class="list-disc list-inside space-y-1 text-slate-600">
                                    <li>شاہ جی کوریئر ایک 3PL Aggregator پورٹل ہے جو TCS، Leopards، M&P وغیرہ کی خدمات فراہم کرتا ہے۔</li>
                                    <li>پارسل گم ہونے پر COD رقم ادا کر دی جائے گی، کلیم پراسس میں اوسطاً 18 سے 20 دن لگتے ہیں۔</li>
                                    <li>اگر کوریئر کمپنی فراڈ کرے تو شاہ جی کوریئر ذمہ دار نہیں، مگر کلیم دلوانے میں مدد کرے گا۔</li>
                                </ul>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">3۔ روزانہ اور فوری ادائیگی (Daily & Instant Payments)</p>
                                <p class="text-slate-600">پارسل ڈیلیور ہونے کے بعد آپ کی رقم خودکار طریقے سے آپ کے اکاؤنٹ میں ٹرانسفر کر دی جائے گی۔</p>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">4۔ پارسل کی واپسی اور چارجز (RTO)</p>
                                <p class="text-slate-600">پارسل ریٹرن آنے پر انتہائی کم ڈیلیوری چارجز وصول کیے جائیں گے۔</p>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">5۔ اپنے شہر میں ڈیلیوری (Same City)</p>
                                <p class="text-slate-600">Same City ڈیلیوری پر مزید ڈسکاؤنٹڈ ریٹس ملیں گے۔</p>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">6۔ ممنوعہ اشیاء (Prohibited Items)</p>
                                <ul class="list-disc list-inside space-y-1 text-slate-600">
                                    <li>غیر قانونی، خطرناک (Flammable) یا نشہ آور چیزیں بھیجنا ممنوع ہے۔ خلاف ورزی پر قانونی کارروائی ہوگی۔</li>
                                    <li>قیمتی جیولری یا نقد رقم پارسلز میں رکھنا منع ہے۔</li>
                                </ul>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">7۔ پیکنگ کی ذمہ داری</p>
                                <ul class="list-disc list-inside space-y-1 text-slate-600">
                                    <li>پارسل کو محفوظ طریقے سے پیک کرنا آپ کی ذمہ داری ہے۔</li>
                                    <li>نازک اشیاء کے لیے "Fragile" کا اسٹیکر لازمی لگائیں۔</li>
                                </ul>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">8۔ وزن (Weight)</p>
                                <p class="text-slate-600">پارسل کا وزن ہمیشہ صحیح درج کریں، غلط وزن پر ایکسٹرا چارجز لگ سکتے ہیں۔</p>
                            </div>

                            <div class="border-t border-emerald-100 pt-2">
                                <p class="font-bold text-emerald-700 mb-1">9۔ کوئی خفیہ چارجز یا ٹیکس نہیں (No Hidden Charges)</p>
                                <p class="text-slate-600">کوئی رجسٹریشن فیس، ماہانہ چارجز، سالانہ فیس، یا پیمنٹ ٹرانسفر پر کوئی چھپے چارجز نہیں۔</p>
                            </div>

                            <div class="border-t border-emerald-100 pt-2 bg-amber-50 rounded-lg p-3">
                                <p class="font-bold text-amber-800">📝 نوٹ (Note):</p>
                                <p class="text-slate-600">رجسٹریشن مکمل کرنے کا مطلب ہے کہ آپ اوپر دیے گئے تمام قوانین اور ہماری Terms of Service سے اتفاق کرتے ہیں۔</p>
                            </div>
                        </div>
                    </div>

                    {{-- WhatsApp Help Button --}}
                    <a href="https://wa.me/923197290092?text=Assalam-o-Alaikum!%20I%20need%20help%20with%20Shipper%20Registration."
                       target="_blank" rel="noopener noreferrer"
                       class="flex items-center gap-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 p-4 text-red shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 animate-whatsapp">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-light blue/20">
                            <svg class="h-7 w-7 fill-current" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-extrabold">Need Help?</p>
                            <p class="text-xs opacity-90">Chat on WhatsApp</p>
                            <p class="text-xs font-bold mt-0.5">+92 319 7290092</p>
                        </div>
                    </a>
                </aside>
            </div>

            {{-- STEP 2: Bank Details --}}
            <section x-show="step === 2" x-cloak style="display:none" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-violet-400 to-purple-500 text-white text-sm">2</span>
                    Bank Details & Payable Days
                </h2>
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
                            <label class="rounded-lg border p-3 cursor-pointer hover:border-violet-400 transition"><input type="radio" name="payment_cycle" value="weekly" @checked(old('payment_cycle')==='weekly')> Once in a week</label>
                            <label class="rounded-lg border p-3 cursor-pointer hover:border-violet-400 transition"><input type="radio" name="payment_cycle" value="twice_weekly" @checked(old('payment_cycle','twice_weekly')==='twice_weekly')> Twice in a week</label>
                        </div>
                    </div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Add Cheque Photo (Optional)</label><input type="file" name="cheque_photo" accept="image/*" class="mt-1 w-full"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" @click="step = 1" class="rounded-lg bg-slate-500 px-6 py-2.5 font-bold text-white hover:bg-slate-600 transition">Previous</button>
                    <button type="button" @click="step = 3" class="rounded-lg bg-gradient-to-r from-violet-400 to-purple-500 px-6 py-2.5 font-bold text-white shadow-md hover:from-violet-500 hover:to-purple-600 transition">Next Step</button>
                </div>
            </section>

            {{-- STEP 3: Verifications --}}
            <section x-show="step === 3" x-cloak style="display:none" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-sky-400 to-blue-500 text-white text-sm">3</span>
                    Verifications
                </h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-sm font-semibold">Profile Image *</label><input type="file" name="profile_photo" accept="image/*" required class="mt-1 w-full"></div>
                    <div><label class="text-sm font-semibold">Selfie Live Face Image *</label><input type="file" name="selfie_photo" accept="image/*" required class="mt-1 w-full"></div>
                    <div><label class="text-sm font-semibold">CNIC Front *</label><input type="file" name="cnic_front" accept="image/*" required class="mt-1 w-full"></div>
                    <div><label class="text-sm font-semibold">CNIC Back *</label><input type="file" name="cnic_back" accept="image/*" required class="mt-1 w-full"></div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Business Photos (Max 5) *</label><input type="file" name="business_photos[]" accept="image/*" multiple required class="mt-1 w-full"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" @click="step = 2" class="rounded-lg bg-slate-500 px-6 py-2.5 font-bold text-white hover:bg-slate-600 transition">Previous</button>
                    <button type="button" @click="step = 4" class="rounded-lg bg-gradient-to-r from-sky-400 to-blue-500 px-6 py-2.5 font-bold text-white shadow-md hover:from-sky-500 hover:to-blue-600 transition">Next Step</button>
                </div>
            </section>

            {{-- STEP 4: Authentication Details --}}
            <section x-show="step === 4" x-cloak style="display:none" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="mb-4 text-2xl font-bold text-slate-800 flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-400 to-teal-500 text-white text-sm">4</span>
                    Authentication Details
                </h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <div><label class="text-sm font-semibold">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Phone (Login) *</label><input name="login_phone" value="{{ old('login_phone', old('phone')) }}" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div><label class="text-sm font-semibold">Username *</label><input name="username" value="{{ old('username') }}" required pattern="[a-z0-9_]+" class="mt-1 w-full rounded-lg border px-3 py-2.5" placeholder="small letters no spaces"></div>
                    <div><label class="text-sm font-semibold">Password *</label><input type="password" name="password" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                    <div class="md:col-span-2"><label class="text-sm font-semibold">Confirm Password *</label><input type="password" name="password_confirmation" required class="mt-1 w-full rounded-lg border px-3 py-2.5"></div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="button" @click="step = 3" class="rounded-lg bg-slate-500 px-6 py-2.5 font-bold text-white hover:bg-slate-600 transition">Previous</button>
                    <button type="submit" class="rounded-lg bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-2.5 font-bold text-white shadow-lg hover:from-emerald-600 hover:to-teal-700 transition">Submit Registration</button>
                </div>
            </section>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f0fdf4; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #86efac; border-radius: 10px; }
    </style>
</body>
</html>