<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, Kelompok, TargetMingguan};
use App\Exports\WeeklyProgressExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    /**
     * Export laporan progress mingguan ke format Excel (XLSX)
     */
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $classRoomId = $request->input('class_room_id');
        $weekNumber = $request->input('week_number');

        // Get data with todoItems eager loaded - termasuk yang belum submit (pending)
        $query = TargetMingguan::with([
            'group.classRoom',
            'group.leader',
            'completedByUser',
            'review',
            'todoItems'
        ]);

        // Filter berdasarkan kelas jika dipilih
        if ($classRoomId) {
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

        // Calculate progress from todo items for each target
        foreach ($targets as $target) {
            // Progress berdasarkan todo list yang sudah dikerjakan mahasiswa
            $target->progress_percent = $target->getCompletionPercentage();
        }

        // Statistics
        $stats = $this->getStatistics($classRoomId, $weekNumber);

        // Generate filename
        $classRoom = $classRoomId ? RuangKelas::find($classRoomId) : null;
        $filename = 'Laporan_Progress_Mingguan';
        if ($classRoom) {
            $filename .= '_' . str_replace(' ', '_', $classRoom->name);
        }
        if ($weekNumber) {
            $filename .= '_Minggu_' . $weekNumber;
        }
        $filename .= '_' . date('Y-m-d') . '.xlsx';

        // Export ke Excel
        return Excel::download(new WeeklyProgressExport($targets, $stats), $filename);
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

        // Total = jumlah minggu unik (bukan jumlah target)
        $totalWeeks = (clone $query)->distinct('week_number')->count('week_number');
        $submitted = (clone $query)->whereIn('submission_status', ['submitted', 'late', 'approved', 'revision'])->count();
        $approved = (clone $query)->where('submission_status', 'approved')->count();
        $pending = (clone $query)->where('submission_status', 'pending')->count();
        $late = (clone $query)->where('submission_status', 'late')->count();
        $total = (clone $query)->count();

        return [
            'total' => $totalWeeks, // Total minggu
            'submitted' => $submitted,
            'approved' => $approved,
            'pending' => $pending,
            'late' => $late,
            'progress_rate' => $total > 0 ? round(($submitted / $total) * 100, 1) : 0,
        ];
    }
}
