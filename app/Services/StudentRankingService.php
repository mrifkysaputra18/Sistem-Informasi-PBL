<?php

namespace App\Services;

use App\Models\{Pengguna, Kriteria, NilaiMahasiswa, RuangKelas};
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentRankingService
{
    /**
     * Calculate and update rankings for all students in a class
     * 
     * @param int $classRoomId
     * @return void
     */
    public function calculateClassRankings(int $classRoomId): void
    {
        $students = Pengguna::where('class_room_id', $classRoomId)
            ->where('role', 'mahasiswa')
            ->with(['groupMembers.group', 'studentScores'])
            ->get();

        if ($students->isEmpty()) {
            return;
        }

        // Get all student criteria
        $criteria = Kriteria::where('segment', 'student')->get();

        if ($criteria->isEmpty()) {
            Log::warning('No student criteria found for ranking calculation');
            return;
        }

        // Calculate scores for each criterion for each student
        foreach ($students as $student) {
            $this->calculateStudentScores($student, $criteria);
        }

        // Calculate weighted total scores and rank students
        $this->rankStudents($students, $criteria);

        Log::info("Student ranking calculated for class {$classRoomId}", [
            'total_students' => $students->count(),
            'criteria_used' => $criteria->count(),
        ]);
    }

    /**
     * Calculate scores for each criterion for a student
     * 
     * @param Pengguna $student
     * @param Collection $criteria
     * @return void
     */
    private function calculateStudentScores(Pengguna $student, Collection $criteria): void
    {
        foreach ($criteria as $criterion) {
            $score = match ($criterion->nama) {
                'Nilai Akhir PBL' => $this->calculateFinalScore($student),
                'Penilaian Teman' => $this->calculatePeerReviewScore($student),
                'Kehadiran' => $this->calculateAttendanceScore($student),
                default => 0,
            };

            // Save or update score
            NilaiMahasiswa::updateOrCreate(
                [
                    'user_id' => $student->id,
                    'criterion_id' => $criterion->id,
                ],
                [
                    'skor' => $score,
                ]
            );
        }
    }

    /**
     * Calculate Final Score (Nilai Akhir PBL)
     * Based on group's final score and individual contributions
     * 
     * @param Pengguna $student
     * @return float Score 0-100
     */
    private function calculateFinalScore(Pengguna $student): float
    {
        // Get student's group
        $groupMember = $student->groupMembers()->with('group.weeklyTargets.review')->first();
        
        if (!$groupMember || !$groupMember->group) {
            return 0;
        }

        $group = $groupMember->group;

        // Get average score from group's approved targets
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

        $avgScore = $approvedTargets->count() > 0 
            ? $totalScore / $approvedTargets->count()
            : 0;

        // Individual contribution factor (can be adjusted based on peer review)
        // For now, use group score directly
        return min(100, $avgScore);
    }

    /**
     * Calculate Peer Review Score (Penilaian Teman)
     * Based on peer evaluation from other team members
     * 
     * @param Pengguna $student
     * @return float Score 0-100
     */
    private function calculatePeerReviewScore(Pengguna $student): float
    {
        // TODO: Implement peer review system
        // For now, return a placeholder based on group participation
        
        // Check if student is active in group
        $groupMember = $student->groupMembers()->first();
        
        if (!$groupMember || $groupMember->status !== 'active') {
            return 50; // Inactive member gets lower score
        }

        // Check if student is a leader (gets bonus)
        if ($groupMember->is_leader) {
            return 85;
        }

        return 75; // Default placeholder for active members
    }

    /**
     * Calculate Attendance Score (Kehadiran)
     * Based on attendance records
     * 
     * @param Pengguna $student
     * @return float Score 0-100
     */
    private function calculateAttendanceScore(Pengguna $student): float
    {
        // TODO: Implement attendance tracking system
        // For now, return a placeholder
        
        // This should be calculated from an attendance table
        // Formula: (Present / Total Sessions) * 100
        
        return 90; // Placeholder - assumes good attendance
    }

    /**
     * Calculate weighted total scores and rank students
     * 
     * @param Collection $students
     * @param Collection $criteria
     * @return void
     */
    private function rankStudents(Collection $students, Collection $criteria): void
    {
        $studentScores = [];

        foreach ($students as $student) {
            $totalScore = 0;

            foreach ($criteria as $criterion) {
                $score = NilaiMahasiswa::where('user_id', $student->id)
                    ->where('criterion_id', $criterion->id)
                    ->value('skor') ?? 0;

                // Apply weight (bobot is already in decimal format)
                $weightedScore = $score * $criterion->bobot;
                $totalScore += $weightedScore;
            }

            $studentScores[$student->id] = $totalScore;
        }

        // Sort by score descending and assign ranks within the class
        arsort($studentScores);
        $rank = 1;

        foreach ($studentScores as $studentId => $score) {
            // Note: Pengguna table doesn't have total_score and ranking columns yet
            // You may need to add these columns or store them separately
            // For now, we'll just log the rankings
            
            Log::info("Student ranking", [
                'student_id' => $studentId,
                'score' => round($score, 2),
                'rank' => $rank,
            ]);

            $rank++;
        }
    }

    /**
     * Get ranking details for a student
     * 
     * @param Pengguna $student
     * @return array
     */
    public function getStudentRankingDetails(Pengguna $student): array
    {
        $criteria = Kriteria::where('segment', 'student')->get();
        $scores = NilaiMahasiswa::where('user_id', $student->id)
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
            'student_id' => $student->id,
            'student_name' => $student->name,
            'nim' => $student->nim,
            'total_score' => round($totalWeightedScore, 2),
            'criteria_scores' => $details,
        ];
    }

    /**
     * Calculate ranking for all active classes
     * 
     * @return void
     */
    public function calculateAllClassRankings(): void
    {
        $classes = RuangKelas::where('is_active', true)->pluck('id');

        foreach ($classes as $classId) {
            $this->calculateClassRankings($classId);
        }
    }
}
