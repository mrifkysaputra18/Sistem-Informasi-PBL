<?php

namespace App\Http\Controllers;

use App\Models\Group;

class MahasiswaDashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        // Get student's group
        $myGroup = $user->groups()->with(['classRoom', 'leader', 'members.user'])->first();

        return view('dashboards.mahasiswa', compact('myGroup'));
    }
}
