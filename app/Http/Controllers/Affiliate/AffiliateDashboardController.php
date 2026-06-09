<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;


class AffiliateDashboardController extends Controller
{
    /**
     * Public affiliate dashboard.
     * Identity is resolved by `ref` or `code` (referral_code) from query string.
     */
public function index(Request $request)
{
    // If affiliate is logged in, use their affiliate record
    if (Auth::guard('affiliate')->check()) {
        $user = Auth::guard('affiliate')->user();
        $affiliate = Affiliate::query()->where('user_id', $user->id)->first();
        if (! $affiliate) {
            abort(404, 'Affiliate profile not found.');
        }
    } else {
        // Fallback to ref/code query parameter
        $code = trim((string) $request->query('ref', $request->query('code', '')));
        if ($code === '') {
            abort(404, 'Affiliate reference is missing.');
        }
        $affiliate = Affiliate::query()
            ->where('referral_code', $code)
            ->first();
        if (! $affiliate) {
            abort(404, 'Affiliate profile not found.');
        }
    }

    $activeTab = (string) $request->query('tab', 'dashboard');

    // Compute notification unread count (replicating Livewire component logic)
    $notificationsUnreadCount = 0;
    if ($affiliate) {
        // Recent new shipper
        $recentShipper = User::query()
            ->where('role', 'shipper')
            ->where('referred_by', $affiliate->referral_code)
            ->orderByDesc('created_at')
            ->first();

        $unreadCount = 0;
        if ($recentShipper) {
            $unreadCount++;
        }

        // Recent clear commissions
        $recentCommissions = DB::table('affiliate_commissions_ledger')
            ->where('affiliate_id', $affiliate->id)
            ->where('status', 'clear')
            ->orderByDesc('created_at')
            ->limit(2)
            ->count();
        $unreadCount += $recentCommissions;

        // Recent COD cleared
        $codCleared = DB::table('affiliate_commissions_ledger')
            ->where('affiliate_id', $affiliate->id)
            ->where('status', 'clear')
            ->orderByDesc('created_at')
            ->limit(1)
            ->count();
        $unreadCount += $codCleared;

        $notificationsUnreadCount = $unreadCount;
    }

    return view('livewire.affiliate.dashboard', [
        'activeTab' => $activeTab,
        'affiliate' => $affiliate,
        'notificationsUnreadCount' => $notificationsUnreadCount,
    ]);
}
}


