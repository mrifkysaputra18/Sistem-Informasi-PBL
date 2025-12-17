<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, TargetMingguan};

class DasborKoordinatorController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'totalClassRooms' => RuangKelas::count(),
            'totalGroups' => Kelompok::count(),
            'activeGroups' => Kelompok::whereHas('members')->count(),
            'totalProgress' => TargetMingguan::count(),
            'pendingReviews' => TargetMingguan::whereIn('submission_status', ['submitted', 'revision'])->count(),
        ];

        // Groups that need attention (no members, incomplete progress, etc.)
        $groupsNeedingAttention = Kelompok::with(['classRoom', 'members'])
            ->withCount('members')
            ->having('members_count', '<', 3)
            ->orWhereDoesntHave('members')
            ->take(10)
            ->get();

        // Recent progress submissions
        $recentProgress = TargetMingguan::with(['group'])
            ->whereIn('submission_status', ['submitted', 'revision'])
            ->latest('completed_at')
            ->take(5)
            ->get();

        return view('dasbor.koordinator', compact('stats', 'groupsNeedingAttention', 'recentProgress'));
    }
}
