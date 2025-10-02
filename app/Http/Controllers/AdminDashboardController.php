<?php

namespace App\Http\Controllers;

use App\Models\{User, Subject, ClassRoom, Group, Criterion, GroupScore};

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalMahasiswa' => User::where('role', 'mahasiswa')->count(),
            'totalDosen' => User::where('role', 'dosen')->count(),
            'totalKoordinator' => User::where('role', 'koordinator')->count(),
            'totalSubjects' => Subject::count(),
            'totalClassRooms' => ClassRoom::count(),
            'totalGroups' => Group::count(),
            'totalCriteria' => Criterion::count(),
            'totalScores' => GroupScore::count(),
        ];

        // Recent activities
        $recentGroups = Group::with(['classRoom', 'leader'])->latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();

        return view('dashboards.admin', compact('stats', 'recentGroups', 'recentUsers'));
    }
}
