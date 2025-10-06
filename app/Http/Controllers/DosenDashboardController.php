<?php

namespace App\Http\Controllers;

use App\Models\{ClassRoom, Group, WeeklyProgress, GroupScore, WeeklyTarget};

class DosenDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Get weekly targets data
        $totalTargets = WeeklyTarget::count();
        $completedTargets = WeeklyTarget::where('is_completed', true)->count();
        $completionRate = $totalTargets > 0 ? ($completedTargets / $totalTargets) * 100 : 0;

        // Dosen dapat melihat kelas yang mereka ampu
        // Untuk saat ini, tampilkan semua data
        $stats = [
            'totalClassRooms' => ClassRoom::count(),
            'totalGroups' => Group::count(),
            'totalScores' => GroupScore::count(),
            'pendingReviews' => WeeklyProgress::where('status', 'submitted')->count(),
            'totalTargets' => $totalTargets,
            'completedTargets' => $completedTargets,
            'completionRate' => round($completionRate, 1),
            'pendingTargets' => $totalTargets - $completedTargets,
        ];

        // Classes with groups
        $classRooms = ClassRoom::with(['groups', 'subject'])
            ->withCount('groups')
            ->latest()
            ->take(5)
            ->get();

        // Progress yang perlu direview
        $progressToReview = WeeklyProgress::with(['group.classRoom'])
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        // Recent weekly targets
        $recentTargets = WeeklyTarget::with(['group.classRoom'])
            ->latest('created_at')
            ->take(10)
            ->get();

        return view('dashboards.dosen', compact('stats', 'classRooms', 'progressToReview', 'recentTargets'));
    }
}
