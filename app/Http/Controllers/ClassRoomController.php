<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of class rooms
     */
    public function index()
    {
        $classRooms = ClassRoom::withCount('groups')
            ->orderBy('name')
            ->get();
            
        return view('classrooms.index', compact('classRooms'));
    }

    /**
     * Show groups for a specific class
     */
    public function show(ClassRoom $classRoom)
    {
        $classRoom->load(['groups.members.user', 'groups.leader']);
        
        return view('classrooms.show', compact('classRoom'));
    }

    /**
     * Show form to create new class room
     */
    public function create()
    {
        return view('classrooms.create');
    }

    /**
     * Store a newly created class room
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:class_rooms,code',
            'semester' => 'required|string',
            'program_studi' => 'required|string',
            'max_groups' => 'required|integer|min:1|max:10',
        ]);

        ClassRoom::create($validated);

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil dibuat!');
    }

    /**
     * Show form to edit class room
     */
    public function edit(ClassRoom $classRoom)
    {
        return view('classrooms.edit', compact('classRoom'));
    }

    /**
     * Update class room
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:class_rooms,code,' . $classRoom->id,
            'semester' => 'required|string',
            'program_studi' => 'required|string',
            'max_groups' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        $classRoom->update($validated);

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil diupdate!');
    }

    /**
     * Delete class room
     */
    public function destroy(ClassRoom $classRoom)
    {
        if ($classRoom->groups()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kelas yang masih memiliki kelompok!');
        }

        $classRoom->delete();

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}