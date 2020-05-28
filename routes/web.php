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
Route::group([
        'prefix' => LaravelLocalization::setLocale(),
       // 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ]
    , function () {
        Route::domain('websolutions.test')->group(function () {

            // Login Routes
            // For sudomain
            Route::get('login', 'Auth\LoginController@showDomainForm')->name('login.domain');
            // For the main domain
            Route::post('login', 'Auth\LoginController@routeToTenant');

            // Landing Page Routes
            Route::get('/', function () {
                return view('welcome');
            });

            // Registration Routes...
            Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
            Route::post('register', 'Auth\RegisterController@register');

            // Catch All Route
            Route::any('{any}', function () {
                abort(404);
            })->where('any', '.*');
        });

        // Not logged in
        Route::get('/', function () {
            return view('welcome');
        });

        // Ensure that the tenant exists with the tenant.exists middleware;
        Route::middleware('tenant.exists')->group(function () {
            // Routes to dashboard
            Route::any('dashboard/{any?}', 'HomeController@index')->where('any', '.*');
            Route::any('/', function () {
                return redirect('dashboard');
            })->where('any', '.*')->middleware(['auth' => 'verified']);

            // Login Routes
            Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
            Route::post('login', 'Auth\LoginController@login');
            Route::post('logout', 'Auth\LoginController@logout')->name('logout');

            // Password Reset Routes
            Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

            // Email Verification Routes
            Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
            Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
            Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

            // Not Found inside SPA
            Route::any('{any}', function () {
                return redirect('dashboard/not-found');
            })->where('any', '.*')->middleware(['auth' => 'verified']);
        });
    });