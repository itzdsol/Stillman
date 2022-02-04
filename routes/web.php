<?php

use Illuminate\Support\Facades\Route;
//use Mail;
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
Route::get('/cache-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    return Redirect::back()->with('success', 'All cache cleared successfully.');
});

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('index');
Route::get('/main-page', [App\Http\Controllers\LandingController::class, 'mainPage'])->name('mainPage');


Route::get('privacy-policy', [App\Http\Controllers\LandingController::class, 'privacyPolicy'])->name('privacyPolicy');
Route::get('terms-and-conditions', [App\Http\Controllers\LandingController::class, 'termCondition'])->name('termCondition');
Route::get('about-us', [App\Http\Controllers\LandingController::class, 'aboutUs'])->name('aboutUs');
Route::get('contact-us', [App\Http\Controllers\LandingController::class, 'contactUs'])->name('contactUs');