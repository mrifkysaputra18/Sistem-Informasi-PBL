# 🚀 Langkah Setup Google Drive - SIAP DIJALANKAN!

**Status:** Konfigurasi `.env` **SUDAH DIPERBAIKI!** ✅

---

## ✅ **YANG SUDAH DILAKUKAN:**

| Item | Status |
|------|--------|
| ✅ Package Google API Client | **INSTALLED** (v2.18.4) |
| ✅ GoogleDriveService.php | **READY** |
| ✅ Config services.php | **UPDATED** |
| ✅ Config `.env` | **DIPERBAIKI** |
| ✅ Folder ID | **ADA** (17Wn7r9hId88Z05L5y8th42cX5RLNqmZv) |
| ⚠️ Service Account JSON | **PERLU DOWNLOAD & COPY** |
| ⚠️ Share Folder | **PERLU DILAKUKAN** |
| ⚠️ Enable API | **PERLU ENABLE** |

---

## 🎯 **KONFIGURASI `.env` SEKARANG:**

```env
# Google Drive Configuration (Service Account)
GOOGLE_DRIVE_FOLDER_ID=17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
GOOGLE_DRIVE_SERVICE_ACCOUNT=storage/app/google-drive-service-account.json
```

✅ **SUDAH BENAR!**

---

## 🚀 **LANGKAH YANG PERLU ANDA LAKUKAN (4 LANGKAH):**

### **LANGKAH 1: Buat Service Account & Download JSON** (5 menit)

#### **1.1. Buka Google Cloud Console**
```
https://console.cloud.google.com/
```

Login dengan akun Google Anda

#### **1.2. Pilih Project**
- Pilih project yang sama dengan Google OAuth
- Atau project: `Sistem PBL Politala`

#### **1.3. Enable Google Drive API**
1. Sidebar → **"APIs & Services"** → **"Library"**
2. Cari: **"Google Drive API"**
3. Klik **"Google Drive API"**
4. Klik **"ENABLE"**

✅ API aktif!

#### **1.4. Create Service Account**
1. Sidebar → **"APIs & Services"** → **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"**
3. Pilih **"Service Account"**

#### **1.5. Isi Form:**
```
Service account name: pbl-drive-service
Service account ID: pbl-drive-service (auto)
Description: Service for PBL file uploads to Google Drive
```
4. Klik **"CREATE AND CONTINUE"**
5. Klik **"DONE"** (skip grant access)

#### **1.6. Download JSON Key:**
1. **Klik service account** yang baru dibuat
2. Tab **"KEYS"**
3. Klik **"ADD KEY"** → **"Create new key"**
4. Key type: **JSON**
5. Klik **"CREATE"**

**File JSON ter-download!** 📥

Nama file contoh: `sistem-pbl-politala-xxxxx.json`

**SIMPAN FILE INI!** Anda akan butuh email service account dari file ini.

---

### **LANGKAH 2: Copy JSON ke Project** (1 menit)

Buka terminal/command prompt di folder project, lalu:

```bash
# Windows
copy Downloads\sistem-pbl-politala-xxxxx.json storage\app\google-drive-service-account.json

# Atau drag & drop file JSON ke folder storage\app\
# Lalu rename jadi: google-drive-service-account.json
```

**Pastikan path:**
```
storage/app/google-drive-service-account.json
```

**Verifikasi:**
```bash
# Cek file ada
dir storage\app\google-drive-service-account.json
```

Jika ada output, berarti berhasil! ✅

---

### **LANGKAH 3: Share Folder Google Drive** (2 menit)

#### **3.1. Buka File JSON**
Buka file `google-drive-service-account.json` dengan Notepad

#### **3.2. Copy Email Service Account**
Cari key `"client_email"`:
```json
{
  "type": "service_account",
  "project_id": "sistem-pbl-politala-xxxxx",
  "client_email": "pbl-drive-service@sistem-pbl-politala-xxxxx.iam.gserviceaccount.com",
  ...
}
```

**Copy email ini:** `pbl-drive-service@sistem-pbl-politala-xxxxx.iam.gserviceaccount.com`

#### **3.3. Buka Folder Google Drive**
```
https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
```

**Atau:**
1. Buka https://drive.google.com/
2. Cari folder dengan ID: `17Wn7r9hId88Z05L5y8th42cX5RLNqmZv`

