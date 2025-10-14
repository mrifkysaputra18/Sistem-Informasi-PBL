<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\{WeeklyTarget, User};

echo "🔍 CEK CREATOR TARGET\n\n";

// Get Billy Sabella
$billy = User::where('email', 'billy.sabella@politala.ac.id')->first();
echo "✅ Dosen yang login: {$billy->name} (ID: {$billy->id})\n\n";

// Get distinct created_by
$creators = WeeklyTarget::select('created_by')->distinct()->get();

echo "📋 Target dibuat oleh:\n";
foreach ($creators as $creator) {
    $dosen = User::find($creator->created_by);
    $count = WeeklyTarget::where('created_by', $creator->created_by)->count();
    echo "   - ID {$creator->created_by}: " . ($dosen ? $dosen->name : 'Unknown') . " ({$count} targets)\n";
}

// Count targets by Billy
$billyTargets = WeeklyTarget::where('created_by', $billy->id)->count();
echo "\n📊 Target yang dibuat oleh Billy: {$billyTargets}\n";

if ($billyTargets == 0) {
    echo "\n❌ MASALAH: Billy tidak punya target!\n";
    echo "🔧 Solusi: Update semua target agar created_by = Billy\n";
}
