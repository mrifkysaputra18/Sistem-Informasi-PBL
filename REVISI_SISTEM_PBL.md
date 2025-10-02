# Revisi Sistem Informasi PBL - Hasil Konsultasi Klien

## 📋 Daftar Revisi

Berdasarkan hasil konsultasi dengan klien, berikut adalah daftar revisi yang perlu diimplementasikan:

---

## 1. ✅ Update Progres → Upload Progres Diganti

**Perubahan:**
- Nama fitur diubah dari "Upload Progres" menjadi "Update Progres"
- Fitur sekarang memiliki 2 opsi:
  1. **Upload bukti** (file/dokumen)
  2. **Hanya mencentang** (tanpa upload)

**Implementasi:**
- ✅ Tabel `weekly_progress` sudah ditambahkan field `is_checked_only`
- ✅ Field `documents` sudah dibuat nullable
- 🔄 Perlu update UI untuk memberikan opsi upload atau centang saja

---

## 2. ✅ Input Target Mingguan

**Perubahan:**
- Kelompok atau mahasiswa hanya input target mingguan
- Target bisa diselesaikan dengan atau tanpa bukti

**Implementasi:**
- ✅ Tabel `weekly_targets` sudah dibuat dengan struktur:
  - `group_id`: Kelompok yang memiliki target
  - `week_number`: Minggu ke-berapa
  - `title`: Judul target
  - `description`: Deskripsi target
  - `is_completed`: Status selesai/belum
  - `evidence_file`: File bukti (opsional)
  - `completed_at`: Waktu penyelesaian
  - `completed_by`: User yang menyelesaikan
- ✅ Model `WeeklyTarget` sudah dibuat
- 🔄 Perlu buat controller dan view untuk input target

---

## 3. ⏳ Mahasiswa Tidak Lihat Peringkat

**Perubahan:**
- Mahasiswa tidak perlu bisa melihat peringkat di sistem
- Hanya admin dan dosen yang bisa melihat peringkat

**Implementasi:**
- ⏳ Perlu update middleware/authorization
- ⏳ Hide menu peringkat untuk role mahasiswa

---

## 4. ⏳ Kriteria Kecepatan Progres dengan To-Do List

**Perubahan:**
- Kriteria "Kecepatan Progres" berisi to-do list perminggu
- Contoh: Jika ada 2 target tapi hanya selesai 1 = nilai 50%
- Jika ada 2 target tapi selesai 3 = nilai lebih bagus

**Implementasi:**
- ✅ Tabel `weekly_targets` sudah support ini
- ⏳ Perlu buat algoritma perhitungan nilai berdasarkan completion rate
- ⏳ Update `RankingService` untuk menghitung kecepatan progres

**Formula yang akan digunakan:**
```
Nilai Kecepatan Progres = (Jumlah Target Selesai / Jumlah Target yang Direncanakan) × 100
```

---

## 5. ⏳ Hanya Admin yang Bisa Menghitung Peringkat

**Perubahan:**
- Hanya admin yang memiliki akses untuk menghitung/recalculate peringkat
- Tombol "Hitung Ulang Peringkat" hanya muncul untuk admin

**Implementasi:**
- ⏳ Update authorization di `GroupScoreController@recalc`
- ⏳ Update view untuk hide tombol dari non-admin

---

## 6. ⏳ Batasi Pengelolaan Anggota

**Perubahan:**
- Hanya koordinator dan admin yang bisa:
  - Menambah anggota kelompok
  - Menghapus anggota kelompok

**Implementasi:**
- ⏳ Update `GroupPolicy` untuk membatasi akses
- ⏳ Update middleware di routes
- ⏳ Update UI untuk hide tombol tambah/hapus untuk non-authorized users

---

## 7. ⏳ Fitur Import Excel

**Perubahan:**
- Tambahkan fitur import Excel untuk:
  1. Daftar kelompok (per kelas)
  2. Daftar mata kuliah yang terkait PBL

**Implementasi:**
- ⏳ Install package `maatwebsite/excel`
- ⏳ Buat import class untuk kelompok
- ⏳ Buat import class untuk mata kuliah
- ⏳ Buat UI upload Excel dengan template download

**Format Excel:**
- **Kelompok:** Nama Kelompok, Kelas, Ketua, Anggota 1-5
- **Mata Kuliah:** Kode, Nama, Deskripsi, Status PBL

