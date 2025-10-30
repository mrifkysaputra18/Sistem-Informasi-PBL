<?php

namespace App\Http\Controllers;

use App\Models\{Kelompok, Kriteria, NilaiKelompok};
use App\Services\RankingService;
use App\Http\Requests\StoreGroupScoreRequest;
use Illuminate\Http\Request;

class NilaiKelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Build query with filters
        $query = Kelompok::with(['classRoom.academicPeriod', 'leader', 'members', 'weeklyTargets']);
        
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
        
        $groups = $query->get();
        $criteria = Kriteria::where('segment', 'group')->get();
        
        // Filter scores based on filtered groups
        $groupIds = $groups->pluck('id');
        $scores = NilaiKelompok::whereIn('group_id', $groupIds)->get();
        
        // Calculate ranking using RankingService dengan metode SAW
        $rankingService = new RankingService();
        $ranking = $rankingService->getGroupRankingWithDetails($groupIds->toArray());
        
        // Calculate average score
        $averageScore = $scores->count() > 0 ? $scores->avg('skor') : 0;
        
        // Get progress speed scores
        $progressSpeedScores = $rankingService->getProgressSpeedScores();
        
        // Get best students per class (with filter)
        $bestStudentsPerClass = $this->getBestStudentsPerClass($request->academic_year, $request->class_room_id);
        
        // Get best groups per class (with filter)
        $bestGroupsPerClass = $this->getBestGroupsPerClass($request->academic_year, $request->class_room_id);
        
        // Get unique academic years for filter
        $academicYears = \App\Models\PeriodeAkademik::orderBy('academic_year', 'desc')
            ->pluck('academic_year')
            ->unique()
            ->values();
        
        // Get classrooms for filter
        $classRooms = \App\Models\RuangKelas::with('academicPeriod')
            ->when($request->filled('academic_year'), function($q) use ($request) {
                $q->whereHas('academicPeriod', function($query) use ($request) {
                    $query->where('academic_year', $request->academic_year);
                });
            })
            ->orderBy('name')
            ->get();
        
        return view('nilai.daftar', compact('groups', 'criteria', 'scores', 'ranking', 'averageScore', 'progressSpeedScores', 'bestStudentsPerClass', 'bestGroupsPerClass', 'academicYears', 'classRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Only admin and dosen can create scores
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat menginput nilai kelompok.');
        }

        return view('nilai.tambah', [
            'groups' => Kelompok::with(['classRoom'])->orderBy('name')->get(),
            'criteria' => Kriteria::where('segment', 'group')->orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupScoreRequest $request)
    {
        // Only admin and dosen can store scores
        if (!auth()->user()->isAdmin() && !auth()->user()->isDosen()) {
            abort(403, 'Unauthorized action. Hanya admin dan dosen yang dapat menginput nilai kelompok.');
        }

        NilaiKelompok::updateOrCreate(
            ['group_id' => $request->group_id, 'criterion_id' => $request->criterion_id],
            ['skor' => $request->skor]
        );
        return redirect()->route('scores.index')->with('ok', 'Nilai berhasil disimpan.');
    }
    
    public function recalc()
    {
        // Only admin can recalculate ranking
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Hanya admin yang dapat menghitung ulang ranking kelompok.');
        }

        $rankingService = new RankingService();
        
        // Update total scores dan rankings menggunakan metode SAW
        $rankingService->updateGroupRankings();
        
        // Get ranking with details
        $ranking = $rankingService->getGroupRankingWithDetails();
        
        return redirect()->route('scores.index')
            ->with('ok', 'Ranking kelompok berhasil dihitung ulang menggunakan metode SAW!');
    }


    /**
     * Display the specified resource.
     */
    public function show(GroupScore $groupScore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GroupScore $groupScore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GroupScore $groupScore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GroupScore $groupScore)
    {
        //
    }
    
    /**
     * Get best students per class based on individual performance
     * 
     * @param string|null $academicYear Filter by academic year
     * @param int|null $classRoomId Filter by specific class
     */
    private function getBestStudentsPerClass($academicYear = null, $classRoomId = null)
    {
        $bestStudents = [];
        
        // Build query with filters
        $query = \App\Models\RuangKelas::with(['groups.members.user', 'academicPeriod']);
        
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
            $students = collect();
            
            // Collect all students from all groups in this class
            foreach ($classRoom->groups as $group) {
                foreach ($group->members as $member) {
                    $student = $member->user;
                    if ($student && $student->role === 'mahasiswa') {
                        // Calculate individual score based on attendance and participation
                        $individualScore = $this->calculateIndividualStudentScore($student, $group);
                        
                        $students->push([
                            'student' => $student,
                            'group' => $group,
                            'individual_score' => $individualScore,
                            'group_score' => $group->total_score ?? 0,
                            'final_score' => ($individualScore + ($group->total_score ?? 0)) / 2
                        ]);
                    }
                }
            }
            
            // Sort by final score and get top 3
            $topStudents = $students->sortByDesc('final_score')->take(3)->values();
            
            if ($topStudents->count() > 0) {
                $bestStudents[] = [
                    'class_room' => $classRoom,
                    'top_students' => $topStudents
                ];
            }
        }
        
        return $bestStudents;
    }
    
    /**
     * Get best groups per class
     * 
     * @param string|null $academicYear Filter by academic year
     * @param int|null $classRoomId Filter by specific class
     */
    private function getBestGroupsPerClass($academicYear = null, $classRoomId = null)
    {
        $bestGroups = [];
        
        // Build query with filters
        $query = \App\Models\RuangKelas::with(['groups', 'academicPeriod']);
        
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
            $groups = $classRoom->groups;
            
            if ($groups->count() > 0) {
                // Calculate scores for each group
                $groupScores = [];
                foreach ($groups as $group) {
                    $totalScore = $this->calculateGroupTotalScore($group);
                    $groupScores[] = [
                        'group' => $group,
                        'total_score' => $totalScore,
                        'completion_rate' => $group->getTargetCompletionRate()
                    ];
                }
                
                // Sort by total score and get top 3
                $topGroups = collect($groupScores)->sortByDesc('total_score')->take(3)->values();
                
                $bestGroups[] = [
                    'class_room' => $classRoom,
                    'top_groups' => $topGroups
                ];
            }
        }
        
        return $bestGroups;
    }
    
    /**
     * Calculate individual student score
     * Based on group performance and leadership
     */
    private function calculateIndividualStudentScore($student, $group)
    {
        try {
            // 1. Calculate based on weekly targets completion (group effort)
            $targetScore = 0;
            $completedTargets = \App\Models\TargetMingguan::where('group_id', $group->id)
                ->where('is_completed', true)
                ->count();
            $totalTargets = \App\Models\TargetMingguan::where('group_id', $group->id)->count();
            
            if ($totalTargets > 0) {
                $completionRate = ($completedTargets / $totalTargets) * 100;
                $targetScore = min(30, $completionRate * 0.3); // Max 30 points from targets
            }
            
            // 2. Calculate based on group progress submissions
            $progressScore = 0;
            $reviewedProgress = \App\Models\KemajuanMingguan::where('group_id', $group->id)
                ->where('status', 'reviewed')
                ->count();
            $progressScore = min(20, $reviewedProgress * 2.5); // Max 20 points, 2.5 per progress
            
            // 3. Calculate based on leadership (if student is group leader)
            $leadershipScore = 0;
            if ($group->leader_id === $student->id) {
                $leadershipScore = 15; // Bonus points for being leader
            }
            
            // 4. Calculate based on group total score (main component)
            $groupScoreComponent = 0;
            if ($group->total_score && $group->total_score > 0) {
                $groupScoreComponent = ($group->total_score / 100) * 35; // Max 35 points from group performance
            }
            
            // 5. Base participation score (all members get this)
            $baseScore = 0;
            $isMember = \App\Models\AnggotaKelompok::where('group_id', $group->id)
                ->where('user_id', $student->id)
                ->exists();
            
            if ($isMember) {
                $baseScore = 10; // Base 10 points for being active member
            }
            
            // Calculate total
            $totalScore = $baseScore + $targetScore + $progressScore + $leadershipScore + $groupScoreComponent;
            
            // Cap at 100
            return min(100, max(0, round($totalScore, 2)));
            
        } catch (\Exception $e) {
            // If any error occurs, return base score based on group performance
            \Log::error('Error calculating individual student score: ' . $e->getMessage());
            
            // Fallback: return score based on group total_score
            if ($group->total_score) {
                $score = ($group->total_score / 100) * 60; // 60% of group score
                if ($group->leader_id === $student->id) {
                    $score += 10; // Leader bonus
                }
                return min(100, max(0, round($score, 2)));
            }
            
            return 50; // Default fallback score
        }
    }
    
    /**
     * Calculate group total score menggunakan metode SAW
     */
    private function calculateGroupTotalScore($group)
    {
        $rankingService = new RankingService();
        $totals = $rankingService->computeGroupTotals();
        
        return $totals[$group->id] ?? 0;
    }
}
