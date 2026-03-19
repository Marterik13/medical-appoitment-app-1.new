<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;  

// Dashboard principal
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestión de roles y usuarios (CRUD completo)
Route::resource('roles', RoleController::class); 
Route::resource('users', UserController::class);