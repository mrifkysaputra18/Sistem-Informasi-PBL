<?php
// Controller Kriteria - Menangani CRUD kriteria penilaian

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Http\Requests\{StoreCriterionRequest, UpdateCriterionRequest};
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    // Tampilkan daftar kriteria (GET /criteria)
    public function index()
    {
        $criteria = Kriteria::orderBy('segment')->orderBy('id')->paginate(20);
        return view('kriteria.daftar', compact('criteria'));
    }

    // Tampilkan form tambah (GET /criteria/create)
    public function create()
    {
        return view('kriteria.tambah');
    }

    // Simpan kriteria baru (POST /criteria)
    public function store(StoreCriterionRequest $request)
    {
        Kriteria::create($request->validated()); // Buat data baru
        return redirect()->route('criteria.index')->with('ok', 'Kriteria dibuat.');
    }

    // Tampilkan detail (tidak digunakan)
    public function show(Kriteria $criterion)
    {
    }

    // Tampilkan form edit (GET /criteria/{id}/edit)
    public function edit(Kriteria $criterion)
    {
        return view('kriteria.ubah', compact('criterion'));
    }

    // Perbarui kriteria (PATCH /criteria/{id})
    public function update(UpdateCriterionRequest $request, Kriteria $criterion)
    {
        $criterion->update($request->validated()); // Perbarui data
        return redirect()->route('criteria.index')->with('ok', 'Kriteria diupdate.');
    }

    // Hapus kriteria (DELETE /criteria/{id})
    public function destroy(Kriteria $criterion)
    {
        $criterion->delete(); // Hapus data
        return back()->with('ok', 'Kriteria dihapus.');
    }
}
