<?php

namespace App\Console\Commands;

use App\Models\AnggotaKelompok;
use App\Models\Kelompok;
use App\Models\Pengguna;
use App\Models\RuangKelas;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoGroupStudents extends Command
{
    protected $signature = 'students:auto-group {--dry-run : Tampilkan saja tanpa eksekusi}';
    protected $description = 'Otomatis kelompokkan mahasiswa yang belum memiliki kelompok';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        $this->info('=== ANALISIS MAHASISWA TANPA KELOMPOK ===');
        $this->newLine();

        $classes = RuangKelas::with(['students', 'groups.members'])->get();
        
        $totalUngrouped = 0;
        $totalGroupsCreated = 0;

        foreach ($classes as $class) {
            // Get all student IDs in this class
            $studentIds = $class->students->pluck('id')->toArray();
            
            // Get all student IDs that are already in groups for this class
            $groupedIds = $class->groups->flatMap(fn($g) => $g->members->pluck('user_id'))->toArray();
            
            // Find ungrouped students
            $ungroupedIds = array_diff($studentIds, $groupedIds);
            $ungroupedStudents = Pengguna::whereIn('id', $ungroupedIds)->get();
            
            if ($ungroupedStudents->isEmpty()) {
                $this->line("✓ Kelas {$class->name}: Semua mahasiswa sudah memiliki kelompok");
                continue;
            }

            $this->warn("Kelas {$class->name}: {$ungroupedStudents->count()} mahasiswa belum memiliki kelompok");
            $totalUngrouped += $ungroupedStudents->count();
            
            // List ungrouped students
            foreach ($ungroupedStudents as $s) {
                $this->line("  - {$s->name} ({$s->nim})");
            }
            
            if (!$isDryRun) {
                // Create groups (max 5 members per group)
                $chunks = $ungroupedStudents->chunk(5);
                $existingGroupCount = $class->groups->count();
                
                foreach ($chunks as $index => $members) {
                    $groupNumber = $existingGroupCount + $index + 1;
                    $groupName = "Kelompok {$groupNumber}";
                    
                    // Check if group name exists
                    while (Kelompok::where('name', $groupName)->where('class_room_id', $class->id)->exists()) {
                        $groupNumber++;
                        $groupName = "Kelompok {$groupNumber}";
                    }
                    
                    DB::beginTransaction();
                    try {
                        $leader = $members->first();
                        
                        // Create group
                        $group = Kelompok::create([
                            'name' => $groupName,
                            'class_room_id' => $class->id,
                            'max_members' => 5,
                            'leader_id' => $leader->id,
                        ]);
                        
                        // Add members
                        foreach ($members as $i => $member) {
                            AnggotaKelompok::create([
                                'group_id' => $group->id,
                                'user_id' => $member->id,
                                'is_leader' => $i === 0,
                                'status' => 'active',
                            ]);
                        }
                        
                        DB::commit();
                        $totalGroupsCreated++;
                        $this->info("  → Dibuat: {$groupName} ({$members->count()} anggota, ketua: {$leader->name})");
                        
                    } catch (\Exception $e) {
                        DB::rollback();
                        $this->error("  ✗ Gagal membuat {$groupName}: {$e->getMessage()}");
                    }
                }
            }
            
            $this->newLine();
        }

        $this->newLine();
        $this->info('=== RINGKASAN ===');
        $this->line("Total mahasiswa tanpa kelompok: {$totalUngrouped}");
        
        if ($isDryRun) {
            $this->warn('Mode dry-run: Tidak ada perubahan yang dibuat.');
            $this->line('Jalankan tanpa --dry-run untuk membuat kelompok otomatis.');
        } else {
            $this->info("Total kelompok baru dibuat: {$totalGroupsCreated}");
        }

        return Command::SUCCESS;
    }
}
