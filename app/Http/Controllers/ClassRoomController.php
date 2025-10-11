<?php

namespace App\Http\Controllers;

use App\Models\{ClassRoom, Subject, AcademicYear, Semester};
use Illuminate\Http\Request;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of class rooms
     */
    public function index(Request $request)
    {
        $query = ClassRoom::withCount('groups');

        // Filter by subject
        if ($request->has('subject_id') && $request->subject_id != '') {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }


        // Search by name or code
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        $statsCollection = (clone $query)->orderBy('name')->get();

        $classRooms = $query->orderBy('name')->paginate(10);

        // Get filter options
        $subjects = Subject::orderBy('name')->get() ?? collect(); // Semua mata kuliah aktif
        $semesters = ClassRoom::distinct()->pluck('semester')->sort();

        // Calculate lightweight statistics for UX cues
        $totalClasses = $statsCollection->count();
        $totalActiveClasses = $statsCollection->where('is_active', true)->count();
        $totalGroups = $statsCollection->sum('groups_count');
        $totalMaxGroups = $statsCollection->sum('max_groups');
        $averageFill = $totalMaxGroups > 0 
            ? round(($totalGroups / $totalMaxGroups) * 100)
            : 0;
        $stats = [
            'total_classes' => $totalClasses,
            'active_classes' => $totalActiveClasses,
            'total_groups' => $totalGroups,
            'average_fill' => $averageFill,
        ];
            
        return view('classrooms.index', compact('classRooms', 'subjects', 'semesters', 'stats'));
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
        // Only admin can create class rooms
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Hanya admin yang dapat membuat kelas.');
        }

        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $semesters = Semester::with('academicYear')->orderBy('academic_year_id', 'desc')->orderBy('number', 'asc')->get();
        
        return view('classrooms.create', compact('subjects', 'academicYears', 'semesters'));
    }

    /**
     * Store a newly created class room
     */
    public function store(Request $request)
    {
        // Only admin can store class rooms
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Hanya admin yang dapat membuat kelas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:class_rooms,code',
            'subject_id' => 'nullable|exists:subjects,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'semester' => 'required|string', // Keep for backward compatibility
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
        // Only admin can edit class rooms
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Hanya admin yang dapat mengedit kelas.');
        }

        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $semesters = Semester::with('academicYear')->orderBy('academic_year_id', 'desc')->orderBy('number', 'asc')->get();
        
        return view('classrooms.edit', compact('classRoom', 'subjects', 'academicYears', 'semesters'));
    }

    /**
     * Update class room
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        // Only admin can update class rooms
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Hanya admin yang dapat mengupdate kelas.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'code' => 'required|string|max:20|unique:class_rooms,code,' . $classRoom->id,
            'subject_id' => 'nullable|exists:subjects,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'semester' => 'required|string', // Keep for backward compatibility
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
        // Only admin can delete class rooms
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Hanya admin yang dapat menghapus kelas.');
        }

        if ($classRoom->groups()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus kelas yang masih memiliki kelompok!');
        }

        $classRoom->delete();

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil dihapus!');
    }
}
