<?php

namespace App\Http\Controllers;

use App\Models\{WeeklyTarget, Group};
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

/**
 * Controller untuk MAHASISWA submit tugas/target
 */
class WeeklyTargetSubmissionController extends Controller
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

        // Get all targets for this group
        $targets = WeeklyTarget::where('group_id', $group->id)
            ->with(['creator', 'completedByUser'])
            ->orderBy('week_number')
            ->orderBy('deadline')
            ->get();

        return view('targets.submissions.index', compact('group', 'targets'));
    }

    /**
     * Show detail target (untuk mahasiswa lihat detail sebelum submit)
     */
    public function show(WeeklyTarget $target)
    {
        // Check if user is member of the group
        $user = auth()->user();
        $isMember = $target->group->members()->where('user_id', $user->id)->exists();

        if (!$isMember && !$user->isAdmin()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        $target->load(['creator', 'completedByUser', 'reviewer', 'group.members.user']);

        return view('targets.submissions.show', compact('target'));
    }

    /**
     * Form untuk submit target
     */
    public function submitForm(WeeklyTarget $target)
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
                ->with('error', $reason . '. Target tidak dapat disubmit lagi.');
        }

        return view('targets.submissions.submit', compact('target'));
    }

    /**
     * Store submission dari mahasiswa
     */
    public function storeSubmission(Request $request, WeeklyTarget $target)
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
                ->with('error', $reason . '. Target tidak dapat disubmit lagi.');
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
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120', // 5MB max
        ]);

        $evidencePaths = $target->evidence_files ?? [];
        $isCheckedOnly = $request->has('is_checked_only') && $request->is_checked_only == '1';

        // Handle file uploads
        if ($request->hasFile('evidence') && !$isCheckedOnly) {
            \Log::info('Processing file uploads', [
                'count' => count($request->file('evidence')),
            ]);

            foreach ($request->file('evidence') as $file) {
                try {
                    // Try upload to Google Drive first
                    $fileId = $this->googleDriveService->uploadFile(
                        $file,
                        config('services.google_drive.folder_id')
                    );
                    
                    $evidencePaths[] = [
                        'file_id' => $fileId,
                        'file_name' => $file->getClientOriginalName(),
                        'file_url' => $this->googleDriveService->getFileUrl($fileId),
                    ];

                    \Log::info('File uploaded to Google Drive', ['file_id' => $fileId]);
                } catch (\Exception $e) {
                    \Log::error('Google Drive upload failed, using local storage', [
                        'error' => $e->getMessage()
                    ]);

                    // Fallback to local storage
                    $path = $file->store('evidence', 'public');
                    $evidencePaths[] = [
                        'local_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
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
    public function editSubmission(WeeklyTarget $target)
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

        return view('targets.submissions.edit', compact('target'));
    }

    /**
     * Update submission dari mahasiswa
     */
    public function updateSubmission(Request $request, WeeklyTarget $target)
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
            'evidence.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $evidencePaths = $target->evidence_files ?? [];
        $isCheckedOnly = $request->has('is_checked_only') && $request->is_checked_only == '1';

        // Handle new file uploads
        if ($request->hasFile('evidence') && !$isCheckedOnly) {
            foreach ($request->file('evidence') as $file) {
                try {
                    $fileId = $this->googleDriveService->uploadFile(
                        $file,
                        config('services.google_drive.folder_id')
                    );
                    
                    $evidencePaths[] = [
                        'file_id' => $fileId,
                        'file_name' => $file->getClientOriginalName(),
                        'file_url' => $this->googleDriveService->getFileUrl($fileId),
                    ];
                } catch (\Exception $e) {
                    $path = $file->store('evidence', 'public');
                    $evidencePaths[] = [
                        'local_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ];
                }
            }

            $isCheckedOnly = false;
        }

        // If switched to check-only mode, clear files
        if ($isCheckedOnly) {
            $evidencePaths = [];
        }

        // Update submission status if needed
        $submissionStatus = $target->submission_status;
        if ($target->deadline && $target->completed_at && $target->completed_at->gt($target->deadline)) {
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
}
