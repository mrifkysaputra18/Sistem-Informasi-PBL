<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\PeriodeAkademik;
use App\Models\RubrikPenilaian;
use App\Models\RubrikItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RubrikPenilaianController extends Controller
{
    /**
     * Cek akses dosen ke mata kuliah
     * Admin boleh semua, Dosen harus ditugaskan
     */
    private function checkDosenAccess(MataKuliah $mataKuliah): void
    {
        $user = auth()->user();
        if ($user->isDosen() && !$mataKuliah->isDosenAssigned($user->id)) {
            abort(403, 'Anda tidak ditugaskan sebagai dosen untuk mata kuliah ini.');
        }
    }

    public function index(MataKuliah $mataKuliah)
    {
        $rubrikPenilaians = $mataKuliah->rubrikPenilaians()
            ->with(['periodeAkademik', 'creator', 'items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('rubrik-penilaian.index', compact('mataKuliah', 'rubrikPenilaians'));
    }

    public function create(MataKuliah $mataKuliah)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $existingRubriks = $mataKuliah->rubrikPenilaians()->with('items')->get();
        
        return view('rubrik-penilaian.create', compact('mataKuliah', 'periodeAkademiks', 'existingRubriks'));
    }

    public function store(Request $request, MataKuliah $mataKuliah)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'bobot_uts' => 'required|numeric|min:0|max:100',
            'bobot_uas' => 'required|numeric|min:0|max:100',
            'items_uts' => 'required|array|min:1',
            'items_uts.*.nama' => 'required|string|max:100',
            'items_uts.*.persentase' => 'required|numeric|min:0|max:100',
            'items_uts.*.deskripsi' => 'nullable|string',
            'items_uas' => 'required|array|min:1',
            'items_uas.*.nama' => 'required|string|max:100',
            'items_uas.*.persentase' => 'required|numeric|min:0|max:100',
            'items_uas.*.deskripsi' => 'nullable|string',
        ]);

        // Ambil semester dari periode akademik
        $periodeAkademik = \App\Models\PeriodeAkademik::find($validated['periode_akademik_id']);
        $semester = $periodeAkademik->semester_number ?? 1;

        // Validasi bobot UTS + UAS = 100%
        if (($validated['bobot_uts'] + $validated['bobot_uas']) != 100) {
            return back()->withErrors(['bobot' => 'Total bobot UTS + UAS harus 100%. Saat ini: ' . ($validated['bobot_uts'] + $validated['bobot_uas']) . '%'])->withInput();
        }

        // Validasi total persentase item UTS = 100%
        $totalUts = collect($validated['items_uts'])->sum('persentase');
        if ($totalUts != 100) {
            return back()->withErrors(['items_uts' => 'Total persentase item UTS harus 100%. Saat ini: ' . $totalUts . '%'])->withInput();
        }

        // Validasi total persentase item UAS = 100%
        $totalUas = collect($validated['items_uas'])->sum('persentase');
        if ($totalUas != 100) {
            return back()->withErrors(['items_uas' => 'Total persentase item UAS harus 100%. Saat ini: ' . $totalUas . '%'])->withInput();
        }

        DB::transaction(function () use ($validated, $mataKuliah, $semester) {
            $rubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $semester,
                'bobot_uts' => $validated['bobot_uts'],
                'bobot_uas' => $validated['bobot_uas'],
                'created_by' => auth()->id(),
                'is_active' => false,
            ]);

            // Simpan item UTS
            foreach ($validated['items_uts'] as $index => $item) {
                RubrikItem::create([
                    'rubrik_penilaian_id' => $rubrik->id,
                    'periode_ujian' => 'uts',
                    'nama' => $item['nama'],
                    'persentase' => $item['persentase'],
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'urutan' => $index + 1,
                ]);
            }

            // Simpan item UAS
            foreach ($validated['items_uas'] as $index => $item) {
                RubrikItem::create([
                    'rubrik_penilaian_id' => $rubrik->id,
                    'periode_ujian' => 'uas',
                    'nama' => $item['nama'],
                    'persentase' => $item['persentase'],
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'urutan' => $index + 1,
                ]);
            }
        });

        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil dibuat.');
    }

    public function show(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $rubrikPenilaian->load(['items', 'periodeAkademik', 'creator']);
        return view('rubrik-penilaian.show', compact('mataKuliah', 'rubrikPenilaian'));
    }

    public function edit(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $rubrikPenilaian->load('items');
        
        return view('rubrik-penilaian.edit', compact('mataKuliah', 'rubrikPenilaian', 'periodeAkademiks'));
    }

    public function update(Request $request, MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'bobot_uts' => 'required|numeric|min:0|max:100',
            'bobot_uas' => 'required|numeric|min:0|max:100',
            'items_uts' => 'required|array|min:1',
            'items_uts.*.id' => 'nullable|exists:rubrik_item,id',
            'items_uts.*.nama' => 'required|string|max:100',
            'items_uts.*.persentase' => 'required|numeric|min:0|max:100',
            'items_uts.*.deskripsi' => 'nullable|string',
            'items_uas' => 'required|array|min:1',
            'items_uas.*.id' => 'nullable|exists:rubrik_item,id',
            'items_uas.*.nama' => 'required|string|max:100',
            'items_uas.*.persentase' => 'required|numeric|min:0|max:100',
            'items_uas.*.deskripsi' => 'nullable|string',
        ]);

        // Ambil semester dari periode akademik
        $periodeAkademik = \App\Models\PeriodeAkademik::find($validated['periode_akademik_id']);
        $semester = $periodeAkademik->semester_number ?? 1;

        // Validasi bobot UTS + UAS = 100%
        if (($validated['bobot_uts'] + $validated['bobot_uas']) != 100) {
            return back()->withErrors(['bobot' => 'Total bobot UTS + UAS harus 100%. Saat ini: ' . ($validated['bobot_uts'] + $validated['bobot_uas']) . '%'])->withInput();
        }

        // Validasi total persentase item UTS = 100%
        $totalUts = collect($validated['items_uts'])->sum('persentase');
        if ($totalUts != 100) {
            return back()->withErrors(['items_uts' => 'Total persentase item UTS harus 100%. Saat ini: ' . $totalUts . '%'])->withInput();
        }

        // Validasi total persentase item UAS = 100%
        $totalUas = collect($validated['items_uas'])->sum('persentase');
        if ($totalUas != 100) {
            return back()->withErrors(['items_uas' => 'Total persentase item UAS harus 100%. Saat ini: ' . $totalUas . '%'])->withInput();
        }

        DB::transaction(function () use ($validated, $rubrikPenilaian, $semester) {
            $rubrikPenilaian->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $semester,
                'bobot_uts' => $validated['bobot_uts'],
                'bobot_uas' => $validated['bobot_uas'],
            ]);

            // Hapus semua item lama
            $rubrikPenilaian->items()->delete();

            // Simpan item UTS baru
            foreach ($validated['items_uts'] as $index => $item) {
                RubrikItem::create([
                    'rubrik_penilaian_id' => $rubrikPenilaian->id,
                    'periode_ujian' => 'uts',
                    'nama' => $item['nama'],
                    'persentase' => $item['persentase'],
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'urutan' => $index + 1,
                ]);
            }

            // Simpan item UAS baru
            foreach ($validated['items_uas'] as $index => $item) {
                RubrikItem::create([
                    'rubrik_penilaian_id' => $rubrikPenilaian->id,
                    'periode_ujian' => 'uas',
                    'nama' => $item['nama'],
                    'persentase' => $item['persentase'],
                    'deskripsi' => $item['deskripsi'] ?? null,
                    'urutan' => $index + 1,
                ]);
            }
        });

        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil diupdate.');
    }

    public function destroy(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $rubrikPenilaian->delete();
        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil dihapus.');
    }

    public function activate(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        if (!$rubrikPenilaian->isComplete()) {
            return back()->withErrors(['error' => 'Rubrik harus memiliki total persentase 100% sebelum diaktifkan.']);
        }

        $rubrikPenilaian->activate();
        return back()->with('success', 'Rubrik penilaian berhasil diaktifkan.');
    }

    public function duplicate(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $rubrikPenilaian->load('items');
        
        return view('rubrik-penilaian.duplicate', compact('mataKuliah', 'rubrikPenilaian', 'periodeAkademiks'));
    }

    public function storeDuplicate(Request $request, MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
        ]);

        // Ambil semester dari periode akademik
        $periodeAkademik = \App\Models\PeriodeAkademik::find($validated['periode_akademik_id']);
        $semester = $periodeAkademik->semester_number ?? 1;

        DB::transaction(function () use ($validated, $mataKuliah, $rubrikPenilaian, $semester) {
            $newRubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'nama' => $validated['nama'],
                'deskripsi' => $rubrikPenilaian->deskripsi,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $semester,
                'bobot_uts' => $rubrikPenilaian->bobot_uts,
                'bobot_uas' => $rubrikPenilaian->bobot_uas,
                'created_by' => auth()->id(),
                'is_active' => false,
            ]);

            foreach ($rubrikPenilaian->items as $item) {
                RubrikItem::create([
                    'rubrik_penilaian_id' => $newRubrik->id,
                    'nama' => $item->nama,
                    'persentase' => $item->persentase,
                    'deskripsi' => $item->deskripsi,
                    'urutan' => $item->urutan,
                ]);
            }
        });

        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil diduplikasi.');
    }
}
