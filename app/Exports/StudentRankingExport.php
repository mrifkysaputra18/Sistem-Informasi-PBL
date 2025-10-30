<?php

namespace App\Exports;

use App\Models\Proyek;
use App\Models\Pengguna;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class StudentRankingExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function collection()
    {
        return Pengguna::whereHas('groups.project', function ($query) {
            $query->where('id', $this->project->id);
        })
        ->with(['groups' => function ($query) {
            $query->where('project_id', $this->project->id)
                  ->with(['weeklyProgress.review']);
        }])
        ->get();
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'NIM',
            'Nama Mahasiswa',
            'Nama Grup',
            'Skor Individual',
            'Skor Grup',
            'Skor Akhir',
            'Total Kehadiran',
        ];
    }

    public function map($student): array
    {
        $group = $student->groups->first();
        $individualScore = $this->calculateIndividualScore($student, $group);
        $finalScore = ($individualScore + ($group->total_score ?? 0)) / 2;
        
        $attendanceCount = $student->attendances()
            ->where('project_id', $this->project->id)
            ->where('status', 'present')
            ->count();

        return [
            '-', // Ranking will be calculated after sorting
            $student->politala_id,
            $student->name,
            $group->name ?? '-',
            number_format($individualScore, 2),
            number_format($group->total_score ?? 0, 2),
            number_format($finalScore, 2),
            $attendanceCount,
        ];
    }

    public function title(): string
    {
        return 'Ranking Mahasiswa - ' . $this->project->title;
    }

    private function calculateIndividualScore($student, $group)
    {
        if (!$group) return 0;
        
        $attendanceScore = $student->attendances()
            ->where('project_id', $this->project->id)
            ->where('status', 'present')
            ->count() * 2;

        return min(100, $attendanceScore);
    }
}



