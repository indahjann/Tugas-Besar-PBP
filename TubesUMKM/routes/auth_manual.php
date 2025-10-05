<?php

use App\Http\Controllers\ManualAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [ManualAuthController::class, 'showRegister'])->name('register');
    Route::post('register', [ManualAuthController::class, 'register']);

    Route::get('login', [ManualAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [ManualAuthController::class, 'login']);
    Route::get('forgot-password', [ManualAuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('forgot-password', [ManualAuthController::class, 'sendForgotPasswordLink'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [ManualAuthController::class, 'logout'])->name('logout');
});
