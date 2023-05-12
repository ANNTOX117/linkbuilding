<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin {

    public function handle($request, Closure $next, $guard = null) {
        if(Auth::guard($guard)->check()) {
            if(!in_array(auth()->user()->roles->name, array('admin', 'moderator', 'writer'))) {
                return redirect()->route('index');
            }
        }

        return $next($request);
    }

}
