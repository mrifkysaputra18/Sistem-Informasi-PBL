<?php

namespace App\Http\Controllers;

use App\Models\DosenPblKelas;
use App\Models\Pengguna;
use App\Models\RuangKelas;
use Illuminate\Http\Request;

class DosenPblController extends Controller
{
    /**
     * Tampilkan daftar Dosen PBL untuk kelas tertentu
     * UPDATE: Dosen PBL sekarang untuk full semester (tidak ada split sebelum/sesudah UTS)
     */
    public function index(RuangKelas $classRoom)
    {
        // Ambil dosen PBL untuk kelas ini
        $dosenPbls = $classRoom->dosenPblKelas()->with('dosen')->get();
        
        // Ambil daftar dosen yang bisa di-assign
        $existingDosenIds = $dosenPbls->pluck('dosen_id')->toArray();
        $availableDosens = Pengguna::where('role', 'dosen')
            ->where('is_active', true)
            ->whereNotIn('id', $existingDosenIds)
            ->orderBy('name')
            ->get();
        
        return view('ruang-kelas.dosen-pbl', compact(
            'classRoom',
            'dosenPbls',
            'availableDosens'
        ));
    }

    /**
     * Tambah Dosen PBL ke kelas
     */
    public function store(Request $request, RuangKelas $classRoom)
    {
        $validated = $request->validate([
            'dosen_id' => 'required|exists:pengguna,id',
        ], [
            'dosen_id.required' => 'Pilih dosen terlebih dahulu.',
        ]);

        // Cek apakah dosen sudah di-assign
        $exists = DosenPblKelas::where('dosen_id', $validated['dosen_id'])
            ->where('class_room_id', $classRoom->id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Dosen sudah menjadi Dosen PBL di kelas ini.');
        }

        DosenPblKelas::create([
            'dosen_id' => $validated['dosen_id'],
            'class_room_id' => $classRoom->id,
            'is_active' => true,
            'academic_period_id' => $classRoom->academic_period_id,
        ]);

        $dosen = Pengguna::find($validated['dosen_id']);
        
        return redirect()->back()->with('ok', "{$dosen->name} berhasil ditambahkan sebagai Dosen PBL (Full Semester).");
    }

    /**
     * Hapus assignment Dosen PBL
     */
    public function destroy(DosenPblKelas $dosenPblKelas)
    {
        $dosenName = $dosenPblKelas->dosen->name;
        $classRoomId = $dosenPblKelas->class_room_id;
        
        $dosenPblKelas->delete();
        
        return redirect()->route('classrooms.dosen-pbl.index', $classRoomId)
            ->with('ok', "{$dosenName} berhasil dihapus dari Dosen PBL.");
    }

    /**
     * Toggle status aktif Dosen PBL
     */
    public function toggleStatus(DosenPblKelas $dosenPblKelas)
    {
        $dosenPblKelas->update([
            'is_active' => !$dosenPblKelas->is_active,
        ]);

        $status = $dosenPblKelas->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()->with('ok', "{$dosenPblKelas->dosen->name} berhasil {$status}.");
    }
}
