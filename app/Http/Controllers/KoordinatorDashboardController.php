<?php

namespace App\Http\Controllers;

use App\Models\{ClassRoom, Group, WeeklyProgress};

class KoordinatorDashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'totalClassRooms' => ClassRoom::count(),
            'totalGroups' => Group::count(),
            'activeGroups' => Group::whereHas('members')->count(),
            'totalProgress' => WeeklyProgress::count(),
            'pendingReviews' => WeeklyProgress::where('status', 'submitted')->count(),
        ];

        // Groups that need attention (no members, incomplete progress, etc.)
        $groupsNeedingAttention = Group::with(['classRoom', 'members'])
            ->withCount('members')
            ->having('members_count', '<', 3)
            ->orWhereDoesntHave('members')
            ->take(10)
            ->get();

        // Recent progress submissions
        $recentProgress = WeeklyProgress::with(['group'])
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('dashboards.koordinator', compact('stats', 'groupsNeedingAttention', 'recentProgress'));
    }
}
