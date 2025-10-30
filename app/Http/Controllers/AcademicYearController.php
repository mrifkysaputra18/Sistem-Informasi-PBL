<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $academicYears = AcademicYear::withCount(['semesters', 'classrooms'])
            ->orderBy('start_date', 'desc')
            ->paginate(10);
        
        return view('academic-years.index', compact('academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('academic-years.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_years,code',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        // Set only one academic year as active
        if ($request->has('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        AcademicYear::create($request->all());

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicYear $academicYear): View
    {
        $academicYear->load(['semesters', 'classrooms']);
        
        return view('academic-years.show', compact('academicYear'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicYear $academicYear): View
    {
        return view('academic-years.edit', compact('academicYear'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:academic_years,code,' . $academicYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        // Set only one academic year as active
        if ($request->has('is_active')) {
            AcademicYear::where('is_active', true)->update(['is_active' => false]);
        }

        $academicYear->update($request->all());

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        // Check if academic year has semesters or classrooms
        if ($academicYear->semesters()->count() > 0 || $academicYear->classrooms()->count() > 0) {
            return redirect()
                ->route('academic-years.index')
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang memiliki semester atau kelas terkait.');
        }

        $academicYear->delete();

        return redirect()
            ->route('academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus!');
    }
}
