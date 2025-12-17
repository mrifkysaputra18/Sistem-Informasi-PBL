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
     */
    public function index(RuangKelas $classRoom)
    {
        // Ambil dosen PBL per periode
        $dosenSebelumUts = $classRoom->dosenPblSebelumUts()->get();
        $dosenSesudahUts = $classRoom->dosenPblSesudahUts()->get();
        
        // Ambil daftar dosen yang bisa di-assign (belum jadi dosen PBL di kelas ini)
        $existingDosenIds = $classRoom->dosenPbls()->pluck('pengguna.id')->toArray();
        $availableDosens = Pengguna::where('role', 'dosen')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        return view('ruang-kelas.dosen-pbl', compact(
            'classRoom',
            'dosenSebelumUts',
            'dosenSesudahUts',
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
            'periode' => 'required|in:sebelum_uts,sesudah_uts',
        ], [
            'dosen_id.required' => 'Pilih dosen terlebih dahulu.',
            'periode.required' => 'Pilih periode terlebih dahulu.',
        ]);

        // Cek apakah dosen sudah di-assign untuk periode ini
        $exists = DosenPblKelas::where('dosen_id', $validated['dosen_id'])
            ->where('class_room_id', $classRoom->id)
            ->where('periode', $validated['periode'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Dosen sudah menjadi Dosen PBL di periode ini.');
        }

        DosenPblKelas::create([
            'dosen_id' => $validated['dosen_id'],
            'class_room_id' => $classRoom->id,
            'periode' => $validated['periode'],
            'is_active' => true,
        ]);

        $dosen = Pengguna::find($validated['dosen_id']);
        $periodeLabel = $validated['periode'] === 'sebelum_uts' ? 'Sebelum UTS' : 'Sesudah UTS';
        
        return redirect()->back()->with('ok', "{$dosen->name} berhasil ditambahkan sebagai Dosen PBL ({$periodeLabel}).");
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
