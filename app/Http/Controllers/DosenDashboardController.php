<?php

namespace App\Http\Controllers;

use App\Models\{ClassRoom, Group, WeeklyProgress, GroupScore};

class DosenDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Dosen dapat melihat kelas yang mereka ampu
        // Untuk saat ini, tampilkan semua data
        $stats = [
            'totalClassRooms' => ClassRoom::count(),
            'totalGroups' => Group::count(),
            'totalScores' => GroupScore::count(),
            'pendingReviews' => WeeklyProgress::where('status', 'submitted')->count(),
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

        return view('dashboards.dosen', compact('stats', 'classRooms', 'progressToReview'));
    }
}
