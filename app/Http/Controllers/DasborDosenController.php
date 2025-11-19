<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, KemajuanMingguan, NilaiKelompok, TargetMingguan};

class DasborDosenController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Filter data berdasarkan kelas yang diampu oleh dosen
        $myClassRoomIds = RuangKelas::where('dosen_id', $user->id)->pluck('id');
        $myGroupIds = Kelompok::whereIn('class_room_id', $myClassRoomIds)->pluck('id');

        // Get weekly targets data for this dosen's classes
        $totalTargets = TargetMingguan::whereIn('group_id', $myGroupIds)->count();
        $completedTargets = TargetMingguan::whereIn('group_id', $myGroupIds)
            ->where('is_completed', true)->count();
        $completionRate = $totalTargets > 0 ? ($completedTargets / $totalTargets) * 100 : 0;

        // Stats untuk kelas yang diampu dosen
        $stats = [
            'totalClassRooms' => RuangKelas::where('dosen_id', $user->id)->count(),
            'totalGroups' => Kelompok::whereIn('class_room_id', $myClassRoomIds)->count(),
            'totalScores' => NilaiKelompok::whereIn('group_id', $myGroupIds)->count(),
            'pendingReviews' => TargetMingguan::whereIn('group_id', $myGroupIds)
                ->whereIn('submission_status', ['submitted', 'revision'])->count(),
            'totalTargets' => $totalTargets,
            'completedTargets' => $completedTargets,
            'completionRate' => round($completionRate, 1),
            'pendingTargets' => $totalTargets - $completedTargets,
        ];

        // Classes assigned to this dosen
        $classRooms = RuangKelas::with(['groups.members.user', 'academicPeriod', 'dosen'])
            ->where('dosen_id', $user->id)
            ->where('is_active', true)
            ->withCount('groups')
            ->latest()
            ->get();

        // Progress yang perlu direview dari kelas dosen
        $progressToReview = TargetMingguan::with(['group.classRoom', 'completedByUser'])
            ->whereIn('group_id', $myGroupIds)
            ->whereIn('submission_status', ['submitted', 'revision'])
            ->latest('completed_at')
            ->take(10)
            ->get();

        // Recent weekly targets from dosen's classes
        $recentTargets = TargetMingguan::with(['group.classRoom', 'creator'])
            ->whereIn('group_id', $myGroupIds)
            ->latest('created_at')
            ->take(10)
            ->get();

        // Quick access: Get submission stats per class (akan kosong jika tidak ada class rooms)
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
