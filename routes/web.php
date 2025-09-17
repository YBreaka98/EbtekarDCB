<?php

use Illuminate\Support\Facades\Route;
use Ybreaka98\EbtekarDCB\Http\Controllers\DemoController;

/*
|--------------------------------------------------------------------------
| EbtekarDCB Web Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the EbtekarDCB package views and API endpoints.
| These routes provide a complete implementation example for DCB services.
|
*/

Route::group(['prefix' => 'ebtekardcb', 'as' => 'ebtekardcb.'], function () {

    // Web Pages
    Route::get('/', [DemoController::class, 'landing'])->name('landing');
    Route::get('/profile', [DemoController::class, 'profile'])->name('profile');
    Route::get('/error', [DemoController::class, 'error'])->name('error');
    Route::get('/terms', [DemoController::class, 'terms'])->name('terms');
    Route::get('/privacy', [DemoController::class, 'privacy'])->name('privacy');
    Route::get('/contact', [DemoController::class, 'contact'])->name('contact');

    // Mobile WebView Pages
    Route::group(['prefix' => 'mobile', 'as' => 'mobile.'], function () {
        Route::get('/login', [DemoController::class, 'mobileLogin'])->name('login');
        Route::get('/otp', [DemoController::class, 'mobileOtp'])->name('otp');
        Route::get('/profile', [DemoController::class, 'mobileProfile'])->name('profile');
        Route::get('/action-otp', [DemoController::class, 'mobileActionOtp'])->name('action-otp');
    });

    // API Endpoints
    Route::group(['prefix' => 'api', 'as' => 'api.'], function () {

        // Anti-fraud / Compliance Protect
        Route::get('/request-protected-script', [DemoController::class, 'requestProtectedScript'])
            ->name('request-protected-script');

        // Authentication
        Route::post('/login', [DemoController::class, 'login'])->name('login');
        Route::post('/confirm-login', [DemoController::class, 'confirmLogin'])->name('confirm-login');

        // Subscription Management
        Route::get('/subscription-details', [DemoController::class, 'subscriptionDetails'])
            ->name('subscription-details');
        Route::post('/subscription-activation', [DemoController::class, 'subscriptionActivation'])
            ->name('subscription-activation');
        Route::post('/subscription-activation-confirm', [DemoController::class, 'subscriptionActivationConfirm'])
            ->name('subscription-activation-confirm');
        Route::post('/unsubscribe', [DemoController::class, 'unsubscribe'])
            ->name('unsubscribe');
        Route::post('/unsubscribe-confirm', [DemoController::class, 'unsubscribeConfirm'])
            ->name('unsubscribe-confirm');

        // Product Management
        Route::post('/buy-product', [DemoController::class, 'buyProduct'])->name('buy-product');
        Route::post('/buy-product-confirm', [DemoController::class, 'buyProductConfirm'])
            ->name('buy-product-confirm');

        // Utility
        Route::get('/subscriber-transactions', [DemoController::class, 'subscriberTransactions'])
            ->name('subscriber-transactions');
        Route::get('/subscription-list', [DemoController::class, 'subscriptionList'])
            ->name('subscription-list');
    });
});

// Fallback route for old URLs
Route::get('/dcb', function () {
    return redirect()->route('ebtekardcb.landing');
})->name('dcb.redirect');