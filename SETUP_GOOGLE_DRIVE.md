# 📁 Setup Google Drive Integration

**GOOD NEWS!** 🎉 Google Drive sudah **90% ready** di sistem Anda!

---

## 📊 **STATUS IMPLEMENTASI:**

| Komponen | Status | Lokasi |
|----------|--------|--------|
| **GoogleDriveService** | ✅ READY | `app/Services/GoogleDriveService.php` |
| **Package Google API** | ✅ INSTALLED | `google/apiclient` |
| **Upload File Method** | ✅ READY | `uploadFile()` |
| **Create Folder Method** | ✅ READY | `createFolder()` |
| **Delete File Method** | ✅ READY | `deleteFile()` |
| **Get File URL Method** | ✅ READY | `getFileUrl()` |
| **Integration** | ✅ READY | `WeeklyProgressController` |
| **Google Drive Credentials** | ⚠️ PERLU SETUP | `.env` |

---

## 🚀 **CARA SETUP (4 LANGKAH):**

### **LANGKAH 1: Buat Google Cloud Project**

#### **1.1. Buka Google Cloud Console**
```
https://console.cloud.google.com/
```

#### **1.2. Buat Project Baru (atau gunakan yang sudah ada)**
- Klik **"Select a project"** → **"NEW PROJECT"**
- Project name: `Sistem PBL Politala`
- Klik **"CREATE"**

---

### **LANGKAH 2: Enable Google Drive API**

#### **2.1. Buka API Library**
- Sidebar → **"APIs & Services"** → **"Library"**

#### **2.2. Cari & Enable Google Drive API**
- Ketik di search: **"Google Drive API"**
- Klik **"Google Drive API"**
- Klik **"ENABLE"**

✅ API sekarang aktif!

---

### **LANGKAH 3: Buat Service Account (Untuk Upload File)**

#### **3.1. Buka Credentials**
- Sidebar → **"APIs & Services"** → **"Credentials"**

#### **3.2. Create Service Account**
- Klik **"+ CREATE CREDENTIALS"** → **"Service Account"**

#### **3.3. Isi Detail:**
```
Service account name: pbl-drive-uploader
Service account ID: pbl-drive-uploader (auto-fill)
Description: Service account untuk upload file ke Google Drive
```
- Klik **"CREATE AND CONTINUE"**

#### **3.4. Grant Access (Skip):**
- Role: (skip dulu, optional)
- Klik **"CONTINUE"**
- Klik **"DONE"**

#### **3.5. Create Key:**
1. Klik service account yang baru dibuat
2. Tab **"KEYS"** → **"ADD KEY"** → **"Create new key"**
3. Key type: **JSON**
4. Klik **"CREATE"**
5. File JSON akan ter-download → **SIMPAN FILE INI!**

**File:** `sistem-pbl-politala-xxxxx.json`

---

### **LANGKAH 4: Buat Folder di Google Drive**

#### **4.1. Buka Google Drive**
- https://drive.google.com/

#### **4.2. Buat Folder Baru**
- Klik **"New"** → **"New folder"**
- Nama: **"Sistem PBL - Upload Files"**
- Klik **"Create"**

#### **4.3. Share Folder ke Service Account**
1. Klik kanan folder → **"Share"**
2. Paste **email service account** (dari JSON file, key: `client_email`)
   - Format: `pbl-drive-uploader@sistem-pbl-politala-xxxxx.iam.gserviceaccount.com`
3. Role: **Editor**
4. Klik **"Send"**

#### **4.4. Copy Folder ID**
- Buka folder di browser
- URL: `https://drive.google.com/drive/folders/FOLDER_ID_DISINI`
- Copy **FOLDER_ID_DISINI** → Simpan!

---

## 📝 **LANGKAH 5: Update File `.env`**

### **5.1. Copy JSON File ke Project**

Copy file JSON yang di-download ke folder project:
```bash
# Windows
copy Downloads\sistem-pbl-politala-xxxxx.json storage\app\google-service-account.json

# Linux/Mac
cp ~/Downloads/sistem-pbl-politala-xxxxx.json storage/app/google-service-account.json
```

### **5.2. Edit File `.env`**

Tambahkan di bagian paling bawah:

```env
# Google Drive Configuration
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_FOLDER_ID=your-folder-id-from-step-4
GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH=storage/app/google-service-account.json
```

**Ganti:**
- `your-folder-id-from-step-4` → Folder ID dari langkah 4.4

---

## ✅ **LANGKAH 6: Test Upload**

### **6.1. Clear Cache**
```bash
php artisan config:clear
php artisan cache:clear
```

### **6.2. Test Upload File**

Buka halaman Weekly Progress dan coba upload file:
```
http://localhost:8000/weekly-progress/create
```

Upload file → Jika berhasil, file akan tersimpan di Google Drive! ✅

---

## 🔍 **VERIFIKASI:**

### **Cek di Google Drive:**
1. Buka: https://drive.google.com/
2. Masuk ke folder **"Sistem PBL - Upload Files"**
3. File yang di-upload harusnya muncul disini! ✅

---

## 📚 **FITUR YANG SUDAH TERSEDIA:**

