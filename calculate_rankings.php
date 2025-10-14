<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{Group, User, Criterion, GroupScore, StudentScore};
use Illuminate\Support\Facades\DB;

echo "🏆 MENGHITUNG RANKING KELOMPOK & MAHASISWA TERBAIK\n\n";

// ========================================
// RANKING KELOMPOK TERBAIK
// ========================================
echo "📊 RANKING KELOMPOK TERBAIK (Metode SAW)\n";
echo str_repeat("=", 80) . "\n\n";

$groupCriteria = Criterion::where('segment', 'group')->get();
$groups = Group::with(['scores', 'classRoom'])->get();

$groupRankings = [];

foreach ($groups as $group) {
    $totalScore = 0;
    $details = [];
    
    foreach ($groupCriteria as $criterion) {
        $score = GroupScore::where('group_id', $group->id)
                          ->where('criterion_id', $criterion->id)
                          ->value('skor') ?? 0;
        
        // Normalisasi (benefit: nilai/max)
        $normalized = $score / 100; // Asumsi max = 100
        $weighted = $normalized * $criterion->bobot;
        
        $totalScore += $weighted;
        $details[$criterion->nama] = [
            'score' => $score,
            'normalized' => $normalized,
            'weighted' => $weighted,
        ];
    }
    
    $groupRankings[] = [
        'group' => $group,
        'total_score' => $totalScore,
        'details' => $details,
    ];
}

// Sort by total_score descending
usort($groupRankings, function($a, $b) {
    return $b['total_score'] <=> $a['total_score'];
});

// Display top 10 groups
echo "TOP 10 KELOMPOK TERBAIK:\n\n";
foreach (array_slice($groupRankings, 0, 10) as $rank => $data) {
    $group = $data['group'];
    $score = $data['total_score'];
    
    echo sprintf(
        "%2d. %-20s | Kelas: %-10s | Skor: %.4f\n",
        $rank + 1,
        $group->name,
        $group->classRoom->name ?? '-',
        $score
    );
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ========================================
// RANKING MAHASISWA TERBAIK
// ========================================
echo "🎓 RANKING MAHASISWA TERBAIK (Metode SAW)\n";
echo str_repeat("=", 80) . "\n\n";

$studentCriteria = Criterion::where('segment', 'student')->get();
$students = User::where('role', 'mahasiswa')->with(['studentScores', 'classRoom'])->get();

$studentRankings = [];

foreach ($students as $student) {
    $totalScore = 0;
    $details = [];
    
    foreach ($studentCriteria as $criterion) {
        $score = StudentScore::where('user_id', $student->id)
                            ->where('criterion_id', $criterion->id)
                            ->value('skor') ?? 0;
        
        // Normalisasi (benefit: nilai/max)
        $normalized = $score / 100; // Asumsi max = 100
        $weighted = $normalized * $criterion->bobot;
        
        $totalScore += $weighted;
        $details[$criterion->nama] = [
            'score' => $score,
            'normalized' => $normalized,
            'weighted' => $weighted,
        ];
    }
    
    $studentRankings[] = [
        'student' => $student,
        'total_score' => $totalScore,
        'details' => $details,
    ];
}

// Sort by total_score descending
usort($studentRankings, function($a, $b) {
    return $b['total_score'] <=> $a['total_score'];
});

// Display top 10 students
echo "TOP 10 MAHASISWA TERBAIK:\n\n";
foreach (array_slice($studentRankings, 0, 10) as $rank => $data) {
    $student = $data['student'];
    $score = $data['total_score'];
    
    echo sprintf(
        "%2d. %-30s | NIM: %-15s | Kelas: %-10s | Skor: %.4f\n",
        $rank + 1,
        $student->name,
        $student->nim ?? '-',
        $student->classRoom->name ?? '-',
        $score
    );
}

echo "\n" . str_repeat("=", 80) . "\n\n";

// ========================================
// STATISTIK
// ========================================
echo "📈 STATISTIK:\n\n";

// Group statistics
$groupScores = array_column($groupRankings, 'total_score');
echo "Kelompok:\n";
echo "  - Rata-rata: " . number_format(array_sum($groupScores) / count($groupScores), 4) . "\n";
echo "  - Tertinggi: " . number_format(max($groupScores), 4) . " (" . $groupRankings[0]['group']->name . ")\n";
echo "  - Terendah: " . number_format(min($groupScores), 4) . " (" . end($groupRankings)['group']->name . ")\n\n";

// Student statistics
$studentScores = array_column($studentRankings, 'total_score');
echo "Mahasiswa:\n";
echo "  - Rata-rata: " . number_format(array_sum($studentScores) / count($studentScores), 4) . "\n";
echo "  - Tertinggi: " . number_format(max($studentScores), 4) . " (" . $studentRankings[0]['student']->name . ")\n";
echo "  - Terendah: " . number_format(min($studentScores), 4) . " (" . end($studentRankings)['student']->name . ")\n\n";

echo "🎉 Perhitungan selesai!\n";
