<?php

namespace App\Http\Controllers;

use App\Models\{RuangKelas, MataKuliah, AcademicYear, Semester, PeriodeAkademik};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RuangKelasController extends Controller
{
    /**
     * Display a listing of class rooms
     */
    public function index(Request $request)
    {
        $query = RuangKelas::withCount('students');

        // Filter by subject
        if ($request->has('subject_id') && $request->subject_id != '') {
            $query->where('subject_id', $request->subject_id);
        }

        // Filter by semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active === '1');
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
        $subjects = MataKuliah::orderBy('name')->get() ?? collect(); // Semua mata kuliah aktif
        $semesters = RuangKelas::distinct()->pluck('semester')->sort();

        // Calculate statistics for students, not groups
        $totalClasses = $statsCollection->count();
        $totalActiveClasses = $statsCollection->where('is_active', true)->count();
        $totalStudents = $statsCollection->sum('students_count');
        $averageStudents = $totalClasses > 0 
            ? round($totalStudents / $totalClasses)
            : 0;
        $stats = [
            'total_classes' => $totalClasses,
            'active_classes' => $totalActiveClasses,
            'total_students' => $totalStudents,
            'average_students' => $averageStudents,
        ];
            
        return view('ruang-kelas.daftar', compact('classRooms', 'subjects', 'semesters', 'stats'));
    }

    /**
     * Show students in a specific class
     */
    public function show(ClassRoom $classRoom)
    {
        // Load students yang terdaftar di kelas ini
        $students = $classRoom->students()
            ->orderBy('name')
            ->get();
        
        return view('ruang-kelas.tampil', compact('classRoom', 'students'));
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

        $subjects = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $semesters = Semester::with('academicYear')->orderBy('academic_year_id', 'desc')->orderBy('number', 'asc')->get();
        
        return view('ruang-kelas.tambah', compact('subjects', 'academicYears', 'semesters'));
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
            'code' => 'required|string|max:20|unique:ruang_kelas,code',
            'subject_id' => 'nullable|exists:mata_kuliah,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'semester' => 'required|string', // Keep for backward compatibility
            'program_studi' => 'required|string',
            'max_groups' => 'required|integer|min:1|max:10',
        ]);

        // Auto-sync academic_period_id based on semester
        $academicPeriod = PeriodeAkademik::where('semester_number', $validated['semester'])
            ->where('is_active', true)
            ->first();

        if ($academicPeriod) {
            $validated['academic_period_id'] = $academicPeriod->id;
            
            Log::info('Auto-synced academic period for new class', [
                'class_code' => $validated['code'],
                'semester' => $validated['semester'],
                'academic_period_id' => $academicPeriod->id,
                'academic_period_name' => $academicPeriod->name
            ]);
        } else {
            Log::warning('No active academic period found for new class', [
                'class_code' => $validated['code'],
                'semester' => $validated['semester']
            ]);
        }

        RuangKelas::create($validated);

        return redirect()
            ->route('classrooms.index')
            ->with('success', 'Kelas berhasil dibuat!' . ($academicPeriod ? ' (Periode akademik: ' . $academicPeriod->name . ')' : ''));
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

        $subjects = MataKuliah::where('is_active', true)->orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        $semesters = Semester::with('academicYear')->orderBy('academic_year_id', 'desc')->orderBy('number', 'asc')->get();
        
        return view('ruang-kelas.ubah', compact('classRoom', 'subjects', 'academicYears', 'semesters'));
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
            'code' => 'required|string|max:20|unique:ruang_kelas,code,' . $classRoom->id,
            'subject_id' => 'nullable|exists:mata_kuliah,id',
            'academic_year_id' => 'nullable|exists:academic_years,id',
            'semester_id' => 'nullable|exists:semesters,id',
            'semester' => 'required|string', // Keep for backward compatibility
            'program_studi' => 'required|string',
            'max_groups' => 'required|integer|min:1|max:10',
            'is_active' => 'boolean',
        ]);

        // Check if semester changed, auto-update academic_period_id
        if (isset($validated['semester']) && $validated['semester'] != $classRoom->semester) {
            $academicPeriod = PeriodeAkademik::where('semester_number', $validated['semester'])
                ->where('is_active', true)
                ->first();

            if ($academicPeriod) {
                $validated['academic_period_id'] = $academicPeriod->id;
                
                Log::info('Auto-updated academic period for class', [
                    'class_code' => $classRoom->code,
                    'old_semester' => $classRoom->semester,
                    'new_semester' => $validated['semester'],
                    'academic_period_id' => $academicPeriod->id,
                    'academic_period_name' => $academicPeriod->name
                ]);
            } else {
                // Clear academic_period_id if no matching period found
                $validated['academic_period_id'] = null;
                
                Log::warning('No active academic period found, cleared academic_period_id', [
                    'class_code' => $classRoom->code,
                    'semester' => $validated['semester']
                ]);
            }
        }

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

    /**
     * Show form to add student to class
     */
    public function createStudent(ClassRoom $classRoom)
    {
        // Only admin and dosen can add students
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action.');
        }

        return view('ruang-kelas.mahasiswa.tambah', compact('classRoom'));
    }

    /**
     * Store student in class
     */
    public function storeStudent(Request $request, ClassRoom $classRoom)
    {
        // Only admin and dosen can add students
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:pengguna,nim',
            'email' => 'required|email|unique:pengguna,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['role'] = 'mahasiswa';
        $validated['program_studi'] = $classRoom->program_studi;
        $validated['class_room_id'] = $classRoom->id;
        $validated['is_active'] = true;
        $validated['password'] = \Hash::make($validated['password']);

        \App\Models\Pengguna::create($validated);

        return redirect()
            ->route('classrooms.show', $classRoom)
            ->with('success', 'Mahasiswa berhasil ditambahkan ke kelas!');
    }

    /**
     * Show form to edit student
     */
    public function editStudent(ClassRoom $classRoom, \App\Models\Pengguna $student)
    {
        // Only admin and dosen can edit students
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action.');
        }

        // Make sure student belongs to this class
        if ($student->class_room_id !== $classRoom->id) {
            abort(404, 'Mahasiswa tidak ditemukan di kelas ini.');
        }

        return view('ruang-kelas.mahasiswa.ubah', compact('classRoom', 'student'));
    }

    /**
     * Update student
     */
    public function updateStudent(Request $request, ClassRoom $classRoom, \App\Models\Pengguna $student)
    {
        // Only admin and dosen can update students
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action.');
        }

        // Make sure student belongs to this class
        if ($student->class_room_id !== $classRoom->id) {
            abort(404, 'Mahasiswa tidak ditemukan di kelas ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:20|unique:pengguna,nim,' . $student->id,
            'email' => 'required|email|unique:pengguna,email,' . $student->id,
            'is_active' => 'boolean',
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $validated['password'] = \Hash::make($request->password);
        }

        $student->update($validated);

        return redirect()
            ->route('classrooms.show', $classRoom)
            ->with('success', 'Data mahasiswa berhasil diupdate!');
    }

    /**
     * Remove student from class
     */
    public function destroyStudent(ClassRoom $classRoom, \App\Models\Pengguna $student)
    {
        // Only admin and dosen can remove students
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action.');
        }

        // Make sure student belongs to this class
        if ($student->class_room_id !== $classRoom->id) {
            abort(404, 'Mahasiswa tidak ditemukan di kelas ini.');
        }

        // Check if student is in any group
        if ($student->groupMembers()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus mahasiswa yang masih terdaftar di kelompok! Hapus dari kelompok terlebih dahulu.');
        }

        // Remove student from class (set class_room_id to null)
        $student->update(['class_room_id' => null]);

        return redirect()
            ->route('classrooms.show', $classRoom)
            ->with('success', 'Mahasiswa berhasil dihapus dari kelas!');
    }
}



