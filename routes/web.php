<?php

use App\Http\Controllers\{
    DasborController,
    DasborAdminController,
    DasborKoordinatorController,
    DasborDosenController,
    DasborMahasiswaController,
    KemajuanDosenController,
    KelompokController,
    RuangKelasController,
    KriteriaController,
    NilaiKelompokController,
    NilaiMahasiswaController,
    InputNilaiMahasiswaController,
    TargetMingguanController,
    PengumpulanTargetMingguanController,
    KemajuanMingguanController,
    UlasanTargetMingguanController,
    ImporController,
    ImporPenggunaController,
    PeriodeAkademikController,
    MataKuliahController,
    RubrikPenilaianController,
    SinkronKriteriaController
};
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;

// Google OAuth Routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// API Routes (untuk AJAX)
Route::middleware(['auth'])->group(function () {
    Route::get('/api/classrooms/{classroom}/groups', [RuangKelasController::class, 'getGroups'])->name('api.classrooms.groups');
});

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth'])->group(function () {
    // Main dashboard (redirects based on role)
    Route::get('/dashboard', DasborController::class)->name('dashboard');

    // Role-specific dashboards
    Route::get('/admin/dashboard', DasborAdminController::class)
        ->middleware('role:admin')
        ->name('admin.dashboard');
    
    Route::get('/koordinator/dashboard', DasborKoordinatorController::class)
        ->middleware('role:koordinator')
        ->name('koordinator.dashboard');
    
    Route::get('/dosen/dashboard', DasborDosenController::class)
        ->middleware('role:dosen')
        ->name('dosen.dashboard');
    
    Route::get('/mahasiswa/dashboard', DasborMahasiswaController::class)
        ->middleware('role:mahasiswa')
        ->name('mahasiswa.dashboard');

    // ========================================
    // ADMIN ONLY ROUTES
    // ========================================
    Route::middleware(['role:admin'])->group(function () {
        // Academic Periods (Periode Akademik - Gabungan: Tahun Ajaran + Semester)
        Route::resource('academic-periods', PeriodeAkademikController::class);
        
        // User Management (Admin only)
        Route::get('admin/users/without-group', [\App\Http\Controllers\Admin\UserController::class, 'studentsWithoutGroup'])->name('admin.users.without-group');
        Route::post('admin/users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class, 'toggleActive'])->name('admin.users.toggle-active');
        Route::resource('admin/users', \App\Http\Controllers\Admin\UserController::class)->names('admin.users');
        
        // User Import (Admin only)
        Route::get('users/import', [ImporPenggunaController::class, 'showImportForm'])->name('users.import.form');
        Route::post('users/import/process', [ImporPenggunaController::class, 'import'])->name('users.import');
        Route::get('users/import/template', [ImporPenggunaController::class, 'downloadTemplate'])->name('users.download-template');
        
        // Import Excel
        Route::get('import/groups', [ImporController::class, 'showGroupsImport'])->name('import.groups');
        Route::post('import/groups', [ImporController::class, 'importGroups'])->name('import.groups.store');
        Route::get('import/groups/template', [ImporController::class, 'downloadGroupTemplate'])->name('import.groups.template');

        // Calculate Ranking (Admin only)
        Route::post('scores/recalc', [NilaiKelompokController::class, 'recalc'])->name('scores.recalc');

        // Google Drive Settings (Admin only)
        Route::get('settings/google-drive', [\App\Http\Controllers\GoogleDriveSettingsController::class, 'index'])->name('settings.google-drive.index');
        Route::put('settings/google-drive', [\App\Http\Controllers\GoogleDriveSettingsController::class, 'update'])->name('settings.google-drive.update');
        Route::post('settings/google-drive/test', [\App\Http\Controllers\GoogleDriveSettingsController::class, 'testConnection'])->name('settings.google-drive.test');
        Route::get('settings/google-drive/quota', [\App\Http\Controllers\GoogleDriveSettingsController::class, 'getStorageQuota'])->name('settings.google-drive.quota');
        
        // Google Drive OAuth
        Route::get('settings/google-drive/connect', [\App\Http\Controllers\GoogleDriveOAuthController::class, 'redirect'])->name('settings.google-drive.connect');
        Route::get('settings/google-drive/callback', [\App\Http\Controllers\GoogleDriveOAuthController::class, 'callback'])->name('settings.google-drive.callback');
        Route::post('settings/google-drive/disconnect', [\App\Http\Controllers\GoogleDriveOAuthController::class, 'disconnect'])->name('settings.google-drive.disconnect');
    });

    // ========================================
    // KOORDINATOR + ADMIN ROUTES
    // ========================================
    Route::middleware(['role:koordinator,admin'])->group(function () {
        // Manage Group Members
        Route::post('groups/{group}/members', [KelompokController::class, 'addMember'])->name('groups.add-member');
        Route::delete('groups/{group}/members/{member}', [KelompokController::class, 'removeMember'])->name('groups.remove-member');
        Route::patch('groups/{group}/set-leader', [KelompokController::class, 'setLeader'])->name('groups.set-leader');
    });

    // ========================================
    // DOSEN + ADMIN + KOORDINATOR ROUTES (View Target Info - READ ONLY)
    // ========================================
    Route::middleware(['role:dosen,admin,koordinator'])->group(function () {
        // Info halaman target mingguan (koordinator bisa melihat tapi tidak bisa edit)
        Route::get('targets/week/{weekNumber}/class/{classRoomId}/info', [TargetMingguanController::class, 'showWeekInfo'])->name('targets.week.info');
    });

    // ========================================
    // DOSEN + ADMIN ROUTES (Kelola Target Mingguan - CREATE/EDIT/DELETE)
    // ========================================
    Route::middleware(['role:dosen,admin'])->group(function () {
        // Weekly Targets (Create - hanya dosen & admin)
        Route::get('targets/create', [TargetMingguanController::class, 'create'])->name('targets.create');
        Route::post('targets', [TargetMingguanController::class, 'store'])->name('targets.store');
        
        // Bulk operations per week (edit/delete/reopen/close semua target dalam 1 minggu)
        Route::get('targets/week/{weekNumber}/class/{classRoomId}/edit', [TargetMingguanController::class, 'editWeek'])->name('targets.week.edit');
        Route::put('targets/week/{weekNumber}/class/{classRoomId}', [TargetMingguanController::class, 'updateWeek'])->name('targets.week.update');
        Route::delete('targets/week/{weekNumber}/class/{classRoomId}', [TargetMingguanController::class, 'destroyWeek'])->name('targets.week.destroy');
        Route::post('targets/week/{weekNumber}/class/{classRoomId}/reopen', [TargetMingguanController::class, 'reopenWeek'])->name('targets.week.reopen');
        Route::post('targets/week/{weekNumber}/class/{classRoomId}/close', [TargetMingguanController::class, 'closeWeek'])->name('targets.week.close');
        
        // Input Nilai Mahasiswa (Form dengan perhitungan otomatis)
        Route::get('scores/student-input', [InputNilaiMahasiswaController::class, 'index'])->name('scores.student-input');
        Route::get('scores/students-by-class', [InputNilaiMahasiswaController::class, 'getStudentsByClass'])->name('scores.students-by-class');
        Route::post('scores/student-input/store', [InputNilaiMahasiswaController::class, 'store'])->name('scores.student-input.store');
        Route::post('scores/student-input/calculate', [InputNilaiMahasiswaController::class, 'calculate'])->name('scores.student-input.calculate');
        Route::delete('scores/student-input/delete-student', [InputNilaiMahasiswaController::class, 'deleteStudentScores'])->name('scores.student-input.delete-student');
        Route::delete('scores/student-input/delete-score', [InputNilaiMahasiswaController::class, 'deleteScore'])->name('scores.student-input.delete-score');

        // Sync Kriteria (Sinkronkan nilai target ke kriteria AHP)
        Route::get('sync-kriteria', [SinkronKriteriaController::class, 'index'])->name('sync-kriteria.index');
        Route::post('sync-kriteria/preview', [SinkronKriteriaController::class, 'preview'])->name('sync-kriteria.preview');
        Route::post('sync-kriteria', [SinkronKriteriaController::class, 'sync'])->name('sync-kriteria.sync');
        Route::delete('sync-kriteria/{syncLog}', [SinkronKriteriaController::class, 'unsync'])->name('sync-kriteria.unsync');
    });
    
    // Admin Only - Force Delete
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('targets/{target}/force', [TargetMingguanController::class, 'forceDestroy'])->name('targets.force-destroy');
    });

    // ========================================
    // DOSEN PROGRESS MONITORING ROUTES
    // ========================================
    Route::middleware(['role:dosen'])->group(function () {
        // Progress monitoring untuk kelas yang diampu
        Route::get('dosen/progress', [KemajuanDosenController::class, 'index'])->name('dosen.progress.index');
        Route::get('dosen/progress/class/{classRoom}', [KemajuanDosenController::class, 'showClass'])->name('dosen.progress.show-class');
        Route::get('dosen/progress/class/{classRoom}/group/{group}', [KemajuanDosenController::class, 'showGroup'])->name('dosen.progress.show-group');
        Route::get('dosen/progress/class/{classRoom}/group/{group}/download/{targetId}/{fileIndex}', [KemajuanDosenController::class, 'downloadFile'])
            ->name('dosen.progress.download-file');
        Route::get('dosen/progress/class/{classRoom}/group/{group}/download-all/{targetId}', [KemajuanDosenController::class, 'downloadAllFiles'])
            ->name('dosen.progress.download-all');
        Route::get('dosen/progress/class/{classRoom}/group/{group}/folder', [KemajuanDosenController::class, 'viewGroupFolder'])
            ->name('dosen.progress.view-folder');
        
        // API endpoint for dropdowns
        Route::get('dosen/api/classroom/{classRoom}/groups', [KemajuanDosenController::class, 'getGroupsByClassroom'])
            ->name('dosen.api.classroom-groups');
    });

    // ========================================
    // DOSEN + KOORDINATOR + ADMIN ROUTES (Lihat & Review Target Mingguan)
    // ========================================
    Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
        // View targets (semua bisa lihat untuk monitoring)
        Route::get('targets', [TargetMingguanController::class, 'index'])->name('targets.index');
        Route::get('targets/{target}/show', [TargetMingguanController::class, 'show'])->name('targets.show');
        
        // Export Laporan Progress PDF
        Route::get('targets/export-pdf', [\App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('targets.export-pdf');
        
        // Auto-close overdue targets (manual trigger)
        Route::post('targets/auto-close-overdue', [TargetMingguanController::class, 'autoCloseOverdueTargets'])->name('targets.auto-close-overdue');
    });

    // ========================================
    // DOWNLOAD ROUTES (All authenticated users with access control in controller)
    // ========================================
    Route::get('targets/{target}/download/{file}', [TargetMingguanController::class, 'download'])
        ->name('targets.download')
        ->where('file', '.*'); // Allow slashes in file path

    // ========================================
    // MAHASISWA ROUTES (Submit Target Mingguan)
    // ========================================
    Route::middleware(['role:mahasiswa'])->group(function () {
        // Weekly Target Submissions
        Route::get('my-targets', [PengumpulanTargetMingguanController::class, 'index'])->name('targets.submissions.index');
        Route::get('targets/{target}', [PengumpulanTargetMingguanController::class, 'show'])->name('targets.submissions.show');
        Route::get('targets/{target}/submit', [PengumpulanTargetMingguanController::class, 'submitForm'])->name('targets.submissions.submit');
        Route::post('targets/{target}/submit', [PengumpulanTargetMingguanController::class, 'storeSubmission'])->name('targets.submissions.store');
        Route::get('targets/{target}/edit-submission', [PengumpulanTargetMingguanController::class, 'editSubmission'])->name('targets.submissions.edit');
        Route::put('targets/{target}/submit', [PengumpulanTargetMingguanController::class, 'updateSubmission'])->name('targets.submissions.update');
        Route::delete('targets/{target}/cancel', [PengumpulanTargetMingguanController::class, 'cancelSubmission'])->name('targets.submissions.cancel');
        
        // Weekly Progress Upload (Flexible)
        Route::get('weekly-progress/upload', [KemajuanMingguanController::class, 'upload'])->name('weekly-progress.upload');
        Route::post('weekly-progress/store', [KemajuanMingguanController::class, 'store'])->name('weekly-progress.store');
        Route::delete('weekly-progress/{weeklyProgress}/cancel', [KemajuanMingguanController::class, 'cancel'])->name('weekly-progress.cancel');
    });

    // ========================================
    // API ROUTES (AJAX)
    // ========================================
    Route::get('api/classrooms/{classroom}/groups', function($classroomId) {
        $groups = \App\Models\Kelompok::where('class_room_id', $classroomId)
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
        Route::resource('classrooms', RuangKelasController::class);
        
        // Groups (CRUD) - IMPORTANT: groups/create must come before groups/{group}
        Route::get('groups', [KelompokController::class, 'index'])->name('groups.index');
        Route::get('groups/create', [KelompokController::class, 'create'])->name('groups.create');
        Route::get('groups/available-students', [KelompokController::class, 'getAvailableStudents'])->name('groups.available-students');
        Route::post('groups', [KelompokController::class, 'store'])->name('groups.store');
        Route::get('groups/{group}', [KelompokController::class, 'show'])->name('groups.show');
        Route::get('groups/{group}/edit', [KelompokController::class, 'edit'])->name('groups.edit');
        Route::patch('groups/{group}', [KelompokController::class, 'update'])->name('groups.update');
        Route::delete('groups/{group}', [KelompokController::class, 'destroy'])->name('groups.destroy');
    });

    // ========================================
    // DOSEN + KOORDINATOR + ADMIN ROUTES (Input Nilai)
    // ========================================
    Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
        // Group Scores (Input Nilai Kelompok)
        Route::get('scores/create', [NilaiKelompokController::class, 'create'])->name('scores.create');
        Route::post('scores', [NilaiKelompokController::class, 'store'])->name('scores.store');
        
        // Weekly Target Reviews
        Route::get('target-reviews', [UlasanTargetMingguanController::class, 'index'])->name('target-reviews.index');
        Route::get('target-reviews/{weeklyTarget}', [UlasanTargetMingguanController::class, 'show'])->name('target-reviews.show');
        Route::post('target-reviews/{weeklyTarget}', [UlasanTargetMingguanController::class, 'store'])->name('target-reviews.store');
        Route::get('target-reviews/{weeklyTarget}/edit', [UlasanTargetMingguanController::class, 'edit'])->name('target-reviews.edit');
        Route::put('target-reviews/{weeklyTarget}', [UlasanTargetMingguanController::class, 'update'])->name('target-reviews.update');
        Route::get('target-reviews/{weeklyTarget}/download/{fileIndex}', [UlasanTargetMingguanController::class, 'downloadFile'])->name('target-reviews.download-file');
        Route::get('target-reviews/{weeklyTarget}/download-all', [UlasanTargetMingguanController::class, 'downloadAllFiles'])->name('target-reviews.download-all');
    });
    
    // ========================================
    // DOSEN + KOORDINATOR + ADMIN ROUTES (Lihat Ranking)
    // ========================================
    Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
        // Group Rankings (View only)
        Route::get('scores', [NilaiKelompokController::class, 'index'])->name('scores.index');
        
        // Student Rankings (View only)
        Route::get('student-scores', [NilaiMahasiswaController::class, 'index'])->name('student-scores.index');
        Route::post('student-scores/recalc', [NilaiMahasiswaController::class, 'recalc'])->name('student-scores.recalc');
    });

    // ========================================
    // MATA KULIAH & RUBRIK PENILAIAN
    // ========================================
    Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
        // Mata Kuliah
        Route::resource('mata-kuliah', MataKuliahController::class)->parameters(['mata-kuliah' => 'mataKuliah']);
        
        // Rubrik Penilaian (nested under mata-kuliah)
        Route::get('mata-kuliah/{mataKuliah}/rubrik', [RubrikPenilaianController::class, 'index'])->name('rubrik-penilaian.index');
        Route::get('mata-kuliah/{mataKuliah}/rubrik/create', [RubrikPenilaianController::class, 'create'])->name('rubrik-penilaian.create');
        Route::post('mata-kuliah/{mataKuliah}/rubrik', [RubrikPenilaianController::class, 'store'])->name('rubrik-penilaian.store');
        Route::get('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}', [RubrikPenilaianController::class, 'show'])->name('rubrik-penilaian.show');
        Route::get('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}/edit', [RubrikPenilaianController::class, 'edit'])->name('rubrik-penilaian.edit');
        Route::put('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}', [RubrikPenilaianController::class, 'update'])->name('rubrik-penilaian.update');
        Route::delete('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}', [RubrikPenilaianController::class, 'destroy'])->name('rubrik-penilaian.destroy');
        Route::post('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}/activate', [RubrikPenilaianController::class, 'activate'])->name('rubrik-penilaian.activate');
        Route::get('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}/duplicate', [RubrikPenilaianController::class, 'duplicate'])->name('rubrik-penilaian.duplicate');
        Route::post('mata-kuliah/{mataKuliah}/rubrik/{rubrikPenilaian}/duplicate', [RubrikPenilaianController::class, 'storeDuplicate'])->name('rubrik-penilaian.store-duplicate');
    });

    // ========================================
    // ADMIN ONLY - CRITERIA MANAGEMENT
    // ========================================
    Route::middleware(['role:admin'])->group(function () {
        // Criteria (Admin only)
        Route::resource('criteria', KriteriaController::class)->except(['show']);
        
        // AHP - Analytical Hierarchy Process
        Route::get('ahp', [\App\Http\Controllers\AhpController::class, 'index'])->name('ahp.index');
        Route::post('ahp/save', [\App\Http\Controllers\AhpController::class, 'saveComparison'])->name('ahp.save');
        Route::get('ahp/calculate', [\App\Http\Controllers\AhpController::class, 'calculate'])->name('ahp.calculate');
        Route::post('ahp/apply', [\App\Http\Controllers\AhpController::class, 'applyWeights'])->name('ahp.apply');
        Route::post('ahp/reset', [\App\Http\Controllers\AhpController::class, 'reset'])->name('ahp.reset');
        Route::get('ahp/help', [\App\Http\Controllers\AhpController::class, 'help'])->name('ahp.help');
    });

    // ========================================
    // MAHASISWA ONLY ROUTES
    // ========================================
    Route::middleware(['role:mahasiswa'])->group(function () {
        // Weekly Targets (mahasiswa can manage their group's targets)
        Route::post('targets/{target}/complete', [TargetMingguanController::class, 'complete'])->name('targets.complete');
        Route::post('targets/{target}/uncomplete', [TargetMingguanController::class, 'uncomplete'])->name('targets.uncomplete');
    });

    // ========================================
    // SHARED ROUTES (All authenticated users)
    // ========================================
    // Weekly Progress routes are defined per role above
    
    // debug route
    Route::get('/debug', fn() => view('debug'))->name('debug');
});

require __DIR__ . '/auth.php';
