<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;  
use App\Http\Controllers\Admin\PatientController;  

// Dashboard principal - LE QUITAMOS EL "admin." DEL NOMBRE
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard'); // <-- Laravel le sumará el 'admin.' automáticamente

// Gestión de roles, usuarios y pacientes
// También les quitamos el prefijo 'admin.' porque ya se hereda del grupo
Route::resource('roles', RoleController::class)->names('roles'); 
Route::resource('users', UserController::class)->names('users');
Route::resource('patients', PatientController::class)->names('patients');