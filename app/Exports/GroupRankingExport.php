<?php

namespace App\Exports;

use App\Models\Proyek;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class GroupRankingExport implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function collection()
    {
        return $this->project->groups()
            ->with(['members', 'leader', 'weeklyProgress.review'])
            ->orderBy('ranking')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Ranking',
            'Nama Grup',
            'Ketua Grup',
            'Jumlah Anggota',
            'Skor Total',
            'Progress Diserahkan',
            'Skor Rata-rata Kecepatan',
            'Skor Rata-rata Kualitas',
            'Skor Rata-rata Ketepatan',
            'Skor Rata-rata Kolaborasi',
        ];
    }

    public function map($group): array
    {
        $reviews = $group->weeklyProgress->pluck('review')->filter();
        
        return [
            $group->ranking ?? '-',
            $group->name,
            $group->leader->name,
            $group->getMembersCount(),
            number_format($group->total_score, 2),
            $group->weeklyProgress->where('status', 'submitted')->count(),
            $reviews->isNotEmpty() ? number_format($reviews->avg('score_progress_speed'), 2) : '-',
            $reviews->isNotEmpty() ? number_format($reviews->avg('score_quality'), 2) : '-',
            $reviews->isNotEmpty() ? number_format($reviews->avg('score_timeliness'), 2) : '-',
            $reviews->isNotEmpty() ? number_format($reviews->avg('score_collaboration'), 2) : '-',
        ];
    }

    public function title(): string
    {
        return 'Ranking Grup - ' . $this->project->title;
    }
}


