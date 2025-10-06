<?php

namespace App\Http\Controllers;

use App\Models\{Group, WeeklyTarget};

class MahasiswaDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Get student's group
        $myGroup = $user->groups()->with(['classRoom', 'leader', 'members.user'])->first();

        // Get weekly targets for this group (dibuat oleh dosen)
        $weeklyTargets = collect();
        $stats = [
            'totalTargets' => 0,
            'completedTargets' => 0,
            'pendingTargets' => 0,
            'completionRate' => 0,
        ];

        if ($myGroup) {
            $weeklyTargets = WeeklyTarget::where('group_id', $myGroup->id)
                ->orderBy('week_number')
                ->orderBy('deadline')
                ->get();

            // Calculate stats
            $totalTargets = $weeklyTargets->count();
            $completedTargets = $weeklyTargets->where('submission_status', 'approved')->count();
            $submittedTargets = $weeklyTargets->whereIn('submission_status', ['submitted', 'approved'])->count();
            $completionRate = $totalTargets > 0 ? ($submittedTargets / $totalTargets) * 100 : 0;

            $stats = [
                'totalTargets' => $totalTargets,
                'completedTargets' => $completedTargets,
                'submittedTargets' => $submittedTargets,
                'pendingTargets' => $totalTargets - $submittedTargets,
                'completionRate' => round($completionRate, 1),
            ];
        }

        return view('dashboards.mahasiswa', compact('myGroup', 'weeklyTargets', 'stats'));
    }
}
