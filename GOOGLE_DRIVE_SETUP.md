# 🔧 SETUP GOOGLE DRIVE UNTUK UPLOAD FILE

## 📋 MASALAH SEBELUMNYA

File yang diupload oleh mahasiswa **tidak masuk ke Google Drive** karena:
1. ❌ Path service account tidak ditemukan
2. ❌ Konfigurasi di `.env` tidak sesuai dengan yang dicari sistem

## ✅ SOLUSI YANG SUDAH DITERAPKAN

### 1. **Perbaikan Konfigurasi**

**File:** `config/services.php`
```php
'google_drive' => [
    'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
    'service_account_path' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH') 
        ? base_path(env('GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH'))
        : storage_path('app/google-drive-service-account.json'),
],
```

**File:** `.env`
```env
GOOGLE_DRIVE_FOLDER_ID=17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH=storage/app/google-drive-service-account.json
```

### 2. **Perbaikan Controller**

**File:** `app/Http/Controllers/WeeklyProgressController.php`

Sekarang controller:
- ✅ Mengecek apakah Google Drive terkonfigurasi sebelum upload
- ✅ Menambahkan logging detail untuk debugging
- ✅ Menyimpan metadata lengkap (file_size, mime_type, storage_type)
- ✅ Fallback ke local storage jika Google Drive gagal

### 3. **Command Testing**

**File:** `app/Console/Commands/TestGoogleDrive.php`

Command untuk test Google Drive:
```bash
php artisan test:google-drive
```

Output jika sukses:
```
✅ Service account file exists!
✅ Google API Client is installed!
✅ Google Drive Service is properly configured!
🎉 Google Drive is working perfectly!
```

---

## 🚀 CARA SETUP DARI NOL

### **Step 1: Buat Service Account di Google Cloud Console**

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih atau buat project baru
3. Aktifkan **Google Drive API**:
   - Navigation Menu → APIs & Services → Library
   - Search "Google Drive API"
   - Klik Enable

4. Buat Service Account:
   - Navigation Menu → APIs & Services → Credentials
   - Click "Create Credentials" → Service Account
   - Isi nama service account (contoh: `pbl-file-uploader`)
   - Klik "Create and Continue"
   - Skip roles (atau beri role "Editor")
   - Klik "Done"

5. Generate JSON Key:
   - Klik service account yang baru dibuat
   - Tab "Keys"
   - "Add Key" → "Create new key"
   - Pilih format **JSON**
   - Download file JSON

### **Step 2: Setup di Laravel**

1. **Copy JSON file** ke folder project:
   ```bash
   # Copy file JSON ke folder storage/app/
   # Rename menjadi: google-drive-service-account.json
   ```

