<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if($this->app->environment('production')) {
        \URL::forceScheme('https');
        }
        
        $test   = false;
        $domain = '';

        if(!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $domain = "https://".$_SERVER['HTTP_X_FORWARDED_HOST'];
        } else {
            if(!empty(@$_SERVER['HTTP_HOST'])) {
                $domain = "https://".$_SERVER['HTTP_HOST'];
            }
        }

        if($test == false) {
            \URL::forceRootUrl($domain);
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } else {
            $domain = "http://localhost:8000";
            \URL::forceRootUrl($domain);
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        }
    }
}
