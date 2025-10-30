<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Http\Requests\{StoreCriterionRequest, UpdateCriterionRequest};
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria = Kriteria::orderBy('segment')->orderBy('id')->paginate(20);
        return view('kriteria.daftar', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kriteria.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCriterionRequest $request)
    {
        Kriteria::create($request->validated());
        return redirect()->route('criteria.index')->with('ok', 'Kriteria dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Criterion $criterion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Criterion $criterion)
    {
        return view('kriteria.ubah', compact('criterion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCriterionRequest $request, Criterion $criterion)
    {
        $criterion->update($request->validated());
        return redirect()->route('criteria.index')->with('ok', 'Kriteria diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Criterion $criterion)
    {
        $criterion->delete();
        return back()->with('ok', 'Kriteria dihapus.');
    }
}
