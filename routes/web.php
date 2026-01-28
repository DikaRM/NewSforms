<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD

Route::get('/', function () {
    return view('welcome');
});
=======
use App\Http\Controllers\AdminController;
Route::get('/', function () {
    return view('welcome');
});
Route::get("/login",[App\Http\Controllers\UsersController::class,"index"]);
Route::resource("/admin",AdminController::class);
>>>>>>> CrudUsers
