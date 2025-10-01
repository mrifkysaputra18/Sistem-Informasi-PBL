<?php

use App\Http\Controllers\{
    DashboardController,
    GroupController,
    ClassRoomController,
    CriterionController,
    GroupScoreController
};
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Class Rooms (Pilih Kelas)
    Route::resource('classrooms', ClassRoomController::class);
    
    // Groups (Kelompok)
    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.add-member');
    Route::delete('groups/{group}/members/{member}', [GroupController::class, 'removeMember'])->name('groups.remove-member');
    Route::patch('groups/{group}/set-leader', [GroupController::class, 'setLeader'])->name('groups.set-leader');
    
    // Criteria & Scores
    Route::resource('criteria', CriterionController::class)->except(['show']);
    Route::get('scores',        [GroupScoreController::class, 'index'])->name('scores.index');
    Route::get('scores/create', [GroupScoreController::class, 'create'])->name('scores.create');
    Route::post('scores',       [GroupScoreController::class, 'store'])->name('scores.store');
    Route::post('scores/recalc', [GroupScoreController::class, 'recalc'])->name('scores.recalc');
    
    // debug route
    Route::get('/debug', fn() => view('debug'))->name('debug');
});

require __DIR__ . '/auth.php';
