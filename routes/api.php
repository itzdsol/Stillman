<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\OrderController;
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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::group(['middleware' => 'firebase.token'], function(){
    Route::post('authentication',[UserController::class, 'signup']);
    //Route::post('login',[UserController::class, 'login']);
    //Route::post('register', [UserController::class, 'register']);
});

Route::post('authentication/email',[UserController::class, 'emailsignup']);
Route::post('email/verify',[UserController::class, 'email_verify']);
Route::post('resend/email/otp',[UserController::class, 'resend_email_otp']);

Route::post('email/check',[UserController::class, 'email_check']);
Route::post('send/otp',[UserController::class, 'send_otp']);
Route::post('password/reset/otp/verify',[UserController::class, 'otp_verify']);
Route::post('reset/password',[UserController::class, 'reset_password']);

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('details',[UserController::class, 'details']);
    Route::post('change/password',[UserController::class, 'change_password']);
    Route::get('catalogs',[UserController::class, 'catalogs']);
    Route::get('products',[UserController::class, 'products']);
    Route::get('catalog/{id}/products', [UserController::class, 'catelog_products']);
    Route::get('product/{id}/details', [UserController::class, 'product_details']);
    Route::post('edit/profile',[UserController::class, 'edit_profile']);
    Route::post('filter/products',[UserController::class, 'filter_products']);
    Route::post('filter/new/products',[UserController::class, 'filter_new_products']);
    Route::post('filter/multi/products',[UserController::class, 'filter_multi_products']);
    Route::post('edit/profile/image',[UserController::class, 'edit_profile_image']);
    Route::post('cart',[UserController::class, 'cart']);
    Route::get('cart',[UserController::class, 'get_cart']);
    Route::get('list/items',[UserController::class, 'get_listings']);

    Route::get('address',[UserController::class, 'get_address']);
    Route::delete('address/{id}',[UserController::class, 'delete_address']);
    Route::POST('add/address',[UserController::class, 'add_address']);
    Route::POST('edit/address/{id}',[UserController::class, 'edit_address']);
    Route::post('default/address',[UserController::class, 'default_address']);
    Route::get('default/address',[UserController::class, 'get_default_address']);

    Route::post('favourite',[UserController::class, 'add_favourite']);
    Route::get('favourite',[UserController::class, 'get_favourite']);

    Route::post('add/order',[OrderController::class, 'add_order']);
    Route::post('order/payment',[OrderController::class, 'order_payment']);

    Route::post('single/order',[OrderController::class, 'single_order']);

    Route::get('order',[OrderController::class, 'get_orders']);
    Route::get('order/{id}/details',[OrderController::class, 'get_order_details']);

    Route::post('order/cancel',[OrderController::class, 'cancel_order']);

    Route::post('device/register',[UserController::class, 'device_register']);

    Route::get('notifications',[UserController::class, 'get_notifications']);

    Route::get('lifestyles',[UserController::class, 'get_lifestyles']);

    Route::get('notify/user',[UserController::class, 'notify_user']);
});