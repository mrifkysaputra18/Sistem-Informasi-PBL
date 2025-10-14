<?php

namespace App\Services;

use App\Models\{Group, Criterion, User, GroupScore, StudentScore};

class RankingService
{
    /**
     * Hitung ranking kelompok menggunakan metode SAW (Simple Additive Weighting)
     * 
     * Algoritma SAW:
     * 1. Normalisasi nilai: 
     *    - Benefit: r_ij = x_ij / max(x_ij)
     *    - Cost: r_ij = min(x_ij) / x_ij
     * 2. Preferensi: V_i = Σ(w_j * r_ij)
     * 
     * @return array [group_id => total_score]
     */
    public function computeGroupTotals(): array
    {
        // Ambil semua kriteria untuk kelompok
        $criteria = Criterion::where('segment', 'group')->get();
        
        if ($criteria->isEmpty()) {
            return [];
        }
        
        // Ambil semua kelompok dengan skor mereka
        $groups = Group::with(['scores' => function($q) use ($criteria) {
            $q->whereIn('criterion_id', $criteria->pluck('id'));
        }, 'weeklyTargets'])->get();
        
        if ($groups->isEmpty()) {
            return [];
        }
        
        // Array untuk menyimpan nilai preferensi
        $preferences = [];
        
        // Proses setiap kriteria
        foreach ($criteria as $criterion) {
            $values = [];
            
            // Kumpulkan nilai untuk kriteria ini dari semua kelompok
            foreach ($groups as $group) {
                // Cek apakah kriteria ini adalah "Kecepatan Progres"
                if (stripos($criterion->nama, 'kecepatan') !== false || stripos($criterion->nama, 'progres') !== false) {
                    // Gunakan completion rate untuk kecepatan progres
                    $values[$group->id] = $group->getTargetCompletionRate();
                } else {
                    // Gunakan nilai normal dari database
                    $score = $group->scores->where('criterion_id', $criterion->id)->first();
                    $values[$group->id] = $score ? (float)$score->skor : 0;
                }
            }
            
            // Hitung normalisasi menggunakan metode SAW
            $normalized = $this->normalizeSAW($values, $criterion->tipe);
            
            // Kalikan dengan bobot dan tambahkan ke preferensi
            foreach ($normalized as $groupId => $normValue) {
                if (!isset($preferences[$groupId])) {
                    $preferences[$groupId] = 0;
                }
                $preferences[$groupId] += $normValue * (float)$criterion->bobot;
            }
        }
        
        // Nilai SAW dalam skala 0-1 (tidak perlu dikali 100)
        $totals = [];
        foreach ($preferences as $groupId => $preference) {
            $totals[$groupId] = round($preference, 4);
        }
        
        return $totals;
    }
    
    /**
     * Hitung ranking mahasiswa menggunakan metode SAW
     * 
     * @param int|null $classRoomId Filter berdasarkan kelas (opsional)
     * @return array
     */
    public function computeStudentTotals($classRoomId = null): array
    {
        // Ambil semua kriteria untuk mahasiswa
        $criteria = Criterion::where('segment', 'student')->get();
        
        if ($criteria->isEmpty()) {
            return [];
        }
        
        // Query mahasiswa
        $studentsQuery = User::where('role', 'mahasiswa')
            ->with(['studentScores' => function($q) use ($criteria) {
                $q->whereIn('criterion_id', $criteria->pluck('id'));
            }]);
        
        // Filter berdasarkan kelas jika diperlukan
        if ($classRoomId) {
            $studentsQuery->whereHas('groups', function($q) use ($classRoomId) {
                $q->where('class_room_id', $classRoomId);
            });
        }
        
        $students = $studentsQuery->get();
        
        if ($students->isEmpty()) {
            return [];
        }
        
        // Array untuk menyimpan nilai preferensi
        $preferences = [];
        
        // Proses setiap kriteria
        foreach ($criteria as $criterion) {
            $values = [];
            
            // Kumpulkan nilai untuk kriteria ini dari semua mahasiswa
            foreach ($students as $student) {
                $score = $student->studentScores->where('criterion_id', $criterion->id)->first();
                $values[$student->id] = $score ? (float)$score->skor : 0;
            }
            
            // Hitung normalisasi menggunakan metode SAW
            $normalized = $this->normalizeSAW($values, $criterion->tipe);
            
            // Kalikan dengan bobot dan tambahkan ke preferensi
            foreach ($normalized as $studentId => $normValue) {
                if (!isset($preferences[$studentId])) {
                    $preferences[$studentId] = 0;
                }
                $preferences[$studentId] += $normValue * (float)$criterion->bobot;
            }
        }
        
        // Nilai SAW dalam skala 0-1 (tidak perlu dikali 100)
        $totals = [];
        foreach ($preferences as $studentId => $preference) {
            $totals[$studentId] = round($preference, 4);
        }
        
        return $totals;
    }
    
