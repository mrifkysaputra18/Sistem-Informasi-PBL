<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\UploadedFile;
use App\Models\{Kelompok, RuangKelas, PeriodeAkademik};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleDriveService
{
    protected $client;
    protected $drive;
    protected $initialized = false;

    /**
     * Initialize Google Client (Lazy Loading)
     * Hanya dipanggil saat benar-benar dibutuhkan
     */
    protected function initialize()
    {
        if ($this->initialized) {
            return;
        }

        // Check if Google Client class exists
        if (!class_exists(Client::class)) {
            throw new \Exception("Google API Client library is not installed. Run: composer require google/apiclient");
        }

        $this->client = new Client();
        
        // Gunakan Service Account untuk authentication
        $serviceAccountPath = config('services.google_drive.service_account_path');
        
        if (!$serviceAccountPath || !file_exists($serviceAccountPath)) {
            throw new \Exception("Google Drive service account file not found. Please configure it in .env");
        }
        
        $this->client->setAuthConfig($serviceAccountPath);
        $this->client->setScopes([Drive::DRIVE, Drive::DRIVE_FILE]);
        $this->client->setSubject(null); // Tidak perlu subject untuk service account
        
        $this->drive = new Drive($this->client);
        $this->initialized = true;
    }

    /**
     * Upload file ke Google Drive
     */
    public function uploadFile(UploadedFile $file, string $folderId = null): string
    {
        $this->initialize();
        
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $file->getClientOriginalName(),
            'parents' => $folderId ? [$folderId] : [config('services.google_drive.folder_id')],
        ]);

        $content = file_get_contents($file->getRealPath());
        
        $uploadedFile = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, webViewLink, webContentLink'
        ]);

        return $uploadedFile->id;
    }

    /**
     * Upload file untuk kelompok dengan struktur folder otomatis
     * Struktur: Root/[Periode Akademik]/[Kelas]/[Kelompok]/[File]
     */
    public function uploadFileForGroup(UploadedFile $file, Kelompok $group, int $weekNumber = null): array
    {
        $this->initialize();
        
        // Dapatkan atau buat folder untuk kelompok
        $groupFolderId = $this->getOrCreateGroupFolder($group);
        
        // Jika ada week number, buat subfolder minggu
        $targetFolderId = $groupFolderId;
        if ($weekNumber) {
            $weekFolderName = "Minggu-{$weekNumber}";
            $targetFolderId = $this->getOrCreateSubFolder($groupFolderId, $weekFolderName);
        }
        
        // Upload file
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $file->getClientOriginalName(),
            'parents' => [$targetFolderId],
        ]);

        $content = file_get_contents($file->getRealPath());
        
        $uploadedFile = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, name, webViewLink, webContentLink, mimeType, size'
        ]);

        Log::info('File uploaded to Google Drive', [
            'file_id' => $uploadedFile->id,
            'file_name' => $uploadedFile->name,
            'group_id' => $group->id,
            'week_number' => $weekNumber,
        ]);

        return [
            'file_id' => $uploadedFile->id,
            'file_name' => $uploadedFile->name,
            'file_url' => $this->getFileUrl($uploadedFile->id),
            'download_url' => $uploadedFile->webContentLink,
            'view_url' => $uploadedFile->webViewLink,
            'mime_type' => $uploadedFile->mimeType,
            'size' => $uploadedFile->size,
        ];
    }

    /**
     * Dapatkan atau buat folder untuk kelompok dengan struktur hierarki
     */
    public function getOrCreateGroupFolder(Kelompok $group): string
    {
        // Cek apakah kelompok sudah punya folder ID
        if ($group->google_drive_folder_id) {
            // Verifikasi folder masih ada
            if ($this->folderExists($group->google_drive_folder_id)) {
                return $group->google_drive_folder_id;
            }
        }

        // Load relasi yang diperlukan
        $group->load(['classRoom.academicPeriod']);
        $classRoom = $group->classRoom;
        $academicPeriod = $classRoom->academicPeriod;

        // Dapatkan root folder ID dari config
        $rootFolderId = config('services.google_drive.folder_id');

        // Buat/dapatkan folder Periode Akademik
        $periodFolderName = $this->sanitizeFolderName($academicPeriod->name ?? 'Periode-' . $academicPeriod->id);
        $periodFolderId = $this->getOrCreateSubFolder($rootFolderId, $periodFolderName);

        // Buat/dapatkan folder Kelas
        $classFolderName = $this->sanitizeFolderName($classRoom->name ?? 'Kelas-' . $classRoom->id);
        $classFolderId = $this->getOrCreateSubFolder($periodFolderId, $classFolderName);

        // Buat/dapatkan folder Kelompok
        $groupFolderName = $this->sanitizeFolderName($group->name ?? 'Kelompok-' . $group->id);
        $groupFolderId = $this->getOrCreateSubFolder($classFolderId, $groupFolderName);

        // Simpan folder ID ke kelompok
        $group->update(['google_drive_folder_id' => $groupFolderId]);

        Log::info('Group folder created/retrieved', [
            'group_id' => $group->id,
            'folder_id' => $groupFolderId,
            'path' => "{$periodFolderName}/{$classFolderName}/{$groupFolderName}",
        ]);

        return $groupFolderId;
    }

    /**
     * Dapatkan atau buat subfolder dalam folder parent
     */
    protected function getOrCreateSubFolder(string $parentFolderId, string $folderName): string
    {
        $this->initialize();

        // Cache key untuk menghindari query berulang
        $cacheKey = "gdrive_folder_{$parentFolderId}_{$folderName}";
        
        return Cache::remember($cacheKey, 3600, function() use ($parentFolderId, $folderName) {
            // Cari folder yang sudah ada
            $existingFolderId = $this->findFolderByName($folderName, $parentFolderId);
            
            if ($existingFolderId) {
                return $existingFolderId;
            }

            // Buat folder baru
            return $this->createFolder($folderName, $parentFolderId);
        });
    }

    /**
     * Cari folder berdasarkan nama dalam parent folder
     */
    public function findFolderByName(string $name, string $parentFolderId = null): ?string
    {
        $this->initialize();

        $query = "mimeType='application/vnd.google-apps.folder' and name='{$name}' and trashed=false";
        
        if ($parentFolderId) {
            $query .= " and '{$parentFolderId}' in parents";
        }

        $response = $this->drive->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name)',
            'pageSize' => 1,
        ]);

        $files = $response->getFiles();
        
        return count($files) > 0 ? $files[0]->getId() : null;
    }

    /**
     * Cek apakah folder masih ada di Google Drive
     */
    public function folderExists(string $folderId): bool
    {
        try {
            $this->initialize();
            $file = $this->drive->files->get($folderId, ['fields' => 'id, trashed']);
            return !$file->getTrashed();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Buat folder baru
     */
    public function createFolder(string $name, string $parentFolderId = null): string
    {
        $this->initialize();
        
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentFolderId ? [$parentFolderId] : [config('services.google_drive.folder_id')],
        ]);

        $folder = $this->drive->files->create($fileMetadata, [
            'fields' => 'id'
        ]);

        // Set folder permission agar bisa diakses
        $this->setFolderPermission($folder->id);

        return $folder->id;
    }

    /**
     * Set permission folder agar bisa diakses oleh siapa saja dengan link
     */
    protected function setFolderPermission(string $folderId): void
    {
        try {
            $permission = new \Google\Service\Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            
            $this->drive->permissions->create($folderId, $permission);
        } catch (\Exception $e) {
            Log::warning('Failed to set folder permission', [
                'folder_id' => $folderId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Dapatkan URL view file
     */
    public function getFileUrl(string $fileId): string
    {
        return "https://drive.google.com/file/d/{$fileId}/view";
    }

    /**
     * Dapatkan URL download langsung
     */
    public function getDownloadUrl(string $fileId): string
    {
        return "https://drive.google.com/uc?export=download&id={$fileId}";
    }

    /**
     * Dapatkan URL folder
     */
    public function getFolderUrl(string $folderId): string
    {
        return "https://drive.google.com/drive/folders/{$folderId}";
    }

    /**
     * Download file dari Google Drive
     */
    public function downloadFile(string $fileId): array
    {
        $this->initialize();

        try {
            // Get file metadata
            $file = $this->drive->files->get($fileId, [
                'fields' => 'id, name, mimeType, size'
            ]);

            // Get file content
            $response = $this->drive->files->get($fileId, [
                'alt' => 'media'
            ]);

            $content = $response->getBody()->getContents();

            return [
                'success' => true,
                'name' => $file->getName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'content' => $content,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to download file from Google Drive', [
                'file_id' => $fileId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Hapus file dari Google Drive
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->initialize();
            $this->drive->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to delete file from Google Drive', [
                'file_id' => $fileId,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * List semua file dalam folder
     */
    public function listFilesInFolder(string $folderId): array
    {
        $this->initialize();

        try {
            $response = $this->drive->files->listFiles([
                'q' => "'{$folderId}' in parents and trashed=false",
                'fields' => 'files(id, name, mimeType, size, createdTime, webViewLink, webContentLink)',
                'orderBy' => 'createdTime desc',
            ]);

            return array_map(function($file) {
                return [
                    'id' => $file->getId(),
                    'name' => $file->getName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'created_at' => $file->getCreatedTime(),
                    'view_url' => $file->getWebViewLink(),
                    'download_url' => $file->getWebContentLink(),
                ];
            }, $response->getFiles());
        } catch (\Exception $e) {
            Log::error('Failed to list files in folder', [
                'folder_id' => $folderId,
                'error' => $e->getMessage(),
            ]);
            return [];
        }
    }
    
    /**
     * Check if Google Drive is configured and ready
     */
    public function isConfigured(): bool
    {
        try {
            $serviceAccountPath = config('services.google_drive.service_account_path');
            return class_exists(Client::class) && $serviceAccountPath && file_exists($serviceAccountPath);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Sanitize folder name untuk Google Drive
     */
    protected function sanitizeFolderName(string $name): string
    {
        // Hapus karakter yang tidak diperbolehkan di Google Drive
        $name = preg_replace('/[<>:\"\/\\|?*]/', '-', $name);
        // Trim dan batasi panjang
        return substr(trim($name), 0, 100);
    }
}