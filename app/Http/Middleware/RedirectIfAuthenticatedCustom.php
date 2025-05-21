<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticatedCustom
{
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::guard('client')->check()) {
            return redirect()->route('client.dashboard');
        }

        return $next($request);
    }
}
