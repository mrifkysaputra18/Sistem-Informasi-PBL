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
 * Catatan: Dosen Mata Kuliah dikelola via KelasMataKuliahController
 * (per kelas, per matkul - lebih dinamis)
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
        
        // Data untuk Dosen PBL
        $dosenPblList = DosenPblKelas::with(['dosen', 'classRoom.academicPeriod'])
            ->when($selectedPeriodeId, function ($q) use ($selectedPeriodeId) {
                return $q->whereHas('classRoom', function ($q2) use ($selectedPeriodeId) {
                    $q2->where('academic_period_id', $selectedPeriodeId);
                });
            })
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
            'periode' => 'required|in:sebelum_uts,sesudah_uts',
        ], [
            'class_room_id.required' => 'Pilih kelas terlebih dahulu.',
            'dosen_id.required' => 'Pilih dosen terlebih dahulu.',
        ]);

        $kelas = RuangKelas::find($validated['class_room_id']);
        
        // Cek duplikat
        $exists = DosenPblKelas::where('dosen_id', $validated['dosen_id'])
            ->where('class_room_id', $validated['class_room_id'])
            ->where('periode', $validated['periode'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Dosen sudah ditugaskan untuk periode ini.');
        }

        DosenPblKelas::create([
            'dosen_id' => $validated['dosen_id'],
            'class_room_id' => $validated['class_room_id'],
            'periode' => $validated['periode'],
            'is_active' => true,
            'academic_period_id' => $kelas->academic_period_id,
        ]);

        return redirect()->route('penugasan-dosen.index')
            ->with('ok', 'Dosen PBL berhasil ditugaskan.');
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