### **1. Upload File**
```php
// Sudah diimplementasi di GoogleDriveService
$fileId = $googleDriveService->uploadFile($file, $folderId);
```

### **2. Create Folder**
```php
$folderId = $googleDriveService->createFolder($folderName, $parentId);
```

### **3. Get File URL**
```php
$url = $googleDriveService->getFileUrl($fileId);
// Output: https://drive.google.com/file/d/{fileId}/view
```

### **4. Delete File**
```php
$success = $googleDriveService->deleteFile($fileId);
```

---

## 🎯 **PENGGUNAAN DI WEEKLY PROGRESS:**

Service sudah terintegrasi di `WeeklyProgressController`:

```php
public function __construct(GoogleDriveService $googleDriveService)
{
    $this->googleDriveService = $googleDriveService;
}

// Upload file progress
if ($request->hasFile('evidence_file')) {
    $fileId = $this->googleDriveService->uploadFile(
        $request->file('evidence_file'),
        config('services.google_drive.folder_id')
    );
}
```

---

## 🐛 **TROUBLESHOOTING:**

### **Error: "Client is not authorized"**

**Penyebab:** Service account belum diberi akses ke folder

**Solusi:**
1. Share folder Google Drive ke service account email
2. Role: Editor
3. Send

---

### **Error: "The API is not enabled"**

**Penyebab:** Google Drive API belum di-enable

**Solusi:**
1. Google Cloud Console → APIs & Services → Library
2. Cari "Google Drive API"
3. Klik "ENABLE"

---

### **Error: "Invalid credentials"**

**Penyebab:** File JSON tidak valid atau path salah

**Solusi:**
1. Cek path di `.env`: `storage/app/google-service-account.json`
2. Pastikan file JSON ada di lokasi tersebut
3. Clear cache: `php artisan config:clear`

---

### **Error: "File not found"**

**Penyebab:** Service account file tidak ada

**Solusi:**
```bash
# Cek file ada
ls storage/app/google-service-account.json

# Jika tidak ada, copy ulang dari Downloads
```

---

## 💡 **TIPS:**

### **1. Organize Folder Structure:**

```
Google Drive:
└── Sistem PBL - Upload Files/
    ├── Kelompok 1/
    │   ├── Week 1/
    │   ├── Week 2/
    │   └── ...
    ├── Kelompok 2/
    └── ...
```

Buat sub-folder untuk setiap kelompok secara otomatis.

---

### **2. Monitoring File:**

Cek file yang ter-upload di Google Drive Console:
```
https://console.cloud.google.com/apis/api/drive.googleapis.com/metrics
```

---

### **3. Quota & Limit:**

**Google Drive API Free Tier:**
- 20,000 requests/100 seconds/user
- 1 billion requests/day/project
- Storage: 15 GB gratis per akun

**Untuk kampus:** Cukup untuk ratusan mahasiswa!

---

## 🔐 **KEAMANAN:**

### **File yang TIDAK boleh di-commit:**

```gitignore
# Sudah di .gitignore
storage/app/google-service-account.json   ← JANGAN COMMIT!
```

### **Best Practices:**

1. ✅ Gunakan Service Account (bukan OAuth user)
2. ✅ Simpan JSON di `storage/app/` (private folder)
3. ✅ Jangan commit file JSON ke Git
4. ✅ Share folder hanya ke service account yang diperlukan

---

## 🌐 **UNTUK PRODUCTION:**

### **1. Setup di Server:**

```bash
# Upload file JSON ke server
scp google-service-account.json user@server:/path/to/project/storage/app/

# Update .env production
GOOGLE_DRIVE_FOLDER_ID=production-folder-id
```

### **2. Buat Folder Production Terpisah:**

Folder development vs production:
```
Development:
└── Sistem PBL - Upload Files (Dev)/

Production:
└── Sistem PBL - Upload Files (Production)/
```

---

## 📖 **DOKUMENTASI API:**

### **Upload File:**
```php
use App\Services\GoogleDriveService;

$service = app(GoogleDriveService::class);
$fileId = $service->uploadFile($request->file('document'), $folderId);
```

### **Create Folder per Kelompok:**
```php
$groupFolderId = $service->createFolder(
    'Kelompok ' . $group->name,
    config('services.google_drive.folder_id')
);
```

### **Get Shareable Link:**
```php
$url = $service->getFileUrl($fileId);
// https://drive.google.com/file/d/xxx/view
```

---

## ✅ **KESIMPULAN:**

**Google Drive Integration: 90% READY!** 🚀

**Yang sudah ada:**
- ✅ Service class lengkap
- ✅ Methods upload, folder, delete
- ✅ Integration di controller
- ✅ Package terinstall

**Yang perlu dilakukan:**
1. ⚠️ Enable Google Drive API di Cloud Console
2. ⚠️ Create Service Account & download JSON
3. ⚠️ Share folder Google Drive ke service account
4. ⚠️ Update `.env` dengan folder ID

**Estimasi waktu:** 10-15 menit ⏱️

---

**Setelah setup, mahasiswa bisa upload file langsung ke Google Drive! 📤**

**Dokumentasi lengkap ada di file ini!** 📚

