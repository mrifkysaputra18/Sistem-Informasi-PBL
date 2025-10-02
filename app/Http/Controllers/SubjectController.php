<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Semua mata kuliah sudah terkait PBL dan aktif, tidak perlu filter

        // Search by title or description
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $subjects = $query->with('supervisor')->latest()->paginate(10);

        return view('subjects.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subjects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubjectRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'active';
        
        Project::create($data);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['groups', 'supervisor']);
        
        // Pass as 'subject' to maintain view compatibility
        return view('subjects.show', ['subject' => $project]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        // Pass as 'subject' to maintain view compatibility
        return view('subjects.edit', ['subject' => $project]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSubjectRequest $request, Project $project)
    {
        $data = $request->validated();
        $data['status'] = 'active';
        
        $project->update($data);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Mata kuliah berhasil dihapus!');
    }
}
