# 📥 FITUR EXPORT FILE UNTUK DOSEN

## 📋 OVERVIEW

Fitur ini memungkinkan dosen untuk **download file evidence** yang diupload oleh mahasiswa saat submit target mingguan. Dosen bisa download file satu per satu atau download semua file sekaligus dalam format ZIP.

---

## ✨ FITUR YANG DITAMBAHKAN

### **1. Download Single File**
- ✅ Download file individual dari submission mahasiswa
- ✅ Support Google Drive dan Local Storage
- ✅ Icon berbeda per tipe file (PDF, Word, Excel, Image, etc)
- ✅ Tombol download hijau per file

### **2. Download All Files (ZIP)**
- ✅ Download semua file sekaligus dalam 1 ZIP
- ✅ Include file info (target details) dalam ZIP
- ✅ Nama ZIP: `Target_WeekX_KelompokY_YYYYMMDDHHMMSS.zip`
- ✅ Untuk Google Drive files: include link download dalam .txt file
- ✅ Auto-delete ZIP setelah download selesai

### **3. UI Improvements**
- ✅ File list dengan icon berdasarkan extension
- ✅ Badge storage type (Google Drive / Local)
- ✅ Hover effect pada file items
- ✅ Responsive design
- ✅ Info jika tidak ada file / checklist only

---

## 🎯 CARA MENGGUNAKAN

### **Step 1: Login Sebagai Dosen**
```
URL: http://localhost:8000/login
Email: dosen@politala.ac.id
Password: password
```

### **Step 2: Akses Menu Review Target**
1. Dari dashboard dosen
2. Klik menu **"Review Target Mingguan"**
3. Pilih target yang sudah di-submit mahasiswa
4. Klik **"Review & Nilai"**

### **Step 3: Lihat & Download Files**

#### **A. Download Single File:**
1. Scroll ke section **"Bukti (X file)"**
2. Lihat list file yang diupload mahasiswa
3. Klik tombol **hijau download** (icon 📥) di sebelah file
4. File akan terdownload ke browser

#### **B. Download All Files (ZIP):**
1. Di section **"Bukti (X file)"**
2. Klik tombol **"Download All (ZIP)"** (warna biru)
3. ZIP file akan terdownload otomatis
4. Extract ZIP untuk lihat semua file + info target

---

## 📂 STRUKTUR ZIP FILE

Ketika download all files, struktur ZIP:

```
Target_Week2_KelompokPBL_20251013161045.zip
├── _INFO_TARGET.txt          ← Info lengkap target
├── laporan.pdf                ← File evidence #1
├── screenshot.png             ← File evidence #2
├── dokumen.docx               ← File evidence #3
└── file_gdrive.pdf.txt        ← Link Google Drive file
```

**Isi _INFO_TARGET.txt:**
```
=== INFORMASI TARGET MINGGUAN ===

Kelompok: Kelompok PBL
Kelas: TI-3A
Minggu: 2
Target: Implementasi Fitur Login
Deskripsi: Implementasi fitur login dengan Google OAuth
Diselesaikan oleh: Mahasiswa
Tanggal Submit: 13/10/2025 16:10
Catatan: Sudah implementasi login dan testing berhasil

Total file: 3
Didownload oleh: Dosen
Tanggal download: 13/10/2025 16:15
```

---

## 🔧 IMPLEMENTASI TEKNIS

### **Controller Method: downloadFile()**

**File:** `app/Http/Controllers/WeeklyTargetReviewController.php`

**Fungsi:**
- Download single file by index
- Handle Google Drive redirect
- Handle local storage download
- Authorization check

**Flow:**
```php
1. Check user permission (dosen/koordinator/admin)
2. Get evidence files array from target
3. Validate file index exists
4. If Google Drive:
   - Redirect to Google Drive download URL
5. If Local Storage:
   - Check file exists
   - Return response()->download()
6. Log download activity
```

---

### **Controller Method: downloadAllFiles()**

**File:** `app/Http/Controllers/WeeklyTargetReviewController.php`

**Fungsi:**
- Create ZIP containing all evidence files
- Add target info as text file
- Handle mixed storage (Drive + Local)
- Auto-delete ZIP after download

**Flow:**
```php
1. Check user permission
2. Get all evidence files
3. Create temp ZIP file
4. Loop through files:
   - Local files: Add to ZIP directly
   - Google Drive files: Create .txt with links
5. Add _INFO_TARGET.txt with target details
6. Close ZIP
7. Return download response
8. Auto-delete ZIP after sent
```

---

