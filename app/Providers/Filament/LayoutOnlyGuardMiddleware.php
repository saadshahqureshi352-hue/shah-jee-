<?php

namespace App\Providers\Filament;

use Closure;
use Illuminate\Http\Request;

class LayoutOnlyGuardMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // If layout-only mode is enabled, prevent any DB-backed logic by short-circuiting.
        // Filament will still render its layout (HTML) but we stop execution that would hit DB.
        if (filter_var(env('FILAMENT_LAYOUT_ONLY', false), FILTER_VALIDATE_BOOL)) {
            return redirect()->to(
                \Filament\Pages\Page::getUrl('layout-only')
            );
        }

        return $next($request);
    }
}

