<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PengawasController;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/login", [UsersController::class, "index"])->name("login");
Route::post("/login/load", [UsersController::class, "login"])->name("users.store");
Route::post("/logout", [UsersController::class, "logout"])->name("users.logout");

// ADMIN ROUTES
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    
    Route::resource('/guru', GuruController::class);
    Route::resource('/siswa', SiswaController::class);
    
    Route::get('/kelas', [AdminController::class, 'KelasIndex'])->name('kelas');
    Route::post('/kelas', [AdminController::class, 'KelasCreate'])->name('tambah');
    Route::put('/kelas/{id}', [AdminController::class, 'KelasUpdate'])->name('date');
    Route::delete('/kelas/{id}', [AdminController::class, 'KelasDestroy'])->name('let');
    Route::post('/kelas/{id}', [AdminController::class, 'AddSiswa'])->name('ade');
    
    Route::get('/mapel', [AdminController::class, 'MapelIndex'])->name('mapel');
    Route::post('/mapel/buat', [AdminController::class, 'Made'])->name('made');
    Route::put('/mapel/{id}', [AdminController::class, 'MapelUpdate'])->name('deat');
    Route::delete('/mapel/{id}', [AdminController::class, 'MapelDestroy'])->name('letroy');
    Route::post('/mapel', [AdminController::class, 'AddGuru'])->name('built');
});

// SISWA ROUTES
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/', [SiswaController::class, 'Siswas'])->name('index');
    Route::get('/riwayat', [SiswaController::class, 'riwayat'])->name('riwayat');
    Route::get('/jadwal', [SiswaController::class, 'jadwal'])->name('jadwal');
    Route::get('/{id}', [SiswaController::class, 'Starts'])->name('shop');
    Route::post('/saved', [SiswaController::class, 'Saved'])->name('save');
    
});

// GURU ROUTES
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/', [GuruController::class, 'TeachIndex'])->name('index');
    Route::post('/create-soal', [GuruController::class, 'rheina'])->name('soal.save');
    Route::post('/create', [GuruController::class, 'sed'])->name('soal.sad');
    Route::delete('/create-soal/pus/{id}', [GuruController::class, 'bowl'])->name('soal.destroy');
    Route::delete('/hapus-soal/{id}', [GuruController::class, 'hapus'])->name('hapus');
    
    Route::post('/', [GuruController::class, 'CreateUjian'])->name('store');
    Route::get('/create-soal/{id}', [GuruController::class, 'CreateSoal'])->name('create');
    Route::post('/save/{id}', [GuruController::class, 'def'])->name('ujian.sold');
    Route::get('/jadwal', [GuruController::class, 'jadwal'])->name('jadwal');
    Route::get('/result', [GuruController::class, 'result'])->name('result');
    Route::get('/hasil/{id}', [GuruController::class, 'hasil'])->name('hasil');
    Route::get('/riwayat', [GuruController::class, 'riwayat'])->name('riwayat');
});

// PENGAWAS ROUTES

    Route::get('/pengawas/{id}', [PengawasController::class, 'index'])->name('pengawas.index');
    Route::post('/pengawas/attach', [PengawasController::class, 'store'])->name('pengawas.store');
    Route::post('/pelanggarans/penalty', [SiswaController::class, 'Pelanggaran'])->name('pengawas.pelanggaran.pen');
    Route::get('/pengawas/show/{id}', [PengawasController::class, 'show'])->name('pengawas.show');

// ADMIN-OPS ROUTES
Route::middleware(['auth', 'role:admin-ops'])->prefix('admin-ops')->name('admin-ops.')->group(function () {
    Route::get('/', [AdminController::class, 'ops'])->name('index');
    Route::get('/{id}', [AdminController::class, 'SetUji'])->name('set');
    Route::post('/create', [AdminController::class, 'operateCreate'])->name('sav');
});
Route::resource('/admin', AdminController::class);