#### **3.4. Share Folder**
1. Klik **"Share"** (tombol pojok kanan atas)
2. **Paste** email service account di field "Add people and groups"
3. Role: Pilih **"Editor"**
4. **Uncheck** "Notify people" (service account tidak perlu notifikasi)
5. Klik **"Share"** atau **"Send"**

✅ Service account sekarang punya akses!

**Verifikasi:**
- Di bagian "People with access", harusnya ada email service account
- Role: Editor

---

### **LANGKAH 4: Clear Cache & Test** (1 menit)

```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Jalankan server
php artisan serve
```

**Test Upload:**
1. Login ke sistem
2. Buka Weekly Progress atau upload file
3. Upload file
4. **Cek Google Drive** → File harusnya muncul di folder! ✅

---

## 📋 **CHECKLIST LENGKAP:**

### **Setup Service Account:**
- [ ] Buka Google Cloud Console
- [ ] Pilih project
- [ ] Enable Google Drive API
- [ ] Create Service Account: `pbl-drive-service`
- [ ] Download JSON key
- [ ] ✅ File JSON ter-download

### **Copy & Configure:**
- [ ] Copy JSON ke `storage/app/google-drive-service-account.json`
- [ ] Verify file ada di `storage/app/`
- [ ] Buka file JSON, copy `client_email`

### **Share Folder:**
- [ ] Buka folder: https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
- [ ] Share ke service account email
- [ ] Role: Editor
- [ ] Uncheck "Notify people"
- [ ] Click "Share"
- [ ] ✅ Service account ada di "People with access"

### **Test:**
- [ ] `php artisan config:clear`
- [ ] `php artisan serve`
- [ ] Upload file di sistem
- [ ] Check Google Drive
- [ ] ✅ File muncul di folder!

---

## 🐛 **TROUBLESHOOTING:**

### **Error: "File not found: google-drive-service-account.json"**

**Solusi:**
```bash
# Cek file ada
dir storage\app\google-drive-service-account.json

# Jika tidak ada, copy ulang
copy Downloads\sistem-pbl-xxxxx.json storage\app\google-drive-service-account.json
```

---

### **Error: "Insufficient Permission"**

**Penyebab:** Folder belum di-share ke service account

**Solusi:**
1. Buka folder di Google Drive
2. Share ke email service account
3. Role: **Editor** (bukan Viewer!)
4. Share

---

### **Error: "The API is not enabled"**

**Solusi:**
```
Google Cloud Console
→ APIs & Services → Library
→ Google Drive API → ENABLE
```

---

### **File tidak muncul di Google Drive**

**Cek:**
1. ✅ File JSON ada di `storage/app/`?
2. ✅ Folder ID benar di `.env`?
3. ✅ Service account punya akses Editor?
4. ✅ API sudah enabled?
5. ✅ Cache sudah di-clear?

**Debug:**
```bash
# Cek log error
tail -f storage/logs/laravel.log

# Test manual
php artisan tinker
>>> config('services.google_drive.folder_id')
=> "17Wn7r9hId88Z05L5y8th42cX5RLNqmZv"
```

---

## 💡 **TIPS:**

### **Email Service Account:**

Saat buat service account, email akan auto-generate:
```
Format: [nama]@[project-id].iam.gserviceaccount.com

Contoh:
pbl-drive-service@sistem-pbl-politala-123456.iam.gserviceaccount.com
```

Copy dari file JSON (key: `client_email`)!

---

### **Folder Organization:**

Setelah setup, Anda bisa buat sub-folder:
```
PBL Upload Files/
├── Kelompok 1/
├── Kelompok 2/
├── Kelompok 3/
└── ...
```

Folder ID tetap parent folder (17Wn7r9hId88Z05L5y8th42cX5RLNqmZv)

---

## ✅ **RINGKASAN:**

**Konfigurasi `.env`: SUDAH DIPERBAIKI!** ✅

**Langkah setup:**
1. ⏳ Create Service Account (5 menit)
2. ⏳ Download JSON & copy (1 menit)
3. ⏳ Share folder (2 menit)
4. ⏳ Enable API (1 menit)
5. ⏳ Test upload (1 menit)

**Total: ~10 menit**

**Folder ID:** `17Wn7r9hId88Z05L5y8th42cX5RLNqmZv` ✅

---

**Silakan mulai setup dari LANGKAH 1! Good luck! 🚀**

**Dokumentasi lengkap:** `CARA_HUBUNGKAN_GOOGLE_DRIVE.md`
