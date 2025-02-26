<?php

use App\Livewire\Home;
use App\Models\Reward;
use App\Livewire\Topup;
use App\Livewire\MiniGame;
use App\Livewire\ArticleList;
use App\Livewire\MisiProgres;
use App\Livewire\Auth\Register;
use App\Livewire\DetailArticle;
use App\Livewire\JobDetailPage;
use App\Livewire\Pasukan\Profile;
use App\Livewire\NotificationRead;
use App\Livewire\Pasukan\ApplyJob;
use App\Livewire\ReferralRegister;
use App\Livewire\Pasukan\Dashboard;
use App\Livewire\Pasukan\ViewProfile;
use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegisterPasukan;
use App\Livewire\Pasukan\WithdrawPoints;
use App\Http\Middleware\PasukanMiddleware;
use App\Livewire\Pasukan\RiwayatPekerjaan;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\JobDetailController;
use App\Livewire\MiniGameCoomingSoon;

Route::get('/', Home::class)->name('home');
Route::get('/register', Register::class)->name('register');
Route::get('pasukan/register/{referred_by}', RegisterPasukan::class)->name('register.pasukan');
Route::get('storage/{filename}', [StorageController::class, 'fetchFile'])
    ->where('filename', '.*')
    ->name('storage.fetch');
Route::get('/rewards', function () {
    return Reward::where('status', 'available')->get();
});


Route::middleware([PasukanMiddleware::class])->group(
    function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/pasukan/apply-job', ApplyJob::class)->name('pasukan.apply-job');
        Route::get('/pasukan/misi-progres', MisiProgres::class)->name('misi.progres');
        Route::get('/pasukan/riwayat-pekerjaan', RiwayatPekerjaan::class)->name('pasukan.riwayat-pekerjaan');
        Route::get('/pasukan/profile', ViewProfile::class)->name('pasukan.profile');
        Route::get('/pasukan/profile/edit', Profile::class)->name('pasukan.profile.edit');
        Route::get('/articles', ArticleList::class)->name('articles.index');
        Route::get('/articles/read/{articleId}', ArticleList::class)->name('articles.read');
        // Route::get('/notification/read/{notification}', RiwayatPekerjaan::class)->name('notification.read');
        Route::get('/withdraw', WithdrawPoints::class)->name('withdraw.index');
        Route::get('/mini-game', MiniGame::class)->name('mini-game.index');
        Route::get('/mini-game-cooming-soon', MiniGameCoomingSoon::class)->name('mini-game-cooming-soon.index');
        Route::get('/articles/{slug}', DetailArticle::class)->name('articles.detail');
        Route::get('/job/{jobId}', JobDetailPage::class)->name('job.detail');
    }
);

require __DIR__ . '/auth.php';
