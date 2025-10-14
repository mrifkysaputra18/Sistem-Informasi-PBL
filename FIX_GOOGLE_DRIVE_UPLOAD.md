# 🔧 FIX: Google Drive Upload Not Working

## ❌ **MASALAH YANG TERJADI**

File yang diupload mahasiswa **TIDAK masuk ke Google Drive**, tetapi tersimpan di **local storage** saja.

**Error Log:**
```
Google Drive upload failed: Service Accounts do not have storage quota. 
Leverage shared drives or use OAuth delegation instead.
```

**Penyebab:**
- Google Service Account tidak punya storage quota di "My Drive" biasa
- Service Account tidak bisa langsung upload ke personal Google Drive
- File fallback ke local storage (`storage/app/public/weekly-progress/evidence/`)

---

## ✅ **SOLUSI LENGKAP**

Ada **2 cara** untuk fix masalah ini:

---

## 🎯 **SOLUSI 1: SHARE FOLDER KE SERVICE ACCOUNT (RECOMMENDED)**

Ini cara **paling mudah** dan tidak perlu Shared Drive / Google Workspace.

### **Step 1: Copy Service Account Email**

Service Account Email Anda:
```
id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
```

### **Step 2: Buka Google Drive**

1. Buka browser → https://drive.google.com
2. Login dengan akun yang punya folder target
3. Cari folder ID: `17Wn7r9hId88Z05L5y8th42cX5RLNqmZv`
   - Atau buka URL: https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv

### **Step 3: Share Folder ke Service Account**

1. **Klik kanan** folder → **Share** (atau icon Share)
2. Di bagian "Add people and groups":
   - **Paste email:** `id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com`
3. **Set permission:** **Editor** (bukan Viewer!)
4. **UNCHECK** "Notify people" (karena ini robot, bukan orang)
5. **Klik "Share"**

**Screenshot Expected:**
```
┌─────────────────────────────────────────────────┐
│ Share "Folder PBL Uploads"                      │
├─────────────────────────────────────────────────┤
│ Add people and groups:                          │
│ [id-pbl-drive-uploader@sistem-pbl-politala...] │
│                                                  │
│ Permission: [Editor ▼]                          │
│                                                  │
│ ☐ Notify people                                 │
│                                                  │
│                        [Cancel]  [Share]        │
└─────────────────────────────────────────────────┘
```

### **Step 4: Verify Permission**

1. Refresh folder di Google Drive
2. Klik folder → Right click → **Manage access**
3. Pastikan muncul:
   ```
   id-pbl-drive-uploader@sistem-pbl-politala... - Editor
   ```

### **Step 5: Test Upload**

1. Login sebagai mahasiswa di aplikasi
2. Upload file ke target mingguan
3. Check Google Drive folder → **File HARUS MASUK!**

---

## 🏢 **SOLUSI 2: GUNAKAN SHARED DRIVE (Untuk Google Workspace)**

Jika organisasi Anda pakai **Google Workspace**, gunakan Shared Drive.

### **Step 1: Buat Shared Drive**

1. Buka Google Drive
2. Klik **Shared drives** (sidebar kiri)
3. Klik **+ New** → **New shared drive**
4. Nama: "PBL Uploads" atau sesuai kebutuhan
5. Klik **Create**

### **Step 2: Add Service Account ke Shared Drive**

1. Klik Shared Drive yang baru dibuat
2. Klik **Manage members**
3. Klik **Add members**
4. Paste email: `id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com`
5. Permission: **Content manager** atau **Manager**
6. **Uncheck** "Notify people"
7. Klik **Send**

### **Step 3: Buat Folder di Shared Drive**

1. Masuk ke Shared Drive
2. Klik **+ New** → **Folder**
3. Nama folder: "Weekly Progress Uploads"
4. Buka folder tersebut
5. **Copy Folder ID** dari URL:
   ```
   https://drive.google.com/drive/folders/[FOLDER_ID_BARU]
   ```

### **Step 4: Update .env**

Edit file `.env`:
```env
GOOGLE_DRIVE_FOLDER_ID=[FOLDER_ID_BARU_DARI_SHARED_DRIVE]
```

Contoh:
```env
GOOGLE_DRIVE_FOLDER_ID=1AbCdEfGhIjKlMnOpQrStUvWxYz123456
```

### **Step 5: Clear Cache & Test**

```bash
php artisan config:clear
php artisan cache:clear
php artisan test:google-drive
```

---

## 🧪 **TESTING SETELAH FIX**

### **Test 1: Upload dari Mahasiswa**

1. Login: `mahasiswa@politala.ac.id` / `password`
2. Dashboard → Target Mingguan → Week 2
3. Klik "Lihat Detail" → "Submit Target"
4. Upload file (PDF, image, doc, etc)
5. Submit
6. ✅ **Success message muncul**

### **Test 2: Check Google Drive**

1. Buka Google Drive → Folder PBL
2. ✅ **File HARUS ADA di folder!**
3. Check file name, size, timestamp
4. Try download file dari Google Drive

### **Test 3: Download dari Dosen**

