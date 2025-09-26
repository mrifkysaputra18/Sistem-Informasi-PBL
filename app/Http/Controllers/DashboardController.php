<?php

namespace App\Http\Controllers;

use App\Models\{Group, Criterion, GroupScore};

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('dashboard', [
            'totalKelompok' => Group::count(),
            'totalKriteria' => Criterion::where('segment', 'group')->count(),
            'totalInputNilai' => GroupScore::count(),
        ]);
    }
}
