<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{WeeklyTarget, ClassRoom, User};

echo "📊 CEK STATISTIK TARGET\n\n";

// Get Billy
$billy = User::where('email', 'billy.sabella@politala.ac.id')->first();
echo "✅ Dosen: {$billy->name} (ID: {$billy->id})\n\n";

// Get kelas yang diampu Billy
$myClassRoomIds = ClassRoom::where('dosen_id', $billy->id)->pluck('id');
echo "📚 Kelas yang diampu Billy: " . $myClassRoomIds->count() . " kelas\n";
echo "   IDs: " . $myClassRoomIds->implode(', ') . "\n\n";

// Get targets dari kelas Billy
$targets = WeeklyTarget::whereHas('group', function($q) use ($myClassRoomIds) {
    $q->whereIn('class_room_id', $myClassRoomIds);
})->get();

echo "📋 Total Target: {$targets->count()}\n\n";

// Group by submission_status
$statusCounts = $targets->groupBy('submission_status')->map->count();

echo "📊 Breakdown by Status:\n";
foreach ($statusCounts as $status => $count) {
    echo "   - {$status}: {$count}\n";
}

echo "\n📈 Statistik (seperti di controller):\n";
echo "   Total: {$targets->count()}\n";
echo "   Submitted: " . $targets->where('submission_status', 'submitted')->count() . "\n";
echo "   Approved: " . $targets->where('submission_status', 'approved')->count() . "\n";
echo "   Revision: " . $targets->where('submission_status', 'revision')->count() . "\n";
echo "   Pending: " . $targets->where('submission_status', 'pending')->count() . "\n";
echo "   Late: " . $targets->where('submission_status', 'late')->count() . "\n";

$submittedCount = $targets->whereIn('submission_status', ['submitted', 'approved', 'revision'])->count();
$percentage = $targets->count() > 0 ? round(($submittedCount / $targets->count()) * 100) : 0;
echo "\n   Progress: {$percentage}%\n";
