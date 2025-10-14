<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassRoom;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImportSimple implements ToCollection, WithStartRow
{
    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    /**
     * Start from row 2 (skip header row 1)
     */
    public function startRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
        Log::info('Simple Import - Collection called', [
            'total_rows' => $rows->count(),
            'first_row' => $rows->first()
        ]);

        foreach ($rows as $index => $row) {
            try {
                // Excel columns by index: 0=nim, 1=nama_lengkap, 2=email_sso, 3=program_studi, 4=kelas
                $nim = $row[0] ?? null;
                $namaLengkap = $row[1] ?? null;
                $emailSSO = $row[2] ?? null;
                $programStudi = $row[3] ?? null;
                $kelas = $row[4] ?? null;

                Log::info('Processing row', [
                    'row_number' => $index + 2,
                    'nim' => $nim,
                    'nama' => $namaLengkap,
                    'email' => $emailSSO,
                    'kelas' => $kelas
                ]);

                // Skip empty rows
                if (empty($namaLengkap) && empty($emailSSO)) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Baris kosong dilewati";
                    continue;
                }

                // Validate required fields
                if (empty($emailSSO)) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Email SSO wajib diisi";
                    continue;
                }

                // Validate email domain
                if (!Str::endsWith($emailSSO, '@mhs.politala.ac.id')) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Email harus @mhs.politala.ac.id";
                    continue;
                }

                // Find class
                $classRoom = ClassRoom::where('code', trim($kelas))->first();
                
                if (!$classRoom) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Kelas '{$kelas}' tidak ditemukan";
                    continue;
                }

                // Check duplicate email
                if (User::where('email', $emailSSO)->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Email {$emailSSO} sudah terdaftar";
                    continue;
                }

                // Check duplicate NIM
                if (!empty($nim) && User::where('nim', $nim)->exists()) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": NIM {$nim} sudah terdaftar";
                    continue;
                }

                // Generate politala_id
                $politalaId = $this->generatePolitalaId($namaLengkap);

                // Create user
                User::create([
                    'nim' => $nim,
                    'name' => $namaLengkap,
                    'email' => $emailSSO,
                    'politala_id' => $politalaId,
                    'program_studi' => $programStudi,
                    'class_room_id' => $classRoom->id,
                    'role' => 'mahasiswa',
                    'is_active' => true,
                    'password' => null,
                    'email_verified_at' => now(),
                ]);

                $this->importedCount++;

                Log::info('User imported', [
                    'email' => $emailSSO,
                    'class' => $classRoom->name
                ]);

            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": " . $e->getMessage();
                
                Log::error('Import failed', [
                    'row' => $index + 2,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Simple Import completed', [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount
        ]);
    }

    protected function generatePolitalaId($name)
    {
        $nameParts = explode(' ', $name);
        $initials = '';
        foreach ($nameParts as $part) {
            if (!empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }
        
        $randomNumber = mt_rand(1000, 9999);
        return $initials . $randomNumber;
    }

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
