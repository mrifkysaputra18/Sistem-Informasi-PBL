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
    public function index()
    {
        $students = User::where('role', 'mahasiswa')
            ->with(['classRoom', 'groups', 'studentScores'])
            ->get();
        
        $criteria = Criterion::where('segment', 'student')->get();
        $scores = StudentScore::all();
        
        // Calculate ranking using RankingService
        $rankingService = new RankingService();
        $ranking = $rankingService->getStudentRankingWithDetails();
        
        // Calculate average score
        $averageScore = $scores->count() > 0 ? $scores->avg('skor') : 0;
        
        // Get best students per class
        $bestStudentsPerClass = $this->getBestStudentsPerClass();
        
        return view('student-scores.index', compact(
            'students', 
            'criteria', 
            'scores', 
            'ranking', 
            'averageScore',
            'bestStudentsPerClass'
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
     */
    private function getBestStudentsPerClass()
    {
        $bestStudents = [];
        $rankingService = new RankingService();
        
        // Get all classes with their students
        $classRooms = ClassRoom::with(['students'])->get();
        
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
