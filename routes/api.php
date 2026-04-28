<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SiswaAuthController;
use App\Http\Controllers\Api\SiswaController;

// Public routes
Route::post('/siswa/login', [SiswaAuthController::class, 'login']);

// Protected routes (hanya untuk siswa)
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::get('/siswa/dashboard', [SiswaAuthController::class, 'dashboard']);
    Route::post('/siswa/logout', [SiswaAuthController::class, 'logout']);
    Route::get('/siswa/profile', [SiswaAuthController::class, 'profile']);
    
    
    // Fitur siswa
    Route::get('/siswa/jadwal', [SiswaController::class, 'jadwal']);
    Route::get('/siswa/riwayat', [SiswaController::class, 'riwayat']);
    Route::get('/siswa/ujian/{id}/mulai', [SiswaController::class, 'mulaiUjian']);
    Route::post('/siswa/ujian/simpan', [SiswaController::class, 'simpanJawaban']);
    Route::post('/siswa/ujian/simpan-sementara', [SiswaController::class, 'simpanJawabanSementara']);
    Route::post('/siswa/pelanggaran', [SiswaController::class, 'pelanggaran']);
});
Route::get('/test', function() {
    return response()->json(['message' => 'API works!']);
});