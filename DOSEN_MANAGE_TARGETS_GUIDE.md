# 👨‍🏫 PANDUAN LENGKAP: DOSEN KELOLA TARGET MINGGUAN

## 📋 OVERVIEW

Dosen dapat **mengelola target mingguan** untuk mahasiswa dengan fitur lengkap:
- ✅ **CREATE** - Buat target baru untuk 1 kelompok, multiple kelompok, atau semua kelas
- ✅ **READ** - Lihat daftar target dan statistik submission
- ✅ **UPDATE** - Edit target yang belum disubmit
- ✅ **DELETE** - Hapus target yang belum disubmit
- ✅ **VIEW SUBMISSIONS** - Lihat hasil pengumpulan mahasiswa
- ✅ **REVIEW** - Review dan beri nilai submission
- ✅ **DOWNLOAD FILES** - Download file evidence mahasiswa
- ✅ **MANAGE STATUS** - Buka/tutup target secara manual

---

## 🎯 AKSES MENU

### **Login sebagai Dosen:**
```
URL: http://localhost:8000/login
Email: dosen@politala.ac.id
Password: password
```

### **Menu Utama:**
1. **Dashboard Dosen** → Lihat ringkasan target mingguan
2. **Kelola Target Mingguan** → `/targets` (Full CRUD)
3. **Review Target Mingguan** → `/target-reviews` (Review submission)

---

## 📝 FITUR 1: CREATE TARGET (Buat Target Baru)

### **Akses:**
```
Dashboard → Tombol "Buat Target Baru"
atau
Kelola Target Mingguan → Tombol "+ Buat Target Baru"
```

### **Step-by-Step:**

#### **Step 1: Klik "Buat Target Baru"**
- Tombol putih di header (kanan atas)
- Icon: ➕

#### **Step 2: Pilih Tipe Target**
Ada **3 opsi:**

##### **Opsi A: Single Group (1 Kelompok)**
```
✅ Gunakan jika:
- Target khusus untuk 1 kelompok
- Kelompok butuh target berbeda

Contoh:
- Kelompok A: Implementasi Login
- Kelompok B: Implementasi Register (berbeda)
```

**Cara:**
1. Pilih radio button "Single Group"
2. Pilih Kelas dari dropdown
3. Pilih Kelompok dari dropdown
4. Isi form target

##### **Opsi B: Multiple Groups (Beberapa Kelompok)**
```
✅ Gunakan jika:
- Target sama untuk beberapa kelompok tertentu
- Tidak semua kelompok di kelas

Contoh:
- Kelompok A, C, E: Implementasi Backend
- Kelompok B, D: Implementasi Frontend
```

**Cara:**
1. Pilih radio button "Multiple Groups"
2. Pilih Kelas dari dropdown
3. **Centang** kelompok yang mau diberi target (multiple checkbox)
4. Isi form target

##### **Opsi C: All Class (Semua Kelompok di Kelas)**
```
✅ Gunakan jika:
- Target sama untuk SEMUA kelompok di 1 kelas
- Paling efisien untuk tugas mingguan

Contoh:
- Semua kelompok TI-3A: Implementasi CRUD
```

**Cara:**
1. Pilih radio button "All Class"
2. Pilih Kelas dari dropdown
3. Isi form target
4. Target akan otomatis dibuat untuk SEMUA kelompok di kelas

#### **Step 3: Isi Form Target**

**Field yang harus diisi:**

| Field | Type | Wajib? | Keterangan |
|-------|------|--------|------------|
| **Minggu** | Number (1-16) | ✅ Ya | Minggu keberapa target ini |
| **Judul Target** | Text (max 255) | ✅ Ya | Judul singkat & jelas |
| **Deskripsi** | Textarea | ✅ Ya | Detail lengkap target |
| **Deadline** | DateTime | ✅ Ya | Batas waktu submit |

**Contoh Form:**
```
Minggu: 3
Judul Target: Implementasi Fitur CRUD Produk
Deskripsi: 
  Mahasiswa membuat fitur CRUD untuk produk dengan:
  - Create: Form tambah produk
  - Read: Daftar produk dengan pagination
  - Update: Form edit produk
  - Delete: Hapus produk dengan konfirmasi
  
  Tech Stack: Laravel 10, Tailwind CSS
  Database: MySQL (tabel products)

Deadline: 20/10/2025 23:59
```

