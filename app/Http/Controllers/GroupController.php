<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::with('term')->latest()->paginate(10);
        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $terms = \App\Models\AcademicTerm::orderByDesc('id')->get();
        return view('groups.create', compact('terms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Group::create($request->validated());
        return redirect()->route('groups.index')->with('ok', 'Kelompok dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        $terms = \App\Models\AcademicTerm::orderByDesc('id')->get();
        return view('groups.edit', compact('group', 'terms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $group->update($request->validated());
        return redirect()->route('groups.index')->with('ok', 'Kelompok diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        $group->delete();
        return back()->with('ok', 'Kelompok dihapus.');
    }
}
