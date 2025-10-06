# 🚀 Next Steps - Sistem PBL

**Tanggal:** 6 Oktober 2025  
**Status:** ✅ **Fondasi Selesai, Siap Lanjut Fitur Utama**

---

## ✅ **YANG SUDAH SELESAI:**

### **1. Infrastruktur Dasar** ✅
- ✅ Laravel 11 setup
- ✅ MySQL database connection
- ✅ Google SSO (@politala.ac.id)
- ✅ Google Drive integration (Service Account)
- ✅ Role-based access (Admin, Koordinator, Dosen, Mahasiswa)
- ✅ Modern UI dengan Tailwind CSS

### **2. Manajemen User** ✅
- ✅ CRUD user lengkap
- ✅ Filter by role, kelas, status
- ✅ View mahasiswa tanpa kelompok
- ✅ Tab navigation by role
- ✅ Toggle active/inactive user

### **3. Periode Akademik** ✅
- ✅ Model & migration
- ✅ Koneksi ke semua komponen:
  - Kelas (classrooms)
  - Kelompok (groups)
  - Mahasiswa (students)
  - Penilaian (scores)
  - Progress (weekly progress)

### **4. Manajemen Kelas** ✅
- ✅ CRUD kelas lengkap (BARU DIPERBAIKI!)
- ✅ Edit kelas ✅
- ✅ Hapus kelas ✅
- ✅ Kelola kelas (show detail) ✅
- ✅ Filter by mata kuliah & semester
- ✅ Link ke periode akademik
- ✅ Max groups per kelas

### **5. Manajemen Kelompok** ✅
- ✅ CRUD kelompok
- ✅ Tambah/hapus anggota
- ✅ Set ketua kelompok
- ✅ Filter mahasiswa by kelas
- ✅ Validasi kelompok penuh
- ✅ Link ke kelas & periode

### **6. Progress Mingguan** ✅
- ✅ Submit progress
- ✅ Upload evidence ke Google Drive
- ✅ Review oleh dosen
- ✅ Target mingguan
- ✅ Feedback system

### **7. Kriteria Penilaian (Model)** ✅
- ✅ Model Criterion
- ✅ Bobot penilaian
- ✅ Tipe: Benefit/Cost
- ✅ Segment: Group/Student
- ✅ Link ke Subject/Mata Kuliah

---

## 🎯 **PRIORITAS FITUR SELANJUTNYA:**

Berdasarkan pemahaman sistem PBL-1 (Semester 3), berikut prioritas fitur:

### **🔴 PRIORITAS TINGGI (Harus Segera):**

#### **1. Dashboard dengan Filter Periode Akademik** 📅
**Tujuan:** Admin/Koordinator bisa switch antar periode akademik

**Fitur:**
- Dropdown pilih periode akademik di header/dashboard
- Session untuk menyimpan periode yang dipilih
- Filter otomatis semua data berdasarkan periode
- Statistik per periode:
  - Total kelas
  - Total kelompok
  - Total mahasiswa
  - Rata-rata nilai
  - Progress keseluruhan

**File yang perlu dibuat/diubah:**
- `app/Http/Middleware/SetAcademicPeriod.php` (middleware baru)
- `app/View/Composers/AcademicPeriodComposer.php` (view composer)
- `resources/views/components/period-selector.blade.php` (component)
- Update semua dashboard controller

**Estimasi:** 2-3 jam

---

#### **2. Kelola Kriteria Penilaian** 📏
**Tujuan:** Admin/Koordinator bisa set kriteria untuk PBL-1

**Fitur:**
- CRUD kriteria penilaian
- Set bobot (total harus 100%)
- Set tipe: Benefit (semakin tinggi semakin baik) atau Cost (semakin rendah semakin baik)
- Set segment: Group atau Student
- Link ke mata kuliah (PBL-1, PBL-2)
- Validation: total bobot = 100%

