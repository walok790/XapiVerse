<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    /**
     * Check installation status on EVERY request.
     * - Not installed + visiting /install → allow through
     * - Not installed + visiting anything else → redirect to /install
     * - Installed + visiting /install → redirect to /
     * - Installed + visiting anything else → allow through
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = File::exists(storage_path('installed/installed.lock'));

        // ─── NOT INSTALLED ─────────────────────────────────────────
        if (!$isInstalled) {
            // Allow install routes through
            if ($request->is('install') || $request->is('install/*')) {
                return $next($request);
            }

            // Everything else → redirect to installer
            return redirect()->route('install.requirements');
        }

        // ─── ALREADY INSTALLED ─────────────────────────────────────
        if ($isInstalled) {
            // Block access to installer
            if ($request->is('install') || $request->is('install/*')) {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
