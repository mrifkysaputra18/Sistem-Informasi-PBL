# PowerShell script to check Google Drive upload logs
Write-Host "=== CHECKING GOOGLE DRIVE UPLOAD LOGS ===" -ForegroundColor Cyan
Write-Host ""

$logFile = "storage/logs/laravel.log"

if (Test-Path $logFile) {
    Write-Host "📄 Reading last 100 lines of laravel.log..." -ForegroundColor Yellow
    Write-Host ""
    
    # Get last 100 lines and filter for Google Drive related logs
    Get-Content $logFile -Tail 100 | Select-String -Pattern "Google Drive|google_drive|uploaded to|upload error|drive upload" -Context 0,2
    
    Write-Host ""
    Write-Host "=== SUMMARY ===" -ForegroundColor Cyan
    
    $successCount = (Get-Content $logFile -Tail 100 | Select-String -Pattern "uploaded to Google Drive successfully").Count
    $errorCount = (Get-Content $logFile -Tail 100 | Select-String -Pattern "Google Drive upload error|upload failed").Count
    
    Write-Host "✅ Success uploads: $successCount" -ForegroundColor Green
    Write-Host "❌ Failed uploads: $errorCount" -ForegroundColor Red
    
    if ($successCount -gt 0) {
        Write-Host ""
        Write-Host "🎉 Google Drive is working! Files are being uploaded successfully!" -ForegroundColor Green
    } elseif ($errorCount -gt 0) {
        Write-Host ""
        Write-Host "⚠️  Google Drive uploads are failing. Check errors above." -ForegroundColor Yellow
        Write-Host "💡 Solution: Share folder to service account with Editor permission" -ForegroundColor Cyan
    } else {
        Write-Host ""
        Write-Host "ℹ️  No recent upload activity found in logs" -ForegroundColor Gray
    }
} else {
    Write-Host "❌ Log file not found: $logFile" -ForegroundColor Red
}

Write-Host ""
