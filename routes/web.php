<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StripePaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegisterForm');
    Route::post('/register', 'register')->name('register');

    Route::get('/login', 'showLoginForm');
    Route::post('/login', 'login')->name('login');

    Route::get('/dashboard', function (Request $request) {
        if ($request->has('success')) {
            session()->flash('success', 'Payment was successful!');
        }
        return view('dashboard');
    })->middleware('auth')->name('dashboard');

    Route::post('/logout', 'logout')->name('logout');
});

Route::get('checkout', [StripePaymentController::class, 'showCheckout'])->name('checkout');
Route::post('create-payment-intent', [StripePaymentController::class, 'createPaymentIntent'])->name('create.payment.intent');
Route::get('/payment-success', function () {
    session()->flash('success', 'Payment was successful!');
    return response()->noContent();
})->name('payment.success');
