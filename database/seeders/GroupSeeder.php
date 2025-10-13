<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Models\ClassRoom;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classRooms = ClassRoom::all();
        
        if ($classRooms->isEmpty()) {
            $this->command->error('Tidak ada kelas yang ditemukan!');
            return;
        }
        
        $this->command->info("Ditemukan {$classRooms->count()} kelas");
        
        $totalGroups = 0;
        $totalMembers = 0;
        
        foreach ($classRooms as $classRoom) {
            $this->command->info("\nMemproses kelas: {$classRoom->name}");
            
            // Get all students from this class
            $students = User::where('role', 'mahasiswa')
                ->where('class_room_id', $classRoom->id)
                ->get();
            
            if ($students->isEmpty()) {
                $this->command->warn("  Tidak ada mahasiswa di kelas {$classRoom->name}");
                continue;
            }
            
            $this->command->info("  Ditemukan {$students->count()} mahasiswa");
            
            // Fixed: 5 groups per class
            $numberOfGroups = 5;
            $membersPerGroup = 5;
            
            $this->command->info("  Akan membuat {$numberOfGroups} kelompok dengan {$membersPerGroup} anggota per kelompok");
            
            // Shuffle students for random distribution
            $shuffledStudents = $students->shuffle();
            
            // Create groups
            for ($i = 0; $i < $numberOfGroups; $i++) {
                $groupNumber = $i + 1;
                $groupName = "Kelompok {$groupNumber}";
                
                // Create group
                $group = Group::create([
                    'name' => $groupName,
                    'class_room_id' => $classRoom->id,
                    'leader_id' => null, // Will be set later
                    'max_members' => $membersPerGroup,
                ]);
                
                // Get exactly 5 students for this group
                $groupStudents = $shuffledStudents->splice(0, $membersPerGroup);
                
                if ($groupStudents->count() < $membersPerGroup) {
                    $this->command->warn("  ⚠️ Kelompok {$groupNumber} hanya memiliki {$groupStudents->count()} anggota (kurang dari {$membersPerGroup})");
                }
                
                // Add members to group
                $memberCount = 0;
                $leaderId = null;
                
                foreach ($groupStudents as $index => $student) {
                    $isLeader = ($index === 0); // First student becomes leader
                    
                    GroupMember::create([
                        'group_id' => $group->id,
                        'user_id' => $student->id,
                        'is_leader' => $isLeader,
                        'status' => 'active',
                    ]);
                    
                    if ($isLeader) {
                        $leaderId = $student->id;
                        $leaderName = $student->name;
                    }
                    
                    $memberCount++;
                    $totalMembers++;
                }
                
                // Update group with leader
                if ($leaderId) {
                    $group->update(['leader_id' => $leaderId]);
                }
                
                $totalGroups++;
                $this->command->info("  ✅ {$groupName} dibuat dengan {$memberCount} anggota (Ketua: {$leaderName})");
            }
        }
        
        $this->command->info("\n🎉 Selesai!");
        $this->command->info("Total Kelompok: {$totalGroups}");
        $this->command->info("Total Anggota: {$totalMembers}");
    }
}
