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
    StudentScoreController,
    SubjectController,
    WeeklyTargetController,
    WeeklyTargetSubmissionController,
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
        
        // User Management (Admin only)
        Route::get('admin/users/without-group', [\App\Http\Controllers\Admin\UserController::class, 'studentsWithoutGroup'])->name('admin.users.without-group');
        Route::post('admin/users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('admin.users.toggle-active');
        Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
        
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
    // DOSEN + KOORDINATOR + ADMIN ROUTES (Kelola Target Mingguan)
    // ========================================
    Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
        // Weekly Targets (CRUD untuk dosen)
        Route::get('targets', [WeeklyTargetController::class, 'index'])->name('targets.index');
        Route::get('targets/create', [WeeklyTargetController::class, 'create'])->name('targets.create');
        Route::post('targets', [WeeklyTargetController::class, 'store'])->name('targets.store');
        Route::get('targets/{target}/show', [WeeklyTargetController::class, 'show'])->name('targets.show');
        Route::get('targets/{target}/edit', [WeeklyTargetController::class, 'edit'])->name('targets.edit');
        Route::put('targets/{target}', [WeeklyTargetController::class, 'update'])->name('targets.update');
        Route::delete('targets/{target}', [WeeklyTargetController::class, 'destroy'])->name('targets.destroy');
        Route::get('targets/{target}/review', [WeeklyTargetController::class, 'review'])->name('targets.review');
        Route::post('targets/{target}/review', [WeeklyTargetController::class, 'storeReview'])->name('targets.review.store');
    });

    // ========================================
    // MAHASISWA ROUTES (Submit Target Mingguan)
    // ========================================
    Route::middleware(['role:mahasiswa'])->group(function () {
        // Weekly Target Submissions
        Route::get('my-targets', [WeeklyTargetSubmissionController::class, 'index'])->name('targets.submissions.index');
        Route::get('targets/{target}', [WeeklyTargetSubmissionController::class, 'show'])->name('targets.submissions.show');
        Route::get('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'submitForm'])->name('targets.submissions.submit');
        Route::post('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'storeSubmission'])->name('targets.submissions.store');
        Route::get('targets/{target}/edit-submission', [WeeklyTargetSubmissionController::class, 'editSubmission'])->name('targets.submissions.edit');
        Route::put('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'updateSubmission'])->name('targets.submissions.update');
    });

    // ========================================
    // API ROUTES (AJAX)
    // ========================================
    Route::get('api/classrooms/{classroom}/groups', function($classroomId) {
        $groups = \App\Models\Group::where('class_room_id', $classroomId)
            ->with('classRoom')
            ->get();
        
        return response()->json([
            'groups' => $groups->map(function($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'class_name' => $group->classRoom->name
                ];
            })
        ]);
    })->name('api.classrooms.groups');

    // ========================================
    // KOORDINATOR + ADMIN ROUTES (Kelola Kelompok)
    // ========================================
    Route::middleware(['role:koordinator,admin'])->group(function () {
        // Class Rooms
        Route::resource('classrooms', ClassRoomController::class);
        
        // Groups (CRUD) - IMPORTANT: groups/create must come before groups/{group}
        Route::get('groups', [GroupController::class, 'index'])->name('groups.index');
        Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create');
        Route::get('groups/available-students', [GroupController::class, 'getAvailableStudents'])->name('groups.available-students');
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
        // Group Scores (Input Nilai Kelompok)
        Route::get('scores/create', [GroupScoreController::class, 'create'])->name('scores.create');
        Route::post('scores', [GroupScoreController::class, 'store'])->name('scores.store');
        
        // Student Scores (Input Nilai Mahasiswa)
        Route::get('student-scores/create', [StudentScoreController::class, 'create'])->name('student-scores.create');
        Route::post('student-scores', [StudentScoreController::class, 'store'])->name('student-scores.store');
        
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
        // Group Rankings (View only)
        Route::get('scores', [GroupScoreController::class, 'index'])->name('scores.index');
        
        // Student Rankings (View only)
        Route::get('student-scores', [StudentScoreController::class, 'index'])->name('student-scores.index');
        Route::post('student-scores/recalc', [StudentScoreController::class, 'recalc'])->name('student-scores.recalc');
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