---

## 8. ✅ Alur Dosen: Kelas → Mata Kuliah → Kelompok → Mahasiswa

**Perubahan:**
- Implementasi navigasi hierarki untuk dosen
- Alur: Pilih Kelas → Pilih Mata Kuliah → Lihat Kelompok → Lihat Mahasiswa

**Implementasi:**
- ✅ Tabel `subjects` sudah dibuat
- ✅ Relationship `class_rooms.subject_id` sudah ada
- 🔄 Perlu update UI untuk menampilkan hierarchy navigation
- 🔄 Update ClassRoomController untuk filter berdasarkan subject

---

## 9. ⏳ Fitur Filter

**Perubahan:**
- Tambahkan filter untuk:
  - Filter kelas (berdasarkan semester, program studi)
  - Filter mata kuliah (berdasarkan status PBL, kode)

**Implementasi:**
- ⏳ Buat component filter di view
- ⏳ Update controller dengan query filter
- ⏳ Implementasi AJAX/query string untuk filtering

---

## 10. ⏳ Rubrik Penilaian per Mata Kuliah

**Perubahan:**
- Nilai laporan termasuk dalam nilai akhir PBL
- Setiap mata kuliah memiliki rubrik penilaian yang berbeda-beda

**Implementasi:**
- ⏳ Tambah field `subject_id` ke tabel `criteria`
- ⏳ Update Criterion model dengan relationship ke Subject
- ⏳ Update UI untuk memilih kriteria berdasarkan mata kuliah
- ⏳ Buat sistem template rubrik untuk setiap mata kuliah

---

## 11. ⏳ Permission Koordinator

**Perubahan:**
- Koordinator bisa:
  - ✅ Menambah dan menghapus anggota
  - 📊 Memantau progress kelompok
- Koordinator tidak bisa:
  - ❌ Menilai (hanya memantau)

**Implementasi:**
- ⏳ Update role di User model (tambah role 'koordinator')
- ⏳ Buat middleware untuk koordinator
- ⏳ Update GroupPolicy untuk koordinator permissions
- ⏳ Update UI untuk distinguish antara koordinator dan dosen

---

## 📊 Status Implementasi

### Completed ✅
- Database structure untuk Subjects
- Database structure untuk Weekly Targets
- Model dan relationships
- Migration berhasil dijalankan

### In Progress 🔄
- Update UI untuk Update Progres
- Controller untuk Weekly Targets
- Hierarchy navigation (Kelas → Mata Kuliah)

### Pending ⏳
- Permission & Authorization
- Import Excel feature
- Filter feature
- Rubrik per Mata Kuliah
- Algoritma perhitungan kecepatan progres

---

## 🗂️ Struktur Database Baru

### Tabel: `subjects`
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| code | string | Kode mata kuliah (unique) |
| name | string | Nama mata kuliah |
| description | text | Deskripsi (nullable) |
| is_pbl_related | boolean | Apakah terkait PBL |
| is_active | boolean | Status aktif |

### Tabel: `weekly_targets`
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| group_id | bigint | Foreign key ke groups |
| week_number | integer | Minggu ke-berapa |
| title | string | Judul target |
| description | text | Deskripsi (nullable) |
| is_completed | boolean | Status selesai |
| evidence_file | string | File bukti (nullable) |
| completed_at | timestamp | Waktu selesai (nullable) |
| completed_by | bigint | User yang menyelesaikan (nullable) |

### Update: `class_rooms`
- Ditambah field: `subject_id` (bigint, nullable, foreign key)

### Update: `weekly_progress`
- Ditambah field: `is_checked_only` (boolean, default false)

---

## 🎯 Next Steps

1. **Phase 1 - Core Features:**
   - Implementasi Weekly Targets CRUD
   - Update Weekly Progress UI (upload vs centang)
   - Implementasi hierarchy navigation

2. **Phase 2 - Permissions:**
   - Update authorization system
   - Implement role-based access control
   - Hide/show features based on roles

3. **Phase 3 - Advanced Features:**
   - Excel import functionality
   - Filter implementation
   - Subject-specific rubrics

4. **Phase 4 - Calculation & Reporting:**
   - Update ranking calculation
   - Progress speed criteria algorithm
   - Reporting system

---

**Last Updated:** 2025-10-01
**Status:** Database & Models Completed, Moving to Controllers & Views