#### **Step 4: Submit**
- Klik tombol **"Simpan Target"** (hijau)
- Target akan dibuat sesuai tipe yang dipilih
- Redirect ke halaman daftar target
- Muncul notifikasi sukses: "Target berhasil dibuat untuk X kelompok!"

---

## 📊 FITUR 2: VIEW TARGETS (Lihat Daftar Target)

### **Akses:**
```
Menu: Kelola Target Mingguan
URL: /targets
```

### **Tampilan Dashboard:**

#### **A. Statistik Cards (Atas)**
```
┌─────────────────────────────────────────────────────┐
│ Total Target     Sudah Submit    Approved          │
│     25              18              12              │
│                                                      │
│ Perlu Revisi     Belum Submit    Terlambat         │
│      3               4              3                │
└─────────────────────────────────────────────────────┘
```

**Keterangan:**
- **Total Target:** Semua target yang dibuat
- **Sudah Submit:** Mahasiswa sudah submit, menunggu review
- **Approved:** Target sudah direview & disetujui
- **Perlu Revisi:** Mahasiswa perlu revisi
- **Belum Submit:** Mahasiswa belum mengumpulkan
- **Terlambat:** Submit setelah deadline

#### **B. Filter & Search**
```
┌─────────────────────────────────────────────────────┐
│ [Filter Kelas ▼]  [Filter Minggu ▼]  [Status ▼]   │
│                                                      │
│ [Reset Filter]                                      │
└─────────────────────────────────────────────────────┘
```

**Cara Filter:**
1. Pilih Kelas → Filter target untuk kelas tertentu
2. Pilih Minggu → Filter minggu tertentu
3. Pilih Status → Filter berdasarkan submission status
4. Reset → Hapus semua filter

#### **C. Tabel Target**
```
┌────┬─────────┬────────────────┬─────────────┬─────────┬─────────┐
│ No │ Minggu  │ Judul Target   │ Kelompok    │ Status  │ Aksi    │
├────┼─────────┼────────────────┼─────────────┼─────────┼─────────┤
│ 1  │ Week 3  │ Implementasi..│ TI-3A / A   │ ✅ Submit│ 👁️ 📝 🗑️│
│ 2  │ Week 3  │ Implementasi..│ TI-3A / B   │ ⏰ Pending│ 👁️ 📝 🗑️│
│ 3  │ Week 2  │ Database...   │ TI-3A / A   │ ✅ Approved│ 👁️     │
└────┴─────────┴────────────────┴─────────────┴─────────┴─────────┘
```

**Icon Aksi:**
- 👁️ **View** - Lihat detail target
- 📝 **Edit** - Edit target (jika belum disubmit)
- 🗑️ **Delete** - Hapus target (jika belum disubmit)

---

## ✏️ FITUR 3: EDIT TARGET (Update Target)

### **Syarat:**
- ❌ **TIDAK bisa edit** jika mahasiswa **SUDAH SUBMIT**
- ✅ **Bisa edit** jika status masih **PENDING** (belum disubmit)

### **Cara Edit:**

#### **Step 1: Klik Icon Edit (📝)**
Dari tabel target, klik icon pensil

#### **Step 2: Update Form**
Sama seperti form create, bisa ubah:
- Minggu
- Judul
- Deskripsi
- Deadline

#### **Step 3: Simpan**
- Klik "Update Target"
- Target akan terupdate
- Notifikasi sukses

#### **Jika Sudah Disubmit:**
```
❌ Error: "Target yang sudah ada submission tidak dapat diedit. 
           Silakan buat target baru."
```

---

## 🗑️ FITUR 4: DELETE TARGET (Hapus Target)

### **Syarat:**
- ❌ **TIDAK bisa hapus** jika mahasiswa **SUDAH SUBMIT**
- ✅ **Bisa hapus** jika status masih **PENDING**

### **Cara Hapus:**

#### **Step 1: Klik Icon Delete (🗑️)**
Dari tabel target, klik icon tempat sampah

#### **Step 2: Konfirmasi**
```
⚠️ PERHATIAN!

Apakah Anda yakin ingin menghapus target ini?

Target: Implementasi CRUD Produk
Minggu: 3
Kelompok: TI-3A / Kelompok A

⚠️ Tindakan ini tidak dapat dibatalkan!

[Batal]  [Hapus]
```

#### **Step 3: Confirm**
- Klik "Hapus"
- Target akan dihapus dari database
- Notifikasi sukses

#### **Jika Sudah Disubmit:**
```
❌ Error: "Target yang sudah ada submission tidak dapat dihapus."
```

---

