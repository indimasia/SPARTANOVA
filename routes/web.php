<?php

use App\Livewire\Home;
use App\Livewire\Auth\Register;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/register', Register::class)->name('register');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
