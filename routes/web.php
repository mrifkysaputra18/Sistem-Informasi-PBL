<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\WeeklyProgressController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\SSOController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// SSO Authentication
Route::get('/auth/sso', [SSOController::class, 'redirect'])->name('sso.redirect');
Route::get('/auth/sso/callback', [SSOController::class, 'callback'])->name('sso.callback');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Projects
    Route::resource('projects', ProjectController::class);
    
    // Groups
    Route::resource('groups', GroupController::class);
    Route::post('/groups/{group}/join', [GroupController::class, 'join'])->name('groups.join');
    Route::delete('/groups/{group}/leave', [GroupController::class, 'leave'])->name('groups.leave');

    // Weekly Progress
    Route::get('/groups/{group}/progress', [WeeklyProgressController::class, 'index'])
        ->name('weekly-progress.index');
    Route::get('/groups/{group}/progress/create/{week}', [WeeklyProgressController::class, 'create'])
        ->name('weekly-progress.create');
    Route::post('/groups/{group}/progress', [WeeklyProgressController::class, 'store'])
        ->name('weekly-progress.store');
    Route::get('/groups/{group}/progress/{weeklyProgress}', [WeeklyProgressController::class, 'show'])
        ->name('weekly-progress.show');
    Route::get('/groups/{group}/progress/{weeklyProgress}/edit', [WeeklyProgressController::class, 'edit'])
        ->name('weekly-progress.edit');
    Route::put('/groups/{group}/progress/{weeklyProgress}', [WeeklyProgressController::class, 'update'])
        ->name('weekly-progress.update');
    Route::post('/groups/{group}/progress/{weeklyProgress}/submit', [WeeklyProgressController::class, 'submit'])
        ->name('weekly-progress.submit');

    // Reviews (Dosen only)
    Route::middleware(['role:dosen'])->group(function () {
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{weeklyProgress}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::post('/reviews/{weeklyProgress}', [ReviewController::class, 'store'])->name('reviews.store');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    });

    // Attendance
    Route::resource('attendances', AttendanceController::class)->except(['show']);

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/groups/{project}', [ReportController::class, 'groupRanking'])->name('reports.groups');
    Route::get('/reports/students/{project}', [ReportController::class, 'studentRanking'])->name('reports.students');
    Route::get('/reports/export/{project}/{type}', [ReportController::class, 'export'])->name('reports.export');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::post('/users', [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    });
});

require __DIR__.'/auth.php';