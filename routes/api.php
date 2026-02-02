<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;

Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('/register', [AuthController::class, 'registerUser']);
Route::get('/paket', [PaketController::class, 'indexApi']);
Route::get('/paket/{id}', [PaketController::class, 'showApi']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::prefix('pemesanan')->group(function () {
        Route::get('/', [PemesananController::class, 'indexApi']);
        Route::post('/', [PemesananController::class, 'storeApi']);
        Route::get('/{id}', [PemesananController::class, 'showApi']);
        Route::put('/{id}/status', [PemesananController::class, 'updateStatusApi']);
    });
    
    Route::prefix('pengiriman')->group(function () {
        Route::get('/', [PengirimanController::class, 'indexApi']);
        Route::post('/{id}/status', [PengirimanController::class, 'updateStatusApi']);
    });
    
    Route::prefix('profile')->group(function () {
        Route::get('/', [PelangganController::class, 'profileApi']);
        Route::put('/', [PelangganController::class, 'updateProfileApi']);
    });
});