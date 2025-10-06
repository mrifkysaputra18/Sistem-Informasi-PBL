# 📁 Cara Terhubung dengan Google Drive

**Status:** Google Drive Service **READY!** Package **INSTALLED!** 🎉

Tinggal setup credentials saja!

---

## ✅ **YANG SUDAH READY:**

| Komponen | Status |
|----------|--------|
| ✅ Package Google API Client | **INSTALLED** (v2.18.4) |
| ✅ GoogleDriveService.php | **READY** |
| ✅ Upload File Method | **READY** |
| ✅ Create Folder Method | **READY** |
| ✅ Delete File Method | **READY** |
| ✅ Config services.php | **UPDATED** |
| ⚠️ Google Credentials | **PERLU SETUP** |

---

## 🚀 **SETUP (3 LANGKAH MUDAH):**

### **LANGKAH 1: Enable Google Drive API**

#### **1.1. Buka Google Cloud Console**
https://console.cloud.google.com/

#### **1.2. Pilih/Buat Project**
- Gunakan project yang sama dengan Google OAuth
- Atau buat baru: `Sistem PBL Politala`

#### **1.3. Enable API**
1. Sidebar → **"APIs & Services"** → **"Library"**
2. Cari: **"Google Drive API"**
3. Klik **"ENABLE"**

✅ API aktif!

---

### **LANGKAH 2: Buat Service Account & Key**

#### **2.1. Create Service Account**
1. **"APIs & Services"** → **"Credentials"**
2. **"+ CREATE CREDENTIALS"** → **"Service Account"**
3. **Isi:**
   - Name: `pbl-drive-service`
   - Description: `Service for PBL file uploads`
4. **"CREATE AND CONTINUE"** → **"DONE"**

#### **2.2. Create JSON Key**
1. Klik service account yang baru dibuat
2. Tab **"KEYS"**
3. **"ADD KEY"** → **"Create new key"**
4. Key type: **JSON**
5. **"CREATE"**

**File JSON ter-download!** Contoh: `sistem-pbl-xxxxx.json`

**SIMPAN FILE INI!** 🔑

---

### **LANGKAH 3: Setup Google Drive Folder**

#### **3.1. Buka Google Drive**
https://drive.google.com/

#### **3.2. Buat Folder Upload**
1. **"New"** → **"New folder"**
2. Nama: **"PBL Upload Files"**
3. **"Create"**

#### **3.3. Share ke Service Account**
1. Klik kanan folder → **"Share"**
2. **Paste email service account:**
   - Lihat di file JSON, key: `client_email`
   - Format: `pbl-drive-service@xxxxx.iam.gserviceaccount.com`
3. Role: **Editor**
4. **Uncheck** "Notify people"
5. **"Share"**

✅ Service account bisa akses folder!

#### **3.4. Copy Folder ID**
1. Buka folder di browser
2. URL: `https://drive.google.com/drive/folders/FOLDER_ID_DISINI`
3. Copy **FOLDER_ID_DISINI** (bagian setelah `/folders/`)

**Contoh:**
```
URL: https://drive.google.com/drive/folders/1a2B3c4D5e6F7g8H9i0J
Folder ID: 1a2B3c4D5e6F7g8H9i0J
```

**SIMPAN Folder ID ini!**

---

## 📝 **LANGKAH 4: Update Project**

### **4.1. Copy JSON ke Project**

```bash
# Windows
copy Downloads\sistem-pbl-xxxxx.json storage\app\google-drive-service-account.json

# Linux/Mac
cp ~/Downloads/sistem-pbl-xxxxx.json storage/app/google-drive-service-account.json
```

### **4.2. Edit `.env`**

Buka file `.env` dan **tambahkan di bagian paling bawah:**

```env
# Google Drive Configuration
GOOGLE_DRIVE_FOLDER_ID=paste-folder-id-anda-disini
GOOGLE_DRIVE_SERVICE_ACCOUNT=storage/app/google-drive-service-account.json
```

**Ganti `paste-folder-id-anda-disini`** dengan Folder ID dari langkah 3.4!

**Contoh:**
```env
GOOGLE_DRIVE_FOLDER_ID=1a2B3c4D5e6F7g8H9i0J
GOOGLE_DRIVE_SERVICE_ACCOUNT=storage/app/google-drive-service-account.json
```

