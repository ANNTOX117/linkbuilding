<?php

use Illuminate\Support\Facades\Redirect;

    Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['xss', 'setTheme:linksbuildingNew'], 'namespace' => '\App\Http\Livewire\Pages'], function ($router) use ($domain) {
        Route::get('/', Home::class)->name('homepage');
        Route::get('index.html', Home::class)->name('homepage'); 
        Route::get('/{url?}', function ($url) {
            return Redirect::to('/');
        })->where(['url' => '|index.html']);
        $website  = \App\Models\Site::get_info($domain);
        #nl
        //$lang = App::getLocale();
        $lang = "nl";
        if ($lang == 'nl'):
        Route::get('categorieen', Daughters::class)->name('subpages');
        Route::get('contact', Contact::class)->name('contact');
        Route::get('voorwaarden', Terms::class)->name('terms');
        Route::get('privacybeleid', Privacy::class)->name('privacy');
        Route::get('blogs/{category?}/{id}', Blog::class)->name('blog')->where('id', '[\d]+');
        Route::get('blogs', Blog::class)->name('blogs');
        //Route::get('post', Post::class)->name('subpages');
        Route::get('blog/{category}/{url}', Post::class)->name('post');
        else:
        #en
        Route::get('categories', Daughters::class)->name('subpages');
        Route::get('contact', Contact::class)->name('contact');
        Route::get('terms', Terms::class)->name('terms');
        Route::get('privacy', Privacy::class)->name('privacy');
        Route::get('blogs/{category?}/{id}', Blog::class)->name('blog');
        Route::get('blogs', Blog::class)->name('blogs');
        // Route::get('post/{url}', Post::class)->name('post');
        Route::get('blog/{category}/{url}', Post::class)->name('post');
        endif;

        #cookies
        Route::get('cookies', [App\Http\Controllers\Pages\AjaxController::class, 'cookies'])->name('cookies');

        #pages, any language
        Route::get('{slug}', Page::class);

        Route::get('login', function () {
            return view('errors.404');
        })->name('login');
       
        #Pages
        
    
        #Subdomain
        Route::group(['prefix' => LaravelLocalization::setLocale(), 'domain' => $domain, 'middleware' => ['xss'], 'namespace' => '\App\Http\Livewire\Pages'], function ($router) {
            Route::get('/', Categories::class)->name('subdomain');
        });
    });