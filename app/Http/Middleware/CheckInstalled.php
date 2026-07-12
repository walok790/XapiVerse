<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class CheckInstalled
{
    /**
     * Redirect to installer if not installed, or block installer if already installed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = File::exists(storage_path('installed/installed.lock'));

        // If accessing installer routes
        if ($request->is('install*')) {
            if ($isInstalled) {
                return redirect('/');
            }
            return $next($request);
        }

        // If accessing app routes but not installed
        if (!$isInstalled) {
            return redirect()->route('install.requirements');
        }

        return $next($request);
    }
}
