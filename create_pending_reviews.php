<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\WeeklyTarget;

echo "📝 MEMBUAT TARGET YANG PERLU DIREVIEW\n\n";

// Get all targets
$targets = WeeklyTarget::all();

echo "Total targets: {$targets->count()}\n\n";

// Update 20 targets to be pending review (submitted but not reviewed)
$targetsToUpdate = $targets->random(min(20, $targets->count()));

$updated = 0;
foreach ($targetsToUpdate as $target) {
    $target->update([
        'submission_status' => 'submitted',
        'is_reviewed' => false,
        'reviewed_at' => null,
        'reviewer_id' => null,
    ]);
    $updated++;
}

echo "✅ {$updated} target diubah menjadi status 'submitted' (perlu direview)\n";
echo "\n📋 Target yang perlu direview sekarang: " . WeeklyTarget::where('is_reviewed', false)->whereIn('submission_status', ['submitted', 'late'])->count() . "\n";
echo "\n🎉 Selesai! Silakan cek halaman Review Target di dashboard dosen.\n";