1. Login: `dosen@politala.ac.id` / `password`
2. Menu "Review Target Mingguan"
3. Pilih submission mahasiswa
4. Klik "Download" atau "Download All (ZIP)"
5. ✅ **File harus bisa didownload**

### **Test 4: Check Storage Type**

Di halaman review dosen, pastikan file badge menunjukkan:
```
☁️ Drive  (bukan 💾 Local)
```

---

## 📊 **VERIFY DENGAN LOG**

### **Check Log Upload:**

```bash
Get-Content storage/logs/laravel.log -Tail 50 | Select-String "Google Drive"
```

**Expected Success Log:**
```
[INFO] File uploaded to Google Drive successfully
       file_name: laporan.pdf
       file_id: 1AbCdEf...
```

**Expected Error Log (Before Fix):**
```
[ERROR] Google Drive upload failed: Service Accounts do not have storage quota
```

**Expected Success Log (After Fix):**
```
[INFO] File uploaded to Google Drive successfully
       storage_type: google_drive
       file_id: 1XyZ...
```

---

## 🐛 **TROUBLESHOOTING**

### **Problem 1: "Permission denied" saat upload**

**Cause:** Service account tidak punya akses ke folder

**Solution:**
1. Check permission di Google Drive
2. Pastikan service account email sudah di-share
3. Permission harus **Editor** atau **Content Manager** (bukan Viewer!)
4. Try reshare folder

### **Problem 2: File masih ke local storage**

**Cause:** Cache belum di-clear atau config belum diupdate

**Solution:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

Restart server:
```bash
php artisan serve
```

### **Problem 3: "Folder not found"**

**Cause:** Folder ID salah atau service account belum punya akses

**Solution:**
1. Verify folder ID di URL Google Drive
2. Pastikan folder ID benar di `.env`
3. Share folder dengan benar
4. Test dengan command:
   ```bash
   php artisan test:google-drive
   ```

### **Problem 4: Test command sukses tapi upload gagal**

**Cause:** Test command buat folder di root, tapi upload ke folder specific yang belum di-share

**Solution:**
1. **WAJIB share folder target** ke service account
2. Tidak cukup hanya root drive, folder specific juga harus di-share
3. Check log untuk error detail

---

## 📋 **CHECKLIST VERIFIKASI**

Sebelum declare "FIXED", pastikan semua ini ✅:

- [ ] Service account email sudah di-copy
- [ ] Folder Google Drive sudah di-share ke service account
- [ ] Permission set ke **Editor** (bukan Viewer)
- [ ] Test command `php artisan test:google-drive` SUCCESS
- [ ] Upload dari mahasiswa → File masuk ke Google Drive
- [ ] File badge di dosen menunjukkan **☁️ Drive**
- [ ] Download dari dosen berhasil
- [ ] Log menunjukkan "uploaded to Google Drive successfully"

---

## 💡 **TIPS**

### **Best Practice:**

1. ✅ **Gunakan Solusi 1** (share folder) untuk setup cepat
2. ✅ **Gunakan Solusi 2** (shared drive) untuk organisasi/tim besar
3. ✅ **Backup local** tetap enabled sebagai fallback
4. ✅ **Monitor logs** untuk early detection masalah
5. ✅ **Test setiap perubahan** sebelum production

### **Permission Guide:**

| Role | Boleh Upload? | Boleh Delete? | Use Case |
|------|---------------|---------------|----------|
| **Viewer** | ❌ No | ❌ No | Read only |
| **Commenter** | ❌ No | ❌ No | Comment only |
| **Editor** | ✅ Yes | ✅ Yes | **← Use this!** |
| **Content Manager** | ✅ Yes | ✅ Yes | Shared Drive |
| **Manager** | ✅ Yes | ✅ Yes | Shared Drive (full) |

### **Security Note:**

- Service account adalah "robot user"
- Tidak perlu password login
- Access control via JSON key file
- **JANGAN share JSON key** ke public
- **JANGAN commit** key file ke Git

---

## 🔍 **CURRENT STATUS**

**Service Account Email:**
```
id-pbl-drive-uploader@sistem-pbl-politala.iam.gserviceaccount.com
```

**Target Folder ID:**
```
17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
```

**Folder URL:**
```
https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
```

**Current Config:**
```env
GOOGLE_DRIVE_SERVICE_ACCOUNT_PATH=storage/app/google-drive-service-account.json
GOOGLE_DRIVE_FOLDER_ID=17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
```

**Status:** ⚠️ **Folder belum di-share ke service account**

---

## 📞 **NEXT STEPS**

1. ✅ **STEP 1:** Share folder Google Drive ke service account
2. ✅ **STEP 2:** Test upload dari mahasiswa
3. ✅ **STEP 3:** Verify file masuk ke Google Drive
4. ✅ **STEP 4:** Test download dari dosen
5. ✅ **STEP 5:** Monitor logs untuk confirmation

---

**Last Updated:** 13 Oktober 2025  
**Version:** 1.0  
**Status:** ⚠️ Need to share folder to service account
