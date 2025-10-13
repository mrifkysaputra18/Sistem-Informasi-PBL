<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleDriveService;

class TestGoogleDrive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:google-drive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google Drive connection and configuration';

    /**
     * Execute the console command.
     */
    public function handle(GoogleDriveService $googleDrive)
    {
        $this->info('🔍 Testing Google Drive Configuration...');
        $this->newLine();

        // Check if service account file exists
        $serviceAccountPath = config('services.google_drive.service_account_path');
        $folderId = config('services.google_drive.folder_id');

        $this->info("📁 Service Account Path: {$serviceAccountPath}");
        $this->info("📂 Target Folder ID: {$folderId}");
        $this->newLine();

        // Check if file exists
        if (!file_exists($serviceAccountPath)) {
            $this->error("❌ Service account file NOT FOUND!");
            $this->error("   Expected at: {$serviceAccountPath}");
            $this->newLine();
            $this->info("💡 Solutions:");
            $this->info("   1. Make sure the file exists at the path above");
            $this->info("   2. Update GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH in .env");
            $this->info("   3. Download service account JSON from Google Cloud Console");
            return 1;
        }

        $this->info("✅ Service account file exists!");
        $this->newLine();

        // Check if Google API Client is installed
        if (!class_exists(\Google\Client::class)) {
            $this->error("❌ Google API Client NOT INSTALLED!");
            $this->error("   Run: composer require google/apiclient");
            return 1;
        }

        $this->info("✅ Google API Client is installed!");
        $this->newLine();

        // Check if service is configured
        if ($googleDrive->isConfigured()) {
            $this->info("✅ Google Drive Service is properly configured!");
            $this->newLine();
            
            // Try to create a test folder
            try {
                $this->info("🧪 Testing folder creation...");
                $testFolderName = "TEST_FOLDER_" . now()->timestamp;
                $testFolderId = $googleDrive->createFolder($testFolderName, $folderId);
                
                $this->info("✅ Test folder created successfully!");
                $this->info("   Folder ID: {$testFolderId}");
                $this->info("   Folder URL: https://drive.google.com/drive/folders/{$testFolderId}");
                $this->newLine();
                
                // Clean up test folder
                $this->info("🧹 Cleaning up test folder...");
                if ($googleDrive->deleteFile($testFolderId)) {
                    $this->info("✅ Test folder deleted successfully!");
                } else {
                    $this->warn("⚠️  Could not delete test folder. Please delete manually.");
                }
                
                $this->newLine();
                $this->info("🎉 Google Drive is working perfectly!");
                $this->info("   You can now upload files to Google Drive.");
                
            } catch (\Exception $e) {
                $this->error("❌ Google Drive test FAILED!");
                $this->error("   Error: " . $e->getMessage());
                $this->newLine();
                $this->info("💡 Possible issues:");
                $this->info("   1. Service account doesn't have access to the folder");
                $this->info("   2. Folder ID is incorrect");
                $this->info("   3. API is not enabled in Google Cloud Console");
                $this->info("   4. Service account credentials are invalid");
                return 1;
            }
            
        } else {
            $this->error("❌ Google Drive Service is NOT configured!");
            return 1;
        }

        return 0;
    }
}
