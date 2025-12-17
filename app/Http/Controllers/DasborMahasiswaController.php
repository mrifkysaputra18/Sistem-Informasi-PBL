<?php

namespace App\Http\Controllers;

use App\Models\{Kelompok, TargetMingguan, PeriodeAkademik};

class DasborMahasiswaController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $activePeriod = PeriodeAkademik::getCurrent();

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
            // Get weekly targets, EXCLUDING closed ones AND expired pending targets
            $weeklyTargets = TargetMingguan::where('group_id', $myGroup->id)
                ->orderBy('week_number')
                ->orderBy('deadline')
                ->get()
                ->filter(function($t) {
                    // Sembunyikan target yang sudah tertutup (is_locked = true)
                    if ($t->isClosed()) {
                        return false;
                    }
                    // Sembunyikan target pending yang deadline-nya sudah lewat
                    if ($t->submission_status === 'pending' && $t->deadline && now()->gt($t->deadline)) {
                        return false;
                    }
                    return true;
                });

            // Calculate stats (based on filtered targets)
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

        return view('dasbor.mahasiswa', compact('myGroup', 'weeklyTargets', 'stats', 'activePeriod'));
    }
}
