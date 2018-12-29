<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth/login/', 'PrivateAPI\AuthController@login');
Route::post('auth/register', 'PublicAPI\RegistrationController@register');
Route::get('email/verify/{token}', 'Auth\VerificationController@verifyEmail')->name('email.verify');

Route::get('products/search', 'PublicAPI\ProductController@searchProduct');
Route::get('products/{id}', 'PublicAPI\ProductController@getProduct');
Route::get('new-products', 'PublicAPI\ProductController@getNewProducts');
Route::get('all-products', 'PublicAPI\ProductController@getAllProducts');

Route::get('carousels', 'PublicAPI\CarouselController@getCarousels');

Route::get('provinces', 'PublicAPI\RegionalController@getProvinces');
Route::get('cities', 'PublicAPI\RegionalController@getCities');
Route::get('subdistricts', 'PublicAPI\RegionalController@getSubdistricts');
Route::post('shipping-cost', 'PublicAPI\RajaOngkirController@getShippingCost');

Route::group(['middleware' => ['jwt.auth']], function(){
    Route::post('auth/logout', 'API\AuthController@logout');

    Route::group(['middleware' => 'admin-guard', 'prefix' => 'admin'], function () {
        Route::get('orders/status/{status}', 'PrivateAPI\OrderController@getOrdersByStatus');
        Route::post('orders/{id}/accept-order', 'PrivateAPI\OrderController@acceptedOrderByAdmin');
        Route::post('orders/{id}/reject-order', 'PrivateAPI\OrderController@rejectedOrderByAdmin');

        Route::get('banners', 'API\BannerController@getBanners');
        Route::post('banners/create', 'PrivateAPI\BannerController@storeBanner');
        Route::post('banners/{id}/update', 'PrivateAPI\BannerController@updateBanner');
        Route::post('banners/{id}/delete', 'PrivateAPI\BannerController@deleteBanner');

        Route::post('carousels/create', 'PrivateAPI\CarouselController@storeCarousel');
        Route::post('carousels/{id}/update', 'PrivateAPI\CarouselController@updateCarousel');
        Route::post('carousels/{id}/delete', 'PrivateAPI\CarouselController@deleteCarousel');

        Route::post('profiles/update', 'PrivateAPI\ProfileController@updateProfileAdmin');
        Route::post('profiles/update-password', 'PrivateAPI\ProfileController@updatePassword');
    });

    Route::group(['middleware' => 'merchant-guard', 'prefix' => 'merchant'], function () {
        Route::get('products', 'PrivateAPI\ProductController@getProductsByMerchant');
        Route::post('products/create', 'PrivateAPI\ProductController@storeProduct');
        Route::post('products/{id}/update', 'PrivateAPI\ProductController@updateProduct');
        Route::post('products/{id}/delete', 'PrivateAPI\ProductController@deleteProduct');

        Route::get('orders', 'PrivateAPI\OrderController@getMerchantOrders');
        Route::get('orders/{id}', 'PrivateAPI\OrderController@getMerchantSingleOrder');

        Route::post('profiles/create-profile', 'PrivateAPI\ProfileController@storeProfile');
        Route::post('profiles/update-password', 'PrivateAPI\ProfileController@updatePassword');
        Route::post('profiles/update-user','PrivateAPI\ProfileController@updateProfileUser');
    });

    Route::group(['middleware' => 'customer-guard', 'prefix' => 'customer'], function () {
        Route::get('carts', 'PrivateAPI\CartController@getProductInCart');
        Route::post('carts/create', 'PrivateAPI\CartController@insertProductToCart');

        Route::get('orders', 'PrivateAPI\OrderController@getCustomerOrders');
        Route::post('orders/create', 'PrivateAPI\OrderController@createCustomerOrder');
        Route::get('orders/{id}', 'PrivateAPI\OrderController@getCustomerSingleOrder');
        Route::post('orders/{id}/upload-proof-of-payment', 'PrivateAPI\OrderController@uploadProofOfPayment');
       
        
        Route::post('profiles/create-profile', 'PrivateAPI\ProfileController@storeProfile');
        Route::post('profiles/update-password', 'PrivateAPI\ProfileController@updatePassword');
        Route::post('profiles/update-user','PrivateAPI\ProfileController@updateProfileUser');
    });

    Route::get('user', 'PublicAPI\UserController@user');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'PrivateAPI\AuthController@refresh');
});


