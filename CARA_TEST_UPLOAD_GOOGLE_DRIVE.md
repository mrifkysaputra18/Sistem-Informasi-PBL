# 📤 Cara Test Upload ke Google Drive

**Upload sudah diupdate untuk menggunakan Google Drive!** ✅

---

## 🚀 **CARA TEST UPLOAD (5 LANGKAH):**

### **LANGKAH 1: Jalankan Server**

```bash
php artisan serve
```

Server akan berjalan di: http://localhost:8000

---

### **LANGKAH 2: Login sebagai Mahasiswa**

**URL:** http://localhost:8000/login

**Akun Demo Mahasiswa:**
```
Email: mahasiswa1@politala.ac.id
Password: password
```

Atau mahasiswa lain yang sudah ada di database.

---

### **LANGKAH 3: Akses Halaman Upload**

**Cara A: Dari Dashboard**
1. Setelah login, masuk ke **Dashboard Mahasiswa**
2. Lihat kelompok Anda
3. Klik **"Upload Progress"** atau **"Weekly Progress"**

**Cara B: Akses Langsung**

Copy URL ini sesuaikan dengan group ID:
```
http://localhost:8000/weekly-progress/create?group_id=1
```

**Ganti `group_id=1`** dengan ID kelompok mahasiswa yang login.

**Cara cek Group ID:**
- Lihat di Dashboard
- Atau via Tinker:
  ```bash
  php artisan tinker
  >>> \App\Models\User::where('email', 'mahasiswa1@politala.ac.id')->first()->groupMembers->first()->group_id
  ```

---

### **LANGKAH 4: Isi Form & Upload File**

Di halaman **"Upload Progress Mingguan"**:

1. **Minggu Ke:** Isi angka (contoh: `1`)

2. **Judul Progress:** Isi judul (contoh: `Progress Minggu 1 - Setup Database`)

3. **Deskripsi:** Isi deskripsi singkat

4. **Upload Bukti:**
   - Klik **"Choose Files"** atau **"Pilih File"**
   - Pilih file (PDF, gambar, doc, dll)
   - Bisa multiple files (max 5 file)
   - Max 2MB per file

5. **Klik tombol "Submit"** atau **"Kirim"**

---

### **LANGKAH 5: Verifikasi di Google Drive**

**Cek folder Google Drive Anda:**
```
https://drive.google.com/drive/folders/17Wn7r9hId88Z05L5y8th42cX5RLNqmZv
```

Atau buka folder **"Sistem PBL - Upload Files"** di Google Drive.

**File yang di-upload harusnya muncul disini!** ✅

---

## 🐛 **TROUBLESHOOTING:**

### **Error Saat Upload:**

Jika muncul error saat upload, file akan otomatis **fallback ke local storage** (`storage/app/public/evidence/`).

**Cek log error:**
```bash
tail -f storage/logs/laravel.log
```

**Error umum:**

#### **1. "File not found: folder_id"**

**Penyebab:** Folder ID salah atau belum di-share

**Solusi:**
1. Cek folder ID di `.env` benar
2. Pastikan folder sudah di-share ke service account
3. Tunggu 30-60 detik (Google sync delay)

---

#### **2. "The API is not enabled"**

**Penyebab:** Google Drive API belum enabled

**Solusi:**
```
Google Cloud Console
→ APIs & Services → Library
→ Google Drive API → ENABLE
```

---

#### **3. "Insufficient Permission"**

**Penyebab:** Service account belum punya akses Editor

**Solusi:**
1. Buka folder di Google Drive
2. Share → Paste service account email
3. Role: **Editor** (bukan Viewer!)
4. Share

---

## 💡 **TIPS:**

### **Test dengan File Kecil Dulu:**

Upload file kecil dulu untuk test:
- ✅ File teks (.txt) - beberapa KB
- ✅ Gambar kecil (.jpg, .png) - < 500 KB
- ✅ PDF kecil - < 1 MB

Jangan langsung upload file besar!

---

### **Cek Upload Berhasil:**

**Di Google Drive:**
- Refresh folder (F5)
- File harusnya muncul dengan nama asli
- Klik file untuk preview

**Di Sistem:**
- Success message: "Progress mingguan berhasil diupload!"
- Redirect ke Dashboard

---

## 📊 **ALUR UPLOAD:**

```
User Upload File di Browser
          ↓
WeeklyProgressController::store()
          ↓
GoogleDriveService::uploadFile()
          ↓
Google Drive API
          ↓
File tersimpan di Google Drive folder
          ↓
File ID disimpan di database
          ↓
Success! ✅
```

---

## ✅ **CHECKLIST TEST:**

- [ ] Server running (`php artisan serve`)
- [ ] Login sebagai mahasiswa
- [ ] Akses halaman upload progress
- [ ] Isi form (minggu, judul, deskripsi)
- [ ] Pilih file untuk upload
- [ ] Klik submit
- [ ] Cek success message
- [ ] Buka Google Drive
- [ ] Refresh folder (F5)
- [ ] ✅ File muncul!

---

## 🎯 **JIKA ERROR 404 MASIH TERJADI:**

Kemungkinan **Folder ID di `.env` SALAH**!

**Solusi:**
1. Buka folder "Sistem PBL - Upload Files" di browser
2. Copy URL lengkap
3. Extract Folder ID dari URL
4. Update `.env` dengan Folder ID yang benar
5. `php artisan config:clear`
6. Test lagi

---

**Silakan coba upload file sekarang dengan langkah-langkah di atas!** 📤

**Jika ada error, screenshot errornya!** 📸

