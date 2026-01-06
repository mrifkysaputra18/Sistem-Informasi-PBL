<?php

namespace App\Http\Controllers;

use App\Models\DosenPblKelas;
use App\Models\Pengguna;
use App\Models\PeriodeAkademik;
use App\Models\RuangKelas;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola penugasan Dosen PBL per Kelas
 * 
 * UPDATE: 1 Dosen PBL untuk full semester (tidak ada rolling sebelum/sesudah UTS)
 * Dosen Mata Kuliah tetap bisa rolling via KelasMataKuliahController
 */
class PenugasanDosenController extends Controller
{
    /**
     * Tampilkan halaman penugasan Dosen PBL
     */
    public function index(Request $request)
    {
        // Periode akademik aktif
        $periodeAktif = PeriodeAkademik::where('is_active', true)->first();
        $allPeriodes = PeriodeAkademik::orderBy('start_date', 'desc')->get();
        $selectedPeriodeId = $request->get('periode_id', $periodeAktif?->id);
        
        // Data untuk Dosen PBL (grouped by class)
        $dosenPblList = DosenPblKelas::with(['dosen', 'classRoom.academicPeriod'])
            ->when($selectedPeriodeId, function ($q) use ($selectedPeriodeId) {
                return $q->whereHas('classRoom', function ($q2) use ($selectedPeriodeId) {
                    $q2->where('academic_period_id', $selectedPeriodeId);
                });
            })
            ->where('is_active', true)
            ->orderBy('class_room_id')
            ->get()
            ->groupBy('class_room_id');
        
        // Dropdown data
        $dosens = Pengguna::where('role', 'dosen')->where('is_active', true)->orderBy('name')->get();
        $kelasList = RuangKelas::when($selectedPeriodeId, fn($q) => $q->where('academic_period_id', $selectedPeriodeId))
            ->orderBy('name')
            ->get();
        
        return view('penugasan-dosen.index', compact(
            'periodeAktif',
            'allPeriodes',
            'selectedPeriodeId',
            'dosenPblList',
            'dosens',
            'kelasList'
        ));
    }

    /**
     * Simpan penugasan Dosen PBL
     */
    public function storePbl(Request $request)
    {
        $validated = $request->validate([
            'class_room_id' => 'required|exists:ruang_kelas,id',
            'dosen_id' => 'required|exists:pengguna,id',
        ], [
            'class_room_id.required' => 'Pilih kelas terlebih dahulu.',
            'dosen_id.required' => 'Pilih dosen terlebih dahulu.',
        ]);

        $kelas = RuangKelas::find($validated['class_room_id']);
        
        // Cek duplikat (1 dosen hanya bisa sekali per kelas)
        $exists = DosenPblKelas::where('dosen_id', $validated['dosen_id'])
            ->where('class_room_id', $validated['class_room_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Dosen sudah ditugaskan sebagai Dosen PBL di kelas ini.');
        }

        DosenPblKelas::create([
            'dosen_id' => $validated['dosen_id'],
            'class_room_id' => $validated['class_room_id'],
            'is_active' => true,
            'academic_period_id' => $kelas->academic_period_id,
        ]);

        return redirect()->route('penugasan-dosen.index')
            ->with('ok', 'Dosen PBL berhasil ditugaskan untuk full semester.');
    }

    /**
     * Hapus penugasan Dosen PBL
     */
    public function destroyPbl(DosenPblKelas $dosenPblKelas)
    {
        $dosenPblKelas->delete();
        return redirect()->route('penugasan-dosen.index')
            ->with('ok', 'Penugasan Dosen PBL berhasil dihapus.');
    }
}
