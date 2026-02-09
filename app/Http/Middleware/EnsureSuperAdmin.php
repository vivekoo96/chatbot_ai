<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->is_super_admin) {
            // If they are impersonating, give a better message than 403
            if (session()->has('admin_impersonator_id')) {
                return redirect()->route('dashboard')->with('error', 'Admin pages are restricted while impersonating a user. Please return to your admin account first.');
            }

            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}

