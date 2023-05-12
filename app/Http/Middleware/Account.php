<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Account {

    public function handle($request, Closure $next, $guard = null) {
        if(Auth::guard($guard)->check()) {
            if(auth()->user()->roles->name !== 'customer') {
                return redirect()->route('index');
            }
        }

        return $next($request);
    }

}
