<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpPasswordResetController extends Controller
{
    /**
     * Step 1: Show forgot password form (email/phone input)
     */
    public function showForgotForm()
    {
        return view('auth.otp-forgot');
    }

    /**
     * Step 2: Send OTP to user's email (hosting email SMTP se jayega)
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|email|max:255',
        ]);

        $identifier = trim($request->identifier);

        // Find user by email only
        $user = DB::table('users')
            ->where('email', $identifier)
            ->first();

        if (!$user) {
            return back()->withErrors(['identifier' => 'No account found with this email.']);
        }

        // Generate 6-digit OTP
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Store OTP in database
        DB::table('password_reset_otps')->insert([
            'identifier' => $identifier,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'used' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // === SEND OTP VIA HOSTING EMAIL (cPanel mail) ===
        try {
            $this->sendOtpEmail($user->email, $otp);
            \Illuminate\Support\Facades\Log::info('OTP email sent successfully', ['to' => $identifier]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('OTP email failed', [
                'error' => $e->getMessage(),
                'to' => $identifier,
            ]);
            // Still show OTP on screen as backup
        }

        // === SHOW OTP ON SCREEN (BACKUP - jab tak SMTP test nahi ho jata) ===
        session(['display_otp' => $otp]);

        // Store identifier in session
        session(['otp_identifier' => $identifier]);

        return redirect()->route('otp.verify.form')
            ->with('success', 'A 6-digit OTP has been sent to your email.');
    }

    /**
     * Step 3: Show OTP verification form with OTP displayed on screen
     */
    public function showOtpForm()
    {
        $identifier = session('otp_identifier');
        $displayOtp = session('display_otp');
        
        if (!$identifier) {
            return redirect()->route('otp.forgot.form');
        }

        $masked = $this->maskIdentifier($identifier);

        return view('auth.otp-verify', compact('masked', 'displayOtp'));
    }

    /**
     * Step 4: Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $identifier = session('otp_identifier');
        
        if (!$identifier) {
            return redirect()->route('otp.forgot.form')
                ->withErrors(['otp' => 'Session expired. Please start again.']);
        }

        $otpRecord = DB::table('password_reset_otps')
            ->where('identifier', $identifier)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->orderByDesc('created_at')
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP. Please request a new one.']);
        }

        // Mark OTP as used
        DB::table('password_reset_otps')
            ->where('id', $otpRecord->id)
            ->update(['used' => true, 'updated_at' => now()]);

        // Store verification in session
        session(['otp_verified' => true, 'otp_verified_for' => $identifier]);
        
        // Clean up old OTPs
        DB::table('password_reset_otps')
            ->where('identifier', $identifier)
            ->delete();

        // Clear display OTP
        session()->forget('display_otp');

        return redirect()->route('otp.reset.form');
    }

    /**
     * Step 5: Show new password form
     */
    public function showResetForm()
    {
        if (!session('otp_verified')) {
            return redirect()->route('otp.forgot.form');
        }

        return view('auth.otp-reset');
    }

    /**
     * Step 6: Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!session('otp_verified')) {
            return redirect()->route('otp.forgot.form')
                ->withErrors(['password' => 'Session expired. Please start again.']);
        }

        $identifier = session('otp_verified_for');

        $user = DB::table('users')
            ->where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['password' => 'User not found. Please contact support.']);
        }

        // Update password
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        // Clear all OTP sessions
        session()->forget(['otp_identifier', 'otp_verified', 'otp_verified_for', 'display_otp']);

        return redirect()->route('login')
            ->with('status', '✓ Password reset successful! You can now login with your new password.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp()
    {
        $identifier = session('otp_identifier');

        if (!$identifier) {
            return redirect()->route('otp.forgot.form');
        }

        $user = DB::table('users')
            ->where('email', $identifier)
            ->orWhere('phone', $identifier)
            ->orWhere('username', $identifier)
            ->first();

        if (!$user) {
            return redirect()->route('otp.forgot.form')
                ->withErrors(['identifier' => 'No account found.']);
        }

        // Generate new OTP
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(10);

        DB::table('password_reset_otps')->insert([
            'identifier' => $identifier,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'used' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update display OTP
        session(['display_otp' => $otp]);

        return back()->with('success', 'A new OTP has been generated and displayed below.');
    }

    /**
     * Send OTP via Email using Laravel's built-in mail
     */
    private function sendOtpEmail(string $email, string $otp): void
    {
        $appName = config('app.name', 'Shah Jee Courier');
        
        Mail::raw(
            "Your {$appName} Password Reset OTP\n\n" .
            "OTP Code: {$otp}\n" .
            "Valid for: 10 minutes\n\n" .
            "If you did not request this, please ignore this email.\n" .
            "© " . date('Y') . " {$appName}",
            function ($message) use ($email, $appName) {
                $message->to($email)
                    ->subject("{$appName} - Password Reset OTP");
            }
        );
    }

    /**
     * Mask email or phone for display
     */
    private function maskIdentifier(string $identifier): string
    {
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $parts = explode('@', $identifier);
            $name = $parts[0];
            $maskedName = substr($name, 0, 2) . str_repeat('*', max(0, strlen($name) - 2));
            return $maskedName . '@' . $parts[1];
        }

        $len = strlen($identifier);
        if ($len >= 7) {
            return substr($identifier, 0, 3) . '****' . substr($identifier, -3);
        }
        
        return substr($identifier, 0, 1) . '****';
    }
}
