<?php
namespace App\Services;

use App\Models\{Group, Criterion};

class RankingService
{
    /**
     * Hitung total skor kelompok berbasis bobot kriteria (segment=group).
     * Normalisasi sederhana min-max per kriteria (benefit/cost) lalu kalikan bobot.
     * PLUS: Kecepatan Progres berdasarkan weekly target completion rate
     * Return: array [group_id => total]
     */
    public function computeGroupTotals(): array
    {
        $criteria = Criterion::where('segment','group')->get();
        $groups   = Group::with([
            'scores' => fn($q) => $q->whereIn('criterion_id', $criteria->pluck('id')),
            'weeklyTargets'
        ])->get();

        // siapkan rentang per kriteria
        $ranges = [];
        foreach ($criteria as $c) {
            $values = [];
            foreach ($groups as $g) {
                // Check if this is "Kecepatan Progres" criteria
                if (stripos($c->nama, 'kecepatan') !== false || stripos($c->nama, 'progres') !== false) {
                    // Use weekly target completion rate
                    $values[] = $g->getTargetCompletionRate();
                } else {
                    // Use normal score
                    $score = optional($g->scores->firstWhere('criterion_id',$c->id))->skor ?? 0;
                    $values[] = (float)$score;
                }
            }
            $min = min($values ?: [0]); $max = max($values ?: [1]);
            $ranges[$c->id] = ['min'=>$min,'max'=>$max,'type'=>$c->tipe,'w'=>(float)$c->bobot];
        }

        // hitung total
        $totals = [];
        foreach ($groups as $g) {
            $sum = 0.0;
            foreach ($criteria as $c) {
                // Check if this is "Kecepatan Progres" criteria
                if (stripos($c->nama, 'kecepatan') !== false || stripos($c->nama, 'progres') !== false) {
                    // Use weekly target completion rate
                    $val = $g->getTargetCompletionRate();
                } else {
                    // Use normal score
                    $val = (float)(optional($g->scores->firstWhere('criterion_id',$c->id))->skor ?? 0);
                }
                
                $min = $ranges[$c->id]['min']; $max = $ranges[$c->id]['max']; $w = $ranges[$c->id]['w'];
                $norm = ($max == $min) ? 1.0 : (($val - $min) / ($max - $min));
                if ($ranges[$c->id]['type'] === 'cost') { $norm = 1 - $norm; }
                $sum += $norm * $w;
            }
            $totals[$g->id] = round($sum, 4);
        }
        return $totals;
    }

    /**
     * Get progress speed scores for all groups
     * Returns: array [group_id => completion_rate]
     */
    public function getProgressSpeedScores(): array
    {
        $groups = Group::with('weeklyTargets')->get();
        $scores = [];
        
        foreach ($groups as $group) {
            $scores[$group->id] = $group->getTargetCompletionRate();
        }
        
        return $scores;
    }
}
