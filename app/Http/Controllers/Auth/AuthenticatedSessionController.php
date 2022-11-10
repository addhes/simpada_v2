<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Parameter;

use Log;
class AuthenticatedSessionController extends Controller
{

    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {


        $request->authenticate();

        $request->session()->regenerate();

        $redirectTo = request()->redirectTo;

        // dd($request);
        $company = Parameter::where('param_key', Auth::user()->company_code)->first()->param_text;
        session(['company' => $company]);

        if (Auth::user()->hasAnyRole(['label']) ) {// do your margic here
            return redirect()->route('label.dashboard');
        }
        elseif (Auth::user()->hasAnyRole(['finance']) ) {// do your margic here
            return redirect()->route('finance.dashboard');
        }
        elseif (Auth::user()->hasAnyRole(['director']) ) {// do your margic here
            return redirect()->route('director.dashboard');
        }
        else if ($redirectTo) {
            return redirect($redirectTo);
        } else {
            return redirect(RouteServiceProvider::HOME);
        }
        
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