    /**
     * Normalisasi nilai menggunakan metode SAW
     * 
     * @param array $values Array nilai [id => value]
     * @param string $type 'benefit' atau 'cost'
     * @return array Array nilai normalisasi [id => normalized_value]
     */
    private function normalizeSAW(array $values, string $type): array
    {
        $normalized = [];
        
        // Filter nilai yang lebih besar dari 0
        $nonZeroValues = array_filter($values, function($v) {
            return $v > 0;
        });
        
        // Jika tidak ada nilai, kembalikan array kosong dengan nilai 0
        if (empty($nonZeroValues)) {
            foreach ($values as $id => $value) {
                $normalized[$id] = 0;
            }
            return $normalized;
        }
        
        if ($type === 'benefit') {
            // Untuk kriteria benefit: r_ij = x_ij / max(x_ij)
            $max = max($nonZeroValues);
            
            foreach ($values as $id => $value) {
                $normalized[$id] = $max > 0 ? $value / $max : 0;
            }
        } else {
            // Untuk kriteria cost: r_ij = min(x_ij) / x_ij
            $min = min($nonZeroValues);
            
            foreach ($values as $id => $value) {
                $normalized[$id] = $value > 0 ? $min / $value : 0;
            }
        }
        
        return $normalized;
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
    
    /**
     * Hitung ranking lengkap dengan detail untuk kelompok
     * 
     * @param array|null $filterGroupIds Optional array of group IDs to filter
     * @return array
     */
    public function getGroupRankingWithDetails(?array $filterGroupIds = null): array
    {
        $totals = $this->computeGroupTotals();
        
        // Filter by group IDs if provided
        if ($filterGroupIds !== null && !empty($filterGroupIds)) {
            $totals = array_intersect_key($totals, array_flip($filterGroupIds));
        }
        
        arsort($totals); // Urutkan dari tertinggi ke terendah
        
        $ranking = [];
        $rank = 1;
        
        foreach ($totals as $groupId => $totalScore) {
            $group = Group::with(['classRoom', 'leader', 'members'])->find($groupId);
            
            if ($group) {
                $ranking[] = [
                    'rank' => $rank,
                    'group_id' => $groupId,
                    'group' => $group,
                    'total_score' => $totalScore,
                    'completion_rate' => $group->getTargetCompletionRate()
                ];
                $rank++;
            }
        }
        
        return $ranking;
    }
    
    /**
     * Hitung ranking lengkap dengan detail untuk mahasiswa
     * 
     * @param int|null $classRoomId Filter by class room
     * @param array|null $filterStudentIds Optional array of student IDs to filter
     * @return array
     */
    public function getStudentRankingWithDetails($classRoomId = null, ?array $filterStudentIds = null): array
    {
        $totals = $this->computeStudentTotals($classRoomId);
        
        // Filter by student IDs if provided
        if ($filterStudentIds !== null && !empty($filterStudentIds)) {
            $totals = array_intersect_key($totals, array_flip($filterStudentIds));
        }
        arsort($totals); // Urutkan dari tertinggi ke terendah
        
        $ranking = [];
        $rank = 1;
        
        foreach ($totals as $studentId => $totalScore) {
            $student = User::with(['groups.classRoom'])->find($studentId);
            
            if ($student) {
                $ranking[] = [
                    'rank' => $rank,
                    'student_id' => $studentId,
                    'student' => $student,
                    'total_score' => $totalScore,
                    'group' => $student->groups->first()
                ];
                $rank++;
            }
        }
        
        return $ranking;
    }
    
    /**
     * Update total_score untuk semua kelompok
     */
    public function updateGroupTotalScores(): void
    {
        $totals = $this->computeGroupTotals();
        
        foreach ($totals as $groupId => $totalScore) {
            Group::where('id', $groupId)->update(['total_score' => $totalScore]);
        }
    }
    
    /**
     * Update ranking untuk semua kelompok berdasarkan kelas
     */
    public function updateGroupRankings(): void
    {
        // Update total scores terlebih dahulu
        $this->updateGroupTotalScores();
        
        // Get all class rooms
        $classRooms = \App\Models\ClassRoom::all();
        
        foreach ($classRooms as $classRoom) {
            // Get groups in this class, sorted by total_score
            $groups = Group::where('class_room_id', $classRoom->id)
                ->orderBy('total_score', 'desc')
                ->get();
            
            $rank = 1;
            foreach ($groups as $group) {
                $group->update(['ranking' => $rank]);
                $rank++;
            }
        }
    }
}
