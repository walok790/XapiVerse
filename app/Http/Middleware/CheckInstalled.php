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
     * Also switches to file-based sessions during installation to avoid DB dependency.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isInstalled = File::exists(storage_path('installed/installed.lock'));

        // If accessing installer routes
        if ($request->is('install*')) {
            if ($isInstalled) {
                return redirect('/');
            }

            // Switch to file session during installation (database may not exist yet)
            config(['session.driver' => 'file']);

            return $next($request);
        }

        // If accessing app routes but not installed
        if (!$isInstalled) {
            // Switch to file session for the redirect too
            config(['session.driver' => 'file']);
            return redirect()->route('install.requirements');
        }

        return $next($request);
    }
}
