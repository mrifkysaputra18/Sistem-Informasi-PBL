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
            'supportsAllDrives' => true,
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
            'supportsAllDrives' => true,
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
     * Auto-regenerate jika folder dihapus dari Google Drive
     */
    public function getOrCreateGroupFolder(Kelompok $group): string
    {
        $this->initialize();
        
        // Cek apakah kelompok sudah punya folder ID dan masih valid
        if ($group->google_drive_folder_id) {
            if ($this->folderExists($group->google_drive_folder_id)) {
                return $group->google_drive_folder_id;
            }
            
            // Folder tidak ada lagi, reset dan buat ulang
            Log::warning('Group folder not found, regenerating...', [
                'group_id' => $group->id,
                'old_folder_id' => $group->google_drive_folder_id,
            ]);
            
            $group->update(['google_drive_folder_id' => null]);
            $this->clearFolderCache();
        }

        // Load relasi yang diperlukan
        $group->load(['classRoom.academicPeriod']);
        $classRoom = $group->classRoom;
        $academicPeriod = $classRoom->academicPeriod;

        // Dapatkan root folder ID dari config
        $rootFolderId = config('services.google_drive.folder_id');
        
        // Verifikasi root folder masih ada
        if (!$this->folderExists($rootFolderId)) {
            throw new \Exception("Root Google Drive folder not found. Please check GOOGLE_DRIVE_FOLDER_ID in .env");
        }

        // Buat/dapatkan folder Periode Akademik (dengan verifikasi)
        $periodFolderName = $this->sanitizeFolderName($academicPeriod->name ?? 'Periode-' . $academicPeriod->id);
        $periodFolderId = $this->getOrCreateSubFolderVerified($rootFolderId, $periodFolderName);

        // Buat/dapatkan folder Kelas (dengan verifikasi)
        $classFolderName = $this->sanitizeFolderName($classRoom->name ?? 'Kelas-' . $classRoom->id);
        $classFolderId = $this->getOrCreateSubFolderVerified($periodFolderId, $classFolderName);

        // Buat/dapatkan folder Kelompok (dengan verifikasi)
        $groupFolderName = $this->sanitizeFolderName($group->name ?? 'Kelompok-' . $group->id);
        $groupFolderId = $this->getOrCreateSubFolderVerified($classFolderId, $groupFolderName);

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
     * Dapatkan atau buat subfolder dengan verifikasi folder masih ada
     * Jika folder dari cache sudah dihapus, buat ulang
     */
    protected function getOrCreateSubFolderVerified(string $parentFolderId, string $folderName): string
    {
        $this->initialize();

        $cacheKey = "gdrive_folder_{$parentFolderId}_{$folderName}";
        
        // Cek cache dulu
        $cachedFolderId = Cache::get($cacheKey);
        
        if ($cachedFolderId) {
            // Verifikasi folder masih ada di Google Drive
            if ($this->folderExists($cachedFolderId)) {
                return $cachedFolderId;
            }
            
            // Folder tidak ada, hapus dari cache
            Log::warning('Cached folder not found in Google Drive, recreating...', [
                'folder_name' => $folderName,
                'cached_id' => $cachedFolderId,
            ]);
            Cache::forget($cacheKey);
        }
        
        // Cari folder yang sudah ada di Google Drive
        $existingFolderId = $this->findFolderByName($folderName, $parentFolderId);
        
        if ($existingFolderId) {
            // Simpan ke cache
            Cache::put($cacheKey, $existingFolderId, 3600);
            return $existingFolderId;
        }

        // Buat folder baru
        $newFolderId = $this->createFolder($folderName, $parentFolderId);
        
        // Simpan ke cache
        Cache::put($cacheKey, $newFolderId, 3600);
        
        Log::info('Created new folder in Google Drive', [
            'folder_name' => $folderName,
            'folder_id' => $newFolderId,
            'parent_id' => $parentFolderId,
        ]);
        
        return $newFolderId;
    }

    /**
     * Dapatkan atau buat subfolder dalam folder parent (legacy, gunakan getOrCreateSubFolderVerified)
     */
    protected function getOrCreateSubFolder(string $parentFolderId, string $folderName): string
    {
        return $this->getOrCreateSubFolderVerified($parentFolderId, $folderName);
    }
    
    /**
     * Clear semua cache folder Google Drive
     */
    public function clearFolderCache(): void
    {
        // Clear all gdrive folder cache keys
        // Since we can't easily iterate cache keys, we'll use tags if available
        // For now, we'll clear specific patterns
        try {
            Cache::flush(); // Clear all cache (simple approach)
            Log::info('Google Drive folder cache cleared');
        } catch (\Exception $e) {
            Log::warning('Failed to clear folder cache', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Reset semua folder ID kelompok (untuk regenerasi massal)
     */
    public function resetAllGroupFolders(): int
    {
        $count = Kelompok::whereNotNull('google_drive_folder_id')
            ->update(['google_drive_folder_id' => null]);
        
        $this->clearFolderCache();
        
        Log::info('Reset all group folder IDs', ['count' => $count]);
        
        return $count;
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
            'supportsAllDrives' => true,
            'includeItemsFromAllDrives' => true,
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
            $file = $this->drive->files->get($folderId, [
                'fields' => 'id, trashed',
                'supportsAllDrives' => true,
            ]);
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
            'fields' => 'id',
            'supportsAllDrives' => true,
        ]);

        // Set folder permission agar bisa diakses
        $this->setFolderPermission($folder->id);

        return $folder->id;
    }

    /**
     * Set permission folder agar bisa diakses oleh siapa saja dengan link
     * Note: Untuk Shared Drive, permission dikelola di level drive
     */
    protected function setFolderPermission(string $folderId): void
    {
        try {
            $permission = new \Google\Service\Drive\Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            
            $this->drive->permissions->create($folderId, $permission, [
                'supportsAllDrives' => true,
            ]);
        } catch (\Exception $e) {
            // Untuk Shared Drive, permission mungkin tidak bisa diubah
            // karena dikelola di level drive
            Log::warning('Failed to set folder permission (may be expected for Shared Drives)', [
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
                'fields' => 'id, name, mimeType, size',
                'supportsAllDrives' => true,
            ]);

            // Get file content
            $response = $this->drive->files->get($fileId, [
                'alt' => 'media',
                'supportsAllDrives' => true,
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
     * Untuk Shared Drive, coba delete langsung, jika gagal pindahkan ke trash
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->initialize();
            
            // Coba delete langsung
            $this->drive->files->delete($fileId, [
                'supportsAllDrives' => true,
            ]);
            
            Log::info('File permanently deleted from Google Drive', ['file_id' => $fileId]);
            return true;
        } catch (\Exception $e) {
            // Jika gagal delete, coba move to trash
            try {
                $fileMetadata = new \Google\Service\Drive\DriveFile([
                    'trashed' => true,
                ]);
                
                $this->drive->files->update($fileId, $fileMetadata, [
                    'supportsAllDrives' => true,
                ]);
                
                Log::info('File moved to trash in Google Drive', ['file_id' => $fileId]);
                return true;
            } catch (\Exception $e2) {
                Log::error('Failed to delete/trash file from Google Drive', [
                    'file_id' => $fileId,
                    'delete_error' => $e->getMessage(),
                    'trash_error' => $e2->getMessage(),
                ]);
                return false;
            }
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
                'supportsAllDrives' => true,
                'includeItemsFromAllDrives' => true,
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