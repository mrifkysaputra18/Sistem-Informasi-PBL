<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{Group, WeeklyTarget, WeeklyTargetReview, User, Criterion, GroupScore, StudentScore};
use Carbon\Carbon;

echo "🎯 Membuat Target Mingguan dan Nilai...\n\n";

// Get first dosen
$dosen = User::where('role', 'dosen')->first();
if (!$dosen) {
    die("❌ Tidak ada dosen di database!\n");
}

// Get all groups
$groups = Group::with('members')->get();
echo "📊 Ditemukan {$groups->count()} kelompok\n";

// Get criteria
$groupCriteria = Criterion::where('segment', 'group')->get();
$studentCriteria = Criterion::where('segment', 'student')->get();

echo "📋 Kriteria Kelompok: {$groupCriteria->count()}\n";
echo "📋 Kriteria Mahasiswa: {$studentCriteria->count()}\n\n";

$targetCount = 0;
$submissionCount = 0;
$groupScoreCount = 0;
$studentScoreCount = 0;

// Create targets for each group (4 weeks)
foreach ($groups as $group) {
    echo "Memproses kelompok: {$group->name}\n";
    
    for ($week = 1; $week <= 4; $week++) {
        // Create target
        $randomMember = $group->members->random();
        $completedAt = Carbon::now()->subDays(rand(1, 7));
        
        $target = WeeklyTarget::create([
            'group_id' => $group->id,
            'title' => "Target Minggu {$week} - {$group->name}",
            'description' => "Pengembangan proyek minggu ke-{$week}",
            'week_number' => $week,
            'deadline' => Carbon::now()->addWeeks($week),
            'created_by' => $dosen->id,
            'is_open' => true,
            'submission_status' => 'approved',
            'is_completed' => true,
            'completed_at' => $completedAt,
            'completed_by' => $randomMember->user_id,
            'submission_notes' => 'Submission untuk minggu ' . $week,
            'evidence_file' => 'submissions/dummy_week_' . $week . '.pdf',
            'is_reviewed' => true,
            'reviewed_at' => $completedAt->addHours(2),
            'reviewer_id' => $dosen->id,
        ]);
        $targetCount++;
        $submissionCount++;
        
    }
    
    // Create group scores (1 score per criterion per group)
    foreach ($groupCriteria as $criterion) {
        GroupScore::create([
            'group_id' => $group->id,
            'criterion_id' => $criterion->id,
            'skor' => rand(70, 100),
        ]);
        $groupScoreCount++;
    }
    
    // Create student scores for each member
    foreach ($group->members as $member) {
        $user = User::find($member->user_id);
        if ($user) {
            foreach ($studentCriteria as $criterion) {
                StudentScore::create([
                    'user_id' => $user->id,
                    'criterion_id' => $criterion->id,
                    'skor' => rand(70, 100),
                ]);
                $studentScoreCount++;
            }
        }
    }
    
    echo "  ✅ 4 target, " . $groupCriteria->count() . " group scores, " . ($studentCriteria->count() * $group->members->count()) . " student scores\n";
}

echo "\n🎉 Selesai!\n";
echo "📊 Ringkasan:\n";
echo "  - Target dibuat: {$targetCount}\n";
echo "  - Submission dibuat: {$submissionCount}\n";
echo "  - Group scores: {$groupScoreCount}\n";
echo "  - Student scores: {$studentScoreCount}\n";
