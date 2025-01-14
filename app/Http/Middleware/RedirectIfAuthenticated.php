<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Log;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if (Auth::guard($guard)->check()) {
            if (Auth::user()->hasAnyRole(['label']) ) {
                return redirect()->intended('/label');
            }elseif(Auth::user()->hasAnyRole(['finance']) ){
                return redirect()->intended('/finance');
            }elseif(Auth::user()->hasAnyRole(['employee'])){
                return redirect()->intended('/employee');
            }
            return redirect()->intended('/admin');
        }



        return $next($request);
    }
}
