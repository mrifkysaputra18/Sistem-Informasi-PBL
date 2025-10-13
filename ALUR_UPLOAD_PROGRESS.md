# 📋 ALUR UPLOAD PROGRESS MINGGUAN MAHASISWA

## 🎯 Overview
Sistem ini memungkinkan mahasiswa untuk mengupload progress mingguan mereka sebagai bagian dari monitoring PBL (Project-Based Learning). Progress dapat berupa dokumen, laporan, atau hanya konfirmasi penyelesaian tanpa file.

---

## 📌 ALUR LENGKAP

### **1️⃣ TAHAP PERSIAPAN**

#### A. Dosen Membuat Target Mingguan
```
Dosen → Buat Target Mingguan → Pilih Kelompok → Set Deadline
```
- Dosen login ke sistem
- Buat target mingguan untuk kelompok tertentu
- Tentukan judul, deskripsi, dan deadline
- Target muncul di dashboard mahasiswa

---

### **2️⃣ TAHAP UPLOAD PROGRESS**

#### A. Mahasiswa Melihat Target
**Lokasi:** Dashboard Mahasiswa (`/dashboard` atau `/mahasiswa/dashboard`)

**Tampilan:**
- Card "Target Mingguan" dengan list target yang diberikan
- Setiap target menampilkan:
  - Week number (Minggu ke berapa)
  - Title dan description
  - Deadline
  - Status (Pending/Submitted/Approved/Revision)
  - Tombol "Upload Progress"

#### B. Klik Upload Progress
**Route:** `/weekly-progress/upload?group_id={id}&week_number={n}&target_id={id}`

**Controller:** `WeeklyProgressController@upload()`

**Proses Backend:**
1. Validasi group_id dan week_number ada
2. Cek apakah user adalah anggota kelompok
3. Load data group dan target
4. Tampilkan form upload

---

### **3️⃣ TAHAP MENGISI FORM**

#### Form Upload Progress
**File:** `resources/views/weekly-progress/upload.blade.php`

**Input Fields:**

| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| `title` | Text | ✅ Yes | Auto-filled dari target |
| `description` | Textarea | ❌ No | Deskripsi progress yang dikerjakan |
| `evidence[]` | File | ❌ No | Multiple files (max 5MB/file) |
| `is_checked_only` | Checkbox | ❌ No | Selesai tanpa upload file |
| `group_id` | Hidden | ✅ Yes | ID kelompok |
| `week_number` | Hidden | ✅ Yes | Minggu ke berapa |
| `target_id` | Hidden | ✅ Yes | ID target mingguan |

**Fitur Form:**
- ✅ Upload multiple files sekaligus
- ✅ Preview file yang dipilih
- ✅ Validasi ukuran file (max 5MB)
- ✅ Support berbagai format (PDF, Word, Excel, Image, ZIP)
- ✅ Opsi submit tanpa file (checkbox)

---

### **4️⃣ TAHAP SUBMIT**

#### A. Klik "Submit Progress"
**Route:** `POST /weekly-progress/store`

**Controller:** `WeeklyProgressController@store()`

**Proses Backend:**

```php
1. Validasi Request
   ├─ Validasi group_id exists
   ├─ Validasi week_number (integer, min:1)
   ├─ Validasi title (required, string)
   ├─ Validasi description (optional)
   ├─ Validasi files (max 5MB per file)
   └─ Cek membership kelompok

2. Upload Files
   ├─ Prioritas: Upload ke Google Drive
   ├─ Fallback: Simpan ke local storage
   ├─ Generate file metadata (name, URL, timestamp)
   └─ Simpan ke array $evidencePaths

3. Simpan ke Database (weekly_progress)
   ├─ group_id
   ├─ week_number
   ├─ title
   ├─ description
   ├─ activities (dari description atau default)
   ├─ documents (array file metadata)
   ├─ is_checked_only
   ├─ status: 'submitted'
   └─ submitted_at: now()

4. Update Target Status
   ├─ Update weekly_targets table
   ├─ Set submission_status = 'submitted'
   └─ Set submitted_at = now()

5. Redirect & Notifikasi
   ├─ Redirect ke dashboard
   └─ Flash message: "Progress berhasil diupload"
```

---

### **5️⃣ TAHAP REVIEW (DOSEN)**

#### A. Dosen Melihat Submission
- Dosen login ke dashboard
- Lihat daftar submission yang masuk
- Klik "Review" pada progress yang di-submit

#### B. Proses Review
- Download dan cek file dokumentasi
- Berikan feedback/comment
- Ubah status:
  - ✅ **Approved** → Progress diterima
  - 🔄 **Revision** → Perlu perbaikan
  - ❌ **Rejected** → Ditolak

#### C. Mahasiswa Menerima Feedback
- Status berubah di dashboard
- Jika revision → bisa upload ulang
- Jika approved → target selesai

---

## 🔄 FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────┐
│                    MAHASISWA LOGIN                          │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│              DASHBOARD MAHASISWA                            │
│  - Lihat Target Mingguan                                    │
│  - Status: Pending/Submitted/Approved/Revision              │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
         ┌─────────────────────────┐
         │  Klik "Upload Progress" │
         └────────────┬────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                  FORM UPLOAD PROGRESS                       │
│  1. Isi Title (auto-filled)                                 │
│  2. Isi Description (optional)                              │
│  3. Upload File (optional)                                  │
│  4. Atau: Centang "Selesai tanpa file"                     │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
         ┌─────────────────────────┐
         │  Klik "Submit Progress" │
         └────────────┬────────────┘
                      │
                      ▼
