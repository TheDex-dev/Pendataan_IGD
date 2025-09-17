<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EscortDataController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Routes (accessible without authentication)
Route::get('/form', [EscortDataController::class, 'index'])->name('form.index');
Route::post('/form/submit', [EscortDataController::class, 'store'])->name('form.store');

// Session and submission tracking routes (public for form debugging)
Route::get('/submission/{submissionId}', [EscortDataController::class, 'getSubmissionDetails'])->name('submission.details');

// Protected Routes (require authentication - IGD Staff only)
Route::middleware('auth')->group(function () {
    Route::get('/', [EscortDataController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [EscortDataController::class, 'dashboard'])->name('dashboard');
    
    // Admin utilities
    Route::post('/admin/clear-session-data', [EscortDataController::class, 'clearOldSessionData'])->name('admin.clear-session');
});

// Redirect root to dashboard if authenticated, otherwise to login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');