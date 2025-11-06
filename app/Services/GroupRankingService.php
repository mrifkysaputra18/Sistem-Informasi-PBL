<?php

namespace App\Services;

use App\Models\{Kelompok, Kriteria, NilaiKelompok};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GroupRankingService
{
    /**
     * Calculate and update rankings for all groups in a class
     * 
     * @param int $classRoomId
     * @return void
     */
    public function calculateClassRankings(int $classRoomId): void
    {
        $groups = Kelompok::where('class_room_id', $classRoomId)
            ->with(['weeklyTargets', 'scores', 'members'])
            ->get();

        if ($groups->isEmpty()) {
            return;
        }

        // Get all criteria
        $criteria = Kriteria::where('segment', 'group')->get();

        if ($criteria->isEmpty()) {
            Log::warning('No group criteria found for ranking calculation');
            return;
        }

        // Calculate scores for each criterion for each group
        foreach ($groups as $group) {
            $this->calculateGroupScores($group, $criteria);
        }

        // Calculate weighted total scores and rank groups
        $this->rankGroups($groups, $criteria);

        Log::info("Ranking calculated for class {$classRoomId}", [
            'total_groups' => $groups->count(),
            'criteria_used' => $criteria->count(),
        ]);
    }

    /**
     * Calculate scores for each criterion for a group
     * 
     * @param Kelompok $group
     * @param Collection $criteria
     * @return void
     */
    private function calculateGroupScores(Kelompok $group, Collection $criteria): void
    {
        foreach ($criteria as $criterion) {
            $score = match ($criterion->nama) {
                'Kecepatan Progres' => $this->calculateSpeedScore($group),
                'Nilai Akhir PBL' => $this->calculateFinalScore($group),
                'Ketepatan Waktu' => $this->calculateTimelinessScore($group),
                'Penilaian Teman (Group)' => $this->calculatePeerReviewScore($group),
                default => 0,
            };

            // Save or update score
            NilaiKelompok::updateOrCreate(
                [
                    'group_id' => $group->id,
                    'criterion_id' => $criterion->id,
                ],
                [
                    'skor' => $score,
                ]
            );
        }
    }

    /**
     * Calculate Speed Score (Kecepatan Progres)
     * Based on weekly target completion rate
     * 
     * @param Kelompok $group
     * @return float Score 0-100
     */
    private function calculateSpeedScore(Kelompok $group): float
    {
        return $group->getTargetCompletionScore();
    }

    /**
     * Calculate Final Score (Nilai Akhir PBL)
     * This should be based on final project evaluation
     * For now, use average of all approved target scores
     * 
     * @param Kelompok $group
     * @return float Score 0-100
     */
    private function calculateFinalScore(Kelompok $group): float
    {
        // Get approved targets with reviews
        $approvedTargets = $group->weeklyTargets()
            ->where('submission_status', 'approved')
            ->whereHas('review')
            ->with('review')
            ->get();

        if ($approvedTargets->isEmpty()) {
            return 0;
        }

        // Calculate average score from reviews
        $totalScore = $approvedTargets->sum(function ($target) {
            return $target->review->score ?? 0;
        });

        return $approvedTargets->count() > 0 
            ? min(100, $totalScore / $approvedTargets->count())
            : 0;
    }

    /**
     * Calculate Timeliness Score (Ketepatan Waktu)
     * Based on on-time submission rate
     * 
     * @param Kelompok $group
     * @return float Score 0-100
     */
    private function calculateTimelinessScore(Kelompok $group): float
    {
        $allTargets = $group->weeklyTargets;
        $totalSubmitted = $allTargets->whereIn('submission_status', ['submitted', 'approved', 'late'])->count();

        if ($totalSubmitted === 0) {
            return 0;
        }

        // Count on-time submissions (not marked as 'late')
        $onTimeSubmissions = $allTargets->whereIn('submission_status', ['submitted', 'approved'])->count();

        return ($onTimeSubmissions / $totalSubmitted) * 100;
    }

    /**
     * Calculate Peer Review Score (Penilaian Teman)
     * This would typically come from peer evaluation forms
     * For now, return a placeholder
     * 
     * @param Kelompok $group
     * @return float Score 0-100
     */
    private function calculatePeerReviewScore(Kelompok $group): float
    {
        // TODO: Implement peer review system
        // For now, return average based on group collaboration metrics
        // You can extend this when peer review feature is implemented
        
        return 75; // Placeholder score
    }

    /**
     * Calculate weighted total scores and rank groups
     * 
     * @param Collection $groups
     * @param Collection $criteria
     * @return void
     */
    private function rankGroups(Collection $groups, Collection $criteria): void
    {
        $groupScores = [];

        foreach ($groups as $group) {
            $totalScore = 0;

            foreach ($criteria as $criterion) {
                $score = NilaiKelompok::where('group_id', $group->id)
                    ->where('criterion_id', $criterion->id)
                    ->value('skor') ?? 0;

                // Apply weight (bobot is already in decimal format, e.g., 0.244465446)
                $weightedScore = $score * $criterion->bobot;
                $totalScore += $weightedScore;
            }

            $groupScores[$group->id] = $totalScore;
        }

        // Sort by score descending and assign ranks
        arsort($groupScores);
        $rank = 1;

        foreach ($groupScores as $groupId => $score) {
            Kelompok::where('id', $groupId)->update([
                'total_score' => round($score, 2),
                'ranking' => $rank++,
            ]);
        }
    }

    /**
     * Get ranking details for a group
     * 
     * @param Kelompok $group
     * @return array
     */
    public function getGroupRankingDetails(Kelompok $group): array
    {
        $criteria = Kriteria::where('segment', 'group')->get();
        $scores = NilaiKelompok::where('group_id', $group->id)
            ->with('criterion')
            ->get()
            ->keyBy('criterion_id');

        $details = [];
        $totalWeightedScore = 0;

        foreach ($criteria as $criterion) {
            $score = $scores->get($criterion->id)?->skor ?? 0;
            $weightedScore = $score * $criterion->bobot;
            $totalWeightedScore += $weightedScore;

            $details[] = [
                'criterion' => $criterion->nama,
                'weight' => $criterion->bobot,
                'weight_percentage' => round($criterion->bobot * 100, 2) . '%',
                'score' => round($score, 2),
                'weighted_score' => round($weightedScore, 2),
            ];
        }

        return [
            'group_id' => $group->id,
            'group_name' => $group->name,
            'total_score' => round($totalWeightedScore, 2),
            'ranking' => $group->ranking,
            'criteria_scores' => $details,
        ];
    }
}