┌─────────────────────────────────────────────────────────────┐
│                  BACKEND PROCESSING                         │
│  1. Validasi data                                           │
│  2. Upload file ke Google Drive / Local                     │
│  3. Simpan ke database                                      │
│  4. Update status target                                    │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
┌─────────────────────────────────────────────────────────────┐
│               STATUS = SUBMITTED                            │
│  - Progress masuk antrian review                            │
│  - Dosen dapat melihat submission                           │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
         ┌─────────────────────────┐
         │    DOSEN REVIEW         │
         └────────────┬────────────┘
                      │
          ┌───────────┴──────────┐
          ▼                      ▼
    ┌──────────┐          ┌──────────┐
    │ APPROVED │          │ REVISION │
    └────┬─────┘          └────┬─────┘
         │                     │
         ▼                     ▼
    ┌──────────┐          ┌──────────┐
    │ SELESAI  │          │ RE-UPLOAD│
    └──────────┘          └──────────┘
```

---

## 📂 FILE STORAGE

### Google Drive (Priority)
```php
Location: Folder ID dari config('services.google_drive.folder_id')
Format: {
    'file_id': 'DRIVE_FILE_ID',
    'file_name': 'original_name.pdf',
    'file_url': 'https://drive.google.com/file/d/...',
    'uploaded_at': '2025-10-13 15:27:48'
}
```

### Local Storage (Fallback)
```php
Location: storage/app/public/weekly-progress/evidence/
Format: {
    'local_path': 'weekly-progress/evidence/random_hash.pdf',
    'file_name': 'original_name.pdf',
    'file_url': 'http://localhost/storage/weekly-progress/evidence/...',
    'uploaded_at': '2025-10-13 15:27:48'
}
```

---

## 🗄️ DATABASE TABLES

### Table: `weekly_progress`
```sql
CREATE TABLE `weekly_progress` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `group_id` bigint NOT NULL,
  `week_number` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NULL,
  `activities` text NULL,
  `achievements` text NULL,
  `challenges` text NULL,
  `next_week_plan` text NULL,
  `documents` json NULL,
  `status` enum('draft','submitted','reviewed') DEFAULT 'draft',
  `submitted_at` timestamp NULL,
  `deadline` timestamp NULL,
  `is_locked` boolean DEFAULT false,
  `is_checked_only` boolean DEFAULT false,
  `created_at` timestamp,
  `updated_at` timestamp,
  UNIQUE KEY (group_id, week_number)
);
```

### Table: `weekly_targets`
```sql
CREATE TABLE `weekly_targets` (
  `id` bigint PRIMARY KEY AUTO_INCREMENT,
  `group_id` bigint NOT NULL,
  `week_number` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NULL,
  `deadline` timestamp NULL,
  `submission_status` enum('pending','submitted','late','approved','revision'),
  `submitted_at` timestamp NULL,
  `is_open` boolean DEFAULT true,
  `created_by` bigint NULL,
  `created_at` timestamp,
  `updated_at` timestamp
);
```

---

## 🔐 AUTHORIZATION & SECURITY

### Validasi di Controller
```php
1. Cek User Authentication
   └─ Middleware: auth

2. Cek Role Mahasiswa
   └─ Middleware: role:mahasiswa

3. Cek Membership Kelompok
   └─ $group->members()->where('user_id', auth()->id())->exists()

4. Validasi File Upload
   ├─ Max size: 5MB per file
   ├─ Allowed types: PDF, Word, Excel, Image, ZIP
   └─ Multiple files allowed
```

---

## ⚠️ ERROR HANDLING

### Common Errors & Solutions

| Error | Penyebab | Solusi |
|-------|----------|--------|
| Field 'activities' doesn't have default value | Database constraint | ✅ Fixed: Migration membuat field nullable |
| Unauthorized | Bukan anggota kelompok | Cek membership di `group_members` |
| File too large | File > 5MB | Kompres file atau pilih file lebih kecil |
| Google Drive upload failed | API error | Fallback ke local storage |

---

## 🎨 UI/UX FEATURES

### Form Upload
- ✅ Auto-fill title dari target
- ✅ File preview before upload
- ✅ Drag & drop support (HTML5)
- ✅ Progress indicator
- ✅ Validation feedback real-time
- ✅ Mobile responsive

### Dashboard
- ✅ Color-coded status badges
- ✅ Deadline countdown
- ✅ Quick action buttons
- ✅ Filter by status
- ✅ Timeline view

---

## 🚀 QUICK REFERENCE

### Routes
```php
GET  /weekly-progress/upload          → Form upload
POST /weekly-progress/store           → Submit progress
GET  /weekly-progress/{id}            → Detail progress
GET  /weekly-progress/{id}/edit       → Edit progress
PUT  /weekly-progress/{id}            → Update progress
```

### Key Files
```
Controllers: app/Http/Controllers/WeeklyProgressController.php
Views:       resources/views/weekly-progress/upload.blade.php
Model:       app/Models/WeeklyProgress.php
Migration:   database/migrations/*_weekly_progress_table.php
```

---

## 📞 SUPPORT

Jika ada pertanyaan atau issue:
1. Cek log error di `storage/logs/laravel.log`
2. Pastikan migration sudah dijalankan: `php artisan migrate`
3. Clear cache: `php artisan cache:clear`
4. Rebuild assets: `npm run build`

---

**Last Updated:** 13 Oktober 2025
**Version:** 1.0
