<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TargetMingguan;
use App\Models\Kelompok;
use App\Models\Pengguna;

class CreateTestTargetPending extends Command
{
    protected $signature = 'create:test-target-pending';
    protected $description = 'Create test target with PENDING status (to test Edit/Delete buttons)';

    public function handle()
    {
        $this->info('🎯 Creating test target with PENDING status...');
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

        // Create target with PENDING status (not submitted yet)
        $target = TargetMingguan::create([
            'group_id' => $group->id,
            'week_number' => 5,
            'title' => 'TEST TARGET - BELUM DISUBMIT (untuk test Edit/Hapus)',
            'description' => 'Target ini dibuat untuk testing tombol Edit dan Hapus. Status: PENDING (belum disubmit mahasiswa).',
            'deadline' => now()->addDays(7),
            'is_open' => true,
            'submission_status' => 'pending', // PENTING: STATUS PENDING
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
                ['Status', $target->submission_status],
                ['Is Completed', $target->is_completed ? 'Yes' : 'No'],
                ['Deadline', $target->deadline->format('Y-m-d H:i')],
                ['Created By', $dosen->name],
            ]
        );

        $this->newLine();
        $this->info('📝 Test Instructions:');
        $this->info('   1. Login as dosen: dosen@politala.ac.id / password');
        $this->info('   2. Go to: http://localhost:8000/targets');
        $this->info('   3. Look for target: "TEST TARGET - BELUM DISUBMIT"');
        $this->info('   4. In Aksi column, you should see:');
        $this->info('      - [Detail] (blue)');
        $this->info('      - [Edit] (yellow) ← THIS ONE!');
        $this->info('      - [Hapus] (red) ← THIS ONE!');
        $this->newLine();
        $this->info('✨ These buttons appear because status is PENDING (not submitted yet)');

        return 0;
    }
}