**Contoh Kriteria PBL-1:**
1. Kecepatan Progress (20%) - Benefit - Group
2. Kualitas Output (30%) - Benefit - Group
3. Keterlambatan (15%) - Cost - Group
4. Kehadiran (10%) - Benefit - Student
5. Presentasi (25%) - Benefit - Group

**File yang perlu dibuat:**
- `app/Http/Controllers/CriterionController.php`
- `resources/views/criteria/index.blade.php`
- `resources/views/criteria/create.blade.php`
- `resources/views/criteria/edit.blade.php`
- Routes untuk CRUD

**Estimasi:** 3-4 jam

---

#### **3. Input Nilai Kelompok** 📊
**Tujuan:** Dosen bisa input nilai per kriteria per kelompok

**Fitur:**
- Form input nilai per kelompok
- List semua kriteria yang ada
- Input nilai 0-100 per kriteria
- Auto-calculate total score (nilai × bobot)
- Auto-update ranking kelompok
- History perubahan nilai
- Preview nilai sebelum save

**Rumus:**
```
Total Score = Σ(Nilai Kriteria × Bobot Kriteria)

Contoh:
- Kecepatan: 85 × 0.20 = 17.00
- Kualitas: 90 × 0.30 = 27.00
- Keterlambatan: 5 × 0.15 = 0.75 (diinvert karena Cost)
- Kehadiran: 95 × 0.10 = 9.50
- Presentasi: 88 × 0.25 = 22.00
Total = 76.25
```

**File yang perlu dibuat:**
- `app/Http/Controllers/GroupScoreController.php`
- `resources/views/scores/create.blade.php`
- `resources/views/scores/edit.blade.php`
- Update `GroupScore` model untuk auto-calculate

**Estimasi:** 4-5 jam

---

#### **4. Ranking System** 🏆
**Tujuan:** Auto-calculate dan display ranking kelompok

**Fitur:**
- Auto-calculate ranking berdasarkan total score
- Ranking per kelas (tidak global)
- Display ranking di:
  - Dashboard kelompok
  - Detail kelompok
  - List kelompok per kelas
- Update ranking setiap kali nilai diubah
- Visual ranking: badge, warna, icon

**Logic:**
```php
// Ranking per kelas
$groups = Group::where('class_room_id', $classRoomId)
    ->orderBy('total_score', 'desc')
    ->get();

foreach ($groups as $index => $group) {
    $group->ranking = $index + 1;
    $group->save();
}
```

**File yang perlu dibuat/diubah:**
- `app/Services/RankingService.php` (sudah ada, perlu update)
- Update `GroupScore` observer untuk trigger ranking update
- `resources/views/components/ranking-badge.blade.php`

**Estimasi:** 2-3 jam

---

### **🟠 PRIORITAS MENENGAH (Setelah Prioritas Tinggi):**

#### **5. Laporan & Export** 📄
- Export nilai per kelas (Excel)
- Export ranking (Excel)
- Generate PDF report per periode
- Export mahasiswa tanpa kelompok
- Statistik per kelas

**Estimasi:** 3-4 jam

---

#### **6. Manajemen Periode Akademik (UI)** 📅
- CRUD periode akademik
- Set periode aktif
- Archive periode lama
- Copy setup dari periode sebelumnya

**Estimasi:** 2-3 jam

---

#### **7. Enhanced Progress Tracking** 📈
- Timeline progress per kelompok
- Grafik progress vs target
- Alert keterlambatan
- Reminder otomatis

**Estimasi:** 4-5 jam

---

### **🟢 PRIORITAS RENDAH (Nice to Have):**

#### **8. Dashboard Analytics** 📊
- Chart.js integration
- Progress visualization
- Ranking comparison chart
- Attendance chart

**Estimasi:** 3-4 hours

---

#### **9. Notification System** 🔔
- Email notification
- In-app notification
- Deadline reminders
- Progress review alerts

**Estimasi:** 3-4 jam

