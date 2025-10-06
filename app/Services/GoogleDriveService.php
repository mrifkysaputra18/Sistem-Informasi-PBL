<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\UploadedFile;

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
        $this->client->setScopes([Drive::DRIVE_FILE]);
        $this->client->setSubject(null); // Tidak perlu subject untuk service account
        
        $this->drive = new Drive($this->client);
        $this->initialized = true;
    }

    public function uploadFile(UploadedFile $file, string $folderId = null): string
    {
        $this->initialize(); // Initialize saat dibutuhkan
        
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $file->getClientOriginalName(),
            'parents' => $folderId ? [$folderId] : null,
        ]);

        $content = file_get_contents($file->getRealPath());
        
        $uploadedFile = $this->drive->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return $uploadedFile->id;
    }

    public function createFolder(string $name, string $parentFolderId = null): string
    {
        $this->initialize(); // Initialize saat dibutuhkan
        
        $fileMetadata = new \Google\Service\Drive\DriveFile([
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => $parentFolderId ? [$parentFolderId] : null,
        ]);

        $folder = $this->drive->files->create($fileMetadata, [
            'fields' => 'id'
        ]);

        return $folder->id;
    }

    public function getFileUrl(string $fileId): string
    {
        return "https://drive.google.com/file/d/{$fileId}/view";
    }

    public function deleteFile(string $fileId): bool
    {
        try {
            $this->initialize(); // Initialize saat dibutuhkan
            $this->drive->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            return false;
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
}