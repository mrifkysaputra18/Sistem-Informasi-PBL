<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{WeeklyTarget, WeeklyTargetReview, User, Group};

echo "📝 MENGISI REVIEW DAN NILAI TARGET\n\n";

// Get dosen (reviewer)
$dosen = User::where('role', 'dosen')->first();

// Get best group (Kelompok 4 TI-3B)
$bestGroup = Group::whereHas('classRoom', function($q) {
    $q->where('name', 'like', '%3B%');
})->where('name', 'Kelompok 4')->first();

// Get best students
$rifky = User::where('name', 'like', '%Rifky%')->first();
$djenar = User::where('name', 'like', '%Djenar%')->first();

echo "🏆 Kelompok Terbaik: {$bestGroup->name} (ID: {$bestGroup->id})\n";
echo "🥇 Mahasiswa Terbaik #1: {$rifky->name} (ID: {$rifky->id})\n";
echo "🥈 Mahasiswa Terbaik #2: {$djenar->name} (ID: {$djenar->id})\n\n";

// Get all targets
$targets = WeeklyTarget::with(['group', 'completedByUser'])->get();

echo "📊 Total targets: {$targets->count()}\n\n";

$reviewCount = 0;
$feedbacks = [
    'excellent' => [
        'Pekerjaan sangat baik! Target tercapai dengan sempurna.',
        'Hasil kerja luar biasa! Dokumentasi lengkap dan rapi.',
        'Excellent work! Semua requirement terpenuhi dengan baik.',
        'Outstanding! Progress sangat memuaskan.',
        'Kerja yang sangat bagus! Keep up the good work!',
    ],
    'good' => [
        'Pekerjaan baik, target tercapai dengan baik.',
        'Good job! Hasil kerja sudah sesuai ekspektasi.',
        'Bagus! Dokumentasi cukup lengkap.',
        'Well done! Progress sesuai target.',
        'Kerja yang bagus! Teruskan!',
    ],
    'average' => [
        'Cukup baik, namun masih ada yang perlu ditingkatkan.',
        'Hasil kerja cukup, perlu lebih detail.',
        'Lumayan, tapi bisa lebih baik lagi.',
        'Progress cukup, tingkatkan kualitas dokumentasi.',
        'Acceptable, namun perlu improvement.',
    ],
];

$suggestions = [
    'excellent' => [
        'Pertahankan kualitas kerja ini!',
        'Teruskan konsistensi dan kualitas kerja!',
        'Excellent! Jadikan contoh untuk minggu depan.',
        'Keep maintaining this quality!',
        'Great! Continue with this momentum.',
    ],
    'good' => [
        'Tingkatkan detail dokumentasi.',
        'Perhatikan kelengkapan laporan.',
        'Bisa lebih baik lagi untuk minggu depan.',
        'Good, but can be improved.',
        'Tingkatkan konsistensi kerja.',
    ],
    'average' => [
        'Perlu lebih detail dalam dokumentasi.',
        'Tingkatkan kualitas dan kelengkapan.',
        'Perhatikan requirement yang diminta.',
        'Needs more attention to details.',
        'Perbaiki kualitas untuk minggu depan.',
    ],
];

foreach ($targets as $target) {
    // Determine score based on group and student
    $score = 75; // Base score
    $category = 'average';
    
    // Best group gets higher score
    if ($target->group_id == $bestGroup->id) {
        $score = rand(92, 98);
        $category = 'excellent';
    }
    // Best students get higher score
    elseif ($target->completed_by == $rifky->id || $target->completed_by == $djenar->id) {
        $score = rand(90, 96);
        $category = 'excellent';
    }
    // Good performers
    elseif (rand(1, 100) > 60) {
        $score = rand(80, 89);
        $category = 'good';
    }
    // Average performers
    else {
        $score = rand(70, 79);
        $category = 'average';
    }
    
    // Create or update review
    WeeklyTargetReview::updateOrCreate(
        ['weekly_target_id' => $target->id],
        [
            'reviewer_id' => $dosen->id,
            'score' => $score,
            'feedback' => $feedbacks[$category][array_rand($feedbacks[$category])],
            'suggestions' => $suggestions[$category][array_rand($suggestions[$category])],
            'status' => $score >= 85 ? 'approved' : ($score >= 75 ? 'approved' : 'needs_revision'),
        ]
    );
    
    // Update target as reviewed
    $target->update([
        'is_reviewed' => true,
        'reviewed_at' => now(),
        'reviewer_id' => $dosen->id,
        'submission_status' => $score >= 85 ? 'approved' : ($score >= 75 ? 'approved' : 'revision'),
    ]);
    
    $reviewCount++;
}

echo "✅ {$reviewCount} review berhasil dibuat!\n\n";

// Show summary
$excellentCount = WeeklyTargetReview::where('score', '>=', 90)->count();
$goodCount = WeeklyTargetReview::whereBetween('score', [80, 89])->count();
$averageCount = WeeklyTargetReview::whereBetween('score', [70, 79])->count();

echo "📊 Ringkasan Review:\n";
echo "   🏆 Excellent (90-100): {$excellentCount}\n";
echo "   ✅ Good (80-89): {$goodCount}\n";
echo "   📝 Average (70-79): {$averageCount}\n\n";

// Show best group's scores
$bestGroupTargets = WeeklyTarget::where('group_id', $bestGroup->id)->with('review')->get();
$bestGroupAvg = $bestGroupTargets->avg(function($t) {
    return $t->review ? $t->review->score : 0;
});

echo "🏆 Kelompok Terbaik ({$bestGroup->name}):\n";
echo "   Rata-rata nilai: " . round($bestGroupAvg, 2) . "\n\n";

echo "🎉 Selesai! Semua target sudah direview dengan nilai yang sesuai ranking.\n";
