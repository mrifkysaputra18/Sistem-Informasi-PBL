<?php

namespace App\Http\Controllers;

use App\Models\{TargetMingguan, Kelompok};
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

/**
 * Controller untuk MAHASISWA submit tugas/target
 */
class PengumpulanTargetMingguanController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    /**
     * Tampilkan list target untuk mahasiswa (di dashboard)
     */
    public function index()
    {
        // Get user's group
        $user = auth()->user();
        $group = $user->groups()->first();

        if (!$group) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('info', 'Anda belum tergabung dalam kelompok.');
        }

        // Get all targets for this group, EXCLUDING closed ones for students
        $targets = TargetMingguan::where('group_id', $group->id)
            ->with(['creator', 'completedByUser'])
            ->orderBy('week_number')
            ->orderBy('deadline')
            ->get()
            ->filter(fn($t) => !$t->isClosed()); // Sembunyikan target tertutup

        return view('target.pengumpulan.daftar', compact('group', 'targets'));
    }

    /**
     * Show detail target (untuk mahasiswa lihat detail sebelum submit)
     */
    public function show(TargetMingguan $target)
    {
        // Check if user is member of the group
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember && !$user->isAdmin()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        $target->load(['creator', 'completedByUser', 'reviewer', 'group.members.user']);

        return view('target.pengumpulan.tampil', compact('target'));
    }

    /**
     * Form untuk submit target
     */
    public function submitForm(TargetMingguan $target)
    {
        // Check if user is member
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target is still open for submission
        if (!$target->canAcceptSubmission()) {
            if ($target->isPastFinalDeadline()) {
                $finalDeadline = $target->getFinalDeadline();
                $message = 'Target sudah ditutup karena melewati batas waktu pengumpulan';
                if ($target->grace_period_minutes > 0) {
                    $message .= ' (termasuk grace period ' . $target->grace_period_minutes . ' menit)';
                }
                $message .= '. Batas akhir: ' . $finalDeadline->format('d/m/Y H:i');
            } else {
                $reason = $target->isClosed() ? $target->getClosureReason() : 'Target ini sudah direview dosen';
                $message = $reason . '. Target tidak dapat disubmit lagi.';
            }
            
            return redirect()->back()->with('error', $message);
        }

        return view('target.pengumpulan.kirim', compact('target'));
    }

    /**
     * Store submission dari mahasiswa
     */
    public function storeSubmission(Request $request, TargetMingguan $target)
    {
        // Check if user is member
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target is still open for submission
        if (!$target->canAcceptSubmission()) {
            if ($target->isPastFinalDeadline()) {
                $finalDeadline = $target->getFinalDeadline();
                $message = 'Target sudah ditutup karena melewati batas waktu pengumpulan';
                if ($target->grace_period_minutes > 0) {
                    $message .= ' (termasuk grace period ' . $target->grace_period_minutes . ' menit)';
                }
                $message .= '. Batas akhir: ' . $finalDeadline->format('d/m/Y H:i');
            } else {
                $reason = $target->isClosed() ? $target->getClosureReason() : 'Target ini sudah direview dosen';
                $message = $reason . '. Target tidak dapat disubmit lagi.';
            }
            
            return redirect()->back()->with('error', $message);
        }

        \Log::info('WeeklyTarget Submission', [
            'target_id' => $target->id,
            'user_id' => $user->id,
            'is_checked_only' => $request->is_checked_only,
            'has_files' => $request->hasFile('evidence'),
        ]);

        $validated = $request->validate([
            'submission_notes' => 'nullable|string',
            'is_checked_only' => 'nullable|boolean',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240', // 10MB max
        ]);

        $evidencePaths = $target->evidence_files ?? [];
        $isCheckedOnly = $request->has('is_checked_only') && $request->is_checked_only == '1';

        // Handle file uploads
        if ($request->hasFile('evidence') && !$isCheckedOnly) {
            \Log::info('Processing file uploads', [
                'count' => count($request->file('evidence')),
            ]);

            // Load group dengan relasi untuk folder hierarchy
            $group = $target->group;

            foreach ($request->file('evidence') as $file) {
                try {
                    // Upload ke Google Drive dengan folder otomatis (Periode/Kelas/Kelompok/Minggu)
                    $uploadResult = $this->googleDriveService->uploadFileForGroup(
                        $file,
                        $group,
                        $target->week_number
                    );
                    
                    $evidencePaths[] = [
                        'file_id' => $uploadResult['file_id'],
                        'file_name' => $uploadResult['file_name'],
                        'file_url' => $uploadResult['file_url'],
                        'download_url' => $uploadResult['download_url'],
                        'view_url' => $uploadResult['view_url'],
                        'mime_type' => $uploadResult['mime_type'],
                        'size' => $uploadResult['size'],
                        'storage' => 'google_drive',
                    ];

                    \Log::info('File uploaded to Google Drive', ['file_id' => $uploadResult['file_id']]);
                } catch (\Exception $e) {
                    \Log::error('Google Drive upload failed, using local storage', [
                        'error' => $e->getMessage()
                    ]);

                    // Fallback to local storage
                    $path = $file->store('evidence', 'public');
                    $evidencePaths[] = [
                        'local_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'storage' => 'local',
                    ];
                }
            }

            $isCheckedOnly = false; // If files uploaded, cannot be check-only
        }

        // Determine submission status
        $submissionStatus = 'submitted';
        if ($target->deadline && now()->gt($target->deadline)) {
            $submissionStatus = 'late';
        }

        // Update target
        $target->update([
            'submission_notes' => $request->submission_notes,
            'evidence_files' => $evidencePaths,
            'is_checked_only' => $isCheckedOnly,
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => $user->id,
            'submission_status' => $submissionStatus,
        ]);

        \Log::info('Submission completed', [
            'target_id' => $target->id,
            'status' => $submissionStatus,
            'file_count' => count($evidencePaths),
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Target berhasil disubmit!' . 
                ($submissionStatus === 'late' ? ' (Terlambat)' : '') .
                (count($evidencePaths) > 0 ? ' (' . count($evidencePaths) . ' file)' : ''));
    }

    /**
     * Form edit submission (sebelum direview dosen)
     */
    public function editSubmission(TargetMingguan $target)
    {
        // Check if user is member
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target is still open for submission
        if (!$target->canAcceptSubmission()) {
            $reason = $target->isClosed() ? $target->getClosureReason() : 'Target ini sudah direview dosen';
            return redirect()->back()
                ->with('error', $reason . '. Target tidak dapat diubah lagi.');
        }

        // Check if has been submitted
        if (!$target->isSubmitted()) {
            return redirect()->route('targets.submit.form', $target)
                ->with('info', 'Target ini belum disubmit.');
        }

        return view('target.pengumpulan.ubah', compact('target'));
    }

    /**
     * Update submission dari mahasiswa
     */
    public function updateSubmission(Request $request, TargetMingguan $target)
    {
        // Check if user is member
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if target is still open for submission
        if (!$target->canAcceptSubmission()) {
            $reason = $target->isClosed() ? $target->getClosureReason() : 'Target ini sudah direview dosen';
            return redirect()->back()
                ->with('error', $reason . '. Target tidak dapat diubah lagi.');
        }

        $validated = $request->validate([
            'submission_notes' => 'nullable|string',
            'is_checked_only' => 'nullable|boolean',
            'keep_files' => 'nullable|array',
            'keep_files.*' => 'integer',
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
        ]);

        $isCheckedOnly = $request->has('is_checked_only') && $request->is_checked_only == '1';
        $oldFiles = $target->evidence_files ?? [];
        $keepFileIndexes = $request->input('keep_files', []);
        $evidencePaths = [];

        // 1. Proses file yang dipertahankan vs dihapus
        foreach ($oldFiles as $index => $oldFile) {
            if (in_array($index, $keepFileIndexes)) {
                // File dipertahankan
                $evidencePaths[] = $oldFile;
            } else {
                // File dihapus
                try {
                    if (isset($oldFile['file_id'])) {
                        $this->googleDriveService->deleteFile($oldFile['file_id']);
                        \Log::info('File deleted from Google Drive', ['file_id' => $oldFile['file_id'], 'file_name' => $oldFile['file_name'] ?? '']);
                    } elseif (isset($oldFile['local_path'])) {
                        $fullPath = storage_path('app/public/' . $oldFile['local_path']);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                            \Log::info('File deleted from local storage', ['path' => $oldFile['local_path']]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete file', ['error' => $e->getMessage()]);
                }
            }
        }

        // 2. Upload file baru (ditambahkan, bukan mengganti)
        if ($request->hasFile('evidence') && !$isCheckedOnly) {
            $group = $target->group;
            
            foreach ($request->file('evidence') as $file) {
                try {
                    $uploadResult = $this->googleDriveService->uploadFileForGroup(
                        $file,
                        $group,
                        $target->week_number
                    );
                    
                    $evidencePaths[] = [
                        'file_id' => $uploadResult['file_id'],
                        'file_name' => $uploadResult['file_name'],
                        'file_url' => $uploadResult['file_url'],
                        'download_url' => $uploadResult['download_url'],
                        'view_url' => $uploadResult['view_url'],
                        'mime_type' => $uploadResult['mime_type'],
                        'size' => $uploadResult['size'],
                        'storage' => 'google_drive',
                    ];
                    \Log::info('New file uploaded to Google Drive', ['file_id' => $uploadResult['file_id']]);
                } catch (\Exception $e) {
                    $path = $file->store('evidence', 'public');
                    $evidencePaths[] = [
                        'local_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'storage' => 'local',
                    ];
                    \Log::warning('Upload to Google Drive failed, saved locally', ['error' => $e->getMessage()]);
                }
            }
        }

        // If switched to check-only mode, clear all files
        if ($isCheckedOnly) {
            // Hapus semua file yang tersisa
            foreach ($evidencePaths as $file) {
                try {
                    if (isset($file['file_id'])) {
                        $this->googleDriveService->deleteFile($file['file_id']);
                    } elseif (isset($file['local_path'])) {
                        $fullPath = storage_path('app/public/' . $file['local_path']);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete file on check-only switch', ['error' => $e->getMessage()]);
                }
            }
            $evidencePaths = [];
        }

        // Update submission status if needed
        $submissionStatus = $target->submission_status;
        if ($target->deadline && now()->gt($target->deadline)) {
            $submissionStatus = 'late';
        }

        $target->update([
            'submission_notes' => $request->submission_notes,
            'evidence_files' => $evidencePaths,
            'is_checked_only' => $isCheckedOnly,
            'submission_status' => $submissionStatus,
        ]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Submission berhasil diupdate!');
    }

    /**
     * Cancel submission yang sudah di-submit (sebelum deadline & belum direview)
     */
    public function cancelSubmission(TargetMingguan $target)
    {
        // Check if user is member
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Check if submission can be cancelled
        if (!$target->canCancelSubmission()) {
            $reasons = [];
            
            if (!in_array($target->submission_status, ['submitted', 'late'])) {
                $reasons[] = 'Target belum disubmit';
            }
            if ($target->is_reviewed) {
                $reasons[] = 'Target sudah direview oleh dosen';
            }
            if ($target->isClosed()) {
                $reasons[] = 'Target sudah ditutup';
            }
            if ($target->deadline && now()->gt($target->deadline)) {
                $reasons[] = 'Deadline sudah lewat';
            }

            $message = 'Submission tidak dapat dibatalkan. ' . implode(', ', $reasons) . '.';
            
            return redirect()->back()->with('error', $message);
        }

        try {
            // Delete files from Google Drive or local storage
            $evidenceFiles = $target->evidence_files ?? [];
            $deletedFiles = 0;
            
            foreach ($evidenceFiles as $file) {
                try {
                    if (isset($file['file_id'])) {
                        // Delete from Google Drive
                        $this->googleDriveService->deleteFile($file['file_id']);
                        $deletedFiles++;
                        \Log::info('File deleted from Google Drive', [
                            'file_id' => $file['file_id'],
                            'file_name' => $file['file_name'] ?? 'unknown'
                        ]);
                    } elseif (isset($file['local_path'])) {
                        // Delete from local storage
                        $fullPath = storage_path('app/public/' . $file['local_path']);
                        if (file_exists($fullPath)) {
                            unlink($fullPath);
                            $deletedFiles++;
                            \Log::info('File deleted from local storage', [
                                'path' => $file['local_path']
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to delete file during cancellation', [
                        'error' => $e->getMessage(),
                        'file' => $file
                    ]);
                    // Continue even if file deletion fails
                }
            }

            // Reset target to pending state
            $target->update([
                'submission_status' => 'pending',
                'submission_notes' => null,
                'evidence_files' => null,
                'is_checked_only' => false,
                'is_completed' => false,  // Reset is_completed
                'completed_at' => null,
                'completed_by' => null,
                'submitted_at' => null,  // Reset submitted_at juga
            ]);

            \Log::info('Submission cancelled successfully', [
                'target_id' => $target->id,
                'user_id' => $user->id,
                'files_deleted' => $deletedFiles
            ]);

            $message = 'Submission berhasil dibatalkan!';
            if ($deletedFiles > 0) {
                $message .= " ({$deletedFiles} file dihapus)";
            }

            return redirect()->route('mahasiswa.dashboard')
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error cancelling submission', [
                'target_id' => $target->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat membatalkan submission. Silakan coba lagi.');
        }
    }
}
