<?php

use App\Livewire\Home;
use App\Livewire\Auth\Register;
use App\Livewire\Actions\Logout;
use App\Livewire\Pejuang\ApplyJob;
use App\Livewire\Pejuang\HomePejuang;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PejuangMiddleware;
use App\Livewire\Pejuang\RiwayatPekerjaan;

Route::get('/', Home::class)->name('home');
Route::get('/register', Register::class)->name('register');

Route::middleware([PejuangMiddleware::class])->group(
    function () {
        Route::get('/pejuang', HomePejuang::class)->name('pejuang');
        Route::get('/pejuang/apply-job', ApplyJob::class)->name('pejuang.apply-job');
        Route::get('/pejuang/riwayat-pekerjaan', RiwayatPekerjaan::class)->name('pejuang.riwayat-pekerjaan');
    }
);

require __DIR__ . '/auth.php';
