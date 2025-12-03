<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, TargetMingguan, KemajuanMingguan};
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;

class KemajuanDosenController extends Controller
{
    protected $googleDriveService;

    public function __construct(GoogleDriveService $googleDriveService)
    {
        $this->googleDriveService = $googleDriveService;
    }
    /**
     * Tampilkan semua kelas yang diampu dosen
     */
    public function index()
    {
        $user = auth()->user();
        
        // Dosen hanya melihat kelas yang dia ampu
        $classRooms = RuangKelas::with(['groups', 'academicPeriod', 'dosen'])
            ->where('dosen_id', $user->id)
            ->where('is_active', true)
            ->withCount(['groups', 'groups as group_members_count' => function($query) {
                $query->join('anggota_kelompok', 'kelompok.id', 'anggota_kelompok.group_id')
                      ->count();
            }])
            ->get();

        // Stats keseluruhan untuk dosen
        $stats = [
            'totalClassRooms' => $classRooms->count(),
            'totalGroups' => $classRooms->sum->groups_count,
            'totalStudents' => $classRooms->sum->group_members_count,
            'pendingReviews' => KemajuanMingguan::whereHas('group.classRoom', function($query) use ($user) {
                $query->where('dosen_id', $user->id);
            })->where('submission_status', 'submitted')->orWhereIn('submission_status', ['submitted', 'revision'])->count(),
        ];

        return view('dosen.kemajuan.daftar', compact('classRooms', 'stats'));
    }

