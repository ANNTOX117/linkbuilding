<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', [App\Http\Controllers\CronController::class, 'index'])->name('test');

Route::get('create-transaction', [App\Http\Controllers\PayPalController::class, 'createTransaction'])->name('createTransaction');
Route::get('process-transaction', [App\Http\Controllers\PayPalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transaction', [App\Http\Controllers\Account\PaymentsController::class, 'successTransaction'])->name('successTransaction');
Route::get('renew-transaction/{order}', [App\Http\Controllers\Account\PaymentsController::class, 'renewTransaction'])->name('renewTransaction');
Route::get('cancel-transaction', [App\Http\Controllers\Account\PaymentsController::class, 'cancelTransaction'])->name('cancelTransaction');
Route::get('cancel-renew/{order}', [App\Http\Controllers\Account\PaymentsController::class, 'cancelRenew'])->name('cancelRenew');

Route::any('pages/{id}/build', [App\Http\Controllers\PageBuilderController::class, 'build'])->name('pagebuilder.build');
Route::any('pages/build', [App\Http\Controllers\PageBuilderController::class, 'build']);

$domain   = domain();
$category = subdomain();

//  $domain = "https://evcportfolio.nl";
//  $category = '';

$website  = \App\Models\Site::get_info($domain);

if(!empty($category)) {
    $domain = $category . '.' . $domain;
}



if(!empty($website)) {
    // session(['website'  => $website]);
    // session(['category' => $category]);
    //App::setLocale($website->languages->name);

    Route::get('login', function () {
        return view('errors.404');
    })->name('login');
   
    #Pages
    Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['xss', 'setTheme:linksbuildingNew'], 'namespace' => '\App\Http\Livewire\Pages'], function ($router) use ($domain) {




       Route::get('index.html', Home::class)->name('homepage');
       Route::get('/', Home::class)->name('homepage');



       $website  = \App\Models\Site::get_info($domain);
        #nl
 	    $lang = App::getLocale();

        if ($lang == 'nl'):
         Route::get('categorieen', Daughters::class)->name('subpages_nl');
         Route::get('contact', Contact::class)->name('contact_nl');
         Route::get('voorwaarden', Terms::class)->name('terms_nl');
         Route::get('privacybeleid', Privacy::class)->name('privacy_nl');
         Route::get('blogs/{category?}/{id}', Blog::class)->name('blog_nl')->where('id', '[\d]+');
         Route::get('blogs', Blog::class)->name('blogs_nl');
         //Route::get('post', Post::class)->name('subpages_nl');
         Route::get('blog/{category}/{url}', Post::class)->name('post_nl');
        else:
        #en
         Route::get('categories', Daughters::class)->name('subpages_en');
         Route::get('contact', Contact::class)->name('contact_en');
         Route::get('terms', Terms::class)->name('terms_en');
         Route::get('privacy', Privacy::class)->name('privacy_en');
         Route::get('blogs/{category?}/{id}', Blog::class)->name('blog_en');
         Route::get('blogs', Blog::class)->name('blogs_en');
         // Route::get('post/{url}', Post::class)->name('post_en');
         Route::get('blog/{category}/{url}', Post::class)->name('post_en');
        endif;

        #cookies
        Route::get('cookies', [App\Http\Controllers\Pages\AjaxController::class, 'cookies'])->name('cookies');

        #pages, any language
        Route::get('{slug}', Page::class);
    });

    #Subdomain
    Route::group(['prefix' => LaravelLocalization::setLocale(), 'domain' => $domain, 'middleware' => ['xss'], 'namespace' => '\App\Http\Livewire\Pages'], function ($router) {
        Route::get('/', Categories::class)->name('subdomain');
    });
} else {
    Route::group(['prefix' => LaravelLocalization::setLocale()], function ($router) {
  Route::get('/', [App\Http\Controllers\HomeController::class, 'build'])->name('index');

        Route::group([ 'namespace' => 'Laravel\Fortify\Http\Controllers', ], function () { 
            require(base_path('vendor/laravel/fortify/routes/routes.php')); 
    });

        
    });

//   Route::get('/', function () {
//     $renderedContent = $this->pageBuilder->renderPage($page, $pageTranslation->locale);
//         return view('home');
//     })->name('index');
       
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () { return view('dashboard'); })->name('dashboard');

# Verify email
Route::group(['prefix' => LaravelLocalization::setLocale()], function ($router) {
    Route::get('email/verify', function() { return view('auth.verify-email'); })->middleware('auth')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) { $request->fulfill(); return redirect('/dashboard'); })->middleware(['auth', 'signed'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) { $request->user()->sendEmailVerificationNotification(); return back()->with('status', 'verification-link-sent'); })->middleware(['auth', 'throttle:6,1'])->name('verification.send');
});

