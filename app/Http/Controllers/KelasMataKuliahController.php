<?php

namespace App\Http\Controllers;

use App\Models\{KelasMataKuliah, MataKuliah, Pengguna, RuangKelas, RubrikPenilaian};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Controller untuk mengelola relasi Kelas - Mata Kuliah.
 * Hanya Admin yang dapat mengakses fitur ini.
 */
class KelasMataKuliahController extends Controller
{
    /**
     * Tampilkan halaman penugasan dosen mata kuliah untuk semua kelas
     */
    public function indexAll(Request $request)
    {
        $classRooms = RuangKelas::with(['kelasMataKuliahs.mataKuliah', 'kelasMataKuliahs.dosenSebelumUts', 'kelasMataKuliahs.dosenSesudahUts', 'kelasMataKuliahs.rubrikPenilaian.items'])
            ->orderBy('name')
            ->get();
        
        $dosens = Pengguna::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Jika ada kelas yang dipilih
        $selectedClassRoom = null;
        $kelasMataKuliahs = collect();
        $availableMataKuliahs = collect();
        
        if ($request->has('kelas')) {
            $selectedClassRoom = RuangKelas::with(['kelasMataKuliahs.mataKuliah', 'kelasMataKuliahs.dosenSebelumUts', 'kelasMataKuliahs.dosenSesudahUts', 'kelasMataKuliahs.rubrikPenilaian.items'])
                ->find($request->kelas);
            
            if ($selectedClassRoom) {
                $kelasMataKuliahs = $selectedClassRoom->kelasMataKuliahs;
                $availableMataKuliahs = MataKuliah::active()
                    ->whereNotIn('id', $kelasMataKuliahs->pluck('mata_kuliah_id'))
                    ->orderBy('nama')
                    ->get();
            }
        }
        
        return view('penugasan-dosen-matkul.index', compact('classRooms', 'dosens', 'selectedClassRoom', 'kelasMataKuliahs', 'availableMataKuliahs'));
    }

    /**
     * Tampilkan daftar mata kuliah terkait untuk kelas tertentu
     */
    public function index(RuangKelas $classRoom)
    {
        $kelasMataKuliahs = $classRoom->kelasMataKuliahs()
            ->with(['mataKuliah', 'rubrikPenilaian.items', 'dosenSebelumUts', 'dosenSesudahUts'])
            ->get();

        $availableMataKuliahs = MataKuliah::active()
            ->whereNotIn('id', $kelasMataKuliahs->pluck('mata_kuliah_id'))
            ->orderBy('nama')
            ->get();

        // Daftar dosen untuk dropdown
        $dosens = Pengguna::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('ruang-kelas.mata-kuliah', compact('classRoom', 'kelasMataKuliahs', 'availableMataKuliahs', 'dosens'));
    }

    /**
     * Tambahkan mata kuliah ke kelas dengan dosen
     */
    public function store(Request $request, RuangKelas $classRoom)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'dosen_sebelum_uts_id' => 'nullable|exists:pengguna,id',
            'dosen_sesudah_uts_id' => 'nullable|exists:pengguna,id',
        ], [
            'mata_kuliah_id.required' => 'Mata kuliah harus dipilih',
            'mata_kuliah_id.exists' => 'Mata kuliah tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cek apakah sudah ada relasi
        $exists = KelasMataKuliah::where('class_room_id', $classRoom->id)
            ->where('mata_kuliah_id', $request->mata_kuliah_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Mata kuliah ini sudah ditambahkan ke kelas.');
        }

        KelasMataKuliah::create([
            'class_room_id' => $classRoom->id,
            'mata_kuliah_id' => $request->mata_kuliah_id,
            'dosen_sebelum_uts_id' => $request->dosen_sebelum_uts_id,
            'dosen_sesudah_uts_id' => $request->dosen_sesudah_uts_id,
        ]);

        $redirectTo = $request->input('redirect_to', route('classrooms.mata-kuliah.index', $classRoom));
        return redirect($redirectTo)->with('ok', 'Mata kuliah berhasil ditambahkan ke kelas.');
    }

    /**
     * Update dosen untuk mata kuliah dalam kelas
     */
    public function updateDosen(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $validator = Validator::make($request->all(), [
            'dosen_sebelum_uts_id' => 'nullable|exists:pengguna,id',
            'dosen_sesudah_uts_id' => 'nullable|exists:pengguna,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kelasMataKuliah->update([
            'dosen_sebelum_uts_id' => $request->dosen_sebelum_uts_id,
            'dosen_sesudah_uts_id' => $request->dosen_sesudah_uts_id,
        ]);

        if ($request->has('redirect_to')) {
            return redirect($request->redirect_to)->with('ok', 'Dosen berhasil diupdate.');
        }
        return redirect()->back()->with('ok', 'Dosen berhasil diupdate.');
    }

    /**
     * Pilih rubrik untuk mata kuliah dalam kelas
     */
    public function selectRubrik(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $validator = Validator::make($request->all(), [
            'rubrik_penilaian_id' => 'required|exists:rubrik_penilaian,id',
        ], [
            'rubrik_penilaian_id.required' => 'Rubrik penilaian harus dipilih',
            'rubrik_penilaian_id.exists' => 'Rubrik penilaian tidak ditemukan',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verifikasi rubrik milik mata kuliah yang benar
        $rubrik = RubrikPenilaian::where('id', $request->rubrik_penilaian_id)
            ->where('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id)
            ->first();

        if (!$rubrik) {
            return redirect()->back()
                ->with('error', 'Rubrik tidak valid untuk mata kuliah ini.');
        }

        // Cek apakah rubrik lengkap
        if (!$rubrik->isComplete()) {
            return redirect()->back()
                ->with('error', 'Rubrik belum lengkap. Total persentase harus 100%.');
        }

        $kelasMataKuliah->update([
            'rubrik_penilaian_id' => $request->rubrik_penilaian_id,
        ]);

        return redirect()->back()
            ->with('ok', 'Rubrik berhasil dipilih untuk mata kuliah ini.');
    }

    /**
     * Hapus mata kuliah dari kelas
     */
    public function destroy(Request $request, KelasMataKuliah $kelasMataKuliah)
    {
        $classRoom = $kelasMataKuliah->classRoom;
        $mataKuliahNama = $kelasMataKuliah->mataKuliah->nama;

        $kelasMataKuliah->delete();

        $redirectTo = $request->input('redirect_to', route('classrooms.mata-kuliah.index', $classRoom));
        return redirect($redirectTo)->with('ok', "Mata kuliah {$mataKuliahNama} berhasil dihapus dari kelas.");
    }

    /**
     * Get daftar rubrik untuk mata kuliah tertentu (AJAX)
     */
    public function getRubriks(KelasMataKuliah $kelasMataKuliah)
    {
        $rubriks = RubrikPenilaian::where('mata_kuliah_id', $kelasMataKuliah->mata_kuliah_id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($rubrik) {
                return [
                    'id' => $rubrik->id,
                    'nama' => $rubrik->nama,
                    'semester' => $rubrik->semester,
                    'periode' => $rubrik->periodeAkademik?->name ?? '-',
                    'total_persentase' => $rubrik->total_persentase,
                    'is_complete' => $rubrik->isComplete(),
                    'items' => $rubrik->items->map(fn($item) => [
                        'nama' => $item->nama,
                        'persentase' => $item->persentase,
                    ]),
                ];
            });

        return response()->json(['rubriks' => $rubriks]);
    }
}
