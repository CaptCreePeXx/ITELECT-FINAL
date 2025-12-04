<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DentistController;
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
    Route::get('/appointments/{id}', [AdminController::class, 'showAppointment'])->name('admin.appointments.show');
    Route::delete('/appointments/{id}', [AdminController::class, 'destroyAppointment'])->name('admin.appointments.destroy');

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

    // Dashboard - all roles
    Route::get('/dashboard', function () {
        $user = Auth::user();
        $appointments = collect();

        if ($user->role === 'patient') {
            $appointments = Appointment::where('patient_id', $user->id)->latest()->get();
        } elseif ($user->role === 'dentist') {
            $appointments = Appointment::where('dentist_id', $user->id)->latest()->get();
        }
        // Receptionist and Admin will handle via separate routes or admin dashboard

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
    Route::post('/appointments/{appointment}/request-cancel', [PatientsController::class, 'requestCancel'])->name('appointments.requestCancel');
    Route::get('/appointments/{appointment}/details', [PatientsController::class, 'showDetailsHtml'])->name('appointments.details.html');

    /*
    |-------------------------------------------------------------------------- 
    | Receptionist Routes
    |-------------------------------------------------------------------------- 
    */
    Route::middleware([RoleMiddleware::class . ':receptionist'])->group(function () {
        // Manage all pending appointments
        Route::get('/receptionist/manage', [ReceptionistController::class, 'manage'])->name('appointments.manage');

        // Assign dentist
        Route::post('/appointments/{id}/assign-dentist', [ReceptionistController::class, 'assignDentist'])->name('appointments.assignDentist');

        // Mark appointment as complete
        Route::post('/appointments/{id}/complete', [ReceptionistController::class, 'complete'])->name('appointments.complete');

        // Accept or decline appointments
        Route::post('/receptionist/{id}/accept', [ReceptionistController::class, 'accept'])->name('appointments.accept');
        Route::post('/receptionist/{id}/decline', [ReceptionistController::class, 'decline'])->name('appointments.decline');
        Route::put('/appointments/{appointment}/decline', [ReceptionistController::class, 'decline'])->name('appointments.decline');

        // Update note (matches your blade form)
        Route::put('/appointments/{appointment}/update-note', [ReceptionistController::class, 'updateNote'])->name('appointments.updateNote');

        Route::get('/appointments/{id}/cancel-handled', function($id){
    return redirect()->back();
});

        
        // Cancel request handled
        // web.php
Route::post('/appointments/{id}/cancel-handled', [ReceptionistController::class, 'cancelRequestHandled'])
    ->name('appointments.cancelRequestHandled');
    });
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dentist/dashboard', [DentistController::class, 'dashboard'])->name('dentist.dashboard');

    Route::get('/dentist/schedule', [DentistController::class, 'schedule'])->name('dentist.schedule');

    Route::get('/dentist/appointment/{id}', [DentistController::class, 'viewAppointment'])->name('dentist.appointment.view');

    Route::post('/dentist/appointment/{id}/notes', [DentistController::class, 'saveNotes'])->name('dentist.notes.save');

    Route::post('/dentist/appointment/{id}/complete', [DentistController::class, 'completeAppointment'])->name('dentist.appointment.complete');
});

// Include Laravel Breeze/Jetstream auth routes
require __DIR__.'/auth.php';