## 👁️ FITUR 5: VIEW SUBMISSION (Lihat Hasil Pengumpulan)

### **Cara:**

#### **Step 1: Klik Icon View (👁️)**
Dari tabel target, klik icon mata

#### **Step 2: Lihat Detail**
**Tampilan akan show:**

##### **A. Info Target**
```
┌─────────────────────────────────────────────────────┐
│ Judul: Implementasi CRUD Produk                     │
│ Minggu: 3                                           │
│ Deadline: 20/10/2025 23:59                          │
│ Dibuat oleh: Dosen                                  │
│ Deskripsi: [detail target...]                      │
└─────────────────────────────────────────────────────┘
```

##### **B. Submission Mahasiswa** (Jika sudah submit)
```
┌─────────────────────────────────────────────────────┐
│ ✅ SUBMISSION                                       │
│                                                      │
│ Diselesaikan oleh: Mahasiswa A                     │
│ Tanggal Submit: 19/10/2025 15:30                   │
│ Status: ⏰ Tepat Waktu                              │
│                                                      │
│ Catatan Mahasiswa:                                  │
│ "Sudah selesai implementasi semua fitur CRUD..."   │
│                                                      │
│ File Evidence: (3 files)                            │
│  📄 laporan.pdf        [Download]                   │
│  🖼️ screenshot.png     [Download]                   │
│  📘 source_code.zip    [Download]                   │
│                                                      │
│ [Download All (ZIP)]  [Review & Beri Nilai]        │
└─────────────────────────────────────────────────────┘
```

##### **C. Review Dosen** (Jika sudah direview)
```
┌─────────────────────────────────────────────────────┐
│ ✅ REVIEW DOSEN                                     │
│                                                      │
│ Direview oleh: Dosen                                │
│ Tanggal Review: 20/10/2025 08:00                   │
│ Nilai: 85                                           │
│ Status: ✅ Approved                                  │
│                                                      │
│ Feedback:                                           │
│ "Good job! Implementasi sudah bagus..."            │
│                                                      │
│ Saran:                                              │
│ "Tambahkan validasi input untuk keamanan..."       │
└─────────────────────────────────────────────────────┘
```

---

## 📥 FITUR 6: DOWNLOAD FILES (Download Evidence)

### **A. Download Single File**

**Cara:**
1. Lihat detail target yang sudah disubmit
2. Di bagian "File Evidence"
3. Klik tombol **[Download]** di samping file
4. File akan terdownload ke browser

### **B. Download All Files (ZIP)**

**Cara:**
1. Lihat detail target yang sudah disubmit
2. Klik tombol **[Download All (ZIP)]** (biru)
3. ZIP akan terdownload otomatis

**Isi ZIP:**
```
Target_Week3_KelompokA_20251020100530.zip
├── _INFO_TARGET.txt        ← Info lengkap target
├── laporan.pdf             ← File evidence #1
├── screenshot.png          ← File evidence #2
└── source_code.zip         ← File evidence #3
```

**Isi _INFO_TARGET.txt:**
```
=== INFORMASI TARGET MINGGUAN ===

Kelompok: Kelompok A
Kelas: TI-3A
Minggu: 3
Target: Implementasi CRUD Produk
Deskripsi: [full description...]
Diselesaikan oleh: Mahasiswa A
Tanggal Submit: 19/10/2025 15:30
Catatan: "Sudah selesai implementasi..."

Total file: 3
Didownload oleh: Dosen
Tanggal download: 20/10/2025 10:05
```

---

## ⭐ FITUR 7: REVIEW & BERI NILAI

### **Akses:**
Ada **2 cara:**

#### **Cara 1: Dari Detail Target**
1. View target yang sudah disubmit
2. Klik tombol **"Review & Beri Nilai"**

#### **Cara 2: Dari Menu Review**
1. Menu **"Review Target Mingguan"** (`/target-reviews`)
2. Pilih target dari daftar pending review
3. Klik **"Review & Nilai"**

### **Form Review:**

#### **Field yang harus diisi:**

| Field | Type | Wajib? | Keterangan |
|-------|------|--------|------------|
| **Nilai** | Number (0-100) | ✅ Ya | Nilai submission |
| **Status Review** | Dropdown | ✅ Ya | Approved/Needs Revision/Rejected |
| **Feedback** | Textarea | ✅ Ya | Komentar untuk mahasiswa |
| **Saran** | Textarea | ❌ Tidak | Saran perbaikan (optional) |

#### **Pilihan Status:**