### **Routes**

**File:** `routes/web.php`

```php
// Download single file
Route::get('target-reviews/{weeklyTarget}/download/{fileIndex}', 
    [WeeklyTargetReviewController::class, 'downloadFile'])
    ->name('target-reviews.download-file');

// Download all files as ZIP
Route::get('target-reviews/{weeklyTarget}/download-all', 
    [WeeklyTargetReviewController::class, 'downloadAllFiles'])
    ->name('target-reviews.download-all');
```

**Authorization:**
- Middleware: `role:dosen,koordinator,admin`
- Additional check in controller

---

### **View Updates**

**File:** `resources/views/reviews/targets/create.blade.php`

**Changes:**
1. ✅ Button "Download All (ZIP)" at top
2. ✅ File list with icons based on extension
3. ✅ Storage type badge (Google Drive / Local)
4. ✅ Individual download button per file
5. ✅ Hover effects
6. ✅ Empty state for checklist only / no files

**UI Components:**

```blade
<!-- Download All Button -->
<a href="{{ route('target-reviews.download-all', $target->id) }}" 
   class="bg-blue-600 hover:bg-blue-700 text-white">
    <i class="fas fa-download"></i> Download All (ZIP)
</a>

<!-- File Item -->
<div class="flex items-center justify-between bg-white p-2 rounded">
    <div class="flex items-center">
        <i class="fas fa-file-pdf text-red-600"></i>
        <span>filename.pdf</span>
        <span class="badge">☁️ Drive</span>
    </div>
    <a href="{{ route('target-reviews.download-file', [$target->id, $index]) }}"
       class="bg-green-600">
        <i class="fas fa-download"></i>
    </a>
</div>
```

---

## 🎨 UI/UX DESIGN

### **File Icons by Extension:**

| Extension | Icon | Color |
|-----------|------|-------|
| PDF | `fa-file-pdf` | Red |
| Word (doc/docx) | `fa-file-word` | Blue |
| Excel (xls/xlsx) | `fa-file-excel` | Green |
| Image (jpg/png/gif) | `fa-file-image` | Purple |
| Archive (zip/rar) | `fa-file-archive` | Yellow |
| Default | `fa-file` | Gray |

### **Storage Type Badge:**

| Type | Badge | Icon |
|------|-------|------|
| Google Drive | `☁️ Drive` | Cloud emoji |
| Local Storage | `💾 Local` | Disk emoji |

### **Button Colors:**

| Action | Color | Usage |
|--------|-------|-------|
| Download All | Blue (`bg-blue-600`) | Primary action |
| Download Single | Green (`bg-green-600`) | Per-file action |

---

## 🔐 SECURITY & AUTHORIZATION

### **Permission Check:**
```php
// Only dosen, koordinator, admin can download
if (!in_array(Auth::user()->role, ['dosen', 'koordinator', 'admin'])) {
    abort(403, 'Unauthorized');
}
```

### **Validation:**
- ✅ File index validation
- ✅ File existence check
- ✅ Path traversal prevention
- ✅ Extension validation (implicit via upload validation)

### **Logging:**
```php
\Log::info('Dosen downloading file', [
    'target_id' => $target->id,
    'file_index' => $fileIndex,
    'user_id' => Auth::id(),
]);
```

---

## 📊 DOWNLOAD STATISTICS

### **Tracking Downloads:**

Log entries created:
```
[INFO] Dosen downloading file
       target_id: 3
       file_index: 0
       user_id: 2
       file: {...}

[INFO] Dosen downloading all files
       target_id: 3
       user_id: 2
       file_count: 3

[INFO] ZIP file created successfully
       files_added: 3
       zip_path: /storage/app/temp/Target_Week2_...zip
```

### **Query Logs:**
```bash
# Check download activity
tail -100 storage/logs/laravel.log | grep "downloading"

# Check ZIP creation
tail -100 storage/logs/laravel.log | grep "ZIP file"
```

---

## 🧪 TESTING SCENARIOS

### **Scenario 1: Download Single Local File**
```
GIVEN mahasiswa submit target with local file
  AND dosen open review page
WHEN dosen click download button for that file
THEN file should download directly
  AND log entry created
```

### **Scenario 2: Download Single Google Drive File**
```
GIVEN mahasiswa submit target with Google Drive file
  AND dosen open review page
WHEN dosen click download button for that file
THEN browser redirect to Google Drive
  AND file download from Google Drive
  AND log entry created
```

