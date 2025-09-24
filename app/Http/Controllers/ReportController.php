<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Group;
use App\Models\User;
use App\Exports\GroupRankingExport;
use App\Exports\StudentRankingExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $projects = collect();
        
        if ($user->isDosen()) {
            $projects = $user->projectsAsSuperviser()->with(['groups'])->get();
        } elseif ($user->isKoordinator()) {
            $projects = Project::where('program_studi', $user->program_studi)
                ->with(['groups', 'supervisor'])->get();
        } elseif ($user->isAdmin()) {
            $projects = Project::with(['groups', 'supervisor'])->get();
        }

        return view('reports.index', compact('projects'));
    }

    public function groupRanking(Project $project)
    {
        $this->authorize('viewReports', $project);
        
        $groups = $project->groups()
            ->with(['members', 'leader', 'weeklyProgress.review'])
            ->orderBy('ranking')
            ->get();

        $stats = [
            'total_groups' => $groups->count(),
            'average_score' => $groups->avg('total_score'),
            'highest_score' => $groups->max('total_score'),
            'lowest_score' => $groups->min('total_score'),
        ];

        return view('reports.group-ranking', compact('project', 'groups', 'stats'));
    }

    public function studentRanking(Project $project)
    {
        $this->authorize('viewReports', $project);
        
        // Get all students from all groups in this project
        $students = User::whereHas('groups.project', function ($query) use ($project) {
            $query->where('id', $project->id);
        })
        ->with(['groups' => function ($query) use ($project) {
            $query->where('project_id', $project->id)->with(['weeklyProgress.review']);
        }])
        ->get()
        ->map(function ($student) use ($project) {
            $group = $student->groups->first();
            $individualScore = $this->calculateIndividualScore($student, $group);
            
            return [
                'student' => $student,
                'group' => $group,
                'individual_score' => $individualScore,
                'group_score' => $group->total_score ?? 0,
                'final_score' => ($individualScore + ($group->total_score ?? 0)) / 2,
            ];
        })
        ->sortByDesc('final_score')
        ->values();

        return view('reports.student-ranking', compact('project', 'students'));
    }

    public function export(Project $project, string $type)
    {
        $this->authorize('viewReports', $project);
        
        $filename = "{$project->title}_{$type}_" . now()->format('Y-m-d');
        
        if ($type === 'groups') {
            return Excel::download(
                new GroupRankingExport($project), 
                $filename . '.xlsx'
            );
        } elseif ($type === 'students') {
            return Excel::download(
                new StudentRankingExport($project), 
                $filename . '.xlsx'
            );
        } elseif ($type === 'pdf') {
            $groups = $project->groups()
                ->with(['members', 'leader', 'weeklyProgress.review'])
                ->orderBy('ranking')
                ->get();

            $pdf = Pdf::loadView('reports.pdf.project-report', compact('project', 'groups'));
            return $pdf->download($filename . '.pdf');
        }

        abort(404);
    }

    private function calculateIndividualScore($student, $group)
    {
        // Calculate individual score based on attendance and participation
        $attendanceScore = $student->attendances()
            ->where('project_id', $group->project_id)
            ->where('status', 'present')
            ->count() * 2; // 2 points per attendance

        // Add other individual metrics as needed
        $participationScore = 0; // Could be calculated from other factors

        return min(100, $attendanceScore + $participationScore);
    }
}