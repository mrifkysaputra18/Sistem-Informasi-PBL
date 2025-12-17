<?php

namespace App\Imports;

use App\Models\Kelompok;
use App\Models\Pengguna;
use App\Models\RuangKelas;
use App\Models\AnggotaKelompok;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class GroupsImport implements ToCollection, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    protected $classRoomId;
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    public function __construct($classRoomId)
    {
        $this->classRoomId = $classRoomId;
    }

    public function collection(Collection $rows)
    {
        $classRoom = RuangKelas::find($this->classRoomId);
        
        if (!$classRoom) {
            $this->errors[] = "Kelas tidak ditemukan";
            return;
        }

        Log::info('Starting group import', [
            'class_room_id' => $this->classRoomId,
            'class_name' => $classRoom->name,
            'total_rows' => $rows->count()
        ]);

        foreach ($rows as $index => $row) {
            try {
                DB::beginTransaction();
                // Skip empty rows
                if (empty($row['nama_kelompok'])) {
                    $this->skippedCount++;
                    DB::rollback();
                    continue;
                }

                $namaKelompok = trim($row['nama_kelompok']);
                
                // Check if group name already exists in this class
                $existingGroup = Kelompok::where('name', $namaKelompok)
                    ->where('class_room_id', $this->classRoomId)
                    ->first();
                
                if ($existingGroup) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Kelompok '{$namaKelompok}' sudah ada di kelas ini";
                    DB::rollback();
                    continue;
                }

                // Find ketua (leader) - support both NIM and email
                $ketuaIdentifier = trim($row['ketua_nim_atau_email'] ?? $row['ketua_email'] ?? '');
                
                if (empty($ketuaIdentifier)) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Ketua kelompok tidak boleh kosong";
                    DB::rollback();
                    continue;
                }

                $leader = $this->findStudent($ketuaIdentifier, $classRoom);
                
                if (!$leader) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Ketua '{$ketuaIdentifier}' tidak ditemukan atau bukan dari kelas {$classRoom->name}";
                    DB::rollback();
                    continue;
                }

                // Check if leader already has a group in this class
                if ($this->hasGroupInClass($leader->id, $this->classRoomId)) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Ketua {$leader->name} sudah tergabung dalam kelompok lain";
                    DB::rollback();
                    continue;
                }

                // Create group
                $group = Kelompok::create([
                    'name' => $namaKelompok,
                    'class_room_id' => $this->classRoomId,
                    'max_members' => 5,
                    'leader_id' => $leader->id,
                ]);

                // Add leader as member
                AnggotaKelompok::create([
                    'group_id' => $group->id,
                    'user_id' => $leader->id,
                    'is_leader' => true,
                    'status' => 'active',
                ]);

                $membersAdded = 1; // Leader already added

                // Add other members (up to 4 more members)
                for ($i = 1; $i <= 4; $i++) {
                    $memberKey = "anggota_{$i}_nim_atau_email";
                    $memberKeyAlt = "anggota_{$i}_email"; // Backward compatibility
                    
                    $memberIdentifier = trim($row[$memberKey] ?? $row[$memberKeyAlt] ?? '');
                    
                    if (empty($memberIdentifier)) {
                        continue; // Skip empty member
                    }

                    $member = $this->findStudent($memberIdentifier, $classRoom);
                    
                    if (!$member) {
                        Log::warning("Member not found", [
                            'identifier' => $memberIdentifier,
                            'group' => $namaKelompok
                        ]);
                        continue;
                    }

                    // Check if member is the leader (duplicate)
                    if ($member->id === $leader->id) {
                        Log::warning("Member is same as leader, skipping", [
                            'member' => $member->name,
                            'group' => $namaKelompok
                        ]);
                        continue;
                    }

                    // Check if member already has a group in this class
                    if ($this->hasGroupInClass($member->id, $this->classRoomId)) {
                        Log::warning("Member already has a group", [
                            'member' => $member->name,
                            'group' => $namaKelompok
                        ]);
                        continue;
                    }

                    // Check if member already added to this group (duplicate in Excel)
                    $alreadyInGroup = AnggotaKelompok::where('group_id', $group->id)
                        ->where('user_id', $member->id)
                        ->exists();
                    
                    if ($alreadyInGroup) {
                        continue;
                    }

                    AnggotaKelompok::create([
                        'group_id' => $group->id,
                        'user_id' => $member->id,
                        'is_leader' => false,
                        'status' => 'active',
                    ]);

                    $membersAdded++;
                }

                DB::commit();
                $this->importedCount++;

                Log::info('Group imported successfully', [
                    'group_id' => $group->id,
                    'group_name' => $namaKelompok,
                    'members_count' => $membersAdded,
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": Error - " . $e->getMessage();
                
                Log::error('Group import failed', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row->toArray()
                ]);
            }
        }

        Log::info('Group import completed', [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount,
            'errors' => count($this->errors)
        ]);
    }

    /**
     * Find student by NIM or Email, must be from the specified class
     */
    private function findStudent($identifier, $classRoom)
    {
        // Try to find by NIM first
        $student = Pengguna::where('role', 'mahasiswa')
            ->where('class_room_id', $classRoom->id)
            ->where(function($query) use ($identifier) {
                $query->where('nim', $identifier)
                      ->orWhere('email', $identifier);
            })
            ->first();

        return $student;
    }

    /**
     * Check if student already has a group in this class
     */
    private function hasGroupInClass($userId, $classRoomId)
    {
        return AnggotaKelompok::whereHas('group', function($query) use ($classRoomId) {
            $query->where('class_room_id', $classRoomId);
        })->where('user_id', $userId)->exists();
    }

    /**
     * Get import statistics
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}