##### **1. Approved (Disetujui)** ✅
```
Gunakan jika:
- Submission bagus dan memenuhi requirement
- Tidak perlu revisi
- Mahasiswa bisa lanjut ke target berikutnya

Contoh Feedback:
"Good job! Implementasi CRUD sudah lengkap dan berfungsi dengan baik. 
Code quality bagus, UI menarik. Keep it up!"
```

##### **2. Needs Revision (Perlu Revisi)** ⚠️
```
Gunakan jika:
- Submission kurang lengkap
- Ada bugs yang perlu diperbaiki
- Perlu improvement

Contoh Feedback:
"Implementasi sudah bagus tapi ada beberapa yang perlu diperbaiki:
1. Delete belum ada konfirmasi
2. Validasi input masih kurang
3. Pagination belum berfungsi

Silakan revisi dan submit ulang."

Saran:
"Gunakan SweetAlert untuk konfirmasi delete, tambahkan Laravel 
validation untuk input form."
```

##### **3. Rejected (Ditolak)** ❌
```
Gunakan jika:
- Submission tidak sesuai requirement sama sekali
- Tidak ada effort yang signifikan
- Plagiarisme

Contoh Feedback:
"Submission tidak sesuai dengan requirement target. Code yang dikumpulkan 
tidak lengkap dan tidak berfungsi. Silakan kerjakan ulang dari awal."
```

### **Step-by-Step Review:**

#### **Step 1: Lihat Submission**
- Download & check file evidence
- Review code/dokumentasi
- Test functionality (jika applicable)

#### **Step 2: Isi Form Review**
```
Nilai: 85
Status: ✅ Approved
Feedback: "Good job! Implementasi sudah bagus..."
Saran: "Tambahkan unit test untuk CRUD operations"
```

#### **Step 3: Submit Review**
- Klik **"Simpan Review"**
- Review tersimpan
- Mahasiswa bisa lihat review di dashboard mereka
- Notifikasi: "Review berhasil disimpan dengan nilai 85!"

#### **Setelah Review:**
- Target status berubah ke "Approved"/"Revision"/"Rejected"
- Mahasiswa dapat notifikasi
- Jika "Needs Revision" → Mahasiswa bisa submit ulang
- Jika "Approved" → Target selesai, tidak bisa diubah lagi

---

## 🔓 FITUR 8: MANAGE TARGET STATUS (Buka/Tutup Target)

### **A. Close Target (Tutup Target Manual)**

#### **Kapan digunakan:**
```
✅ Gunakan jika:
- Mau tutup target lebih awal dari deadline
- Ada perubahan rencana
- Mahasiswa sudah dikasih waktu cukup tapi belum submit
```

#### **Cara:**
1. View detail target
2. Klik tombol **"Tutup Target"** (merah)
3. Konfirmasi
4. Target tertutup → Mahasiswa **TIDAK** bisa submit lagi

#### **Effect:**
- `is_open = false`
- Mahasiswa lihat: "Target sudah tertutup"
- Tidak bisa submit/edit submission

### **B. Reopen Target (Buka Kembali Target)**

#### **Kapan digunakan:**
```
✅ Gunakan jika:
- Mahasiswa minta perpanjangan waktu
- Ada technical issue saat deadline
- Mau kasih second chance
```

#### **Syarat:**
- ❌ **TIDAK bisa** reopen jika **sudah direview**
- ✅ **Bisa** reopen jika belum direview

#### **Cara:**
1. View detail target yang tertutup
2. Klik tombol **"Buka Kembali Target"** (hijau)
3. Konfirmasi
4. Target terbuka → Mahasiswa bisa submit lagi

#### **Effect:**
- `is_open = true`
- `reopened_by` = dosen ID
- `reopened_at` = timestamp
- Mahasiswa bisa submit lagi

### **C. Auto-Close Overdue Targets**

#### **Kapan digunakan:**
```
✅ Gunakan jika:
- Banyak target yang sudah lewat deadline
- Mau tutup semua target overdue sekaligus
- Maintenance rutin
```

#### **Cara:**
1. Menu: Kelola Target Mingguan
2. Tombol **"Auto-Close Overdue"** (atas)
3. Konfirmasi
4. Semua target yang melewati deadline akan **tertutup otomatis**

#### **Effect:**
- Semua target dengan `deadline < now()` dan `is_open = true` → tutup
- Notifikasi: "Berhasil menutup X target yang melewati deadline"

---

## 📈 FITUR 9: MONITORING & STATISTICS