2. **Buat folder di Google Drive**:
   - Buka [Google Drive](https://drive.google.com/)
   - Buat folder baru (contoh: "PBL_Uploads")
   - Klik kanan folder → "Share"
   - Tambahkan email service account (dari file JSON)
   - Beri permission **Editor**
   - Copy **Folder ID** dari URL:
     ```
     https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
                                              ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
                                              Ini adalah Folder ID
     ```

3. **Update `.env`**:
   ```env
   GOOGLE_DRIVE_FOLDER_ID=17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
   GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH=storage/app/google-drive-service-account.json
   ```

4. **Clear cache dan test**:
   ```bash
   php artisan config:clear
   php artisan test:google-drive
   ```

---

## 🧪 TESTING

### **Test Connection**
```bash
php artisan test:google-drive
```

### **Test Manual Upload**
1. Login sebagai mahasiswa
2. Buka Target Mingguan
3. Klik "Upload Progress"
4. Upload file (max 5MB)
5. Cek di Google Drive folder apakah file masuk

### **Check Log**
```bash
# Check Laravel log untuk error
tail -f storage/logs/laravel.log
```

Jika sukses, akan ada log:
```
[INFO] File uploaded to Google Drive successfully
       file_name: document.pdf
       file_id: 1abc...xyz
```

Jika gagal, akan ada log:
```
[ERROR] Google Drive upload error: ...
[INFO] File saved to local storage as fallback
```

---

## 📂 STRUKTUR FILE METADATA

File yang diupload akan disimpan dengan metadata berikut:

### **Google Drive (Primary)**
```json
{
    "storage_type": "google_drive",
    "file_id": "1cja-UzO74bT0tFtqdwochUap-sDYe3bJ",
    "file_name": "laporan.pdf",
    "file_url": "https://drive.google.com/file/d/1cja-UzO74bT0tFtqdwochUap-sDYe3bJ/view",
    "file_size": 1024567,
    "mime_type": "application/pdf",
    "uploaded_at": "2025-10-13 16:30:45"
}
```

### **Local Storage (Fallback)**
```json
{
    "storage_type": "local",
    "local_path": "weekly-progress/evidence/abc123.pdf",
    "file_name": "laporan.pdf",
    "file_url": "http://localhost:8000/storage/weekly-progress/evidence/abc123.pdf",
    "file_size": 1024567,
    "mime_type": "application/pdf",
    "uploaded_at": "2025-10-13 16:30:45"
}
```

---

## ⚠️ TROUBLESHOOTING

### **Problem 1: "Service account file not found"**
**Solution:**
```bash
# Check if file exists
ls -la storage/app/google-drive-service-account.json

# If not exists, copy the JSON file
cp /path/to/downloaded-key.json storage/app/google-drive-service-account.json

# Clear config
php artisan config:clear
```

### **Problem 2: "Permission denied" saat upload**
**Solution:**
1. Pastikan service account email sudah di-share ke folder Google Drive
2. Permission minimal: **Editor**
3. Check sharing settings di Google Drive

### **Problem 3: "API not enabled"**
**Solution:**
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Enable "Google Drive API"
3. Wait 1-2 minutes untuk propagation

### **Problem 4: File masuk local storage, tidak ke Google Drive**
**Solution:**
```bash
# Test Google Drive connection
php artisan test:google-drive

# Check error log
tail -100 storage/logs/laravel.log | grep "Google Drive"

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### **Problem 5: "Invalid credentials"**
**Solution:**
1. Re-download service account JSON dari Google Cloud Console
2. Pastikan JSON file valid (buka dengan text editor)
3. Copy ulang ke `storage/app/google-drive-service-account.json`
4. Test lagi

---

## 🔒 SECURITY

### **Protect Service Account JSON**

**File:** `.gitignore`
```gitignore
# Google Drive credentials
storage/app/google-drive-service-account.json
google-drive-*.json
```

**Important:**
- ❌ JANGAN commit service account JSON ke Git
- ❌ JANGAN share service account JSON ke public
- ✅ Simpan backup di tempat yang aman
- ✅ Rotate key secara berkala (6-12 bulan)

---

## 📊 MONITORING

### **Check Upload Statistics**

```sql
-- Count files by storage type
SELECT 
    JSON_EXTRACT(documents, '$[0].storage_type') as storage_type,
    COUNT(*) as total
FROM weekly_progress
WHERE documents IS NOT NULL
GROUP BY storage_type;
```

### **Find Failed Uploads**

```bash
# Check log for failed uploads
grep "Google Drive upload error" storage/logs/laravel.log | tail -20
```

---

## 🎯 HASIL AKHIR

Setelah setup selesai, sistem akan:

1. ✅ **Upload ke Google Drive** sebagai primary storage
2. ✅ **Fallback ke local** jika Google Drive gagal
3. ✅ **Logging detail** untuk debugging
4. ✅ **Metadata lengkap** untuk setiap file
5. ✅ **Easy download** via URL yang disimpan

---

## 📞 SUPPORT

Jika masih ada masalah:

1. Run test command:
   ```bash
   php artisan test:google-drive
   ```

2. Check log:
   ```bash
   tail -100 storage/logs/laravel.log
   ```

3. Verify configuration:
   ```bash
   php artisan config:show services.google_drive
   ```

4. Test manual upload via dashboard

---

**Last Updated:** 13 Oktober 2025  
**Status:** ✅ Working & Tested
