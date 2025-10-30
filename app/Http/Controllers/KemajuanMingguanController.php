<?php

namespace App\Http\Controllers;

use App\Models\KemajuanMingguan;
use App\Models\Kelompok;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KemajuanMingguanController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }

    public function index(Kelompok $group)
    {
        $this->authorize('view', $group);
        
        $weeklyProgress = $group->weeklyProgress()
            ->with('review')
            ->orderBy('week_number')
            ->get();

        return view('kemajuan-mingguan.daftar', compact('group', 'weeklyProgress'));
    }

    public function create(Request $request)
    {
        $groupId = $request->get('group_id');
        
        if (!$groupId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Group ID tidak ditemukan.');
        }
        
        $group = Kelompok::findOrFail($groupId);
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        return view('kemajuan-mingguan.tambah', compact('group'));
    }

    /**
     * Show upload form for weekly progress (flexible upload)
     */
    public function upload(Request $request)
    {
        $groupId = $request->get('group_id');
        $weekNumber = $request->get('week_number');
        $targetId = $request->get('target_id');
        
        if (!$groupId || !$weekNumber) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data tidak lengkap. Silakan coba lagi.');
        }
        
        $group = Kelompok::findOrFail($groupId);
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }

        // Get target if exists
        $target = null;
        if ($targetId) {
            $target = \App\Models\TargetMingguan::find($targetId);
        }

        return view('kemajuan-mingguan.unggah', compact('group', 'weekNumber', 'target'));
    }

    public function store(Request $request)
    {
        $group = Kelompok::findOrFail($request->group_id);
        
        // Check if user is member of the group
        $isMember = $group->members()->where('user_id', auth()->id())->exists();
        
        if (!$isMember && !auth()->user()->isAdmin() && !auth()->user()->isKoordinator()) {
            abort(403, 'Anda bukan anggota kelompok ini.');
        }
        
        $validated = $request->validate([
            'group_id' => 'required|exists:kelompok,id',
            'week_number' => 'required|integer|min:1',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_checked_only' => 'nullable|boolean',
            'target_id' => 'nullable|exists:target_mingguan,id',
            'evidence.*' => 'nullable|file|max:5120', // 5MB max per file
        ]);

        // Handle evidence uploads - upload to Google Drive
        $evidencePaths = [];
        if ($request->hasFile('evidence') && !$request->is_checked_only) {
            foreach ($request->file('evidence') as $file) {
                try {
                    // Check if Google Drive is configured
                    if ($this->googleDriveService->isConfigured()) {
                        // Upload ke Google Drive
                        $fileId = $this->googleDriveService->uploadFile(
                            $file,
                            config('services.google_drive.folder_id')
                        );
                        
                        // Simpan file ID dan URL
                        $evidencePaths[] = [
                            'storage_type' => 'google_drive',
                            'file_id' => $fileId,
                            'file_name' => $file->getClientOriginalName(),
                            'file_url' => $this->googleDriveService->getFileUrl($fileId),
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getMimeType(),
                            'uploaded_at' => now()->toDateTimeString(),
                        ];
                        
                        \Log::info('File uploaded to Google Drive successfully', [
                            'file_name' => $file->getClientOriginalName(),
                            'file_id' => $fileId,
                        ]);
                    } else {
                        throw new \Exception('Google Drive is not configured');
                    }
                } catch (\Exception $e) {
                    \Log::error('Google Drive upload error: ' . $e->getMessage(), [
                        'file_name' => $file->getClientOriginalName(),
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    
                    // Fallback: simpan ke local storage jika Google Drive gagal
                    $path = $file->store('weekly-progress/evidence', 'public');
                    $evidencePaths[] = [
                        'storage_type' => 'local',
                        'local_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_url' => asset('storage/' . $path),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                        'uploaded_at' => now()->toDateTimeString(),
                    ];
                    
                    \Log::info('File saved to local storage as fallback', [
                        'file_name' => $file->getClientOriginalName(),
                        'path' => $path,
                    ]);
                }
            }
        }

        // Create weekly progress entry
        $weeklyProgress = KemajuanMingguan::create([
            'group_id' => $group->id,
            'week_number' => $validated['week_number'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? '',
            'activities' => $validated['description'] ?? 'Progress telah disubmit', // Use description as activities or default text
            'documents' => $evidencePaths,
            'is_checked_only' => $request->is_checked_only ?? false,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        // Update related weekly target status if exists
        if ($request->target_id) {
            $target = \App\Models\TargetMingguan::find($request->target_id);
            if ($target && $target->group_id == $group->id) {
                $target->update([
                    'submission_status' => 'submitted',
                    'submitted_at' => now(),
                    'is_completed' => true,  // Set is_completed untuk review dosen
                    'completed_at' => now(),
                    'completed_by' => auth()->id(),
                    'evidence_files' => $evidencePaths,  // Save evidence files reference
                    'submission_notes' => $validated['description'] ?? null,
                    'is_checked_only' => $request->is_checked_only ?? false,
                ]);
                
                \Log::info('Weekly target updated with submission', [
                    'target_id' => $target->id,
                    'group_id' => $group->id,
                    'user_id' => auth()->id(),
                ]);
            }
        }

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Progress mingguan berhasil diupload! Dosen akan segera mereview progress Anda.');
    }

    public function show(Kelompok $group, KemajuanMingguan $weeklyProgress)
    {
        $this->authorize('view', $group);
        
        $weeklyProgress->load('review.reviewer');
        
        return view('kemajuan-mingguan.tampil', compact('group', 'weeklyProgress'));
    }

    public function edit(Kelompok $group, KemajuanMingguan $weeklyProgress)
    {
        $this->authorize('update', $group);
        
        if (!$weeklyProgress->canBeEdited()) {
            return redirect()->back()->with('error', 'Progress ini tidak dapat diedit lagi.');
        }

        return view('kemajuan-mingguan.ubah', compact('group', 'weeklyProgress'));
    }

    public function update(Request $request, Kelompok $group, KemajuanMingguan $weeklyProgress)
    {
        $this->authorize('update', $group);
        
        if (!$weeklyProgress->canBeEdited()) {
            return redirect()->back()->with('error', 'Progress ini tidak dapat diedit lagi.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'activities' => 'required|string',
            'achievements' => 'nullable|string',
            'challenges' => 'nullable|string',
            'next_week_plan' => 'nullable|string',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240',
        ]);

        // Handle new document uploads
        $documentIds = $weeklyProgress->documents ?? [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $documentId = $this->googleDriveService->uploadFile(
                    $file,
                    $group->google_drive_folder_id
                );
                $documentIds[] = $documentId;
            }
        }

        $weeklyProgress->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'activities' => $validated['activities'],
            'achievements' => $validated['achievements'],
            'challenges' => $validated['challenges'],
            'next_week_plan' => $validated['next_week_plan'],
            'documents' => $documentIds,
        ]);

        return redirect()->route('weekly-progress.show', [$group, $weeklyProgress])
            ->with('success', 'Progress mingguan berhasil diperbarui.');
    }

    public function submit(Kelompok $group, KemajuanMingguan $weeklyProgress)
    {
        $this->authorize('update', $group);
        
        if (!$weeklyProgress->canBeEdited()) {
            return redirect()->back()->with('error', 'Progress ini tidak dapat disubmit lagi.');
        }

        $weeklyProgress->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return redirect()->route('weekly-progress.show', [$group, $weeklyProgress])
            ->with('success', 'Progress mingguan berhasil disubmit untuk direview.');
    }

    private function calculateDeadline(Kelompok $group, int $weekNumber)
    {
        $project = $group->project;
        $startDate = $project->start_date;
        
        // Deadline is end of Sunday of the specified week
        return $startDate->copy()->addWeeks($weekNumber - 1)->endOfWeek();
    }
}