### **4.3. Clear Cache**

```bash
php artisan config:clear
php artisan cache:clear
```

---

## ✅ **TEST UPLOAD:**

### **Cara Test:**

1. **Jalankan server:**
```bash
php artisan serve
```

2. **Login ke sistem**

3. **Buka halaman upload** (Weekly Progress)

4. **Upload file**

5. **Cek Google Drive:**
   - Buka folder "PBL Upload Files"
   - File harusnya muncul! ✅

---

## 🎯 **FITUR YANG BISA DIGUNAKAN:**

### **1. Upload File Progress Mingguan**
Mahasiswa upload file → Langsung ke Google Drive!

### **2. Auto Folder per Kelompok**
Setiap kelompok punya folder sendiri

### **3. Shareable Link**
File bisa di-share via link Google Drive

### **4. Delete File**
Admin bisa hapus file yang tidak perlu

---

## 📊 **STRUKTUR FOLDER DI GOOGLE DRIVE:**

```
PBL Upload Files/
├── Kelompok 1/
│   ├── Week 1 - Laporan Progress.pdf
│   ├── Week 2 - Dokumentasi.docx
│   └── Week 3 - Screenshot.png
├── Kelompok 2/
│   ├── Week 1 - Progress.pdf
│   └── Week 2 - Update.pdf
└── Kelompok 3/
    └── ...
```

---

## 🐛 **TROUBLESHOOTING:**

### **Error: "Class 'Google\Client' not found"**

**Solusi:**
```bash
composer require google/apiclient -W
composer dump-autoload
```

---

### **Error: "Insufficient Permission"**

**Penyebab:** Service account belum diberi akses

**Solusi:**
1. Share folder ke service account email
2. Role: **Editor** (bukan Viewer!)
3. Try again

---

### **Error: "The API is not enabled"**

**Solusi:**
1. Google Cloud Console
2. APIs & Services → Library
3. Google Drive API → **ENABLE**

---

### **File tidak muncul di Google Drive**

**Solusi:**
1. Cek folder ID benar di `.env`
2. Cek service account ada akses ke folder
3. Refresh Google Drive (F5)
4. Cek log: `storage/logs/laravel.log`

---

## 🔐 **KEAMANAN:**

### **File Penting yang JANGAN di-commit:**

```gitignore
storage/app/google-drive-service-account.json  ← JANGAN COMMIT!
```

**Sudah di .gitignore:** ✅

### **Best Practices:**

1. ✅ Simpan JSON di `storage/app/` (private)
2. ✅ Jangan commit ke Git
3. ✅ Gunakan Service Account (bukan OAuth)
4. ✅ Beri akses minimal (Editor untuk folder itu saja)

---

## 📱 **BONUS: Monitoring Upload**

### **Cek Quota Google Drive API:**

```
https://console.cloud.google.com/apis/api/drive.googleapis.com/quotas
```

### **Lihat Files yang Ter-upload:**

```
https://console.cloud.google.com/apis/api/drive.googleapis.com/metrics
```

---

## ✅ **CHECKLIST:**

- [x] ✅ Install package: `google/apiclient`
- [ ] Enable Google Drive API
- [ ] Create Service Account
- [ ] Download JSON key
- [ ] Copy JSON ke `storage/app/google-drive-service-account.json`
- [ ] Buat folder di Google Drive
- [ ] Share folder ke service account
- [ ] Copy Folder ID
- [ ] Update `.env`
- [ ] Clear cache
- [ ] Test upload
- [ ] ✅ **DONE!**

---

## 🎉 **KESIMPULAN:**

**Google Drive Integration: READY TO USE!** 🚀

**Yang sudah dilakukan:**
- ✅ Package terinstall
- ✅ Service ready
- ✅ Config updated

**Yang perlu Anda lakukan:**
1. Enable Google Drive API (1 menit)
2. Create Service Account + Key (2 menit)
3. Setup folder + share (2 menit)
4. Update `.env` (1 menit)

**Total: ~10 menit!** ⏱️

**Dokumentasi lengkap:** `SETUP_GOOGLE_DRIVE.md`

**Selamat! Upload file akan tersimpan di Google Drive! 📁✨**

