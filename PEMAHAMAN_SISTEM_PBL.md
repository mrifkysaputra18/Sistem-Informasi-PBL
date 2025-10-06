# 📚 PEMAHAMAN SISTEM PBL (Project-Based Learning)

**Dibuat:** 6 Oktober 2025  
**Status:** ✅ **DIPAHAMI DENGAN BAIK**

---

## 🎯 **TUJUAN WEBSITE:**

Website ini bertujuan untuk **penilaian mahasiswa yang melakukan Project-Based Learning (PBL)** dengan 2 tingkatan:

| PBL | Semester | Status Fokus |
|-----|----------|--------------|
| **PBL-1** | **Semester 3** | ✅ **FOKUS SEKARANG** |
| **PBL-2** | **Semester 5** | ⏳ Nanti |

---

## 🏗️ **STRUKTUR SISTEM PBL-1 (FOKUS SAAT INI):**

```
📅 PERIODE AKADEMIK (Tahun Ajaran)
    │
    ├─→ 📖 SUBJECT (Mata Kuliah PBL-1)
    │      └─→ 📏 KRITERIA PENILAIAN
    │
    ├─→ 🏫 KELAS (TI-3A, TI-3B, dll) - Semester 3
    │      │
    │      ├─→ 👥 MAHASISWA (25-30 per kelas)
    │      │
    │      └─→ 👨‍👩‍👧‍👦 KELOMPOK (5-6 kelompok per kelas)
    │             │
    │             ├─→ ANGGOTA KELOMPOK (5 mahasiswa per kelompok)
    │             │
    │             ├─→ 📊 PROJECT (Tugas/Projek)
    │             │
    │             ├─→ 📈 PROGRESS MINGGUAN
    │             │
    │             ├─→ 🎯 TARGET MINGGUAN
    │             │
    │             └─→ 🏆 PENILAIAN
    │                    ├─→ Nilai per Kriteria
    │                    ├─→ Total Score
    │                    └─→ Ranking
```

---

## 📊 **KOMPONEN UTAMA YANG HARUS DIKELOLA:**

### **1. PERIODE AKADEMIK / TAHUN AJARAN** ✅
- Contoh: "2024/2025 Ganjil (Semester 3)"
- Setiap periode akademik punya:
  - Tanggal mulai & selesai
  - Kelas-kelas yang aktif
  - Semester (3, 4, 5)

### **2. KELAS (ClassRoom)** ✅
- Contoh: TI-3A, TI-3B, TI-3C
- Setiap kelas punya:
  - Mahasiswa (25-30 orang)
  - Kelompok (5-6 kelompok)
  - Max kelompok yang diperbolehkan
  - Program studi (TI, SIB, dll)

### **3. KELOMPOK (Group)** ✅
- Contoh: Kelompok 1, Kelompok 2
- Setiap kelompok punya:
  - Anggota (5 mahasiswa)
  - Ketua kelompok
  - Project yang dikerjakan
  - Progress mingguan
  - Target mingguan
  - **Nilai/Score**
  - **Ranking** (dibanding kelompok lain di kelas yang sama)

### **4. MAHASISWA (User - role: mahasiswa)** ✅
- Terdaftar di:
  - 1 Kelas tertentu (TI-3A)
  - 1 Kelompok (atau belum punya kelompok)
  - 1 Periode Akademik
- Data mahasiswa:
  - NIM (Politala ID)
  - Nama
  - Email (@politala.ac.id)
  - Program Studi

### **5. KRITERIA PENILAIAN** ✅
- Digunakan untuk menilai kelompok
- Contoh kriteria:
  - **Kecepatan Progress** (Benefit, bobot 20%)
  - **Kualitas Output** (Benefit, bobot 30%)
  - **Keterlambatan Deadline** (Cost, bobot 15%)
  - **Kehadiran** (Benefit, bobot 10%)
  - **Presentasi** (Benefit, bobot 25%)

**Properti Kriteria:**
- `nama` - Nama kriteria
- `bobot` - Bobot penilaian (0-100%)
- `tipe` - Benefit (semakin tinggi semakin baik) atau Cost (semakin rendah semakin baik)
- `segment` - Group (nilai kelompok) atau Student (nilai individu)
- `subject_id` - Terkait mata kuliah PBL

