<?php

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

Route::get('/', 'HomeController@index');

Route::prefix('products')->group(function(){
    Route::get('/', 'ProductsController@index');
    Route::get('/detail', 'ProductsController@show');
});

Route::get('/about', 'AboutController@index');
Route::get('/faqs', 'FaqsController@index');
Route::get('/contacts', 'ContactsController@index');
Route::get('/politics', 'PoliticsController@index');
Route::get('/conditions', 'ConditionsController@index');
Route::get('/search', 'SearchController@index');









/*
 * Robots
 */
Route::get('robots.txt', function () {
    return response(view('robots'))->header('Content-Type', 'text/plain');
});
/*
 * Sitemap
 */
Route::get('sitemap.xml', 'SitemapController@index');


/*
|--------------------------------------------------------------------------
| Translated routes
|--------------------------------------------------------------------------
*/
try{
    $alt_langs_arr = \App\Models\Language::localesArr();
}catch(\Exception $e){
    $alt_langs_arr = [config('app.fallback_locale')];
}

$alt_langs_arr = array_diff(
    $alt_langs_arr,
    [
        config('app.fallback_locale')
    ]
);

/**
 *  Set up locale and locale_prefix if other language is selected
 */
if(Request::segment(1) && in_array(Request::segment(1), $alt_langs_arr)) {
    app()->setLocale(Request::segment(1));
    config(['app.locale_prefix' => Request::segment(1)]);
}

Route::group(['prefix' => config('app.locale_prefix')], function() {
    Route::get('/', 'HomeController@index');

    Route::get(trans('routes.products'), 'ProductsController@index');
    Route::get(trans('routes.product') . '/{slug}', 'ProductsController@show');
    Route::get('load-sizes', 'ProductsController@loadSizes');

    Route::get(trans('routes.about'), 'AboutController@index');

    Route::get(trans('routes.faqs'), 'FaqsController@index');

    Route::get(trans('routes.shipping-and-returns'), 'ShippingInfoController@index');
    Route::get(trans('routes.privacy-policy'), 'PoliciesInfoController@index');
    Route::get(trans('routes.terms-and-conditions'), 'TermsInfoController@index');



    Route::get(trans('routes.about'), 'AboutController@index');

    Route::get(trans('routes.contacts'), 'ContactsController@index');

    Route::get(trans('routes.search'), 'SearchController@index');


    Auth::routes();


    /*
     * Private Area
     */
    Route::get(trans('routes.user-welcome'), 'PrivateArea\UserWelcomeController@index');
    Route::get(trans('routes.user-orders'), 'PrivateArea\UserOrdersController@index');
    Route::get(trans('routes.user-favourites'), 'PrivateArea\UserFavouritesController@index');
    Route::get(trans('routes.user-profile'), 'PrivateArea\UserController@edit');
    Route::get(trans('routes.user-password'), 'PrivateArea\UserPasswordController@edit');


    /*
     * Products xml (Google Shopping)
     */
    Route::get('products.xml', 'GoogleShoppingController@xml');


    /*
     * PayPal
     */
    Route::prefix('paypal')->group(function(){
        Route::get('credit-card', '\App\Packages\PayPal\PayPalPaymentController@creditCard');
        Route::get('express-checkout', '\App\Packages\PayPal\PayPalPaymentController@expressCheckout');
        Route::get('success', '\App\Packages\PayPal\PayPalPaymentController@success');
        Route::get('fail', '\App\Packages\PayPal\PayPalPaymentController@fail');
    });


    /*
     * IfThen
     */
    Route::prefix('ifthen')->group(function(){
        Route::get('payment-received', '\App\Packages\IfThen\IfThenController@paymentReceived');
    });


    /*
     * Wishlist
     */
    Route::prefix('wishlist')->group(function () {
        Route::post('create', 'Store\WishlistController@create');
        Route::delete('{wishlist_item}', 'Store\WishlistController@destroy');
    });


    /*
     * Cart
     */
    Route::prefix('cart')->group(function () {
        Route::post('add', 'Store\CartController@add');
        Route::post('update', 'Store\CartController@update');
        Route::post('remove', 'Store\CartController@remove');
        Route::post('add-shipping-method', 'Store\CartController@addShippingMethod');
    });


    /*
     * Checkout
     */
    Route::prefix('checkout')->group(function () {
        Route::get('/', 'CheckoutController@index');
        Route::get('items', 'CheckoutController@items');
        Route::get('shipping-methods', 'CheckoutController@shippingMethods');
        Route::get('summary', 'CheckoutController@summary');
        Route::get('conclude/{order_id}', 'CheckoutController@conclude');
    });

    Route::prefix('store')->group(function () {
        Route::get('payment-received', 'Store\StoreController@paymentReceived');
    });

    /*
     * Voucher
     */
    Route::prefix('voucher')->group(function () {
        Route::post('add', 'Store\VoucherController@add');
        Route::post('remove', 'Store\VoucherController@remove');
    });

    /*
     * Points
     */
    Route::prefix('points')->group(function () {
        Route::post('add', 'Store\PointsController@add');
        Route::post('remove', 'Store\PointsController@remove');
    });

    /*
     * Order
     */
    Route::post('order/store', 'Store\OrderController@store');


    /*
    |--------------------------------------------------------------------------
    | Forms
    |--------------------------------------------------------------------------
    */

    /* newsletter */
    Route::post('subscribe-newsletter', 'Newsletter\SubscriptionController@store');

    /* private area */
    Route::prefix('user')->group(function () {
        Route::patch('update/{user}', 'PrivateArea\UserController@update');
        Route::post('change-password/{user}', 'PrivateArea\UserPasswordController@update');
    });
});
