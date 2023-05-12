<?php
    Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => [], 'namespace' => '\App\Http\Livewire\DatingTemplateProfile1'], function ($router) {
        $lang = App::getLocale();
        if ($lang == 'nl'):
            Route::get('/', Home::class)->name('home');
            Route::get('advertenties', AllAds::class)->name('ads');
            Route::get('blogs', Blog::class)->name('blog');
            Route::get('regions', Region::class)->name('regions');
            Route::get('regions/{name}', Province::class)->name('regions.name');
            Route::get('categorieen', Categories::class)->name('all-categories');
            Route::get('blog/{url}', InteriorBlog::class)->name('interior-blog');
            Route::get('profielen/{url}', InteriorProfile::class)->name('interior-profile');
            Route::get('categorie/{url}', Category::class)->name('category');
            Route::get('categoryByCity/{category}', CitiesByCategory::class)->name('category-by-city');
            Route::post('zoeken', SearchProfile::class)->name('search');
            Route::get('zoeken/{name?}', SearchProfile::class)->name('search.name');
            Route::get('vind-een-date/{city}', FindDate::class)->name('find-date');
            Route::get('{article}-{articleId}/{city}', SeoPageGenerated::class)->name('seo-pages');
        else:
            Route::get('/', Home::class)->name('home');
            Route::get('ads', AllAds::class)->name('ads');
            Route::get('blogs', Blog::class)->name('blog');
            Route::get('regions', Region::class)->name('regions');
            Route::get('regions/{name}', Province::class)->name('regions.name');
            Route::get('categories', Categories::class)->name('all-categories');
            Route::get('blog/{url}', InteriorBlog::class)->name('interior-blog');
            Route::get('profiles/{url}', InteriorProfile::class)->name('interior-profile');
            Route::get('category/{url}', Category::class)->name('category');
            Route::get('category-by-city/{category}', CitiesByCategory::class)->name('category-by-city');
            Route::post('search', SearchProfile::class)->name('search');
            Route::get('search/{name?}', SearchProfile::class)->name('search.name');
            Route::get('find-a-date/{city}', FindDate::class)->name('find-date');
            Route::get('{article}-{articleId}/{city}', SeoPageGenerated::class)->name('seo-pages');
        endif;
    });