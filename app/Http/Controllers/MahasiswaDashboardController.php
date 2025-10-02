<?php

namespace App\Http\Controllers;

use App\Models\{Group, WeeklyProgress, WeeklyTarget};

class MahasiswaDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Get student's group
        $myGroup = $user->groups()->with(['classRoom', 'leader', 'members.user'])->first();

        if ($myGroup) {
            // Get weekly targets for this group
            $weeklyTargets = WeeklyTarget::where('group_id', $myGroup->id)
                ->orderBy('week_number')
                ->get();

            // Get weekly progress for this group
            $weeklyProgress = WeeklyProgress::where('group_id', $myGroup->id)
                ->orderBy('week_number', 'desc')
                ->take(5)
                ->get();

            // Calculate completion rate
            $totalTargets = $weeklyTargets->count();
            $completedTargets = $weeklyTargets->where('is_completed', true)->count();
            $completionRate = $totalTargets > 0 ? ($completedTargets / $totalTargets) * 100 : 0;

            $stats = [
                'totalTargets' => $totalTargets,
                'completedTargets' => $completedTargets,
                'completionRate' => round($completionRate, 1),
                'pendingTargets' => $totalTargets - $completedTargets,
                'totalProgress' => $weeklyProgress->count(),
            ];

            return view('dashboards.mahasiswa', compact('myGroup', 'weeklyTargets', 'weeklyProgress', 'stats'));
        }

        // Student belum punya kelompok
        return view('dashboards.mahasiswa', [
            'myGroup' => null,
            'weeklyTargets' => collect(),
            'weeklyProgress' => collect(),
            'stats' => [
                'totalTargets' => 0,
                'completedTargets' => 0,
                'completionRate' => 0,
                'pendingTargets' => 0,
                'totalProgress' => 0,
            ],
        ]);
    }
}
