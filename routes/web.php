<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientsController; // For patient appointments
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Appointment;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Admin Appointments
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('admin.appointments');

    // Admin Users
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/create', [AdminController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Patient Dashboard - own appointments
    Route::get('/dashboard', function () {
        $appointments = Appointment::where('patient_id', Auth::id())->latest()->get();
        return view('dashboard', compact('appointments'));
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Patient Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware([RoleMiddleware::class . ':patient'])->group(function () {
        Route::resource('appointments', PatientsController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | Receptionist Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware([RoleMiddleware::class . ':receptionist'])->group(function () {
        // Manage all pending appointments
        Route::get('/receptionist/manage', [ReceptionistController::class, 'manage'])->name('appointments.manage');

        // Accept or decline appointments
        Route::post('/receptionist/{id}/accept', [ReceptionistController::class, 'accept'])->name('appointments.accept');
        Route::post('/receptionist/{id}/decline', [ReceptionistController::class, 'decline'])->name('appointments.decline');
    });
});

// Include Laravel Breeze/Jetstream auth routes
require __DIR__.'/auth.php';
