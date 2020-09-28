<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::get('unauthenticated', 'PassportController@unAuthenticated')->name('unAuthenticated');
//Route::post('login', 'PassportController@login');
//Route::post('signup', 'PassportController@signup');
//Route::post('validateToken', 'PassportController@validateToken');
//Route::post('password/send-password-reset-link', 'ForgotPasswordController@sendPasswordResetLink');
//Route::post('password/reset', 'ResetPasswordController@resetPassword');
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('/business_categories', function (Request $request) {
    return 'a';
});

//Route::get('a', 'App\Http\Controllers\BusinessCategoryController@index');

Route::apiResources([
    'business_category' => App\Http\Controllers\BusinessCategoryController::class,
]);

//Route::apiResources([
//    'business_categories' => App\Http\Controllers\BusinessCategoryController::class,
//]);

