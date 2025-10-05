<?php

namespace App\Http\Controllers;

use App\Models\{Group, Criterion, GroupScore};
use App\Services\RankingService;
use App\Http\Requests\StoreGroupScoreRequest;
use Illuminate\Http\Request;

class GroupScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Group::with(['classRoom', 'leader', 'members', 'weeklyTargets'])->get();
        $criteria = Criterion::where('segment', 'group')->get();
        $scores = GroupScore::all();
        
        // Calculate ranking using RankingService
        $ranking = [];
        if ($groups->count() > 0 && $criteria->count() > 0) {
            try {
                $rankingService = new RankingService();
                $totals = $rankingService->computeGroupTotals();
                arsort($totals); // Sort descending
                
                foreach ($totals as $groupId => $totalScore) {
                    $group = $groups->find($groupId);
                    if ($group) {
                        $ranking[] = [
                            'group_id' => $groupId,
                            'kode' => $group->name,
                            'nama' => $group->name,
                            'total_skor' => $totalScore,
                            'completion_rate' => $group->getTargetCompletionRate()
                        ];
                    }
                }
            } catch (\Exception $e) {
                $ranking = [];
            }
        }
        
        // Calculate average score
        $averageScore = $scores->count() > 0 ? $scores->avg('skor') : 0;
        
        // Get progress speed scores
        $progressSpeedScores = [];
        try {
            $rankingService = new RankingService();
            $progressSpeedScores = $rankingService->getProgressSpeedScores();
        } catch (\Exception $e) {
            $progressSpeedScores = [];
        }
        
        // Get best students per class
        $bestStudentsPerClass = $this->getBestStudentsPerClass();
        
        // Get best groups per class
        $bestGroupsPerClass = $this->getBestGroupsPerClass();
        
        return view('scores.index', compact('groups', 'criteria', 'scores', 'ranking', 'averageScore', 'progressSpeedScores', 'bestStudentsPerClass', 'bestGroupsPerClass'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('scores.create', [
            'groups' => Group::with(['classRoom'])->orderBy('name')->get(),
            'criteria' => Criterion::where('segment', 'group')->orderBy('id')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupScoreRequest $request)
    {
        GroupScore::updateOrCreate(
            ['group_id' => $request->group_id, 'criterion_id' => $request->criterion_id],
            ['skor' => $request->skor]
        );
        return redirect()->route('scores.index')->with('ok', 'Nilai berhasil disimpan.');
    }
    
    public function recalc(RankingService $svc)
    {
        $totals = $svc->computeGroupTotals();
        // untuk demo: kirim ke view sebagai ranking
        arsort($totals); // besar ke kecil
        return view('scores.ranking', ['ranking'=>$totals, 'groups'=>Group::pluck('name','id')]);
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
     */
    private function getBestStudentsPerClass()
    {
        $bestStudents = [];
        
        // Get all classes with their groups
        $classRooms = \App\Models\ClassRoom::with(['groups.members.user'])->get();
        
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
     */
    private function getBestGroupsPerClass()
    {
        $bestGroups = [];
        
        // Get all classes with their groups
        $classRooms = \App\Models\ClassRoom::with(['groups'])->get();
        
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
            $completedTargets = \App\Models\WeeklyTarget::where('group_id', $group->id)
                ->where('is_completed', true)
                ->count();
            $totalTargets = \App\Models\WeeklyTarget::where('group_id', $group->id)->count();
            
            if ($totalTargets > 0) {
                $completionRate = ($completedTargets / $totalTargets) * 100;
                $targetScore = min(30, $completionRate * 0.3); // Max 30 points from targets
            }
            
            // 2. Calculate based on group progress submissions
            $progressScore = 0;
            $reviewedProgress = \App\Models\WeeklyProgress::where('group_id', $group->id)
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
            $isMember = \App\Models\GroupMember::where('group_id', $group->id)
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
     * Calculate group total score
     */
    private function calculateGroupTotalScore($group)
    {
        $scores = \App\Models\GroupScore::where('group_id', $group->id)->get();
        $criteria = \App\Models\Criterion::where('segment', 'group')->get();
        
        $totalScore = 0;
        foreach ($scores as $score) {
            $criterion = $criteria->find($score->criterion_id);
            if ($criterion) {
                $totalScore += $score->skor * $criterion->bobot;
            }
        }
        
        return $totalScore;
    }
}
