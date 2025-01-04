<?php

use App\Livewire\Home;
use App\Livewire\Auth\Register;
use App\Livewire\Actions\Logout;
use App\Livewire\Pejuang\HomePejuang;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\PejuangMiddleware;

Route::get('/', Home::class)->name('home');
Route::get('/register', Register::class)->name('register');

Route::middleware([PejuangMiddleware::class])->group(
    function () {
        Route::get('/pejuang', HomePejuang::class)->name('pejuang');
    }
);

require __DIR__ . '/auth.php';