### **A. Dashboard Stats**

Di dashboard dosen, ada **statistik real-time:**

```
┌─────────────────────────────────────────────────────┐
│ TARGET MINGGUAN STATS                               │
├─────────────────────────────────────────────────────┤
│ Total Target:        25                             │
│ Target Selesai:      18  (72%)                      │
│ Target Pending:       7  (28%)                      │
│ Perlu Review:         5  (⚠️ Butuh perhatian!)     │
└─────────────────────────────────────────────────────┘
```

### **B. Recent Targets Table**

```
┌───────────────────────────────────────────────────────┐
│ TARGET MINGGUAN TERBARU (10 terbaru)                 │
├───────────────────────────────────────────────────────┤
│ Week 3 │ CRUD Produk   │ TI-3A/A │ ✅ Submitted      │
│ Week 3 │ CRUD Produk   │ TI-3A/B │ ⏰ Pending        │
│ Week 2 │ Database      │ TI-3A/A │ ✅ Approved       │
│ ...                                                   │
└───────────────────────────────────────────────────────┘
```

### **C. Filter & Analytics**

Di halaman Kelola Target:
- Filter by Kelas → Lihat target per kelas
- Filter by Minggu → Lihat target per minggu
- Filter by Status → Lihat pending/submitted/approved
- Stats cards → Visual quick overview

---

## 🎯 WORKFLOW LENGKAP (End-to-End)

### **Skenario: Dosen Create Target untuk Minggu 3**

```
Step 1: LOGIN DOSEN
  ↓
Step 2: CREATE TARGET
  - Pilih "All Class"
  - Pilih Kelas: TI-3A
  - Minggu: 3
  - Judul: "Implementasi CRUD Produk"
  - Deskripsi: [lengkap]
  - Deadline: 27/10/2025 23:59
  - Submit
  ↓
  ✅ Target dibuat untuk 10 kelompok di TI-3A
  ↓
Step 3: MAHASISWA SUBMIT
  - Mahasiswa dari berbagai kelompok mengerjakan & submit
  - Dosen monitoring dari dashboard
  ↓
Step 4: REVIEW SUBMISSION
  - Dosen buka menu "Review Target Mingguan"
  - Pilih submission yang sudah dikumpulkan
  - Download file evidence
  - Review & beri nilai
  - Submit review
  ↓
Step 5: MAHASISWA LIHAT HASIL
  - Mahasiswa lihat nilai & feedback
  - Jika "Approved" → Selesai ✅
  - Jika "Needs Revision" → Mahasiswa revisi & submit ulang
  ↓
Step 6: MONITORING
  - Dosen monitoring statistics
  - Check completion rate
  - Follow up kelompok yang belum submit
```

---

## 🔒 PERMISSION & AUTHORIZATION

### **Yang Bisa Dilakukan Dosen:**

| Fitur | Dosen Kelas Sendiri | Dosen Kelas Lain | Admin |
|-------|---------------------|------------------|-------|
| **View Targets** | ✅ Ya | ❌ Tidak | ✅ Ya |
| **Create Target** | ✅ Ya | ❌ Tidak | ✅ Ya |
| **Edit Target** | ✅ Ya (own) | ❌ Tidak | ✅ Ya |
| **Delete Target** | ✅ Ya (own) | ❌ Tidak | ✅ Ya |
| **Review Submission** | ✅ Ya | ❌ Tidak | ✅ Ya |
| **Reopen/Close** | ✅ Ya | ❌ Tidak | ✅ Ya |

**Catatan:**
- Dosen **hanya bisa kelola** target untuk **kelas yang dia ampu**
- Tidak bisa lihat/edit target kelas lain
- Admin bisa kelola semua

---

## ⚠️ LIMITATIONS & RULES

### **Aturan Edit/Delete:**

```
❌ TIDAK BISA EDIT jika:
  - Mahasiswa sudah submit
  - Target sudah direview

✅ BISA EDIT jika:
  - Status masih PENDING
  - Mahasiswa belum submit
```

### **Aturan Reopen:**

```
❌ TIDAK BISA REOPEN jika:
  - Target sudah direview

✅ BISA REOPEN jika:
  - Target ditutup tapi belum direview
  - Mau kasih kesempatan mahasiswa submit
```

### **Best Practices:**

