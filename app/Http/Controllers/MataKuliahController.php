<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isDosen()) {
            $mataKuliahs = $user->mataKuliahs()->with('rubrikPenilaians')->paginate(20);
        } else {
            $mataKuliahs = MataKuliah::with(['dosens', 'rubrikPenilaians'])->paginate(20);
        }
        
        return view('mata-kuliah.index', compact('mataKuliahs'));
    }

    public function create()
    {
        return view('mata-kuliah.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode',
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'sks' => 'required|integer|min:1|max:6',
        ], [
            'kode.unique' => 'Kode mata kuliah sudah digunakan.',
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
        ]);

        MataKuliah::create([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'sks' => $validated['sks'],
        ]);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(MataKuliah $mataKuliah)
    {
        $mataKuliah->load(['rubrikPenilaians.items', 'rubrikPenilaians.periodeAkademik', 'rubrikPenilaians.creator']);
        return view('mata-kuliah.show', compact('mataKuliah'));
    }

    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata-kuliah.edit', compact('mataKuliah'));
    }

    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:20|unique:mata_kuliah,kode,' . $mataKuliah->id,
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'sks' => 'required|integer|min:1|max:6',
            'is_active' => 'boolean',
        ], [
            'kode.unique' => 'Kode mata kuliah sudah digunakan.',
            'kode.required' => 'Kode mata kuliah wajib diisi.',
            'nama.required' => 'Nama mata kuliah wajib diisi.',
            'sks.required' => 'SKS wajib diisi.',
        ]);

        $mataKuliah->update([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'sks' => $validated['sks'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil diupdate.');
    }

    public function destroy(MataKuliah $mataKuliah)
    {
        $mataKuliah->delete();
        return redirect()->route('mata-kuliah.index')->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
