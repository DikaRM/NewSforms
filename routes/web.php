<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaController;
Route::get('/', function () {
    return view('welcome');
});
Route::get("/login",[App\Http\Controllers\UsersController::class,"index"]);
Route::resource("/admin",AdminController::class);

Route::post("/login/load",[App\Http\Controllers\UsersController::class,"login"])->name("users.store");
Route::resource("/admin-siswa",App\Http\Controllers\SiswaController::class);
Route::get("/siswa",[App\Http\Controllers\SiswaController::class,"Siswas"])->name("siswa.index");
Route::get("/siswa/{id}",[App\Http\Controllers\SiswaController::class,"Starts"])->name("siswa.shop");
Route::get("/siswa/saved",[App\Http\Controllers\SiswaController::class,"Saved"])->name("siswa.save");
Route::post("/logout",[App\Http\Controllers\UsersController::class,"logout"])->name("users.logout");
Route::resource("admin/siswa",SiswaController::class);

  Route::resource("/admin-guru",App\Http\Controllers\GuruController::class);
  Route::resource("/admin-siswa",App\Http\Controllers\SiswaController::class);
Route::get("/guru",[App\Http\Controllers\GuruController::class,"TeachIndex"])->name("guru.index");
Route::post("/guru/create-soal",[App\Http\Controllers\GuruController::class,"rheina"])->name("soal.save");
Route::delete("/guru/create-soal/pus/{id}",[App\Http\Controllers\GuruController::class,"bowl"])->name("soal.destroy");
Route::post("/guru",[App\Http\Controllers\GuruController::class,"CreateUjian"])->name("guru.store");
Route::get("/guru/create-soal/{id}",[App\Http\Controllers\GuruController::class,"CreateSoal"])->name("guru.create");
Route::put("/guru/save/{id}",[App\Http\Controllers\GuruController::class,"def"])->name("ujian.sold");
Route::get("/admin-kelas",[AdminController::class,"KelasIndex"])->name("admin.kelas");
Route::post("/admin-kelas",[AdminController::class,"KelasCreate"])->name("admin.tambah");
Route::put("/admin-kelas/{id}",[AdminController::class,"KelasUpdate"])->name("admin.date");
Route::delete("/admin-kelas/{id}",[AdminController::class,"KelasDestroy"])->name("admin.let");
Route::post("/admin-kelas/{id}",[AdminController::class,"AddSiswa"])->name("admin.ade");
Route::get("/admin-mapel",[AdminController::class,"MapelIndex"])->name("admin.mapel");
Route::post("/admin-mapel/buat",[AdminController::class,"Made"])->name("admin.made");
Route::put("/admin-mapel/{id}",[AdminController::class,"MapelUpdate"])->name("admin.deat");
Route::delete("/admin-mapel/{id}",[AdminController::class,"MapelDestroy"])->name("admin.letroy");
Route::post("/admin-mapel",[AdminController::class,"AddGuru"])->name("admin.built");
?>
