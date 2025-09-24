<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Group;
use App\Models\WeeklyProgress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'mahasiswa':
                return $this->mahasiswaDashboard();
            case 'dosen':
                return $this->dosenDashboard();
            case 'admin':
                return $this->adminDashboard();
            case 'koordinator':
                return $this->koordinatorDashboard();
            default:
                abort(403);
        }
    }

    private function mahasiswaDashboard()
    {
        $user = Auth::user();
        $groups = $user->groups()->with(['project', 'weeklyProgress'])->get();
        
        $stats = [
            'total_projects' => $groups->count(),
            'completed_tasks' => WeeklyProgress::whereIn('group_id', $groups->pluck('id'))
                ->where('status', 'submitted')->count(),
            'pending_tasks' => WeeklyProgress::whereIn('group_id', $groups->pluck('id'))
                ->where('status', 'draft')->count(),
        ];

        return view('dashboard.mahasiswa', compact('stats', 'groups'));
    }

    private function dosenDashboard()
    {
        $user = Auth::user();
        $projects = $user->projectsAsSuperviser()->with(['groups'])->get();
        $totalGroups = $projects->sum(function ($project) {
            return $project->groups->count();
        });

        $pendingReviews = WeeklyProgress::whereHas('group.project', function ($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })->where('status', 'submitted')->count();

        $stats = [
            'total_projects' => $projects->count(),
            'total_groups' => $totalGroups,
            'pending_reviews' => $pendingReviews,
        ];

        return view('dashboard.dosen', compact('stats', 'projects'));
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_projects' => Project::count(),
            'total_groups' => Group::count(),
            'active_projects' => Project::where('status', 'active')->count(),
        ];

        return view('dashboard.admin', compact('stats'));
    }

    private function koordinatorDashboard()
    {
        $user = Auth::user();
        $projects = Project::where('program_studi', $user->program_studi)
            ->with(['groups', 'supervisor'])->get();

        $stats = [
            'total_projects' => $projects->count(),
            'active_projects' => $projects->where('status', 'active')->count(),
            'completed_projects' => $projects->where('status', 'completed')->count(),
            'total_groups' => $projects->sum(function ($project) {
                return $project->groups->count();
            }),
        ];

        return view('dashboard.koordinator', compact('stats', 'projects'));
    }
}