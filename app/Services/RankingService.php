<?php
namespace App\Services;

use App\Models\{Group, Criterion};

class RankingService
{
    /**
     * Hitung total skor kelompok berbasis bobot kriteria (segment=group).
     * Normalisasi sederhana min-max per kriteria (benefit/cost) lalu kalikan bobot.
     * Return: array [group_id => total]
     */
    public function computeGroupTotals(): array
    {
        $criteria = Criterion::where('segment','group')->get();
        $groups   = Group::with(['scores'=>fn($q)=>$q->whereIn('criterion_id',$criteria->pluck('id'))])->get();

        // siapkan rentang per kriteria
        $ranges = [];
        foreach ($criteria as $c) {
            $values = [];
            foreach ($groups as $g) {
                $score = optional($g->scores->firstWhere('criterion_id',$c->id))->skor ?? 0;
                $values[] = (float)$score;
            }
            $min = min($values ?: [0]); $max = max($values ?: [1]);
            $ranges[$c->id] = ['min'=>$min,'max'=>$max,'type'=>$c->tipe,'w'=>(float)$c->bobot];
        }

        // hitung total
        $totals = [];
        foreach ($groups as $g) {
            $sum = 0.0;
            foreach ($criteria as $c) {
                $val = (float)(optional($g->scores->firstWhere('criterion_id',$c->id))->skor ?? 0);
                $min = $ranges[$c->id]['min']; $max = $ranges[$c->id]['max']; $w = $ranges[$c->id]['w'];
                $norm = ($max == $min) ? 1.0 : (($val - $min) / ($max - $min));
                if ($ranges[$c->id]['type'] === 'cost') { $norm = 1 - $norm; }
                $sum += $norm * $w;
            }
            $totals[$g->id] = round($sum, 4);
        }
        return $totals;
    }
}
