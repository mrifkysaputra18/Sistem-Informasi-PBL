<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, TargetMingguan};
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $classRoomId = $request->input('class_room_id');
        $weekNumber = $request->input('week_number');

        // Get allowed class room IDs based on role
        $allowedClassRoomIds = collect();
        if ($user->isDosen()) {
            $allowedClassRoomIds = RuangKelas::where('dosen_id', $user->id)->pluck('id');
        }

        // Get data
        $query = TargetMingguan::with([
            'group.classRoom.dosen',
            'group.leader',
            'completedByUser',
            'review'
        ])
        ->whereIn('submission_status', ['submitted', 'late', 'approved', 'revision']);

        // Filter by dosen's classes if not admin
        if ($user->isDosen() && $allowedClassRoomIds->isNotEmpty()) {
            $query->whereHas('group', function ($q) use ($allowedClassRoomIds) {
                $q->whereIn('class_room_id', $allowedClassRoomIds);
            });
        }

        if ($classRoomId) {
            // Validate dosen can only export their own class
            if ($user->isDosen() && !$allowedClassRoomIds->contains($classRoomId)) {
                abort(403, 'Anda tidak memiliki akses ke kelas ini.');
            }
            $query->whereHas('group', function ($q) use ($classRoomId) {
                $q->where('class_room_id', $classRoomId);
            });
        }

        if ($weekNumber) {
            $query->where('week_number', $weekNumber);
        }

        $targets = $query->orderBy('week_number')
            ->orderBy('completed_at')
            ->get();

        // Calculate progress for each group
        $groupProgress = [];
        foreach ($targets as $target) {
            $groupId = $target->group_id;
            if (!isset($groupProgress[$groupId])) {
                $totalTargets = TargetMingguan::where('group_id', $groupId)->count();
                $completedTargets = TargetMingguan::where('group_id', $groupId)
                    ->whereIn('submission_status', ['submitted', 'late', 'approved', 'revision'])
                    ->count();
                $groupProgress[$groupId] = $totalTargets > 0 
                    ? round(($completedTargets / $totalTargets) * 100, 1) 
                    : 0;
            }
            $target->progress_percent = $groupProgress[$groupId];
        }

        // Statistics
        $stats = $this->getStatistics($classRoomId, $weekNumber);

        // Get filter info for title
        $classRoom = $classRoomId ? RuangKelas::find($classRoomId) : null;
        $filterInfo = [
            'class_room' => $classRoom ? $classRoom->name : 'Semua Kelas',
            'week' => $weekNumber ? 'Minggu ' . $weekNumber : 'Semua Minggu',
        ];

        $pdf = Pdf::loadView('laporan.pdf', compact('targets', 'stats', 'filterInfo'));
        $pdf->setPaper('A4', 'landscape');

        $filename = 'Laporan_Progress_Mingguan';
        if ($classRoom) {
            $filename .= '_' . str_replace(' ', '_', $classRoom->name);
        }
        if ($weekNumber) {
            $filename .= '_Minggu_' . $weekNumber;
        }
        $filename .= '_' . date('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    private function getStatistics($classRoomId = null, $weekNumber = null)
    {
        $query = TargetMingguan::query();

        if ($classRoomId) {
            $query->whereHas('group', function ($q) use ($classRoomId) {
                $q->where('class_room_id', $classRoomId);
            });
        }

        if ($weekNumber) {
            $query->where('week_number', $weekNumber);
        }

        $total = (clone $query)->count();
        $submitted = (clone $query)->whereIn('submission_status', ['submitted', 'late', 'approved', 'revision'])->count();
        $approved = (clone $query)->where('submission_status', 'approved')->count();
        $pending = (clone $query)->where('submission_status', 'pending')->count();
        $late = (clone $query)->where('submission_status', 'late')->count();

        return [
            'total' => $total,
            'submitted' => $submitted,
            'approved' => $approved,
            'pending' => $pending,
            'late' => $late,
            'progress_rate' => $total > 0 ? round(($submitted / $total) * 100, 1) : 0,
        ];
    }
}
