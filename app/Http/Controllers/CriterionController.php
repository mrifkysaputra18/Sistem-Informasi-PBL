<?php

namespace App\Http\Controllers;

use App\Models\Criterion;
use App\Http\Requests\{StoreCriterionRequest, UpdateCriterionRequest};
use Illuminate\Http\Request;

class CriterionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $criteria = Criterion::orderBy('segment')->orderBy('id')->paginate(20);
        return view('criteria.index', compact('criteria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('criteria.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCriterionRequest $request)
    {
        Criterion::create($request->validated());
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
        return view('criteria.edit', compact('criterion'));
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
