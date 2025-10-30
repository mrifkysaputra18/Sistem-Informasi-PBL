<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, TargetMingguan, KemajuanMingguan};

class KemajuanDosenController extends Controller
{
    /**
     * Tampilkan semua kelas yang diampu dosen
     */
    public function index()
    {
        $user = auth()->user();
        
        // Dosen hanya melihat kelas yang dia ampu
        $classRooms = RuangKelas::with(['subject', 'groups', 'academicPeriod'])
            ->where('dosen_id', $user->id)
            ->where('is_active', true)
            ->withCount(['groups', 'groups as group_members_count' => function($query) {
                $query->join('group_members', 'groups.id', 'group_members.group_id')
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
    public function showClass(ClassRoom $classRoom)
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
            'subject',
            'academicPeriod'
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
    public function showGroup(ClassRoom $classRoom, Group $group)
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
     * Download file submission mahasiswa
     */
    public function downloadFile(ClassRoom $classRoom, Group $group, $targetId, $fileName)
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

        if (!$files || !isset($files[$fileName])) {
            abort(404, 'File not found.');
        }

        $filePath = $files[$fileName]['path'];
        $fullPath = public_path($filePath);

        if (!file_exists($fullPath)) {
            abort(404, 'File not found on server.');
        }

        return response()->download($fullPath, $fileName);
    }

    /**
     * API untuk get groups by classroom (untuk dropdown)
     */
    public function getGroupsByClassroom(ClassRoom $classRoom)
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