### **6. PENILAIAN (GroupScore)** ✅
- Nilai kelompok per kriteria
- Contoh:
  - Kelompok 1 → Kecepatan Progress → 85
  - Kelompok 1 → Kualitas Output → 90
  - Kelompok 1 → Keterlambatan → 5 (rendah = baik karena Cost)

**Total Score:**
- Dihitung dari semua kriteria × bobot
- Digunakan untuk **ranking kelompok**

---

## 🔗 **KONEKSI ANTAR KOMPONEN:**

```
PERIODE AKADEMIK 2024/2025 Ganjil
    │
    ├─→ Subject: PBL-1 (Semester 3)
    │      └─→ Kriteria:
    │           ├─→ Kecepatan Progress (20%)
    │           ├─→ Kualitas Output (30%)
    │           ├─→ Keterlambatan (15%)
    │           ├─→ Kehadiran (10%)
    │           └─→ Presentasi (25%)
    │
    └─→ Kelas TI-3A
           ├─→ Mahasiswa:
           │    ├─→ Andi (2301010001)
           │    ├─→ Budi (2301010002)
           │    ├─→ Citra (2301010003)
           │    └─→ ... (25 mahasiswa)
           │
           └─→ Kelompok:
                ├─→ Kelompok 1 (Leader: Andi)
                │    ├─→ Anggota: Andi, Budi, Citra, Dedi, Eka
                │    ├─→ Project: Sistem Informasi Perpustakaan
                │    ├─→ Progress: Minggu 1-10
                │    └─→ Nilai:
                │         ├─→ Kecepatan: 85 × 20% = 17
                │         ├─→ Kualitas: 90 × 30% = 27
                │         ├─→ Keterlambatan: 5 × 15% = 0.75
                │         ├─→ Kehadiran: 95 × 10% = 9.5
                │         ├─→ Presentasi: 88 × 25% = 22
                │         └─→ TOTAL: 76.25 → RANKING: 1
                │
                ├─→ Kelompok 2
                ├─→ Kelompok 3
                └─→ ... (5 kelompok)
```

---

## 📋 **FITUR YANG SUDAH ADA:**

### ✅ **SUDAH DIBUAT:**

1. **Manajemen User:**
   - Admin bisa kelola semua user
   - Filter by role, kelas, status
   - Lihat mahasiswa tanpa kelompok

2. **Manajemen Kelompok:**
   - Buat kelompok per kelas
   - Tambah/hapus anggota kelompok
   - Pilih ketua kelompok
   - Filter mahasiswa yang belum punya kelompok **PER KELAS**

3. **Periode Akademik:**
   - Model sudah ada
   - Koneksi dengan kelas, kelompok, mahasiswa ✅
   - Koneksi dengan penilaian ✅

4. **Kelas:**
   - Linked ke periode akademik ✅
   - Punya mahasiswa ✅
   - Punya kelompok ✅
   - Max groups

5. **Progress Mingguan:**
   - Upload evidence ke Google Drive ✅
   - Review oleh dosen
   - Target mingguan

6. **Kriteria Penilaian:**
   - Model sudah ada
   - Linked ke Subject
   - Tipe: Benefit/Cost
   - Bobot

7. **Group Score:**
   - Model sudah ada
   - Nilai per kriteria per kelompok

---

## 🎯 **YANG PERLU DILENGKAPI UNTUK PBL-1:**

### 🔴 **PENTING:**

1. **Dashboard per Periode Akademik:**
   - Dropdown pilih periode akademik
   - Tampilkan data yang sesuai dengan periode yang dipilih
   - Statistik: Total kelas, kelompok, mahasiswa

2. **Kelola Kriteria Penilaian:**
   - CRUD kriteria per Subject/PBL
   - Set bobot (harus total 100%)
   - Set tipe (Benefit/Cost)

3. **Input Nilai Kelompok:**
   - Form input nilai per kriteria
   - Hitung total score otomatis
   - Update ranking otomatis

