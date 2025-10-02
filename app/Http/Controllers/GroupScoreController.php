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
        
        return view('scores.index', compact('groups', 'criteria', 'scores', 'ranking', 'averageScore', 'progressSpeedScores'));
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
}
