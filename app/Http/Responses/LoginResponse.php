<?php

namespace App\Http\Responses;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LoginResponse implements LoginResponseContract {

    /**
     * @param  $request
     * @return mixed
     */
    public function toResponse($request) {
        // dd($request, $request->input('lang'));
        $home = RouteServiceProvider::HOME;
        $lang = $request->input('lang');
        // dd('/' . $lang .'/'. $home);

        // Redirect to Admin
        if(auth()->user()->roles->name === 'admin') {
            $home = '/admin/dashboard';
        }

        // Redirect to Moderator
        if(auth()->user()->roles->name === 'moderator') {
            $home = '/admin/dashboard';
        }

        // Redirect to Write
        if(auth()->user()->roles->name === 'writer') {
            $home = '/admin/dashboard';
        }

        if(auth()->user()->roles->name === 'customer') {
            User::set_last_login();
        }

        // if(!empty(auth()->user()->languages)) {
        //     if(auth()->user()->languages->name != 'nl') {
        //         $home = '/' . auth()->user()->languages->name . $home;
        //     }
        // }

        // if(!empty(auth()->user()->languages)) {
            // if(auth()->user()->languages->name != 'nl') {
                $home = '/' . $lang . $home;
            // }
        // }

        // $home = LaravelLocalization::localizeUrl('/'.$home);

        // return redirect()->intended($home);
        return $home;
    }
}
