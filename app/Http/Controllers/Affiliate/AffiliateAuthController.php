<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AffiliateAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.affiliate-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $login = trim((string) $request->input('login'));

        // Accept email OR username OR phone in a single field
        $userQuery = \App\Models\User::query();

        $emailLike = $login;
        $user = $userQuery
            ->where(function ($q) use ($login, $emailLike) {
                $q->where('email', $emailLike)
                    ->orWhere('username', $login)
                    ->orWhere('phone', $login);
            })
            ->first();

        if (! $user) {
            return back()->withErrors(['login' => 'Invalid credentials.'])->withInput();
        }

        // Must be an affiliate (has row in affiliates table)
        $affiliate = Affiliate::query()->where('user_id', $user->id)->first();
        if (! $affiliate) {
            return back()->withErrors(['login' => 'This account is not an affiliate agent.'])->withInput();
        }

 // Normal password validation and login
if (! Auth::guard('affiliate')->attempt(['email' => $user->email, 'password' => $request->input('password')])) {
    return back()->withErrors(['password' => 'Invalid credentials.'])->withInput();
}

        // Mark that this session is affiliate-specific (used by affiliate dashboard)
        $request->session()->put('affiliate_auth_id', $user->id);

        // Ensure Livewire dashboard can resolve the affiliate without requiring ?ref=...
        // Livewire resolves affiliate via query param `ref`/`code` OR cookie `affiliate_ref`.
        $referralCode = (string) ($affiliate->referral_code ?? '');
        if ($referralCode !== '') {
            return redirect()->route('affiliate.dashboard.full', ['ref' => $referralCode]);
        }

        return redirect()->route('affiliate.dashboard.full');
    }

    public function logout(Request $request)
    {
Auth::guard('affiliate')->logout();
        $request->session()->forget('affiliate_auth_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

