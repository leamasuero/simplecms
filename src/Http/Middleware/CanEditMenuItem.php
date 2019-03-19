<?php

namespace Lebenlabs\SimpleCMS\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CanEditMenuItem
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest() ) {
            return redirect()->guest('login');
        }

        if (!Auth::user()->canEditMenuItem()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                abort(403);
            }
        }

        return $next($request);
    }
}
