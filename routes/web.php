<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StripePaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegisterForm');
    Route::post('/register', 'register')->name('register');
    Route::get('/login', 'showLoginForm');
    Route::post('/login', 'login')->name('login');
});

Route::controller(DashboardController::class)->middleware('auth')->group(function () {
    Route::get('dashboard', 'dashboard')->name('dashboard');
    Route::get('/download-bible', 'downloadBible')->name('download.bible');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(StripePaymentController::class)->middleware('auth')->group(function () {
    Route::get('stripe-checkout', 'stripeCheckout')->name('stripe.checkout');
    Route::post('create-payment-intent', 'createPaymentIntent')->name('create.payment.intent');
    Route::get('payment-success', 'paymentSuccess')->name('payment.success');
});