4. **Ranking System:**
   - Auto-calculate ranking berdasarkan total score
   - Tampilkan ranking per kelas
   - Filter by periode akademik

5. **Laporan & Export:**
   - Export nilai per kelas
   - Export ranking
   - Laporan per periode

6. **Filter by Periode di Semua Halaman:**
   - Kelas → Filter by periode
   - Kelompok → Filter by periode
   - Mahasiswa → Filter by periode
   - Nilai → Filter by periode

---

## 🎨 **WORKFLOW PBL-1:**

### **ALUR KERJA SEMESTER 3:**

```
1. ADMIN/KOORDINATOR:
   ├─→ Buat Periode Akademik Baru (2024/2025 Ganjil - Semester 3)
   ├─→ Buat Kelas (TI-3A, TI-3B, TI-3C) → link ke periode
   ├─→ Assign mahasiswa ke kelas
   └─→ Set kriteria penilaian untuk PBL-1

2. MAHASISWA:
   ├─→ Login dengan SSO @politala.ac.id
   ├─→ Lihat kelas mereka
   └─→ Menunggu pembentukan kelompok

3. DOSEN/KOORDINATOR:
   ├─→ Buat kelompok per kelas (5 kelompok per kelas)
   └─→ Assign mahasiswa ke kelompok (5 orang per kelompok)

4. KELOMPOK (via Ketua):
   ├─→ Upload progress mingguan
   ├─→ Submit target mingguan
   └─→ Upload bukti/evidence ke Google Drive

5. DOSEN:
   ├─→ Review progress mingguan
   ├─→ Beri feedback
   └─→ Input nilai per kriteria

6. SISTEM:
   ├─→ Hitung total score (bobot × nilai)
   └─→ Update ranking otomatis

7. ADMIN/KOORDINATOR:
   ├─→ Lihat ranking semua kelompok
   ├─→ Export laporan
   └─→ Generate report per periode
```

---

## 🔢 **CONTOH PERHITUNGAN NILAI:**

### **Kriteria PBL-1:**
1. Kecepatan Progress: 20% (Benefit)
2. Kualitas Output: 30% (Benefit)
3. Keterlambatan: 15% (Cost)
4. Kehadiran: 10% (Benefit)
5. Presentasi: 25% (Benefit)

### **Kelompok 1 (TI-3A):**
- Kecepatan: 85 → 85 × 0.20 = **17.00**
- Kualitas: 90 → 90 × 0.30 = **27.00**
- Keterlambatan: 5 (rendah=baik) → 5 × 0.15 = **0.75** (diinvert karena Cost)
- Kehadiran: 95 → 95 × 0.10 = **9.50**
- Presentasi: 88 → 88 × 0.25 = **22.00**

**TOTAL SCORE: 76.25**
**RANKING: 1** (tertinggi di kelas TI-3A)

---

## 📌 **KEY POINTS:**

1. ✅ **Fokus PBL-1 (Semester 3)** - Untuk sekarang
2. ✅ **Kelompok per Kelas per Periode** - Sudah terkoneksi
3. ✅ **Mahasiswa hanya bisa di 1 kelompok per kelas** - Sudah di-handle
4. ✅ **Kriteria penilaian fleksibel** - Benefit/Cost, bobot
5. ✅ **Ranking otomatis** - Berdasarkan total score
6. ✅ **Multi-periode** - Bisa kelola beberapa tahun ajaran
7. ✅ **Google SSO** - Login pakai @politala.ac.id
8. ✅ **Google Drive** - Upload evidence/bukti

---

## 🚀 **NEXT STEPS:**

Berdasarkan pemahaman saya, yang perlu segera dilakukan:

1. **UI untuk pilih Periode Akademik** (dropdown di dashboard)
2. **Kelola Kriteria Penilaian** (CRUD)
3. **Input Nilai** (form untuk dosen)
4. **Ranking System** (auto-calculate & display)
5. **Filter by Periode** di semua halaman

**Apakah pemahaman saya sudah benar?** 
**Fitur mana yang ingin dikerjakan dulu?** 🎯

