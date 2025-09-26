<?php

use App\Http\Controllers\{
    DashboardController,
    GroupController,
    CriterionController,
    GroupScoreController
};
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('groups', GroupController::class);
    Route::resource('criteria', CriterionController::class)->except(['show']);

    Route::get('scores',        [GroupScoreController::class, 'index'])->name('scores.index');
    Route::get('scores/create', [GroupScoreController::class, 'create'])->name('scores.create');
    Route::post('scores',       [GroupScoreController::class, 'store'])->name('scores.store');

    // tombol "Hitung Hasil Otomatis"
    Route::post('scores/recalc', [GroupScoreController::class, 'recalc'])->name('scores.recalc');
    
    // debug route
    Route::get('/debug', fn() => view('debug'))->name('debug');
});

require __DIR__ . '/auth.php';
