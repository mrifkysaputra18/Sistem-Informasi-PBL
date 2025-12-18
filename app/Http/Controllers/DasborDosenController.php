<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, KemajuanMingguan, NilaiKelompok, TargetMingguan, MataKuliah};
use Illuminate\Support\Facades\DB;

class DasborDosenController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Cari mata kuliah yang diampu dosen ini
        $mataKuliahIds = DB::table('dosen_mata_kuliah')
            ->where('dosen_id', $user->id)
            ->pluck('mata_kuliah_id');

        // Cari kelas yang ditugaskan ke dosen ini (sebagai Dosen PBL)
        $myClassRooms = $user->kelasPblAktif; 
        $myClassRoomIds = $myClassRooms->pluck('id');
        
        // Groups under my assigned classes
        $myGroupIds = Kelompok::whereIn('class_room_id', $myClassRoomIds)->pluck('id');

        // Get weekly targets data
        $totalTargets = TargetMingguan::whereIn('group_id', $myGroupIds)->count();
        $completedTargets = TargetMingguan::whereIn('group_id', $myGroupIds)
            ->where('is_completed', true)->count();
        $completionRate = $totalTargets > 0 ? ($completedTargets / $totalTargets) * 100 : 0;

        // Stats untuk dosen
        $stats = [
            'totalClassRooms' => $myClassRoomIds->count(),
            'totalGroups' => $myGroupIds->count(),
            'totalScores' => NilaiKelompok::whereIn('group_id', $myGroupIds)->count(),
            'pendingReviews' => TargetMingguan::whereIn('group_id', $myGroupIds)
                ->whereIn('submission_status', ['submitted', 'revision'])->count(),
            'totalTargets' => $totalTargets,
            'completedTargets' => $completedTargets,
            'completionRate' => round($completionRate, 1),
            'pendingTargets' => $totalTargets - $completedTargets,
            'mataKuliahDiampu' => $mataKuliahIds->count(),
        ];

        // Only active classes assigned to this lecturer
        $classRooms = RuangKelas::with(['groups.members.user', 'academicPeriod'])
            ->whereIn('id', $myClassRoomIds)
            ->where('is_active', true)
            ->withCount('groups')
            ->latest()
            ->get();

        // Progress yang perlu direview (only from my assigned classes)
        $progressToReview = TargetMingguan::with(['group.classRoom', 'completedByUser'])
            ->whereIn('group_id', $myGroupIds)
            ->whereIn('submission_status', ['submitted', 'late']) // Changed to match logic in UlasanController
            ->where('is_reviewed', false)
            ->latest('completed_at')
            ->take(10)
            ->get();

        // Recent weekly targets
        $recentTargets = TargetMingguan::with(['group.classRoom', 'creator'])
            ->whereIn('group_id', $myGroupIds)
            ->latest('created_at')
            ->take(10)
            ->get();

        // Quick access: Get submission stats per class
        $classStats = collect();
        
        if ($classRooms->isNotEmpty()) {
            $classStats = $classRooms->map(function($classRoom) {
                $targets = TargetMingguan::whereHas('group', function($query) use ($classRoom) {
                    $query->where('class_room_id', $classRoom->id);
                })->get();

                $approvedCount = $targets->where('submission_status', 'approved')->count();
                
                return [
                    'classRoom' => $classRoom,
                    'totalTargets' => $targets->count(),
                    'submittedTargets' => $targets->whereIn('submission_status', ['submitted', 'approved', 'revision'])->count(),
                    'approvedTargets' => $approvedCount,
                    'pendingReviews' => $targets->whereIn('submission_status', ['submitted', 'revision'])->count(),
                    'completionRate' => $targets->count() > 0 
                        ? round(($approvedCount / $targets->count()) * 100, 1)
                        : 0,
                ];
            });
        }

        return view('dasbor.dosen', 
            compact('stats', 'classRooms', 'progressToReview', 'recentTargets', 'classStats'));
    }
}

