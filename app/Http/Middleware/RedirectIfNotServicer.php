<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNotServicer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param string $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'servicer')
    {

        if (!auth()->guard($guard)->check()) {
            $request->session()->flash('error', 'You must be an servicer to see this page');
            return redirect(route('servicer.login'));
        }
         
        return $next($request);
    }
}
