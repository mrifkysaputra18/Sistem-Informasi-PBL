<?php

namespace App\Http\Controllers;

use App\Models\{ClassRoom, Group, WeeklyProgress, GroupScore, WeeklyTarget};

class DosenDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Filter data berdasarkan kelas yang diampu oleh dosen
        $myClassRoomIds = ClassRoom::where('dosen_id', $user->id)->pluck('id');
        $myGroupIds = Group::whereIn('class_room_id', $myClassRoomIds)->pluck('id');

        // Get weekly targets data for this dosen's classes
        $totalTargets = WeeklyTarget::whereIn('group_id', $myGroupIds)->count();
        $completedTargets = WeeklyTarget::whereIn('group_id', $myGroupIds)
            ->where('is_completed', true)->count();
        $completionRate = $totalTargets > 0 ? ($completedTargets / $totalTargets) * 100 : 0;

        // Stats untuk kelas yang diampu dosen
        $stats = [
            'totalClassRooms' => ClassRoom::where('dosen_id', $user->id)->count(),
            'totalGroups' => Group::whereIn('class_room_id', $myClassRoomIds)->count(),
            'totalScores' => GroupScore::whereIn('group_id', $myGroupIds)->count(),
            'pendingReviews' => WeeklyTarget::whereIn('group_id', $myGroupIds)
                ->whereIn('submission_status', ['submitted', 'revision'])->count(),
            'totalTargets' => $totalTargets,
            'completedTargets' => $completedTargets,
            'completionRate' => round($completionRate, 1),
            'pendingTargets' => $totalTargets - $completedTargets,
        ];

        // Classes assigned to this dosen
        $classRooms = ClassRoom::with(['groups', 'subject', 'academicPeriod'])
            ->where('dosen_id', $user->id)
            ->where('is_active', true)
            ->withCount(['groups', 'groups as members_total' => function($query) {
                $query->join('group_members', 'groups.id', 'group_members.group_id')->count();
            }])
            ->latest()
            ->get();

        // Progress yang perlu direview dari kelas dosen
        $progressToReview = WeeklyTarget::with(['group.classRoom', 'completedByUser'])
            ->whereIn('group_id', $myGroupIds)
            ->whereIn('submission_status', ['submitted', 'revision'])
            ->latest('completed_at')
            ->take(10)
            ->get();

        // Recent weekly targets from dosen's classes
        $recentTargets = WeeklyTarget::with(['group.classRoom', 'creator'])
            ->whereIn('group_id', $myGroupIds)
            ->latest('created_at')
            ->take(10)
            ->get();

        // Quick access: Get submission stats per class
        $classStats = $classRooms->map(function($classRoom) {
            $targets = WeeklyTarget::whereHas('group', function($query) use ($classRoom) {
                $query->where('class_room_id', $classRoom->id);
            })->get();

            return [
                'classRoom' => $classRoom,
                'totalTargets' => $targets->count(),
                'submittedTargets' => $targets->whereIn('submission_status', ['submitted', 'approved', 'revision'])->count(),
                'approvedTargets' => $targets->where('submission_status', 'approved')->count(),
                'pendingReviews' => $targets->whereIn('submission_status', ['submitted', 'revision'])->count(),
                'completionRate' => $targets->count() > 0 
                    ? round(($targets->where('submission_status', 'approved')->count() / $targets->count()) * 100, 1)
                    : 0,
            ];
        });

        return view('dashboards.dosen', 
            compact('stats', 'classRooms', 'progressToReview', 'recentTargets', 'classStats'));
    }
}
