<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisPembayaranController;
use App\Http\Middleware\AdminOwner;
use App\Http\Middleware\Owner;
use App\Http\Middleware\Kurir;

Route::get('/', [PelangganController::class, 'home'])->name('home');
Route::get('/paket', [PelangganController::class, 'paket'])->name('paket');
Route::get('/paket/{id}', [PelangganController::class, 'detailPaket'])->name('detail-paket');

Route::middleware(['guest:web', 'guest:pelanggan'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth:web'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
});

Route::middleware(['auth:pelanggan'])->group(function () {
    Route::get('/profile', [PelangganController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [PelangganController::class, 'editProfile'])->name('edit-profile');
    Route::post('/profile/update', [PelangganController::class, 'updateProfile'])->name('update-profile');
    
    Route::get('/pesan/{id}', [PemesananController::class, 'create'])->name('pesan');
    Route::post('/pesan/store', [PemesananController::class, 'store'])->name('pesan.store');
    
    Route::get('/pesanan-saya', [PemesananController::class, 'index'])->name('pesanan-saya');
    Route::get('/pesanan-saya/{id}', [PemesananController::class, 'show'])->name('detail-pesanan');
    Route::post('/pesanan-saya/{id}/cancel', [PemesananController::class, 'cancel'])->name('pesanan.cancel');
});

Route::middleware(['auth:web'])->group(function () {
    Route::get('/staff/profile', [UserController::class, 'profile'])->name('staff.profile');
    Route::get('/staff/profile/edit', [UserController::class, 'editProfile'])->name('staff.profile.edit');
    Route::post('/staff/profile/update', [UserController::class, 'updateProfile'])->name('staff.profile.update');
});

Route::middleware(['auth:web', Owner::class])->group(function () {
    Route::get('/owner/users', [UserController::class, 'index'])->name('owner.users');
    Route::get('/owner/users/create', [UserController::class, 'create'])->name('owner.users.create');
    Route::post('/owner/users', [UserController::class, 'store'])->name('owner.users.store');
    Route::get('/owner/users/{id}/edit', [UserController::class, 'edit'])->name('owner.users.edit');
    Route::put('/owner/users/{id}', [UserController::class, 'update'])->name('owner.users.update');
    Route::delete('/owner/users/{id}', [UserController::class, 'destroy'])->name('owner.users.destroy');
    
    Route::get('/owner/reports', [UserController::class, 'reports'])->name('owner.reports');
    Route::get('/owner/export-reports', [UserController::class, 'exportReports'])->name('owner.export.reports');
});

Route::middleware(['auth:web', AdminOwner::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/pemesanan', [PemesananController::class, 'adminIndex'])->name('pemesanan');
    Route::get('/pemesanan/{id}', [PemesananController::class, 'adminShow'])->name('pemesanan.show');
    Route::post('/pemesanan/{id}/status', [PemesananController::class, 'updateStatus'])->name('pemesanan.status');
    Route::post('/pemesanan/{id}/cancel', [PemesananController::class, 'adminCancel'])->name('pemesanan.cancel');
    
       Route::prefix('pengiriman')->group(function () {
        Route::get('/', [PengirimanController::class, 'index'])->name('pengiriman');
        Route::get('/create', [PengirimanController::class, 'create'])->name('pengiriman.create');
        Route::post('/', [PengirimanController::class, 'store'])->name('pengiriman.store');
        Route::get('/{id}', [PengirimanController::class, 'show'])->name('pengiriman.show');
        Route::post('/{id}/assign', [PengirimanController::class, 'assignKurir'])->name('pengiriman.assign');
        Route::post('/{id}/status', [PengirimanController::class, 'updateStatus'])->name('pengiriman.status');
    });
    
    Route::prefix('paket')->group(function () {
        Route::get('/', [PaketController::class, 'index'])->name('paket');
        Route::get('/create', [PaketController::class, 'create'])->name('paket.create');
        Route::post('/', [PaketController::class, 'store'])->name('paket.store');
        Route::get('/{id}/edit', [PaketController::class, 'edit'])->name('paket.edit');
        Route::put('/{id}', [PaketController::class, 'update'])->name('paket.update');
        Route::delete('/{id}', [PaketController::class, 'destroy'])->name('paket.destroy');
    });
    
    Route::prefix('pelanggan')->group(function () {
        Route::get('/', [UserController::class, 'pelangganIndex'])->name('pelanggan');
        Route::get('/{id}', [UserController::class, 'pelangganShow'])->name('pelanggan.show');
    });
    
    Route::prefix('jenis-pembayaran')->group(function () {
        Route::get('/', [JenisPembayaranController::class, 'index'])->name('jenis-pembayaran'); 
        Route::get('/create', [JenisPembayaranController::class, 'create'])->name('jenis-pembayaran.create');
        Route::post('/', [JenisPembayaranController::class, 'store'])->name('jenis-pembayaran.store');
        Route::get('/{id}/edit', [JenisPembayaranController::class, 'edit'])->name('jenis-pembayaran.edit');
        Route::put('/{id}', [JenisPembayaranController::class, 'update'])->name('jenis-pembayaran.update');
        Route::delete('/{id}', [JenisPembayaranController::class, 'destroy'])->name('jenis-pembayaran.destroy');
    });
});

Route::middleware(['auth:web', Kurir::class])->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'kurirDashboard'])->name('dashboard');
    
    Route::prefix('pengiriman')->group(function () {
        Route::get('/', [PengirimanController::class, 'kurirIndex'])->name('pengiriman');
        Route::get('/{id}', [PengirimanController::class, 'show'])->name('pengiriman.show');
        Route::post('/{id}/status', [PengirimanController::class, 'kurirUpdateStatus'])->name('pengiriman.status');
        Route::post('/{id}/upload', [PengirimanController::class, 'uploadBukti'])->name('pengiriman.upload');
    });
});