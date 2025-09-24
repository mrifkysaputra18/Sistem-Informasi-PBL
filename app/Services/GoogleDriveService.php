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
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect'));
        $this->client->setScopes(['https://www.googleapis.com/auth/drive']);
        
        // Set access token if available
        if (session('google_access_token')) {
            $this->client->setAccessToken(session('google_access_token'));
        }
        
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