### **Scenario 3: Download All Files (Mixed Storage)**
```
GIVEN mahasiswa submit with 2 local files + 1 Google Drive file
  AND dosen open review page
WHEN dosen click "Download All (ZIP)"
THEN ZIP created with:
  - 2 local files directly
  - 1 .txt file with Google Drive link
  - 1 _INFO_TARGET.txt
  AND ZIP downloads to browser
  AND ZIP deleted after download
  AND log entries created
```

### **Scenario 4: No Files Uploaded**
```
GIVEN mahasiswa submit without files (checklist only)
  AND dosen open review page
THEN show message "Target diselesaikan tanpa upload file"
  AND no download buttons shown
```

### **Scenario 5: Unauthorized Access**
```
GIVEN mahasiswa user tries to access download URL
WHEN request download file endpoint
THEN return 403 Forbidden
  AND log warning
```

---

## 🐛 ERROR HANDLING

### **Error 1: File Not Found**
```php
if (!file_exists($filePath)) {
    \Log::error('File not found in local storage');
    abort(404, 'File not found in storage');
}
```

**User sees:** 404 page "File not found"

### **Error 2: Invalid File Index**
```php
if (!isset($evidenceFiles[$fileIndex])) {
    abort(404, 'File not found');
}
```

**User sees:** 404 page

### **Error 3: ZIP Creation Failed**
```php
if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
    \Log::error('Failed to create ZIP file');
    return redirect()->back()->with('error', 'Gagal membuat file ZIP');
}
```

**User sees:** Flash message "Gagal membuat file ZIP"

### **Error 4: No Files to Download**
```php
if (empty($evidenceFiles)) {
    return redirect()->back()->with('error', 'Tidak ada file untuk didownload');
}
```

**User sees:** Flash message "Tidak ada file untuk didownload"

---

## 💡 TIPS & BEST PRACTICES

### **For Dosen:**
1. ✅ **Download All** jika banyak file - lebih efisien
2. ✅ Buka _INFO_TARGET.txt dalam ZIP untuk context
3. ✅ Google Drive files perlu koneksi internet untuk download
4. ✅ Check file extension untuk tahu tipe file

### **For Admin:**
1. ✅ Monitor storage space - ZIP files temporary tapi check if cleanup works
2. ✅ Check logs untuk download activity
3. ✅ Validate Google Drive permissions jika file tidak bisa didownload
4. ✅ Regular backup untuk local storage files

---

## 🔍 TROUBLESHOOTING

### **Problem: Download button tidak muncul**
**Solution:**
1. Clear cache: `php artisan cache:clear`
2. Clear view: `php artisan view:clear`
3. Hard refresh browser (Ctrl+F5)

### **Problem: File not found error**
**Solution:**
1. Check if file exists in storage:
   ```bash
   ls -la storage/app/public/weekly-progress/evidence/
   ```
2. Check database `evidence_files` column
3. Verify storage link: `php artisan storage:link`

### **Problem: Google Drive file tidak bisa didownload**
**Solution:**
1. Check Google Drive permissions
2. Verify service account has access to file
3. Check file_id is correct
4. Try manual URL: `https://drive.google.com/uc?export=download&id={file_id}`

### **Problem: ZIP download gagal**
**Solution:**
1. Check disk space: `df -h`
2. Check temp directory writable:
   ```bash
   ls -la storage/app/temp/
   chmod 755 storage/app/temp/
   ```
3. Check ZipArchive extension installed:
   ```bash
   php -m | grep zip
   ```

---

## 📚 RELATED DOCUMENTATION

- **GOOGLE_DRIVE_SETUP.md** - Setup Google Drive integration
- **SYNC_MAHASISWA_DOSEN_FIX.md** - Fix submission sync issue
- **CANCEL_SUBMISSION_FEATURE.md** - Cancel submission feature
- **ALUR_UPLOAD_PROGRESS.md** - Upload progress flow

---

## 🎯 SUMMARY

### **What's New:**
- ✅ Download single file dari submission
- ✅ Download all files as ZIP
- ✅ Enhanced file list UI with icons
- ✅ Storage type badges
- ✅ Target info included in ZIP
- ✅ Logging for audit trail

### **Benefits:**
- ✅ Dosen bisa download bukti mahasiswa dengan mudah
- ✅ Support Google Drive dan Local Storage
- ✅ Batch download dengan ZIP
- ✅ Clear UI/UX dengan icon dan badges
- ✅ Audit trail untuk compliance

---

**Last Updated:** 13 Oktober 2025  
**Version:** 1.0  
**Status:** ✅ Fully Implemented & Ready for Testing
