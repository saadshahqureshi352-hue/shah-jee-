<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $banks = [
            'Allied Bank', 'Askari Bank', 'Bank Alfalah', 'Bank Al Habib', 'BankIslami',
            'Dubai Islamic Bank', 'Faysal Bank', 'Habib Bank Limited', 'Habib Metropolitan Bank',
            'JS Bank', 'MCB Bank', 'Meezan Bank', 'National Bank of Pakistan', 'Punjab Bank',
            'Samba Bank', 'Silkbank', 'Soneri Bank', 'Standard Chartered', 'Summit Bank',
            'United Bank Limited', 'Zarai Taraqiati Bank', 'JazzCash', 'Easypaisa', 'UPaisa',
            'NayaPay', 'SadaPay',
        ];

        return view('auth.register', compact('banks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand_name' => ['required', 'string', 'max:255'],
            'father_name' => ['required', 'string', 'max:255'],
            'cnic_or_passport' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:100'],
            'home_address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'max:25'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female,Other'],

            'account_holder_name' => ['required', 'string', 'max:255'],
            'account_number' => ['required', 'string', 'max:100'],
            'iban_number' => ['required', 'string', 'max:100'],
            'bank_name' => ['required', 'string', 'max:255'],
            'payment_cycle' => ['required', 'in:weekly,twice_weekly'],
            'cheque_photo' => ['nullable', 'image', 'max:5120'],

            'profile_photo' => ['required', 'image', 'max:5120'],
            'selfie_photo' => ['required', 'image', 'max:5120'],
            'cnic_front' => ['required', 'image', 'max:5120'],
            'cnic_back' => ['required', 'image', 'max:5120'],
            'business_photos' => ['required', 'array', 'min:1', 'max:5'],
            'business_photos.*' => ['image', 'max:5120'],

            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'login_phone' => ['required', 'string', 'max:25', 'unique:'.User::class.',phone'],
            'username' => ['required', 'string', 'min:4', 'max:40', 'regex:/^[a-z0-9_]+$/', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'username.regex' => 'Username must be lowercase and without spaces.',
        ]);

        $store = fn ($name) => $request->file($name)?->store('shipper_profiles', 'public');

        $business = [];
        foreach ((array) $request->file('business_photos', []) as $img) {
            $business[] = $img->store('shipper_profiles', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'owner_name' => $request->name,
            'brand_name' => $request->brand_name,
            'father_name' => $request->father_name,
            'cnic_or_passport' => $request->cnic_or_passport,
            'city' => $request->city,
            'home_address' => $request->home_address,
            'phone' => $request->login_phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'account_holder_name' => $request->account_holder_name,
            'account_number' => $request->account_number,
            'iban_number' => $request->iban_number,
            'bank_name' => $request->bank_name,
            'wallet_method' => in_array($request->bank_name, ['JazzCash','Easypaisa','UPaisa','NayaPay','SadaPay']) ? $request->bank_name : null,
            'payment_cycle' => $request->payment_cycle,
            'cheque_photo_path' => $store('cheque_photo'),
            'profile_photo_path' => $store('profile_photo'),
            'selfie_photo_path' => $store('selfie_photo'),
            'cnic_front_path' => $store('cnic_front'),
            'cnic_back_path' => $store('cnic_back'),
            'business_photo_paths' => $business,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'is_approved' => false,
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('status', 'Registration submitted successfully. Your account will be activated soon after review.');
    }
}