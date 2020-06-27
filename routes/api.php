<?php

use Illuminate\Http\Request;

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

// Auth Routes
Route::domain('websolutions.test')->group(function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('check-domain', 'Auth\RegisterController@checkDomain');
    Route::post('domain-login', 'Auth\LoginController@domainLogin');
});

Route::middleware(['tenant.exists'])->group(function () {
    // Login
    Route::post('login', 'Auth\LoginController@login');
    // Email verification;
    Route::get('email-verification', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::get('email-resend', 'Auth\VerificationController@resend')->name('verification.resend');
    Route::post('forgot-password', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('api.forgot-password');
    Route::post('reset-password', 'Auth\ResetPasswordController@reset')->name('api.reset-password');
    // Auth api
    Route::group(['middleware' => ['auth:api', 'verified:api']], function () {
        Route::post('logout', 'Auth\LoginController@logout');
        Route::get('profile', 'Api\UserController@profile');
        Route::apiResource('users', 'Api\UserController');
        Route::apiResource('roles', 'Api\RoleController');
        Route::apiResource('permissions', 'Api\PermissionController');
        Route::apiResource('groups', 'Api\GroupController');
    });
});
