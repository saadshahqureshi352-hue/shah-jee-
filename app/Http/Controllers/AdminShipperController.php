<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminShipperController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin();

        $search = trim((string) $request->get('search', ''));
        $status = (string) $request->get('status', 'all');

        $query = User::query()->orderByDesc('created_at');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('username', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('city', 'like', '%'.$search.'%');
            });
        }

        if ($status === 'pending') {
            $query->where('is_approved', false);
        } elseif ($status === 'approved') {
            $query->where('is_approved', true);
        }

        $shippers = $query->paginate(20)->withQueryString();

        return view('admin.shippers.index', compact('shippers', 'search', 'status'));
    }

    public function edit(User $user): View
    {
        $this->ensureAdmin();

        $banks = [
            'Allied Bank', 'Askari Bank', 'Bank Alfalah', 'Bank Al Habib', 'BankIslami',
            'Dubai Islamic Bank', 'Faysal Bank', 'Habib Bank Limited', 'Habib Metropolitan Bank',
            'JS Bank', 'MCB Bank', 'Meezan Bank', 'National Bank of Pakistan', 'Punjab Bank',
            'Samba Bank', 'Silkbank', 'Soneri Bank', 'Standard Chartered', 'Summit Bank',
            'United Bank Limited', 'Zarai Taraqiati Bank', 'JazzCash', 'Easypaisa', 'UPaisa',
            'NayaPay', 'SadaPay',
        ];

        return view('admin.shippers.edit', compact('user', 'banks'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand_name' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'cnic_or_passport' => 'nullable|string|max:50',
            'city' => 'nullable|string|max:100',
            'home_address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:25',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'username' => 'required|string|min:4|max:40|regex:/^[a-z0-9_]+$/|unique:users,username,'.$user->id,
            'account_holder_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:100',
            'iban_number' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'payment_cycle' => 'nullable|in:weekly,twice_weekly',
            'is_approved' => 'nullable|boolean',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'username.regex' => 'Username must be lowercase and without spaces.',
        ]);

        if (! $request->has('is_approved')) {
            $validated['is_approved'] = false;
        }

        if ($validated['is_approved']) {
            $validated['approved_at'] = $user->approved_at ?: now();
        } else {
            $validated['approved_at'] = null;
            $validated['has_seen_welcome'] = false;
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        $user->update($validated);

        return redirect()->route('admin.shippers.edit', $user)->with('success', 'Shipper data updated successfully.');
    }

    public function approve(User $user): RedirectResponse
    {
        $this->ensureAdmin();

        $user->update([
            'is_approved' => true,
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Shipper approved successfully.');
    }

    public function reject(User $user): RedirectResponse
    {
        $this->ensureAdmin();

        $user->update([
            'is_approved' => false,
            'approved_at' => null,
            'has_seen_welcome' => false,
        ]);

        return back()->with('success', 'Shipper moved to pending/rejected state.');
    }

    private function ensureAdmin(): void
    {
        $email = (string) auth()->user()?->email;
        $adminEmails = config('finance.admin_emails', []);

        $isAdmin = in_array($email, $adminEmails, true) || (int) auth()->id() === 1;

        abort_unless($isAdmin, 403);
    }
}