# Webhook
Route::group(['prefix' => LaravelLocalization::setLocale()], function ($router) {
    Route::post('payment/webhook', [App\Http\Controllers\Account\PaymentsController::class, 'webhook'])->name('webhook');
    Route::post('payment/renewal/webhook', [App\Http\Controllers\Account\PaymentsController::class, 'webhook_renewed'])->name('webhook_renewed');
});

#Admin
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['web', 'auth:sanctum', 'verified', 'admin', 'xss'], 'namespace' => '\App\Http\Livewire\Admin'], function ($router) {
    Route::get('admin/articles', Articles::class)->name('articles');
    Route::get('admin/authority', Authority::class)->name('authority');
    Route::get('admin/categories', Categories::class)->name('categories');
    Route::get('admin/dashboard', Dashboard::class)->name('dashboard');
    Route::get('admin/discounts', Discounts::class)->name('discounts');
    Route::get('admin/emails', Emails::class)->name('emails');
    Route::get('admin/general', General::class)->name('general');
    Route::get('admin/languages', Languages::class)->name('languages');
    Route::get('admin/linkages', Links::class)->name('links');
    Route::get('admin/packages', Packages::class)->name('packages');
    Route::get('admin/pages', Pages::class)->name('pages');
    Route::get('admin/payments', Payments::class)->name('payments');
    Route::get('admin/sites', Sites::class)->name('sites');
    Route::get('admin/metas', Metas::class)->name('metas');
    Route::get('admin/content', Content::class)->name('content');
    Route::get('admin/taxes', Taxes::class)->name('taxes');
    Route::get('admin/texts', Texts::class)->name('texts');
    Route::get('admin/users', Users::class)->name('users');
    Route::get('admin/wordpress', Wordpress::class)->name('wordpress');
    Route::get('admin/approvements', Approvements::class)->name('approvements');
    Route::get('admin/validate', Validate::class)->name('validate');
    Route::get('admin/profiles', Profiles::class)->name('profiles');
    Route::get('admin/templates', Templates::class)->name('templates');
    Route::get('admin/downloads/sites.csv', [App\Http\Controllers\Admin\DownloadsController::class, 'export_sites'])->name('download_sites');
});

#Account (customer)
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['auth:sanctum', 'verified', 'account', 'xss'], 'namespace' => '\App\Http\Livewire\Account'], function ($router) {
    Route::get('blogs', Blogs::class)->name('customer_blogs');
    Route::get('buy', Buy::class)->name('customer_buy');
    Route::get('cart', Cart::class)->name('customer_cart');
    Route::get('categories', Categories::class)->name('customer_categories');
    Route::get('checkout', Checkout::class)->name('customer_checkout');
    Route::get('dashboard', Dashboard::class)->name('customer_dashboard');
    Route::get('links', Links::class)->name('customer_links');
    Route::get('orders', Orders::class)->name('customer_orders');
    Route::get('packages', Packages::class)->name('customer_packages');
    Route::get('pages', Pages::class)->name('customer_pages');
    Route::get('renewal', Renewal::class)->name('customer_renewal');
    Route::get('support', Support::class)->name('customer_support');
    Route::get('buy-links', Buylinks::class)->name('customer_buylinks');
    Route::get('profile', Profile::class)->name('customer_profile');
    Route::get('settings', Settings::class)->name('customer_settings');
    Route::get('addbulk', Addbulk::class)->name('customer_addbulk');

    Route::get('download_addbulk', function () {

        $filename = public_path("linksbuildingNew/downloads/addbulk.csv");

        return Response::download($filename, 'addbulk.csv', ['Content-Description' =>  'File Transfer','Content-Type' => 'application/octet-stream','Content-Disposition' => 'attachment; filename=import.csv']);
    })->name('download_addbulk');

    Route::get('downloads/invoice/{name}.pdf', [App\Http\Controllers\Account\DownloadsController::class, 'invoice'])->name('download_invoice');

    Route::get('payment', [App\Http\Controllers\Account\PaymentsController::class, 'pay'])->name('pay');
    Route::get('payment/order/{order}', [App\Http\Controllers\Account\PaymentsController::class, 'transaction'])->name('transaction');
    Route::get('payment/renewal/{order}', [App\Http\Controllers\Account\PaymentsController::class, 'renewal'])->name('renewal');
    Route::get('payment/renewed/{order}', [App\Http\Controllers\Account\PaymentsController::class, 'renewed'])->name('renewed');
});

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['web', 'xss']], function () {

    Route::any('{uri}', ['uses' => 'App\Http\Controllers\WebsiteController@uri', 'as' => 'page'])->where('uri', '.*');
});

}
// Route::get('test', function (){
//     return view('auth.verify-email');
// });