1. **Buat target di awal minggu** - Kasih waktu cukup untuk mahasiswa
2. **Deadline realistic** - Minimal 7 hari
3. **Deskripsi jelas** - Detail requirement, tech stack, deliverables
4. **Review tepat waktu** - Jangan tunda review agar mahasiswa tau progress
5. **Feedback konstruktif** - Kasih saran konkret untuk improvement
6. **Monitor statistics** - Check completion rate rutin

---

## 📝 TIPS & TRICKS

### **1. Bulk Create Target**
```
Gunakan "All Class" untuk create target ke semua kelompok sekaligus.
Lebih efisien daripada create satu-satu.
```

### **2. Template Deskripsi Target**
```
Judul: Implementasi [Fitur]

Deskripsi:
1. Objective:
   - [Apa yang harus dicapai]

2. Requirements:
   - [Requirement 1]
   - [Requirement 2]

3. Tech Stack:
   - Backend: [Laravel, etc]
   - Frontend: [Tailwind, etc]
   - Database: [MySQL, etc]

4. Deliverables:
   - Source code (ZIP)
   - Screenshot aplikasi
   - Laporan (PDF)

5. Kriteria Penilaian:
   - Functionality (40%)
   - Code Quality (30%)
   - UI/UX (20%)
   - Documentation (10%)

Deadline: [Date Time]
```

### **3. Naming Convention**
```
Good:
- "Implementasi CRUD Produk dengan Validation"
- "Database Design untuk Sistem Inventory"
- "API Integration dengan Payment Gateway"

Bad:
- "Tugas 1"
- "Minggu 3"
- "Project"
```

### **4. Deadline Setting**
```
Recommended:
- Weekday (Senin-Jumat): 23:59
- Give at least 7 days
- Avoid holidays

Example:
- Create: Senin, 14 Okt 2025
- Deadline: Minggu, 20 Okt 2025 23:59
- Buffer: 6 days
```

### **5. Review Schedule**
```
Best Practice:
- Review within 2-3 days setelah deadline
- Batch review: semua kelompok di hari yang sama
- Kasih feedback detail, bukan cuma nilai

Schedule Example:
- Deadline: Minggu 23:59
- Review: Selasa-Rabu
- Feedback: Kamis (mahasiswa sudah bisa lihat)
```

---

## 📊 MONITORING CHECKLIST

### **Weekly Monitoring:**
- [ ] Check completion rate per kelas
- [ ] Follow up kelompok yang belum submit
- [ ] Review submissions yang masuk
- [ ] Update statistics

### **Monthly Monitoring:**
- [ ] Analyze submission quality trends
- [ ] Check average scores
- [ ] Identify struggling groups
- [ ] Adjust difficulty level if needed

---

## 🆘 TROUBLESHOOTING

### **Problem 1: Target tidak muncul di dashboard mahasiswa**
**Solution:**
- Check apakah `is_open = true`
- Check deadline belum lewat
- Check mahasiswa adalah member kelompok
- Check kelas assignment benar

### **Problem 2: Tidak bisa edit target**
**Solution:**
- Check apakah mahasiswa sudah submit
- Jika sudah submit → buat target baru sebagai gantinya
- Atau minta mahasiswa cancel submission dulu

### **Problem 3: File tidak bisa didownload**
**Solution:**
- Check Google Drive permissions
- Check file masih exist di storage
- Check network connection
- Try "Download All (ZIP)" sebagai alternatif

### **Problem 4: Review tidak tersimpan**
**Solution:**
- Check semua field required sudah diisi
- Check nilai antara 0-100
- Check koneksi internet
- Check log error di laravel.log

---

## 🎉 SUMMARY

### **Quick Reference:**

| Action | URL/Path | Permission |
|--------|----------|------------|
| **List Targets** | `/targets` | Dosen, Admin |
| **Create Target** | `/targets/create` | Dosen, Admin |
| **Edit Target** | `/targets/{id}/edit` | Dosen (own), Admin |
| **Delete Target** | DELETE `/targets/{id}` | Dosen (own), Admin |
| **View Detail** | `/targets/{id}/show` | Dosen, Admin |
| **Review Submission** | `/targets/{id}/review` | Dosen, Admin |
| **Download File** | `/targets/{id}/download/{file}` | All with access |

### **Key Features:**
✅ Full CRUD untuk target mingguan  
✅ Bulk create (1/multiple/all groups)  
✅ Review & nilai submission  
✅ Download files (single/ZIP)  
✅ Statistics & monitoring  
✅ Manage target status (open/close)  
✅ Authorization & security  

---

**Last Updated:** 13 Oktober 2025  
**Version:** 1.0  
**Status:** ✅ Fully Documented
