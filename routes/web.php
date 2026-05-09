<?php

use Illuminate\Support\Facades\Route;
// Importamos los controladores con su ruta real (Namespace)
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // --- TUS RUTAS DE ADMINISTRACIÓN ---
    
  

Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');

Route::get('/admin/roles', [\App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index');
    
    Route::resource('/admin/doctors', \App\Http\Controllers\Admin\DoctorController::class)->names('admin.doctors');
Route::get('/admin/doctors/{doctor}/schedules', [\App\Http\Controllers\Admin\DoctorController::class, 'schedules'])->name('admin.doctors.schedules');
    
Route::resource('/admin/appointments', \App\Http\Controllers\Admin\AppointmentController::class)->names('admin.appointments');
    Route::get('/admin/appointments/{appointment}/consultation', [\App\Http\Controllers\Admin\AppointmentController::class, 'consultation'])->name('admin.appointments.consultation');
    Route::get('/admin/appointments/{appointment}/pdf', [\App\Http\Controllers\Admin\AppointmentController::class, 'downloadPdf'])->name('admin.appointments.pdf');
});