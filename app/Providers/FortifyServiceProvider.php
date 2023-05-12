<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\Country;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\LogoutResponse;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use PHPageBuilder\Theme;
use PHPageBuilder\Modules\GrapesJS\PageRenderer;
use PHPageBuilder\Repositories\PageRepository;
use App\Models\PagebuilderTranslation;

class FortifyServiceProvider extends ServiceProvider
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
        Fortify::registerView(function () {
            $pageId = PagebuilderTranslation::select('page_id')->where('route', '/register')->first();
            $config = config('pagebuilder.theme');
            $config['active_theme'] = site_theme();
            $theme  = new Theme($config, site_theme());
            $page   = (new PageRepository)->findWithId($pageId->page_id);
            $pageRenderer = new PageRenderer($theme, $page);
            return $pageRenderer->render();
            // return view('auth.register');
        });

        Fortify::loginView(function () {
            $pageId = PagebuilderTranslation::select('page_id')->where('route', '/login')->first();
            $config = config('pagebuilder.theme');
            $config['active_theme'] = site_theme();
            $theme  = new Theme($config, site_theme());
            $page   = (new PageRepository)->findWithId($pageId->page_id);
            $pageRenderer = new PageRenderer($theme, $page);
            return $pageRenderer->render();
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();
     
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(LogoutResponseContract::class, LogoutResponse::class);
    }
}
