<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\PeriodeAkademik;
use App\Models\RubrikPenilaian;
use App\Models\RubrikKategori;
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

    /**
     * Simpan items secara rekursif (termasuk sub-items)
     */
    private function saveItemsRecursive(
        array $items, 
        int $rubrikId, 
        int $kategoriId, 
        ?int $parentId = null, 
        int $level = 0
    ): void {
        foreach ($items as $index => $item) {
            $rubrikItem = RubrikItem::create([
                'rubrik_penilaian_id' => $rubrikId,
                'rubrik_kategori_id' => $kategoriId,
                'parent_id' => $parentId,
                'level' => $level,
                'nama' => $item['nama'],
                'persentase' => $item['persentase'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'urutan' => $index + 1,
            ]);

            // Rekursif simpan sub-items jika ada
            if (!empty($item['sub_items']) && $level < RubrikItem::MAX_LEVEL) {
                $this->saveItemsRecursive(
                    $item['sub_items'], 
                    $rubrikId, 
                    $kategoriId, 
                    $rubrikItem->id, 
                    $level + 1
                );
            }
        }
    }


    public function index(MataKuliah $mataKuliah)
    {
        $rubrikPenilaians = $mataKuliah->rubrikPenilaians()
            ->with(['periodeAkademik', 'creator', 'kategoris.items'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('rubrik-penilaian.index', compact('mataKuliah', 'rubrikPenilaians'));
    }

    public function create(MataKuliah $mataKuliah)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $existingRubriks = $mataKuliah->rubrikPenilaians()->with('kategoris.items')->get();
        
        return view('rubrik-penilaian.create', compact('mataKuliah', 'periodeAkademiks', 'existingRubriks'));
    }

    public function store(Request $request, MataKuliah $mataKuliah)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            // Kategori penilaian
            'kategoris' => 'required|array|min:1',
            'kategoris.*.nama' => 'required|string|max:100',
            'kategoris.*.bobot' => 'required|numeric|min:0|max:100',
            'kategoris.*.deskripsi' => 'nullable|string',
            'kategoris.*.kode' => 'nullable|string|max:20',
            // Items dalam kategori
            'kategoris.*.items' => 'required|array|min:1',
            'kategoris.*.items.*.nama' => 'required|string|max:100',
            'kategoris.*.items.*.persentase' => 'required|numeric|min:0|max:100',
            'kategoris.*.items.*.deskripsi' => 'nullable|string',
            // Sub-items (level 1)
            'kategoris.*.items.*.sub_items' => 'nullable|array',
            'kategoris.*.items.*.sub_items.*.nama' => 'required_with:kategoris.*.items.*.sub_items|string|max:100',
            'kategoris.*.items.*.sub_items.*.persentase' => 'required_with:kategoris.*.items.*.sub_items|numeric|min:0|max:100',
            'kategoris.*.items.*.sub_items.*.deskripsi' => 'nullable|string',
            // Sub-sub-items (level 2)
            'kategoris.*.items.*.sub_items.*.sub_items' => 'nullable|array',
            'kategoris.*.items.*.sub_items.*.sub_items.*.nama' => 'required_with:kategoris.*.items.*.sub_items.*.sub_items|string|max:100',
            'kategoris.*.items.*.sub_items.*.sub_items.*.persentase' => 'required_with:kategoris.*.items.*.sub_items.*.sub_items|numeric|min:0|max:100',
            'kategoris.*.items.*.sub_items.*.sub_items.*.deskripsi' => 'nullable|string',
        ]);

        // Ambil semester dari periode akademik
        $periodeAkademik = PeriodeAkademik::find($validated['periode_akademik_id']);
        $semester = $periodeAkademik->semester_number ?? 1;

        // Validasi total bobot kategori = 100%
        $totalBobot = collect($validated['kategoris'])->sum('bobot');
        if ($totalBobot != 100) {
            return back()->withErrors(['kategoris' => 'Total bobot semua kategori harus 100%. Saat ini: ' . $totalBobot . '%'])->withInput();
        }

        // Validasi total persentase item dalam setiap kategori = 100%
        foreach ($validated['kategoris'] as $index => $kategori) {
            $totalItems = collect($kategori['items'])->sum('persentase');
            if ($totalItems != 100) {
                return back()->withErrors(['kategoris' => "Total persentase item dalam kategori '{$kategori['nama']}' harus 100%. Saat ini: {$totalItems}%"])->withInput();
            }

            // Validasi sub-items (jika ada, total harus 100%)
            foreach ($kategori['items'] as $itemIndex => $item) {
                if (!empty($item['sub_items'])) {
                    $totalSubItems = collect($item['sub_items'])->sum('persentase');
                    if ($totalSubItems != 100) {
                        return back()->withErrors(['kategoris' => "Total persentase sub-item pada item '{$item['nama']}' harus 100%. Saat ini: {$totalSubItems}%"])->withInput();
                    }

                    // Validasi sub-sub-items (jika ada, total harus 100%)
                    foreach ($item['sub_items'] as $subItemIndex => $subItem) {
                        if (!empty($subItem['sub_items'])) {
                            $totalSubSubItems = collect($subItem['sub_items'])->sum('persentase');
                            if ($totalSubSubItems != 100) {
                                return back()->withErrors(['kategoris' => "Total persentase sub-sub-item pada '{$subItem['nama']}' harus 100%. Saat ini: {$totalSubSubItems}%"])->withInput();
                            }
                        }
                    }
                }
            }
        }


        DB::transaction(function () use ($validated, $mataKuliah, $semester) {
            $rubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $semester,
                'created_by' => auth()->id(),
                'is_active' => false,
            ]);

            // Simpan kategori dan items
            foreach ($validated['kategoris'] as $kategoriIndex => $kategoriData) {
                $kategori = RubrikKategori::create([
                    'rubrik_penilaian_id' => $rubrik->id,
                    'nama' => $kategoriData['nama'],
                    'bobot' => $kategoriData['bobot'],
                    'urutan' => $kategoriIndex + 1,
                    'deskripsi' => $kategoriData['deskripsi'] ?? null,
                    'kode' => $kategoriData['kode'] ?? strtolower(str_replace(' ', '_', $kategoriData['nama'])),
                ]);

                // Simpan items untuk kategori ini
                $this->saveItemsRecursive($kategoriData['items'], $rubrik->id, $kategori->id);
            }
        });

        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil dibuat.');
    }


    public function show(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $rubrikPenilaian->load(['kategoris.items.subItems', 'periodeAkademik', 'creator']);
        return view('rubrik-penilaian.show', compact('mataKuliah', 'rubrikPenilaian'));
    }

    public function edit(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $rubrikPenilaian->load(['kategoris.items.subItems']);
        
        return view('rubrik-penilaian.edit', compact('mataKuliah', 'rubrikPenilaian', 'periodeAkademiks'));
    }

    public function update(Request $request, MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'periode_akademik_id' => 'required|exists:periode_akademik,id',
            // Kategori penilaian
            'kategoris' => 'required|array|min:1',
            'kategoris.*.id' => 'nullable|exists:rubrik_kategori,id',
            'kategoris.*.nama' => 'required|string|max:100',
            'kategoris.*.bobot' => 'required|numeric|min:0|max:100',
            'kategoris.*.deskripsi' => 'nullable|string',
            'kategoris.*.kode' => 'nullable|string|max:20',
            // Items dalam kategori
            'kategoris.*.items' => 'required|array|min:1',
            'kategoris.*.items.*.id' => 'nullable|exists:rubrik_item,id',
            'kategoris.*.items.*.nama' => 'required|string|max:100',
            'kategoris.*.items.*.persentase' => 'required|numeric|min:0|max:100',
            'kategoris.*.items.*.deskripsi' => 'nullable|string',
            // Sub-items (level 1)
            'kategoris.*.items.*.sub_items' => 'nullable|array',
            'kategoris.*.items.*.sub_items.*.nama' => 'required_with:kategoris.*.items.*.sub_items|string|max:100',
            'kategoris.*.items.*.sub_items.*.persentase' => 'required_with:kategoris.*.items.*.sub_items|numeric|min:0|max:100',
            'kategoris.*.items.*.sub_items.*.deskripsi' => 'nullable|string',
            // Sub-sub-items (level 2)
            'kategoris.*.items.*.sub_items.*.sub_items' => 'nullable|array',
            'kategoris.*.items.*.sub_items.*.sub_items.*.nama' => 'required_with:kategoris.*.items.*.sub_items.*.sub_items|string|max:100',
            'kategoris.*.items.*.sub_items.*.sub_items.*.persentase' => 'required_with:kategoris.*.items.*.sub_items.*.sub_items|numeric|min:0|max:100',
            'kategoris.*.items.*.sub_items.*.sub_items.*.deskripsi' => 'nullable|string',
        ]);

        // Ambil semester dari periode akademik
        $periodeAkademik = PeriodeAkademik::find($validated['periode_akademik_id']);
        $semester = $periodeAkademik->semester_number ?? 1;

        // Validasi total bobot kategori = 100%
        $totalBobot = collect($validated['kategoris'])->sum('bobot');
        if ($totalBobot != 100) {
            return back()->withErrors(['kategoris' => 'Total bobot semua kategori harus 100%. Saat ini: ' . $totalBobot . '%'])->withInput();
        }

        // Validasi total persentase item dalam setiap kategori = 100%
        foreach ($validated['kategoris'] as $index => $kategori) {
            $totalItems = collect($kategori['items'])->sum('persentase');
            if ($totalItems != 100) {
                return back()->withErrors(['kategoris' => "Total persentase item dalam kategori '{$kategori['nama']}' harus 100%. Saat ini: {$totalItems}%"])->withInput();
            }

            // Validasi sub-items (jika ada, total harus 100%)
            foreach ($kategori['items'] as $itemIndex => $item) {
                if (!empty($item['sub_items'])) {
                    $totalSubItems = collect($item['sub_items'])->sum('persentase');
                    if ($totalSubItems != 100) {
                        return back()->withErrors(['kategoris' => "Total persentase sub-item pada item '{$item['nama']}' harus 100%. Saat ini: {$totalSubItems}%"])->withInput();
                    }

                    // Validasi sub-sub-items (jika ada, total harus 100%)
                    foreach ($item['sub_items'] as $subItemIndex => $subItem) {
                        if (!empty($subItem['sub_items'])) {
                            $totalSubSubItems = collect($subItem['sub_items'])->sum('persentase');
                            if ($totalSubSubItems != 100) {
                                return back()->withErrors(['kategoris' => "Total persentase sub-sub-item pada '{$subItem['nama']}' harus 100%. Saat ini: {$totalSubSubItems}%"])->withInput();
                            }
                        }
                    }
                }
            }
        }


        DB::transaction(function () use ($validated, $rubrikPenilaian, $semester) {
            $rubrikPenilaian->update([
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $semester,
            ]);

            // Hapus semua kategori dan items lama (cascade akan hapus items juga)
            $rubrikPenilaian->kategoris()->delete();

            // Simpan kategori dan items baru
            foreach ($validated['kategoris'] as $kategoriIndex => $kategoriData) {
                $kategori = RubrikKategori::create([
                    'rubrik_penilaian_id' => $rubrikPenilaian->id,
                    'nama' => $kategoriData['nama'],
                    'bobot' => $kategoriData['bobot'],
                    'urutan' => $kategoriIndex + 1,
                    'deskripsi' => $kategoriData['deskripsi'] ?? null,
                    'kode' => $kategoriData['kode'] ?? strtolower(str_replace(' ', '_', $kategoriData['nama'])),
                ]);

                // Simpan items untuk kategori ini
                $this->saveItemsRecursive($kategoriData['items'], $rubrikPenilaian->id, $kategori->id);
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
            return back()->withErrors(['error' => 'Rubrik harus lengkap (total bobot kategori 100% dan setiap kategori 100%) sebelum diaktifkan.']);
        }

        $rubrikPenilaian->activate();
        return back()->with('success', 'Rubrik penilaian berhasil diaktifkan.');
    }

    public function duplicate(MataKuliah $mataKuliah, RubrikPenilaian $rubrikPenilaian)
    {
        $this->checkDosenAccess($mataKuliah);
        
        $periodeAkademiks = PeriodeAkademik::orderBy('academic_year', 'desc')->get();
        $rubrikPenilaian->load(['kategoris.items.subItems']);
        
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
        $periodeAkademik = PeriodeAkademik::find($validated['periode_akademik_id']);
        $semester = $periodeAkademik->semester_number ?? 1;

        DB::transaction(function () use ($validated, $mataKuliah, $rubrikPenilaian, $semester) {
            $newRubrik = RubrikPenilaian::create([
                'mata_kuliah_id' => $mataKuliah->id,
                'nama' => $validated['nama'],
                'deskripsi' => $rubrikPenilaian->deskripsi,
                'periode_akademik_id' => $validated['periode_akademik_id'],
                'semester' => $semester,
                'created_by' => auth()->id(),
                'is_active' => false,
            ]);

            // Duplikasi kategori dan items
            foreach ($rubrikPenilaian->kategoris as $kategori) {
                $newKategori = RubrikKategori::create([
                    'rubrik_penilaian_id' => $newRubrik->id,
                    'nama' => $kategori->nama,
                    'bobot' => $kategori->bobot,
                    'urutan' => $kategori->urutan,
                    'deskripsi' => $kategori->deskripsi,
                    'kode' => $kategori->kode,
                ]);

                // Duplikasi items (root items)
                foreach ($kategori->items as $item) {
                    $this->duplicateItem($item, $newRubrik->id, $newKategori->id, null);
                }
            }
        });

        return redirect()->route('mata-kuliah.show', $mataKuliah)->with('success', 'Rubrik penilaian berhasil diduplikasi.');
    }

    /**
     * Helper untuk duplikasi item secara rekursif
     */
    private function duplicateItem(RubrikItem $item, int $rubrikId, int $kategoriId, ?int $parentId): void
    {
        $newItem = RubrikItem::create([
            'rubrik_penilaian_id' => $rubrikId,
            'rubrik_kategori_id' => $kategoriId,
            'parent_id' => $parentId,
            'level' => $item->level,
            'nama' => $item->nama,
            'persentase' => $item->persentase,
            'deskripsi' => $item->deskripsi,
            'urutan' => $item->urutan,
        ]);

        // Duplikasi sub-items
        foreach ($item->subItems as $subItem) {
            $this->duplicateItem($subItem, $rubrikId, $kategoriId, $newItem->id);
        }
    }
}
