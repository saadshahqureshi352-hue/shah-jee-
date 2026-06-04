<?php

namespace App\Providers\Filament;

use Closure;
use Illuminate\Http\Request;

class LayoutOnlyGuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!config('filament_layout_only.enabled', false)) {
            return $next($request);
        }

        $path = trim($request->path(), '/');

        // Allow authentication routes to function (login, password reset, logout)
        if (str_contains($path, 'login') || str_contains($path, 'password') || str_contains($path, 'logout')) {
            return $next($request);
        }

        // Allow Livewire & asset requests through so the panel loads
        if (str_contains($path, 'livewire') || str_contains($path, 'css') || str_contains($path, 'js')) {
            return $next($request);
        }

        // Serve the layout-only placeholder view for all other requests
        return response()->view('filament.layout-only');
    }
}
