<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, KemajuanMingguan};

class DasborKoordinatorController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'totalClassRooms' => RuangKelas::count(),
            'totalGroups' => Kelompok::count(),
            'activeGroups' => Kelompok::whereHas('members')->count(),
            'totalProgress' => KemajuanMingguan::count(),
            'pendingReviews' => KemajuanMingguan::where('status', 'submitted')->count(),
        ];

        // Groups that need attention (no members, incomplete progress, etc.)
        $groupsNeedingAttention = Kelompok::with(['classRoom', 'members'])
            ->withCount('members')
            ->having('members_count', '<', 3)
            ->orWhereDoesntHave('members')
            ->take(10)
            ->get();

        // Recent progress submissions
        $recentProgress = KemajuanMingguan::with(['group'])
            ->where('status', 'submitted')
            ->latest('submitted_at')
            ->take(5)
            ->get();

        return view('dasbor.koordinator', compact('stats', 'groupsNeedingAttention', 'recentProgress'));
    }
}
