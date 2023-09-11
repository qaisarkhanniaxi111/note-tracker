<?php

use App\Http\Controllers\Admin\ClinicianController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ErrorTypeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\NoteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Clinician\DashboardController as ClinicianDashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('site.home');

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Auth::routes();

Route::middleware('auth')->group(function() {

    Route::prefix('admin')->as('admin.')->middleware(['isAdmin'])->group(function() {
        Route::get('profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::post('profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');

        Route::resource('locations', LocationController::class)->except('create', 'update');
        Route::resource('clinicians', ClinicianController::class)->except('create', 'update');
        Route::resource('error_types', ErrorTypeController::class)->except('create', 'update');
        Route::resource('notes', NoteController::class);
        Route::get('find/patients', [NoteController::class, 'findPatients'])->name('find.patients');

    });

    Route::prefix('clinician')->as('clinician.')->group(function() {
        Route::get('/dashboard', [ClinicianDashboardController::class, 'dashboard'])->name('dashboard');

        Route::get('profile/edit', [DashboardController::class, 'editProfile'])->name('profile.edit');
        Route::post('profile/update', [DashboardController::class, 'updateProfile'])->name('profile.update');
        // Route::resource('notes', NoteController::class);
    });

    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

});
