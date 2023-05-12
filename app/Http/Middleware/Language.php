<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Language {

    public function handle($request, Closure $next) {
        $lang = LaravelLocalization::setLocale();

        if(empty($lang)) {
            $lang = config('app.fallback_locale');
        }

        App::setLocale($lang);
        LaravelLocalization::setLocale($lang);

        $default_language = config('app.fallback_locale');
        $current_url = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $defaultLanguage = '/'.$default_language.'/';

        if($defaultLanguage == "/en/") {
            if(isset($_POST) && empty($_POST)) {
                if(stristr($current_url, $defaultLanguage) == true) {
                    $url = str_replace($defaultLanguage, '/', $current_url);
                    $url = str_replace(request()->getHttpHost(), '', $url);
                    $url = rtrim($url, '/');
                    return redirect($url);
                }
            }
        } else {
            if(stristr($current_url, $defaultLanguage) == true) {
                $url = str_replace($defaultLanguage, '/', $current_url);
                $url = str_replace(request()->getHttpHost(), '', $url);
                $url = rtrim($url, '/');
                return redirect($url);
            }
        }

        return $next($request);
    }

}
