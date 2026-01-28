<?php

use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> 9e49eb1d5bd8dba657255b965419e2559044f188

Route::get('/', function () {
    return view('welcome');
});
<<<<<<< HEAD
=======
use App\Http\Controllers\AdminController;
Route::get('/', function () {
    return view('welcome');
});
Route::get("/login",[App\Http\Controllers\UsersController::class,"index"]);
Route::resource("/admin",AdminController::class);
>>>>>>> CrudUsers
=======
>>>>>>> 9e49eb1d5bd8dba657255b965419e2559044f188
