<?php

namespace App\Http\Controllers;

use App\Models\{Pengguna, RuangKelas, Kelompok, Kriteria, NilaiKelompok};

class DasborAdminController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'totalUsers' => Pengguna::count(),
            'totalMahasiswa' => Pengguna::where('role', 'mahasiswa')->count(),
            'totalDosen' => Pengguna::where('role', 'dosen')->count(),
            'totalKoordinator' => Pengguna::where('role', 'koordinator')->count(),
            'totalClassRooms' => RuangKelas::count(),
            'totalGroups' => Kelompok::count(),
            'totalCriteria' => Kriteria::count(),
            'totalScores' => NilaiKelompok::count(),
        ];

        // Recent activities
        $recentGroups = Kelompok::with(['classRoom', 'leader'])->latest()->take(5)->get();
        $recentUsers = Pengguna::latest()->take(5)->get();

        return view('dasbor.admin', compact('stats', 'recentGroups', 'recentUsers'));
    }
}
