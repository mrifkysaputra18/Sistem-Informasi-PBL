<?php

namespace App\Http\Controllers;

use App\Models\{User, Criterion, StudentScore, ClassRoom};
use App\Services\RankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = User::where('role', 'mahasiswa')
            ->with(['classRoom.academicPeriod', 'groups', 'studentScores']);
        
        // Filter by academic year (angkatan)
        if ($request->filled('academic_year')) {
            $query->whereHas('classRoom.academicPeriod', function($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }
        
        // Filter by class
        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }
        
        $students = $query->get();
        $criteria = Criterion::where('segment', 'student')->get();
        
        // Filter scores based on filtered students
        $studentIds = $students->pluck('id');
        $scores = StudentScore::whereIn('user_id', $studentIds)->get();
        
        // Calculate ranking using RankingService
        $rankingService = new RankingService();
        $ranking = $rankingService->getStudentRankingWithDetails($request->class_room_id, $studentIds->toArray());
        
        // Calculate average score
        $averageScore = $scores->count() > 0 ? $scores->avg('skor') : 0;
        
        // Get best students per class (with filter)
        $bestStudentsPerClass = $this->getBestStudentsPerClass($request->academic_year, $request->class_room_id);
        
        // Get unique academic years for filter
        $academicYears = \App\Models\AcademicPeriod::orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->unique()
            ->values();
        
        // Get classrooms for filter
        $classRooms = ClassRoom::with('academicPeriod')
            ->when($request->filled('academic_year'), function($q) use ($request) {
                $q->whereHas('academicPeriod', function($query) use ($request) {
                    $query->where('academic_year', $request->academic_year);
                });
            })
            ->orderBy('name')
            ->get();
        
        return view('student-scores.index', compact(
            'students', 
            'criteria', 
            'scores', 
            'ranking', 
            'averageScore',
            'bestStudentsPerClass',
            'academicYears',
            'classRooms'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin and dosen can create scores
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat menginput nilai mahasiswa.');
        }

        $students = User::where('role', 'mahasiswa')
            ->with(['classRoom', 'groups'])
            ->orderBy('name')
            ->get();
        
        $criteria = Criterion::where('segment', 'student')->get();
        
        return view('student-scores.create', compact('students', 'criteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only admin and dosen can store scores
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat menginput nilai mahasiswa.');
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'criterion_id' => 'required|exists:criteria,id',
            'skor' => 'required|numeric|min:0|max:100',
        ], [
            'user_id.required' => 'Mahasiswa harus dipilih',
            'user_id.exists' => 'Mahasiswa tidak ditemukan',
            'criterion_id.required' => 'Kriteria harus dipilih',
            'criterion_id.exists' => 'Kriteria tidak ditemukan',
            'skor.required' => 'Skor harus diisi',
            'skor.numeric' => 'Skor harus berupa angka',
            'skor.min' => 'Skor minimal 0',
            'skor.max' => 'Skor maksimal 100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        StudentScore::updateOrCreate(
            [
                'user_id' => $request->user_id, 
                'criterion_id' => $request->criterion_id
            ],
            ['skor' => $request->skor]
        );
        
        return redirect()->route('student-scores.index')
            ->with('ok', 'Nilai mahasiswa berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    /**
     * Recalculate student rankings using SAW method
     */
    public function recalc()
    {
        // Only admin and dosen can recalculate ranking
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat menghitung ulang ranking.');
        }

        $rankingService = new RankingService();
        $ranking = $rankingService->getStudentRankingWithDetails();
        
        return view('student-scores.ranking', compact('ranking'));
    }
    
    /**
     * Get best students per class
     * 
     * @param string|null $academicYear Filter by academic year
     * @param int|null $classRoomId Filter by specific class
     */
    private function getBestStudentsPerClass($academicYear = null, $classRoomId = null)
    {
        $bestStudents = [];
        $rankingService = new RankingService();
        
        // Build query with filters
        $query = ClassRoom::with(['students', 'academicPeriod']);
        
        // Filter by academic year
        if ($academicYear) {
            $query->whereHas('academicPeriod', function($q) use ($academicYear) {
                $q->where('academic_year', $academicYear);
            });
        }
        
        // Filter by specific class
        if ($classRoomId) {
            $query->where('id', $classRoomId);
        }
        
        $classRooms = $query->get();
        
        foreach ($classRooms as $classRoom) {
            if ($classRoom->students->count() > 0) {
                // Get ranking for this class
                $totals = $rankingService->computeStudentTotals();
                
                // Filter students from this class and sort
                $classStudents = [];
                foreach ($classRoom->students as $student) {
                    if (isset($totals[$student->id])) {
                        $classStudents[] = [
                            'student' => $student,
                            'total_score' => $totals[$student->id],
                            'group' => $student->groups->first()
                        ];
                    }
                }
                
                // Sort by total score descending
                usort($classStudents, function($a, $b) {
                    return $b['total_score'] <=> $a['total_score'];
                });
                
                // Take top 3
                $topStudents = array_slice($classStudents, 0, 3);
                
                if (count($topStudents) > 0) {
                    $bestStudents[] = [
                        'class_room' => $classRoom,
                        'top_students' => $topStudents
                    ];
                }
            }
        }
        
        return $bestStudents;
    }
}