---

#### **10. API untuk Mobile** 📱
- RESTful API
- Authentication token
- Endpoint untuk mahasiswa
- Endpoint untuk dosen

**Estimasi:** 5-6 jam

---

## 📋 **WORKFLOW PENGEMBANGAN YANG DISARANKAN:**

### **Minggu 1: Setup Penilaian**
```
Hari 1-2: Dashboard dengan Filter Periode
Hari 3-4: Kelola Kriteria Penilaian
Hari 5:   Testing & Bug Fixing
```

### **Minggu 2: Sistem Penilaian**
```
Hari 1-3: Input Nilai Kelompok
Hari 4-5: Ranking System
```

### **Minggu 3: Laporan & Polish**
```
Hari 1-2: Laporan & Export
Hari 3-4: Manajemen Periode (UI)
Hari 5:   Testing Keseluruhan
```

### **Minggu 4: Enhancement**
```
Hari 1-2: Enhanced Progress Tracking
Hari 3-4: Dashboard Analytics
Hari 5:   Final Testing & Deployment
```

---

## 🎯 **REKOMENDASI: MULAI DARI MANA?**

**Saran saya, mulai dari urutan ini:**

1. **Dashboard dengan Filter Periode** (2-3 jam)
   - Paling fundamental
   - Diperlukan untuk fitur lainnya
   - Relatif cepat dikerjakan

2. **Kelola Kriteria Penilaian** (3-4 jam)
   - Diperlukan sebelum input nilai
   - Foundasi untuk sistem penilaian

3. **Input Nilai Kelompok** (4-5 jam)
   - Core feature untuk PBL
   - Termasuk validation & calculation

4. **Ranking System** (2-3 jam)
   - Auto-update saat nilai diinput
   - Visual feedback untuk mahasiswa

**Total untuk fitur utama: ~12-15 jam kerja**

---

## 💡 **PERTANYAAN UNTUK ANDA:**

1. **Apakah urutan prioritas ini sudah sesuai dengan kebutuhan?**
2. **Fitur mana yang ingin dikerjakan PERTAMA?**
3. **Apakah ada fitur lain yang belum saya sebutkan?**
4. **Berapa lama target deadline untuk PBL-1 siap digunakan?**

---

## 🔧 **CARA TEST HASIL FIX HARI INI:**

### **Test Edit & Hapus Kelas:**
```bash
# 1. Pastikan server jalan
php artisan serve

# 2. Buka browser
http://localhost:8000/classrooms

# 3. Test Edit:
- Klik tombol "Edit" (kuning)
- Ubah nama kelas
- Klik "Update Kelas"
- Seharusnya berhasil!

# 4. Test Hapus:
- Klik tombol "Hapus" (merah) pada kelas kosong
- Konfirmasi
- Seharusnya berhasil dihapus!

# 5. Test Kelola Kelas:
- Klik "Kelola Kelas" (biru)
- Seharusnya muncul detail kelas + list kelompok
```

---

## 📚 **DOKUMENTASI TERKAIT:**

- `PEMAHAMAN_SISTEM_PBL.md` - Pemahaman lengkap sistem
- `KONEKSI_PERIODE_AKADEMIK.md` - Relasi database
- `FIX_KELOLA_KELAS.md` - Fix yang baru saja dikerjakan
- `DATA_DUMMY_SUMMARY.md` - Data dummy yang tersedia

---

## ✅ **CHECKLIST SEBELUM LANJUT:**

- [x] Database connection OK (MySQL)
- [x] Google SSO working
- [x] Google Drive integration working
- [x] User management complete
- [x] Class management fixed
- [x] Group management working
- [x] Period akademik connected
- [ ] **Dashboard with period filter** ← NEXT!
- [ ] Criteria management
- [ ] Score input
- [ ] Ranking system

---

**Silakan konfirmasi fitur mana yang ingin dikerjakan selanjutnya!** 🚀

