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

Route::post('auth/login/', 'API\AuthController@login');
Route::post('auth/register', 'API\RegistrationController@register');
Route::get('email/verify/{token}', 'Auth\VerificationController@verifyEmail')->name('email.verify');

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::post('auth/logout', 'API\AuthController@logout');

    Route::get('user', 'API\UserController@user');
});

Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'API\AuthController@refresh');
});

Route::resource('products', 'API\ProductController');
Route::get('merchant/products/{id}', 'API\ProductController@getProducts');
Route::get('/product/search', 'API\ProductController@searchProduct');
