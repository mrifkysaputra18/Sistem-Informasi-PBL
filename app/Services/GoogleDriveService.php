<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\UploadedFile;

class GoogleDriveService
{
    protected $client;
    protected $drive;

    public function __construct()
    {
        $this->client = new Client();
        
        // Gunakan Service Account untuk authentication
        $serviceAccountPath = config('services.google_drive.service_account_path');
        
        if (file_exists($serviceAccountPath)) {
            $this->client->setAuthConfig($serviceAccountPath);
        } else {
            throw new \Exception("Google Drive service account file not found at: {$serviceAccountPath}");
        }
        
        $this->client->setScopes([Drive::DRIVE_FILE]);
        $this->client->setSubject(null); // Tidak perlu subject untuk service account
        
        $this->drive = new Drive($this->client);
    }

    public function uploadFile(UploadedFile $file, string $folderId = null): string
    {
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
            $this->drive->files->delete($fileId);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}