<?php

namespace App\Services;

use App\Models\{Criterion, AhpComparison};

class AhpService
{
    // Random Index (RI) values untuk n kriteria
    private $randomIndex = [
        1 => 0.00,
        2 => 0.00,
        3 => 0.58,
        4 => 0.90,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49,
    ];

    /**
     * Hitung bobot kriteria menggunakan metode AHP
     * 
     * @param string $segment 'group' atau 'student'
     * @return array
     */
    public function calculateWeights(string $segment): array
    {
        // Get criteria for this segment
        $criteria = Kriteria::where('segment', $segment)->orderBy('id')->get();
        
        if ($criteria->count() < 2) {
            throw new \Exception('Minimal 2 kriteria diperlukan untuk perhitungan AHP');
        }

        $n = $criteria->count();
        $criteriaIds = $criteria->pluck('id')->toArray();

        // Build comparison matrix
        $matrix = $this->buildComparisonMatrix($criteriaIds, $segment);

        // Calculate normalized matrix
        $normalizedMatrix = $this->normalizeMatrix($matrix);

        // Calculate priority vector (weights)
        $weights = $this->calculatePriorityVector($normalizedMatrix);

        // Calculate consistency
        $consistency = $this->calculateConsistency($matrix, $weights, $n);

        return [
            'criteria' => $criteria,
            'matrix' => $matrix,
            'normalized_matrix' => $normalizedMatrix,
            'weights' => $weights,
            'lambda_max' => $consistency['lambda_max'],
            'ci' => $consistency['ci'],
            'cr' => $consistency['cr'],
            'is_consistent' => $consistency['cr'] <= 0.1, // CR ≤ 0.1 = konsisten
        ];
    }

    /**
     * Build comparison matrix from database
     */
    private function buildComparisonMatrix(array $criteriaIds, string $segment): array
    {
        $n = count($criteriaIds);
        $matrix = array_fill(0, $n, array_fill(0, $n, 1));

        // Fill matrix from database
        $comparisons = AhpComparison::where('segment', $segment)
            ->whereIn('criterion_a_id', $criteriaIds)
            ->whereIn('criterion_b_id', $criteriaIds)
            ->get();

        foreach ($comparisons as $comparison) {
            $i = array_search($comparison->criterion_a_id, $criteriaIds);
            $j = array_search($comparison->criterion_b_id, $criteriaIds);

            if ($i !== false && $j !== false) {
                $matrix[$i][$j] = (float) $comparison->value;
                $matrix[$j][$i] = 1 / (float) $comparison->value; // Reciprocal
            }
        }

        return $matrix;
    }

    /**
     * Normalize matrix (sum of each column = 1)
     */
    private function normalizeMatrix(array $matrix): array
    {
        $n = count($matrix);
        $normalized = array_fill(0, $n, array_fill(0, $n, 0));

        // Calculate column sums
        $columnSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalize each element
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $columnSums[$j] > 0 
                    ? $matrix[$i][$j] / $columnSums[$j] 
                    : 0;
            }
        }

        return $normalized;
    }

    /**
     * Calculate priority vector (average of each row in normalized matrix)
     */
    private function calculatePriorityVector(array $normalizedMatrix): array
    {
        $n = count($normalizedMatrix);
        $weights = [];

        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $normalizedMatrix[$i][$j];
            }
            $weights[$i] = $sum / $n;
        }

        return $weights;
    }

    /**
     * Calculate consistency metrics
     */
    private function calculateConsistency(array $matrix, array $weights, int $n): array
    {
        // Calculate weighted sum vector
        $weightedSum = array_fill(0, $n, 0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSum[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            if ($weights[$i] > 0) {
                $lambdaMax += $weightedSum[$i] / $weights[$i];
            }
        }
        $lambdaMax /= $n;

        // Calculate Consistency Index (CI)
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Calculate Consistency Ratio (CR)
        $ri = $this->randomIndex[$n] ?? 1.49;
        $cr = $ri > 0 ? $ci / $ri : 0;

        return [
            'lambda_max' => round($lambdaMax, 4),
            'ci' => round($ci, 4),
            'cr' => round($cr, 4),
        ];
    }

    /**
     * Save comparison value
     */
    public function saveComparison(string $segment, int $criterionAId, int $criterionBId, float $value): void
    {
        // Ensure criterion A ID is always less than criterion B ID for consistency
        if ($criterionAId > $criterionBId) {
            [$criterionAId, $criterionBId] = [$criterionBId, $criterionAId];
            $value = 1 / $value;
        }

        AhpComparison::updateOrCreate(
            [
                'segment' => $segment,
                'criterion_a_id' => $criterionAId,
                'criterion_b_id' => $criterionBId,
            ],
            ['value' => $value]
        );
    }

    /**
     * Get AHP scale description
     */
    public static function getScaleDescription(float $value): string
    {
        if ($value == 1) return 'Sama penting';
        if ($value == 2) return 'Sedikit lebih penting';
        if ($value == 3) return 'Cukup penting';
        if ($value == 4) return 'Lebih penting';
        if ($value == 5) return 'Sangat penting';
        if ($value == 6) return 'Sangat lebih penting';
        if ($value == 7) return 'Jauh lebih penting';
        if ($value == 8) return 'Sangat jauh lebih penting';
        if ($value == 9) return 'Mutlak lebih penting';
        return 'Nilai antara';
    }

    /**
     * Get all pairwise comparisons for a segment
     */
    public function getComparisons(string $segment): array
    {
        $criteria = Kriteria::where('segment', $segment)->orderBy('id')->get();
        $comparisons = [];

        for ($i = 0; $i < $criteria->count(); $i++) {
            for ($j = $i + 1; $j < $criteria->count(); $j++) {
                $criterionA = $criteria[$i];
                $criterionB = $criteria[$j];

                $comparison = AhpComparison::where('segment', $segment)
                    ->where('criterion_a_id', $criterionA->id)
                    ->where('criterion_b_id', $criterionB->id)
                    ->first();

                $comparisons[] = [
                    'criterion_a' => $criterionA,
                    'criterion_b' => $criterionB,
                    'value' => $comparison ? $comparison->value : 1,
                ];
            }
        }

        return $comparisons;
    }

    /**
     * Apply calculated weights to criteria
     */
    public function applyWeightsToCriteria(string $segment, array $weights): void
    {
        $criteria = Kriteria::where('segment', $segment)->orderBy('id')->get();

        foreach ($criteria as $index => $criterion) {
            if (isset($weights[$index])) {
                $criterion->update(['bobot' => $weights[$index]]);
            }
        }
    }

    /**
     * Reset all comparisons for a segment
     */
    public function resetComparisons(string $segment): void
    {
        AhpComparison::where('segment', $segment)->delete();
    }
}


