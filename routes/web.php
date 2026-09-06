<?php

use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\PublicController;
use App\Http\Middleware\EnsureTeamMembership;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/original-ip/{slug}', [PublicController::class, 'originalIp'])
    ->where('slug', '[a-z0-9-]+')->name('original-ip.show');

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::redirect('/', '/admin/identitas');

        Route::get('identitas', [SiteSettingController::class, 'identitas'])->name('identitas');
        Route::get('hero', [SiteSettingController::class, 'hero'])->name('hero');
        Route::get('kontak', [SiteSettingController::class, 'kontak'])->name('kontak');
        Route::get('klien', [SiteSettingController::class, 'klien'])->name('klien');
        Route::get('original-ip', [SiteSettingController::class, 'originalIp'])->name('original-ip');
        Route::get('tim', [SiteSettingController::class, 'tim'])->name('tim');
        Route::get('produk', [SiteSettingController::class, 'produk'])->name('produk');
        Route::put('pengaturan', [SiteSettingController::class, 'update'])->name('pengaturan.update');

        Route::get('layanan', [ServiceController::class, 'index'])->name('layanan.index');
        Route::post('layanan', [ServiceController::class, 'store'])->name('layanan.store');
        Route::put('layanan/{service}', [ServiceController::class, 'update'])->name('layanan.update');
        Route::delete('layanan/{service}', [ServiceController::class, 'destroy'])->name('layanan.destroy');

        Route::get('portofolio', [PortfolioController::class, 'index'])->name('portofolio.index');
        Route::post('portofolio', [PortfolioController::class, 'store'])->name('portofolio.store');
        Route::put('portofolio/{portfolio}', [PortfolioController::class, 'update'])->name('portofolio.update');
        Route::delete('portofolio/{portfolio}', [PortfolioController::class, 'destroy'])->name('portofolio.destroy');

        Route::get('testimoni', [TestimonialController::class, 'index'])->name('testimoni.index');
        Route::post('testimoni', [TestimonialController::class, 'store'])->name('testimoni.store');
        Route::put('testimoni/{testimonial}', [TestimonialController::class, 'update'])->name('testimoni.update');
        Route::delete('testimoni/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimoni.destroy');
    });

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::get('dashboard', [SiteSettingController::class, 'identitas'])->name('dashboard');
    });

require __DIR__.'/settings.php';
