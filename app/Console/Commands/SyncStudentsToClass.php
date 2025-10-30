<?php

namespace App\Console\Commands;

use App\Models\Pengguna;
use App\Models\AnggotaKelompok;
use App\Models\RuangKelas;
use App\Models\PeriodeAkademik;
use Illuminate\Console\Command;

class SyncStudentsToClass extends Command
{
    protected $signature = 'students:sync-class 
                            {--user_id= : Specific user ID to sync}
                            {--group_id= : Sync all members of specific group}
                            {--sync-periods : Also sync class rooms to academic periods}';

    protected $description = 'Sync mahasiswa class_room_id based on their group membership and optionally sync classes to academic periods';

    public function handle()
    {
        $this->info('🔄 Starting student-class synchronization...');
        $this->newLine();

        $userId = $this->option('user_id');
        $groupId = $this->option('group_id');

        $query = GroupMember::with(['user', 'group.classRoom']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $groupMembers = $query->get();

        if ($groupMembers->isEmpty()) {
            $this->warn('⚠️  No group members found to sync.');
            return;
        }

        $this->info("Found {$groupMembers->count()} group member(s) to sync.");
        $this->newLine();

        $synced = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($groupMembers as $member) {
            try {
                $user = $member->user;
                $group = $member->group;
                $classRoom = $group->classRoom;

                if (!$user) {
                    $this->error("❌ User not found for member ID: {$member->id}");
                    $errors++;
                    continue;
                }

                if (!$classRoom) {
                    $this->warn("⚠️  Group '{$group->name}' has no class assigned. Skipping {$user->name}");
                    $skipped++;
                    continue;
                }

                if ($user->class_room_id == $classRoom->id) {
                    $this->line("✓ {$user->name} already in class {$classRoom->name}");
                    $skipped++;
                    continue;
                }

                $oldClass = $user->class_room_id 
                    ? "Class ID: {$user->class_room_id}" 
                    : "No class";
                
                $user->update(['class_room_id' => $classRoom->id]);

                $this->info("✅ Synced: {$user->name}");
                $this->line("   From: {$oldClass} → To: {$classRoom->name} (ID: {$classRoom->id})");
                
                $synced++;
            } catch (\Exception $e) {
                $this->error("❌ Error syncing member ID {$member->id}: " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        $this->info('═══════════════════════════════════');
        $this->info('📊 Synchronization Summary:');
        $this->info('═══════════════════════════════════');
        $this->line("✅ Synced:  {$synced}");
        $this->line("⏭️  Skipped: {$skipped}");
        $this->line("❌ Errors:  {$errors}");
        $this->info('═══════════════════════════════════');

        if ($synced > 0) {
            $this->newLine();
            $this->info('🔍 Verification:');
            
            $nullClassStudents = Pengguna::where('role', 'mahasiswa')
                ->whereNull('class_room_id')
                ->whereHas('groupMemberships')
                ->count();
            
            if ($nullClassStudents > 0) {
                $this->warn("⚠️  Warning: {$nullClassStudents} mahasiswa still have null class_room_id but are in groups!");
                $this->line("   Run: php artisan students:sync-class (to fix all)");
            } else {
                $this->info('✅ All group members are properly synced to their classes!');
            }
        }

        if ($this->option('sync-periods')) {
            $this->newLine();
            $this->syncAcademicPeriods();
        }
    }

    protected function syncAcademicPeriods()
    {
        $this->info('🔄 Syncing class rooms to academic periods...');
        $this->newLine();

        $classRooms = RuangKelas::whereNull('academic_period_id')->get();

        if ($classRooms->isEmpty()) {
            $this->info('✅ All class rooms already have academic periods assigned!');
            return;
        }

        $this->info("Found {$classRooms->count()} class room(s) without academic period.");
        $this->newLine();

        $synced = 0;
        $skipped = 0;

        foreach ($classRooms as $classRoom) {
            $academicPeriod = AcademicPeriod::where('semester_number', $classRoom->semester)
                ->where('is_active', true)
                ->first();

            if (!$academicPeriod) {
                $this->warn("⚠️  No active academic period found for semester {$classRoom->semester}. Skipping {$classRoom->name}");
                $skipped++;
                continue;
            }

            $classRoom->update(['academic_period_id' => $academicPeriod->id]);

            $this->info("✅ Synced: {$classRoom->name} (Semester {$classRoom->semester})");
            $this->line("   Academic Period: {$academicPeriod->name}");
            $synced++;
        }

        $this->newLine();
        $this->info('═══════════════════════════════════');
        $this->info('📊 Academic Period Sync Summary:');
        $this->info('═══════════════════════════════════');
        $this->line("✅ Synced:  {$synced}");
        $this->line("⏭️  Skipped: {$skipped}");
        $this->info('═══════════════════════════════════');
    }
}




