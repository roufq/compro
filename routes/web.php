<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\OriginalIpController;
use App\Http\Controllers\Admin\PortfolioController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\TeamMemberController;
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

        Route::get('akun', [AccountController::class, 'edit'])->name('akun.edit');
        Route::put('akun/profil', [AccountController::class, 'updateProfile'])->name('akun.profil.update');
        Route::put('akun/password', [AccountController::class, 'updatePassword'])->name('akun.password.update');

        Route::get('identitas', [SiteSettingController::class, 'identitas'])->name('identitas');
        Route::get('hero', [SiteSettingController::class, 'hero'])->name('hero');
        Route::get('kontak', [SiteSettingController::class, 'kontak'])->name('kontak');
        Route::put('pengaturan', [SiteSettingController::class, 'update'])->name('pengaturan.update');

        Route::get('klien', [ClientController::class, 'index'])->name('klien.index');
        Route::post('klien', [ClientController::class, 'store'])->name('klien.store');
        Route::put('klien/{client}', [ClientController::class, 'update'])->name('klien.update');
        Route::delete('klien/{client}', [ClientController::class, 'destroy'])->name('klien.destroy');

        Route::get('original-ip', [OriginalIpController::class, 'index'])->name('original-ip.index');
        Route::post('original-ip', [OriginalIpController::class, 'store'])->name('original-ip.store');
        Route::put('original-ip/{originalIp}', [OriginalIpController::class, 'update'])->name('original-ip.update');
        Route::delete('original-ip/{originalIp}', [OriginalIpController::class, 'destroy'])->name('original-ip.destroy');

        Route::get('tim', [TeamMemberController::class, 'index'])->name('tim.index');
        Route::post('tim', [TeamMemberController::class, 'store'])->name('tim.store');
        Route::put('tim/{teamMember}', [TeamMemberController::class, 'update'])->name('tim.update');
        Route::delete('tim/{teamMember}', [TeamMemberController::class, 'destroy'])->name('tim.destroy');

        Route::get('produk', [ProductController::class, 'index'])->name('produk.index');
        Route::post('produk', [ProductController::class, 'store'])->name('produk.store');
        Route::put('produk/{product}', [ProductController::class, 'update'])->name('produk.update');
        Route::delete('produk/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

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
