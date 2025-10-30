<?php

namespace App\Http\Controllers;

use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PeriodeAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $academicPeriods = PeriodeAkademik::withCount(['subjects', 'classrooms'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('semester_number', 'desc')
            ->paginate(10);
        
        return view('periode-akademik.daftar', compact('academicPeriods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('periode-akademik.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'academic_year' => 'required|string|max:20',
            'semester_number' => 'required|integer|min:1|max:8',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        // Generate name and code
        $name = "TA {$request->academic_year} - Semester {$request->semester_number}";
        $code = str_replace('/', '-', $request->academic_year) . '-' . $request->semester_number;

        // Check if combination already exists
        $exists = PeriodeAkademik::where('academic_year', $request->academic_year)
            ->where('semester_number', $request->semester_number)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Periode akademik {$name} sudah ada.");
        }

        // Set only one academic period as active if requested
        if ($request->has('is_active')) {
            PeriodeAkademik::where('is_active', true)->update(['is_active' => false]);
        }

        PeriodeAkademik::create([
            'name' => $name,
            'code' => $code,
            'academic_year' => $request->academic_year,
            'semester_number' => $request->semester_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('academic-periods.index')
            ->with('success', "Periode akademik {$name} berhasil dibuat!");
    }

    /**
     * Display the specified resource.
     */
    public function show(PeriodeAkademik $academicPeriod): View
    {
        $academicPeriod->load(['subjects', 'classrooms']);
        
        return view('periode-akademik.tampil', compact('academicPeriod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PeriodeAkademik $academicPeriod): View
    {
        return view('periode-akademik.ubah', compact('academicPeriod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PeriodeAkademik $academicPeriod): RedirectResponse
    {
        $request->validate([
            'academic_year' => 'required|string|max:20',
            'semester_number' => 'required|integer|min:1|max:8',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        // Generate name and code
        $name = "TA {$request->academic_year} - Semester {$request->semester_number}";
        $code = str_replace('/', '-', $request->academic_year) . '-' . $request->semester_number;

        // Check if combination already exists (excluding current record)
        $exists = PeriodeAkademik::where('academic_year', $request->academic_year)
            ->where('semester_number', $request->semester_number)
            ->where('id', '!=', $academicPeriod->id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', "Periode akademik {$name} sudah ada.");
        }

        // Set only one academic period as active if requested
        if ($request->has('is_active')) {
            PeriodeAkademik::where('is_active', true)->update(['is_active' => false]);
        }

        $academicPeriod->update([
            'name' => $name,
            'code' => $code,
            'academic_year' => $request->academic_year,
            'semester_number' => $request->semester_number,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'is_active' => $request->has('is_active'),
            'description' => $request->description,
        ]);

        return redirect()
            ->route('academic-periods.index')
            ->with('success', "Periode akademik {$name} berhasil diupdate!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PeriodeAkademik $academicPeriod): RedirectResponse
    {
        // Check if academic period has subjects or classrooms
        if ($academicPeriod->subjects()->count() > 0 || $academicPeriod->classrooms()->count() > 0) {
            return redirect()
                ->route('academic-periods.index')
                ->with('error', 'Tidak dapat menghapus periode akademik yang memiliki mata kuliah atau kelas terkait.');
        }

        $name = $academicPeriod->name;
        $academicPeriod->delete();

        return redirect()
            ->route('academic-periods.index')
            ->with('success', "Periode akademik {$name} berhasil dihapus!");
    }
}
