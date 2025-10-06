# ⚡ Quick Setup Google Drive (10 Menit)

**Status:** Google Drive Service **sudah ready**, tinggal setup credentials! 🚀

---

## ✅ **YANG SUDAH ADA:**

✅ `GoogleDriveService.php` - Service lengkap  
✅ Upload file method  
✅ Create folder method  
✅ Delete file method  
✅ Integration di WeeklyProgressController  
✅ Package Google API Client (perlu install)  

**Tinggal:** Setup credentials & folder Google Drive!

---

## 🚀 **SETUP CEPAT (5 LANGKAH):**

### **LANGKAH 1: Install Package Google API**

```bash
composer require google/apiclient:"^2.0"
```

**Tunggu install selesai** (~2 menit)

---

### **LANGKAH 2: Enable Google Drive API**

1. Buka: https://console.cloud.google.com/
2. Pilih project (atau buat baru)
3. **APIs & Services** → **Library**
4. Cari: **"Google Drive API"**
5. Klik **"ENABLE"**

✅ API aktif!

---

### **LANGKAH 3: Buat Service Account**

1. **Credentials** → **"+ CREATE CREDENTIALS"** → **"Service Account"**

2. **Isi:**
   - Name: `pbl-drive-uploader`
   - ID: `pbl-drive-uploader`
   - Klik **"CREATE AND CONTINUE"** → **"DONE"**

3. **Create Key:**
   - Klik service account
   - Tab **"KEYS"** → **"ADD KEY"** → **"Create new key"**
   - Type: **JSON**
   - **"CREATE"**
   - **File JSON ter-download** → SIMPAN!

---

### **LANGKAH 4: Setup Google Drive Folder**

1. **Buka:** https://drive.google.com/

2. **Buat Folder:**
   - New → New folder
   - Nama: **"Sistem PBL - Files"**

3. **Share ke Service Account:**
   - Klik kanan folder → **Share**
   - Paste email dari JSON: `pbl-drive-uploader@...iam.gserviceaccount.com`
   - Role: **Editor**
   - **Send**

4. **Copy Folder ID:**
   - Buka folder
   - URL: `drive.google.com/drive/folders/FOLDER_ID`
   - Copy **FOLDER_ID** → Simpan!

---

### **LANGKAH 5: Update Project**

**A. Copy JSON ke Project:**
```bash
copy Downloads\sistem-pbl-xxxxx.json storage\app\google-drive.json
```

**B. Tambahkan ke `config/services.php`:**

Buka file `config/services.php` dan tambahkan:

```php
'google_drive' => [
    'folder_id' => env('GOOGLE_DRIVE_FOLDER_ID'),
    'service_account_path' => env('GOOGLE_DRIVE_SERVICE_ACCOUNT', storage_path('app/google-drive.json')),
],
```

**C. Update `.env`:**

```env
# Google Drive
GOOGLE_DRIVE_FOLDER_ID=your-folder-id-here
GOOGLE_DRIVE_SERVICE_ACCOUNT=storage/app/google-drive.json
```

**Ganti `your-folder-id-here`** dengan Folder ID dari langkah 4!

**D. Clear Cache:**
```bash
php artisan config:clear
```

---

## ✅ **TEST UPLOAD:**

1. Jalankan server:
```bash
php artisan serve
```

2. Buka halaman Weekly Progress

3. Upload file

4. **Cek di Google Drive** → File harusnya muncul! ✅

---

## 🐛 **TROUBLESHOOTING CEPAT:**

### **Error: "google/apiclient not found"**
```bash
composer require google/apiclient:"^2.0"
```

### **Error: "Client is not authorized"**
→ Share folder ke service account email!

### **Error: "Invalid credentials"**
```bash
# Cek file ada
ls storage/app/google-drive.json

# Clear cache
php artisan config:clear
```

---

## 📋 **CHECKLIST:**

- [ ] Install: `composer require google/apiclient`
- [ ] Enable Google Drive API
- [ ] Create Service Account
- [ ] Download JSON key
- [ ] Copy JSON ke `storage/app/google-drive.json`
- [ ] Buat folder di Google Drive
- [ ] Share folder ke service account
- [ ] Copy Folder ID
- [ ] Update `config/services.php`
- [ ] Update `.env`
- [ ] `php artisan config:clear`
- [ ] Test upload file
- [ ] ✅ **DONE!**

---

## 🎉 **SELESAI!**

Upload file sekarang langsung ke Google Drive!

**Dokumentasi lengkap:** `SETUP_GOOGLE_DRIVE.md`

**Good luck! 📁✨**

