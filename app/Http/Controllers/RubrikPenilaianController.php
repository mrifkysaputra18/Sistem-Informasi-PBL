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
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $existingRubriks = $mataKuliah->rubrikPenilaians()->with('items')->get();
        
        return view('rubrik-penilaian.create', compact('mataKuliah', 'periodeAkademiks', 'existingRubriks'));
    }

    public function store(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'semester' => 'required|integer|min:1|max:8',
            'items' => 'required|array|min:1',
            'items.*.nama' => 'required|string|max:100',
            'items.*.persentase' => 'required|numeric|min:0|max:100',
            'items.*.deskripsi' => 'nullable|string',
        ]);

        $totalPersentase = collect($validated['items'])->sum('persentase');
        if ($totalPersentase != 100) {
            return back()->withErrors(['items' => 'Total persentase harus 100%. Saat ini: ' . $totalPersentase . '%'])->withInput();
        }

        DB::transaction(function () use ($validated, $mataKuliah) {
            $rubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $validated['semester'],
                'created_by' => auth()->id(),
                'is_active' => false,
            ]);

            foreach ($validated['items'] as $index => $item) {
                RubrikItem::create([
                    'rubrik_penilaian_id' => $rubrik->id,
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
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $rubrikPenilaian->load('items');
        
        return view('rubrik-penilaian.edit', compact('mataKuliah', 'rubrikPenilaian', 'periodeAkademiks'));
    }

    public function update(Request $request, MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'semester' => 'required|integer|min:1|max:8',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:rubrik_item,id',
            'items.*.nama' => 'required|string|max:100',
            'items.*.persentase' => 'required|numeric|min:0|max:100',
            'items.*.deskripsi' => 'nullable|string',
        ]);

        $totalPersentase = collect($validated['items'])->sum('persentase');
        if ($totalPersentase != 100) {
            return back()->withErrors(['items' => 'Total persentase harus 100%. Saat ini: ' . $totalPersentase . '%'])->withInput();
        }

        DB::transaction(function () use ($validated, $rubrikPenilaian) {
            $rubrikPenilaian->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $validated['semester'],
            ]);

            $existingIds = collect($validated['items'])->pluck('id')->filter()->toArray();
            $rubrikPenilaian->items()->whereNotIn('id', $existingIds)->delete();

            foreach ($validated['items'] as $index => $item) {
                if (!empty($item['id'])) {
                    RubrikItem::where('id', $item['id'])->update([
                        'nama' => $item['nama'],
                        'persentase' => $item['persentase'],
                        'deskripsi' => $item['deskripsi'] ?? null,
                        'urutan' => $index + 1,
                    ]);
                } else {
                    RubrikItem::create([
                        'rubrik_penilaian_id' => $rubrikPenilaian->id,
                        'nama' => $item['nama'],
                        'persentase' => $item['persentase'],
                        'deskripsi' => $item['deskripsi'] ?? null,
                        'urutan' => $index + 1,
                    ]);
                }
            }
        });

        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil diupdate.');
    }

    public function destroy(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
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
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $rubrikPenilaian->load('items');
        
        return view('rubrik-penilaian.duplicate', compact('mataKuliah', 'rubrikPenilaian', 'periodeAkademiks'));
    }

    public function storeDuplicate(Request $request, MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        DB::transaction(function () use ($validated, $mataKuliah, $rubrikPenilaian) {
            $newRubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'nama' => $validated['nama'],
                'deskripsi' => $rubrikPenilaian->deskripsi,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $validated['semester'],
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
