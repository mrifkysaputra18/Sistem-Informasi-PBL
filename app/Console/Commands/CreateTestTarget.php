<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TargetMingguan;
use App\Models\Kelompok;
use App\Models\Pengguna;

class CreateTestTarget extends Command
{
    protected $signature = 'test:create-target';
    protected $description = 'Create a test weekly target for testing submission flow';

    public function handle()
    {
        $this->info('🎯 Creating test weekly target...');
        $this->newLine();

        // Get first group
        $group = Kelompok::first();
        if (!$group) {
            $this->error('❌ No group found! Please create a group first.');
            return 1;
        }

        // Get dosen user
        $dosen = Pengguna::where('role', 'dosen')->first();
        if (!$dosen) {
            $this->error('❌ No dosen user found!');
            return 1;
        }

        // Find next available week number
        $lastWeek = TargetMingguan::where('group_id', $group->id)
            ->max('week_number') ?? 0;
        $nextWeek = $lastWeek + 1;

        // Create target
        $target = TargetMingguan::create([
            'group_id' => $group->id,
            'week_number' => $nextWeek,
            'title' => 'Test Target - Implementasi Fitur ' . $nextWeek,
            'description' => 'Target test untuk validasi flow submission dari mahasiswa ke dosen. Silakan upload progress untuk target ini.',
            'deadline' => now()->addDays(7),
            'is_open' => true,
            'submission_status' => 'pending',
            'is_completed' => false,
            'is_reviewed' => false,
            'created_by' => $dosen->id,
        ]);

        $this->info('✅ Test target created successfully!');
        $this->newLine();
        
        $this->table(
            ['Property', 'Value'],
            [
                ['ID', $target->id],
                ['Group', $group->name],
                ['Week', $target->week_number],
                ['Title', $target->title],
                ['Deadline', $target->deadline->format('Y-m-d H:i')],
                ['Status', $target->submission_status],
                ['Created By', $dosen->name],
            ]
        );

        $this->newLine();
        $this->info('📝 Next steps:');
        $this->info('   1. Login as mahasiswa (mahasiswa@politala.ac.id / password)');
        $this->info('   2. Go to Dashboard → Target Mingguan');
        $this->info('   3. Upload progress for Week ' . $target->week_number);
        $this->info('   4. Login as dosen (dosen@politala.ac.id / password)');
        $this->info('   5. Go to Review Target Mingguan');
        $this->info('   6. Verify submission appears in the list');

        return 0;
    }
}



