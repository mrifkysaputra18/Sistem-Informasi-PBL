# ✅ Google Drive - READY TO USE!

**Status:** ✅ **LENGKAP & SIAP DIGUNAKAN!**

---

## 🎉 **YANG SUDAH DILAKUKAN:**

| Item | Status |
|------|--------|
| ✅ Package Google API Client | **INSTALLED** (v2.18.4) |
| ✅ Service Account JSON | **TERSIMPAN** |
| ✅ GoogleDriveService.php | **UPDATED** |
| ✅ Config services.php | **CONFIGURED** |
| ✅ Config `.env` | **CONFIGURED** |
| ✅ Folder ID | **ADA** (17Wn7r9hId88Z05L5y8th42cX5RLNqmZv) |
| ⚠️ Share Folder | **PERLU DILAKUKAN** |
| ⚠️ Enable API | **PERLU ENABLE** |

---

## 📁 **FILE JSON TERSIMPAN:**

**Lokasi:** `storage/app/google-drive-service-account.json` ✅

**Service Account Email:**
```
id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
```

**COPY EMAIL INI!** Akan digunakan untuk share folder.

---

## 🚀 **LANGKAH TERAKHIR (2 LANGKAH SAJA!):**

### **LANGKAH 1: Enable Google Drive API** (1 menit)

1. **Buka:** https://console.cloud.google.com/
2. **Pilih project:** `sistem-pbl-politala`
3. **Sidebar** → **"APIs & Services"** → **"Library"**
4. **Cari:** "Google Drive API"
5. **Klik** "Google Drive API"
6. **Klik** "ENABLE"

✅ API aktif!

---

### **LANGKAH 2: Share Folder ke Service Account** (1 menit)

#### **2.1. Buka Folder Google Drive**

Klik link ini:
```
https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
```

Atau buka Google Drive dan cari folder dengan ID: `17Wn7r9hId88Z05L5y8th42cX5RLNqmZv`

#### **2.2. Share Folder**

1. **Klik tombol "Share"** (pojok kanan atas, atau klik kanan folder → Share)

2. **Di field "Add people and groups", paste email:**
   ```
   id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
   ```

3. **Role:** Pilih **"Editor"** (BUKAN Viewer!)

4. **Uncheck** "Notify people" (service account tidak perlu email)

5. **Klik "Share"** atau **"Send"**

✅ Service account sekarang punya akses!

**Verifikasi:**
- Di bagian "Who has access", harusnya muncul:
  ```
  id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
  Editor
  ```

---

## ✅ **TEST UPLOAD FILE:**

```bash
# Clear cache
php artisan config:clear

# Jalankan server
php artisan serve
```

**Test:**
1. Login ke sistem: http://localhost:8000
2. Buka **Weekly Progress**
3. **Upload file**
4. **Buka Google Drive** → Folder `17Wn7r9hId88Z05L5y8th42cX5RLNqmZv`
5. ✅ **File harusnya muncul!**

---

## 📊 **KONFIGURASI LENGKAP:**

### **File `.env`:**
```env
GOOGLE_DRIVE_FOLDER_ID=17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
GOOGLE_DRIVE_SERVICE_ACCOUNT=storage/app/google-drive-service-account.json
```

### **File JSON:**
```
Path: storage/app/google-drive-service-account.json
Email: id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
Project: sistem-pbl-politala
```

### **Service Updated:**
```php
GoogleDriveService.php
→ Menggunakan Service Account (setAuthConfig)
→ Scope: DRIVE_FILE
→ Ready to use! ✅
```

---

## 🎯 **YANG PERLU ANDA LAKUKAN SEKARANG:**

### **1. Enable Google Drive API** ⚠️
```
https://console.cloud.google.com/
→ Project: sistem-pbl-politala
→ APIs & Services → Library
→ Google Drive API → ENABLE
```

### **2. Share Folder** ⚠️
```
https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
→ Share
→ Email: id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
→ Role: Editor
→ Share
```

### **3. Test Upload** ✅
```bash
php artisan serve
→ Upload file
→ Check Google Drive
```

---

## ✅ **CHECKLIST:**

- [x] ✅ Package installed
- [x] ✅ JSON file saved
- [x] ✅ Service updated
- [x] ✅ Config updated
- [x] ✅ Cache cleared
- [ ] ⚠️ **Enable Google Drive API** ← LAKUKAN INI!
- [ ] ⚠️ **Share folder** ← LAKUKAN INI!
- [ ] ⏳ Test upload

---

## 📧 **SERVICE ACCOUNT EMAIL (COPY INI!):**

```
id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
```

**Gunakan email ini untuk share folder!**

---

## 🎉 **KESIMPULAN:**

**Google Drive Integration: 95% READY!** 🚀

**Tinggal 2 langkah:**
1. ⚠️ Enable API (1 menit)
2. ⚠️ Share folder (1 menit)

**Total: 2 menit!** ⏱️

---

**Selamat! Setelah 2 langkah terakhir, upload file akan langsung ke Google Drive! 📁✨**

