<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdoptionRequestController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

Auth::routes();

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Pet Routes
Route::resource('pets', PetController::class);

// Adoption Request Routes
Route::middleware(['auth'])->group(function () {
    // Store adoption request
    Route::post('/pets/{pet}/adoption-requests', [AdoptionRequestController::class, 'store'])
        ->name('adoption-requests.store');
    
    // List all adoption requests (for admin/staff)
    Route::get('/adoption-requests', [AdoptionRequestController::class, 'index'])
        ->name('adoption-requests.index')
        ->middleware('can:viewAny,App\Models\AdoptionRequest');
    
    // Update adoption request status
    Route::put('/adoption-requests/{adoptionRequest}', [AdoptionRequestController::class, 'update'])
        ->name('adoption-requests.update')
        ->middleware('can:update,adoptionRequest');
    
    // Delete adoption request
    Route::delete('/adoption-requests/{adoptionRequest}', [AdoptionRequestController::class, 'destroy'])
        ->name('adoption-requests.destroy')
        ->middleware('can:delete,adoptionRequest');
    
    // User's adoption requests
    Route::get('/my-applications', [AdoptionRequestController::class, 'userIndex'])
        ->name('adoption-requests.user-index');
});

// Custom Registration Route with Role
Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('guest');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Admin-only Routes
Route::middleware(['auth', 'can:isAdmin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/applications', [AdoptionRequestController::class, 'index'])
        ->name('adoption-requests.index');
    Route::put('/admin/applications/{adoptionRequest}', [AdoptionRequestController::class, 'update'])
        ->name('adoption-requests.update');
});