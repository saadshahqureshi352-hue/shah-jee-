<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureShipperApproved
{
    /**
     * Agar logged-in user ka role 'shipper' hai aur wo approved nahi hai,
     * to dashboard aur baaki sabhi pages ki jagah sirf pending-approval page dikhao.
     *
     * Admin aur approved shippers ko normally allow karo.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->role === 'shipper' && !$user->is_approved) {
            // Allow only the pending-approval page and logout route
            $allowedRoutes = ['auth/pending-approval', 'logout', 'pending-approval'];

            foreach ($allowedRoutes as $route) {
                if ($request->is($route) || $request->is($route . '/*')) {
                    return $next($request);
                }
            }

            // Agar koi specific route hai jo dashboard ya kisi aur page ka hai, block karo
            return response()->view('auth.pending-approval');
        }

        return $next($request);
    }
}