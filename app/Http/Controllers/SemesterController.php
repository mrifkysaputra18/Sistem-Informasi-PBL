<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $semesters = Semester::with(['academicYear'])
            ->withCount('classrooms')
            ->orderBy('academic_year_id', 'desc')
            ->orderBy('number', 'asc')
            ->paginate(10);
        
        return view('semesters.index', compact('semesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        
        return view('semesters.create', compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'number' => 'required|integer|min:1|max:8',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:semesters,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        // Check if semester number already exists for this academic year
        $existingSemester = Semester::where('academic_year_id', $request->academic_year_id)
            ->where('number', $request->number)
            ->first();

        if ($existingSemester) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Semester ' . $request->number . ' sudah ada untuk tahun ajaran ini.');
        }

        // Set only one semester as active per academic year
        if ($request->has('is_active')) {
            Semester::where('academic_year_id', $request->academic_year_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        Semester::create($request->all());

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester): View
    {
        $semester->load(['academicYear', 'classrooms']);
        
        return view('semesters.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester): View
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        
        return view('semesters.edit', compact('semester', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester): RedirectResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'number' => 'required|integer|min:1|max:8',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:semesters,code,' . $semester->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        // Check if semester number already exists for this academic year (excluding current semester)
        $existingSemester = Semester::where('academic_year_id', $request->academic_year_id)
            ->where('number', $request->number)
            ->where('id', '!=', $semester->id)
            ->first();

        if ($existingSemester) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Semester ' . $request->number . ' sudah ada untuk tahun ajaran ini.');
        }

        // Set only one semester as active per academic year
        if ($request->has('is_active')) {
            Semester::where('academic_year_id', $request->academic_year_id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }

        $semester->update($request->all());

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Semester $semester): RedirectResponse
    {
        // Check if semester has classrooms
        if ($semester->classrooms()->count() > 0) {
            return redirect()
                ->route('semesters.index')
                ->with('error', 'Tidak dapat menghapus semester yang memiliki kelas terkait.');
        }

        $semester->delete();

        return redirect()
            ->route('semesters.index')
            ->with('success', 'Semester berhasil dihapus!');
    }
}
