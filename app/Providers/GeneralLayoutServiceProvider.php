<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\SiteExtraSetting;
use Illuminate\Support\ServiceProvider;

class GeneralLayoutServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.datingTemplateProfile1', function ($view) {
            $domain  = domain();
            $domain = "hotpaginas.nl";
            $website  = \App\Models\Site::get_info($domain);
            if (isset($website) && !empty($website)) {
                $extra_settings = SiteExtraSetting::where("site_id",$website->id)->first();
                $view->with('extraSettings', $extra_settings);
            }
        });
    }
}
