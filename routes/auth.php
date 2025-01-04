<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegisterPengiklan;
use App\Http\Controllers\Auth\VerifyEmailController;

Route::middleware('guest')->group(function () {
    Route::get('pengiklan/register', RegisterPengiklan::class)
        ->name('pengiklan.register');

    Volt::route('pejuang/register', 'pages.auth.register-pejuang')
        ->name('pejuang.register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
