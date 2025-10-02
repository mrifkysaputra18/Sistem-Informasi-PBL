<?php

namespace App\Http\Controllers;

use App\Models\{Group, Criterion, GroupScore};

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Redirect to role-specific dashboard
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'koordinator':
                return redirect()->route('koordinator.dashboard');
            case 'dosen':
                return redirect()->route('dosen.dashboard');
            case 'mahasiswa':
                return redirect()->route('mahasiswa.dashboard');
            default:
                // Fallback to generic dashboard
                return view('dashboard', [
                    'totalKelompok' => Group::count(),
                    'totalKriteria' => Criterion::where('segment', 'group')->count(),
                    'totalInputNilai' => GroupScore::count(),
                ]);
        }
    }
}
