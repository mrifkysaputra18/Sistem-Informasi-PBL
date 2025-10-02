<?php

use App\Http\Controllers\{
    DashboardController,
    AdminDashboardController,
    KoordinatorDashboardController,
    DosenDashboardController,
    MahasiswaDashboardController,
    GroupController,
    ClassRoomController,
    CriterionController,
    GroupScoreController,
    SubjectController,
    WeeklyTargetController,
    WeeklyProgressController,
    WeeklyTargetReviewController,
    ImportController,
    AcademicYearController,
    SemesterController,
    AcademicPeriodController
};
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    // Main dashboard (redirects based on role)
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Role-specific dashboards
    Route::get('/admin/dashboard', AdminDashboardController::class)
        ->middleware('role:admin')
        ->name('admin.dashboard');
    
    Route::get('/koordinator/dashboard', KoordinatorDashboardController::class)
        ->middleware('role:koordinator')
        ->name('koordinator.dashboard');
    
    Route::get('/dosen/dashboard', DosenDashboardController::class)
        ->middleware('role:dosen')
        ->name('dosen.dashboard');
    
    Route::get('/mahasiswa/dashboard', MahasiswaDashboardController::class)
        ->middleware('role:mahasiswa')
        ->name('mahasiswa.dashboard');

    // ========================================
    // ADMIN ONLY ROUTES
    // ========================================
    Route::middleware(['role:admin'])->group(function () {
        // Academic Periods (Periode Akademik - Gabungan: Tahun Ajaran + Semester)
        Route::resource('academic-periods', AcademicPeriodController::class);
        
        // Subjects (Mata Kuliah) - accessible from academic-periods page
        Route::resource('projects', SubjectController::class);
        
        // Import Excel
        Route::get('import/groups', [ImportController::class, 'showGroupsImport'])->name('import.groups');
        Route::post('import/groups', [ImportController::class, 'importGroups'])->name('import.groups.store');
        Route::get('import/groups/template', [ImportController::class, 'downloadGroupTemplate'])->name('import.groups.template');
        
        // Calculate Ranking (Admin only)
        Route::post('scores/recalc', [GroupScoreController::class, 'recalc'])->name('scores.recalc');
    });

    // ========================================
    // KOORDINATOR + ADMIN ROUTES
    // ========================================
    Route::middleware(['role:koordinator,admin'])->group(function () {
        // Manage Group Members
        Route::post('groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.add-member');
        Route::delete('groups/{group}/members/{member}', [GroupController::class, 'removeMember'])->name('groups.remove-member');
        Route::patch('groups/{group}/set-leader', [GroupController::class, 'setLeader'])->name('groups.set-leader');
    });

    // ========================================
    // KOORDINATOR + ADMIN ROUTES (Kelola Kelompok)
    // ========================================
    Route::middleware(['role:koordinator,admin'])->group(function () {
        // Class Rooms
        Route::resource('classrooms', ClassRoomController::class);
        
        // Groups (CRUD) - IMPORTANT: groups/create must come before groups/{group}
        Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
        Route::post('groups', [GroupController::class, 'store'])->name('groups.store');
        Route::get('groups/{group}', [GroupController::class, 'show'])->name('groups.show');
        Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
        Route::patch('groups/{group}', [GroupController::class, 'update'])->name('groups.update');
        Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    });

    // ========================================
    // DOSEN + KOORDINATOR ROUTES (Input Nilai)
    // ========================================
    Route::middleware(['role:dosen,koordinator'])->group(function () {
        // Scores (Input Nilai - Dosen only)
        Route::get('scores/create', [GroupScoreController::class, 'create'])->name('scores.create');
        Route::post('scores', [GroupScoreController::class, 'store'])->name('scores.store');
        
        // Weekly Target Reviews
        Route::get('target-reviews', [WeeklyTargetReviewController::class, 'index'])->name('target-reviews.index');
        Route::get('target-reviews/{weeklyTarget}', [WeeklyTargetReviewController::class, 'show'])->name('target-reviews.show');
        Route::post('target-reviews/{weeklyTarget}', [WeeklyTargetReviewController::class, 'store'])->name('target-reviews.store');
        Route::get('target-reviews/{weeklyTarget}/edit', [WeeklyTargetReviewController::class, 'edit'])->name('target-reviews.edit');
        Route::put('target-reviews/{weeklyTarget}', [WeeklyTargetReviewController::class, 'update'])->name('target-reviews.update');
    });
    
    // ========================================
    // DOSEN + KOORDINATOR + ADMIN ROUTES (Lihat Ranking)
    // ========================================
    Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
        // Rankings (View only)
        Route::get('scores', [GroupScoreController::class, 'index'])->name('scores.index');
    });

    // ========================================
    // ADMIN ONLY - CRITERIA MANAGEMENT
    // ========================================
    Route::middleware(['role:admin'])->group(function () {
        // Criteria (Admin only)
        Route::resource('criteria', CriterionController::class)->except(['show']);
    });

    // ========================================
    // MAHASISWA ONLY ROUTES
    // ========================================
    Route::middleware(['role:mahasiswa'])->group(function () {
        // Weekly Targets (mahasiswa can manage their group's targets)
        Route::resource('groups.targets', WeeklyTargetController::class)->except(['index', 'show']);
        Route::post('targets/{target}/complete', [WeeklyTargetController::class, 'complete'])->name('targets.complete');
        Route::post('targets/{target}/uncomplete', [WeeklyTargetController::class, 'uncomplete'])->name('targets.uncomplete');
    });

    // ========================================
    // SHARED ROUTES (All authenticated users)
    // ========================================
    Route::post('groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::patch('groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy');
    
    // Weekly Targets & Progress (Mahasiswa dapat akses)
    Route::resource('weekly-targets', WeeklyTargetController::class);
    Route::post('weekly-targets/{weeklyTarget}/complete', [WeeklyTargetController::class, 'complete'])->name('weekly-targets.complete');
    Route::post('weekly-targets/{weeklyTarget}/uncomplete', [WeeklyTargetController::class, 'uncomplete'])->name('weekly-targets.uncomplete');
    
    // Weekly Progress
    Route::resource('weekly-progress', WeeklyProgressController::class);
    
    // debug route
    Route::get('/debug', fn() => view('debug'))->name('debug');
});

require __DIR__ . '/auth.php';