    /**
     * Tampilkan detail progress kelas tertentu
     */
    public function showClass(RuangKelas $classRoom)
    {
        $user = auth()->user();
        
        // Validasi: dosen hanya bisa lihat kelas sendiri
        if ($classRoom->dosen_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Load groups dengan members dan progress
        $classRoom->load([
            'groups.members.user',
            'groups.weeklyTargets' => function($query) {
                $query->with(['creator', 'completedByUser', 'reviewer'])
                      ->orderBy('week_number');
            },
            'academicPeriod',
            'dosen'
        ]);

        // Stats untuk kelas ini
        $stats = [
            'totalGroups' => $classRoom->groups->count(),
            'totalStudents' => $classRoom->groups->sum(fn($group) => $group->members->count()),
            'totalTargets' => $classRoom->groups->sum(fn($group) => $group->weeklyTargets->count()),
            'completedTargets' => $classRoom->groups->sum(fn($group) => $group->weeklyTargets->where('is_completed', true)->count()),
            'pendingReviews' => $classRoom->groups->sum(fn($group) => $group->weeklyTargets
                ->whereIn('submission_status', ['submitted', 'revision'])->count()),
        ];

        $stats['completionRate'] = $stats['totalTargets'] > 0 
            ? round(($stats['completedTargets'] / $stats['totalTargets']) * 100, 1)
            : 0;

        return view('dosen.kemajuan.tampil-kelas', compact('classRoom', 'stats'));
    }

    /**
     * Tampilkan detail kelompok dengan semua submission files
     */
    public function showGroup(RuangKelas $classRoom, Kelompok $group)
    {
        $user = auth()->user();
        
        // Validasi akses
        if (($classRoom->dosen_id !== $user->id || $group->class_room_id !== $classRoom->id) && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        // Load data lengkap kelompok
        $group->load([
            'members.user',
            'leader',
            'weeklyTargets' => function($query) {
                $query->with(['creator', 'completedByUser', 'reviewer', 'review'])
                      ->orderBy('week_number');
            },
            'weeklyProgress' => function($query) {
                $query->with(['review'])
                      ->orderBy('week_number');
            }
        ]);

        // Stats untuk kelompok ini
        $stats = [
            'totalTargets' => $group->weeklyTargets->count(),
            'submittedTargets' => $group->weeklyTargets->whereIn('submission_status', ['submitted', 'approved', 'revision'])->count(),
            'approvedTargets' => $group->weeklyTargets->where('submission_status', 'approved')->count(),
            'revisionTargets' => $group->weeklyTargets->where('submission_status', 'revision')->count(),
            'pendingTargets' => $group->weeklyTargets->where('submission_status', 'pending')->count(),
        ];

        $stats['submissionRate'] = $stats['totalTargets'] > 0 
            ? round(($stats['submittedTargets'] / $stats['totalTargets']) * 100, 1)
            : 0;

        // Group files by target untuk tampilan yang lebih baik
        $targetsWithFiles = $group->weeklyTargets->map(function($target) {
            // Parse evidence files (JSON array)
            $files = [];
            if ($target->evidence_files) {
                $files = is_string($target->evidence_files) 
                    ? json_decode($target->evidence_files, true) 
                    : $target->evidence_files;
            }

            return [
                'target' => $target,
                'files' => $files,
                'submission' => $target->completed_at ? [
                    'submitted_at' => $target->completed_at,
                    'submitted_by' => $target->completedByUser,
                    'notes' => $target->submission_notes,
                ] : null,
                'review' => $target->review,
            ];
        });

        return view('dosen.kemajuan.tampil-kelompok', compact('classRoom', 'group', 'stats', 'targetsWithFiles'));
    }

    /**
     * Download file submission mahasiswa dari Google Drive atau local storage
     */
    public function downloadFile(RuangKelas $classRoom, Kelompok $group, $targetId, $fileIndex)
    {
        $user = auth()->user();
        
        // Validasi akses
        if (($classRoom->dosen_id !== $user->id || $group->class_room_id !== $classRoom->id) && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $target = $group->weeklyTargets()->findOrFail($targetId);
        
        // Parse files
        $files = is_string($target->evidence_files) 
            ? json_decode($target->evidence_files, true) 
            : $target->evidence_files;

        if (!$files || !isset($files[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $fileInfo = $files[$fileIndex];
        $fileName = $fileInfo['file_name'] ?? 'download';

        // Check if file is on Google Drive
        if (isset($fileInfo['file_id']) && ($fileInfo['storage'] ?? '') === 'google_drive') {
            try {
                $result = $this->googleDriveService->downloadFile($fileInfo['file_id']);
                
                if ($result['success']) {
                    return response($result['content'])
                        ->header('Content-Type', $result['mime_type'])
                        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
                        ->header('Content-Length', $result['size']);
                }
                
                // If download fails, redirect to Google Drive view URL
                return redirect($fileInfo['file_url'] ?? $this->googleDriveService->getFileUrl($fileInfo['file_id']));
            } catch (\Exception $e) {
                \Log::error('Failed to download from Google Drive', [
                    'file_id' => $fileInfo['file_id'],
                    'error' => $e->getMessage()
                ]);
                
                // Redirect to Google Drive as fallback
                if (isset($fileInfo['file_url'])) {
                    return redirect($fileInfo['file_url']);
                }
                
                abort(500, 'Gagal mengunduh file dari Google Drive.');
            }
        }

        // Local storage fallback
        if (isset($fileInfo['local_path'])) {
            $fullPath = storage_path('app/public/' . $fileInfo['local_path']);
            
            if (!file_exists($fullPath)) {
                abort(404, 'File not found on server.');
            }

            return response()->download($fullPath, $fileName);
        }

        abort(404, 'File not found.');
    }

    /**
     * Redirect ke Google Drive folder kelompok
     */
    public function viewGroupFolder(RuangKelas $classRoom, Kelompok $group)
    {
        $user = auth()->user();
        
        // Validasi akses
        if (($classRoom->dosen_id !== $user->id || $group->class_room_id !== $classRoom->id) && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$group->google_drive_folder_id) {
            return back()->with('error', 'Kelompok ini belum memiliki folder Google Drive.');
        }

        return redirect($this->googleDriveService->getFolderUrl($group->google_drive_folder_id));
    }

    /**
     * Download semua file dari target tertentu sebagai redirect ke Google Drive
     */
    public function downloadAllFiles(RuangKelas $classRoom, Kelompok $group, $targetId)
    {
        $user = auth()->user();
        
        // Validasi akses
        if (($classRoom->dosen_id !== $user->id || $group->class_room_id !== $classRoom->id) && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $target = $group->weeklyTargets()->findOrFail($targetId);
        
        // Jika kelompok punya folder Google Drive, redirect ke folder minggu
        if ($group->google_drive_folder_id) {
            $weekFolderName = "Minggu-{$target->week_number}";
            $weekFolderId = $this->googleDriveService->findFolderByName($weekFolderName, $group->google_drive_folder_id);
            
            if ($weekFolderId) {
                return redirect($this->googleDriveService->getFolderUrl($weekFolderId));
            }
            
            // Fallback ke folder kelompok
            return redirect($this->googleDriveService->getFolderUrl($group->google_drive_folder_id));
        }

        return back()->with('error', 'Tidak ada file yang bisa diunduh.');
    }

    /**
     * API untuk get groups by classroom (untuk dropdown)
     */
    public function getGroupsByClassroom(RuangKelas $classRoom)
    {
        $user = auth()->user();
        
        // Validasi akses
        if ($classRoom->dosen_id !== $user->id && !$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $groups = $classRoom->groups()
            ->with(['members.user', 'leader'])
            ->get()
            ->map(function($group) {
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'project_title' => $group->project?->title ?? 'No project',
                    'leader' => $group->leader?->name ?? 'No leader',
                    'members_count' => $group->members->count(),
                    'targets_count' => $group->weeklyTargets->count(),
                    'completed_count' => $group->weeklyTargets->where('is_completed', true)->count(),
                ];
            });

        return response()->json($groups);
    }
}
