<?php

use App\Livewire\Home;
use App\Livewire\Auth\Register;
use App\Livewire\Pasukan\ApplyJob;
use App\Livewire\Pasukan\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PasukanMiddleware;
use App\Livewire\Pasukan\RiwayatPekerjaan;

Route::get('/', Home::class)->name('home');
Route::get('/register', Register::class)->name('register');

Route::middleware([PasukanMiddleware::class])->group(
    function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/pasukan/apply-job', ApplyJob::class)->name('pasukan.apply-job');
        Route::get('/pasukan/riwayat-pekerjaan', RiwayatPekerjaan::class)->name('pasukan.riwayat-pekerjaan');
    }
);

require __DIR__ . '/auth.php';
