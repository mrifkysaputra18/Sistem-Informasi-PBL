<?php

namespace App\Imports;

use App\Models\User;
use App\Models\ClassRoom;
use App\Models\AcademicPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class UsersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $importedCount = 0;
    protected $skippedCount = 0;
    protected $errors = [];

    public function collection(Collection $rows)
    {
        $rowsArray = $rows->toArray();
        
        Log::info('Excel Import - Collection called', [
            'total_rows' => $rows->count(),
            'is_empty' => $rows->isEmpty(),
            'first_3_rows' => array_slice($rowsArray, 0, 3)
        ]);

        if ($rows->isEmpty()) {
            Log::warning('Excel Import - No rows found!');
            return;
        }

        Log::info('Excel Import - Processing started', [
            'total_rows_to_process' => $rows->count()
        ]);

        foreach ($rows as $index => $row) {
            try {
                // Skip empty rows - check both nim AND nama_lengkap
                if (empty($row['nim']) && empty($row['nama_lengkap'])) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Baris kosong dilewati";
                    continue;
                }

                // Validate required fields
                if (empty($row['email_sso'])) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Email SSO wajib diisi";
                    continue;
                }

                // Validate email domain
                if (!Str::endsWith($row['email_sso'], '@mhs.politala.ac.id')) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Email harus menggunakan domain @mhs.politala.ac.id";
                    continue;
                }

                // Find class room by code
                $classRoom = ClassRoom::where('code', trim($row['kelas']))->first();
                
                if (!$classRoom) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Kelas '{$row['kelas']}' tidak ditemukan di database";
                    continue;
                }

                // Check if email already exists
                $existingUser = User::where('email', $row['email_sso'])->first();
                if ($existingUser) {
                    $this->skippedCount++;
                    $this->errors[] = "Baris " . ($index + 2) . ": Email {$row['email_sso']} sudah terdaftar";
                    continue;
                }

                // Check if NIM already exists
                if (!empty($row['nim'])) {
                    $existingNim = User::where('nim', $row['nim'])->first();
                    if ($existingNim) {
                        $this->skippedCount++;
                        $this->errors[] = "Baris " . ($index + 2) . ": NIM {$row['nim']} sudah terdaftar";
                        continue;
                    }
                }

                // Generate politala_id
                $politalaId = $this->generatePolitalaId($row['nama_lengkap']);

                // Create user
                User::create([
                    'nim' => $row['nim'] ?? null,
                    'name' => $row['nama_lengkap'],
                    'email' => $row['email_sso'],
                    'politala_id' => $politalaId,
                    'program_studi' => $row['program_studi'],
                    'class_room_id' => $classRoom->id,  // AUTO SYNC!
                    'role' => 'mahasiswa',
                    'is_active' => true,
                    'password' => null, // SSO - no password needed
                    'email_verified_at' => now(), // Auto verify for SSO users
                ]);

                $this->importedCount++;

                Log::info('User imported from Excel', [
                    'email' => $row['email_sso'],
                    'class' => $classRoom->name,
                    'row' => $index + 2
                ]);

            } catch (\Exception $e) {
                $this->skippedCount++;
                $this->errors[] = "Baris " . ($index + 2) . ": Error - " . $e->getMessage();
                
                Log::error('Import user failed', [
                    'row' => $index + 2,
                    'error' => $e->getMessage(),
                    'data' => $row
                ]);
            }
        }

        Log::info('Excel Import - Processing completed', [
            'imported' => $this->importedCount,
            'skipped' => $this->skippedCount,
            'errors' => count($this->errors)
        ]);
    }

    public function rules(): array
    {
        return [
            'nim' => 'nullable|string|max:50',
            'nama_lengkap' => 'required|string|max:255',
            'email_sso' => 'required|email|max:255',
            'program_studi' => 'required|string|max:255',
            'kelas' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nim.required' => 'NIM wajib diisi',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email_sso.required' => 'Email SSO wajib diisi',
            'email_sso.email' => 'Format email tidak valid',
            'program_studi.required' => 'Program studi wajib diisi',
            'kelas.required' => 'Kelas wajib diisi',
        ];
    }

    /**
     * Generate unique Politala ID
     */
    private function generatePolitalaId($name)
    {
        $nameParts = explode(' ', trim($name));
        $firstName = strtoupper($nameParts[0] ?? 'USER');
        
        // Generate unique ID with random number
        do {
            $random = rand(1000, 9999);
            $politalaId = 'MHS_' . $firstName . '_' . $random;
        } while (User::where('politala_id', $politalaId)->exists());
        
        return $politalaId;
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
