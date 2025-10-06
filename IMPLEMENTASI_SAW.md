# 📊 Implementasi Metode SAW (Simple Additive Weighting)

## ✅ FITUR YANG SUDAH DIIMPLEMENTASI

Sistem penilaian dan ranking menggunakan metode SAW untuk kelompok dan mahasiswa!

---

## 🎯 Apa itu Metode SAW?

**Simple Additive Weighting (SAW)** adalah metode perhitungan multi-kriteria yang:
- Menormalisasi nilai berdasarkan tipe kriteria (Benefit/Cost)
- Mengalikan nilai normalisasi dengan bobot kriteria
- Menjumlahkan hasil untuk mendapatkan nilai preferensi

### Formula SAW:

**1. Normalisasi:**
- **Benefit** (semakin tinggi semakin baik): 
  ```
  r_ij = x_ij / max(x_ij)
  ```
- **Cost** (semakin rendah semakin baik):
  ```
  r_ij = min(x_ij) / x_ij
  ```

**2. Nilai Preferensi:**
```
V_i = Σ(w_j × r_ij)
```

Di mana:
- `r_ij` = nilai normalisasi alternatif i pada kriteria j
- `x_ij` = nilai asli alternatif i pada kriteria j
- `w_j` = bobot kriteria j
- `V_i` = nilai preferensi alternatif i

---

## 📁 File-File yang Dibuat/Dimodifikasi

### 1. **Database**
- ✅ `database/migrations/2025_10_06_174416_create_student_scores_table.php`
  - Tabel untuk menyimpan nilai mahasiswa per kriteria

### 2. **Models**
- ✅ `app/Models/StudentScore.php` (Baru)
  - Model untuk nilai mahasiswa
- ✅ `app/Models/User.php` (Diperbarui)
  - Tambah relasi `studentScores()`

### 3. **Services**
- ✅ `app/Services/RankingService.php` (Diperbarui Total)
  - `computeGroupTotals()` - Hitung ranking kelompok dengan SAW
  - `computeStudentTotals()` - Hitung ranking mahasiswa dengan SAW
  - `normalizeSAW()` - Normalisasi nilai sesuai tipe kriteria
  - `getGroupRankingWithDetails()` - Ranking kelompok lengkap
  - `getStudentRankingWithDetails()` - Ranking mahasiswa lengkap
  - `updateGroupTotalScores()` - Update total score kelompok
  - `updateGroupRankings()` - Update ranking kelompok per kelas

### 4. **Controllers**
- ✅ `app/Http/Controllers/StudentScoreController.php` (Baru)
  - `index()` - Tampilkan matriks nilai & ranking mahasiswa
  - `create()` - Form input nilai mahasiswa
  - `store()` - Simpan nilai mahasiswa
  - `recalc()` - Hitung ulang ranking
  - `getBestStudentsPerClass()` - Mahasiswa terbaik per kelas

- ✅ `app/Http/Controllers/GroupScoreController.php` (Diperbarui)
  - Menggunakan `RankingService` dengan metode SAW
  - `recalc()` - Hitung ulang ranking kelompok dengan SAW

### 5. **Views**
- ✅ `resources/views/student-scores/index.blade.php` (Baru)
  - Matriks nilai mahasiswa
  - Ranking mahasiswa dengan SAW
  - Mahasiswa terbaik per kelas
  - Info metode SAW

- ✅ `resources/views/student-scores/create.blade.php` (Baru)
  - Form input nilai mahasiswa
  - Info dinamis tentang tipe kriteria
  - Validasi input

- ✅ `resources/views/student-scores/ranking.blade.php` (Baru)
  - Tampilan ranking mahasiswa dalam bentuk kartu
  - Detail lengkap per mahasiswa

- ✅ `resources/views/scores/index.blade.php` (Diperbarui)
  - Menggunakan struktur ranking baru dari SAW
  - Menampilkan label "SAW" pada skor

### 6. **Routes**
- ✅ `routes/web.php` (Diperbarui)
  ```php
  // Input nilai mahasiswa (Dosen + Koordinator)
  Route::get('student-scores/create', [StudentScoreController::class, 'create'])
  Route::post('student-scores', [StudentScoreController::class, 'store'])
  
  // Lihat ranking mahasiswa (Dosen + Koordinator + Admin)
  Route::get('student-scores', [StudentScoreController::class, 'index'])
  Route::post('student-scores/recalc', [StudentScoreController::class, 'recalc'])
  ```

---

## 🎨 Fitur-Fitur Utama

### 1. **Input Nilai Kelompok**
- Form input nilai per kriteria per kelompok
- Validasi 0-100
- Info tipe kriteria (Benefit/Cost)
- Auto-update ranking menggunakan SAW

### 2. **Input Nilai Mahasiswa**
- Form input nilai per kriteria per mahasiswa
- Validasi 0-100
- Info dinamis tentang kriteria yang dipilih
- Filter mahasiswa berdasarkan kelas dan kelompok

### 3. **Ranking Kelompok (SAW)**
- Normalisasi otomatis berdasarkan tipe kriteria
- Penanganan khusus untuk "Kecepatan Progres" (dari weekly targets)
- Ranking per kelas
- Top 3 kelompok per kelas

### 4. **Ranking Mahasiswa (SAW)**
- Normalisasi otomatis berdasarkan tipe kriteria
- Filter per kelas (opsional)
- Top 3 mahasiswa per kelas
- Detail lengkap (nama, NIM, kelas, kelompok)

### 5. **Matriks Nilai**
- Tabel nilai kelompok × kriteria
- Tabel nilai mahasiswa × kriteria
- Color coding berdasarkan nilai
- Kolom total SAW

---

## 📊 Contoh Perhitungan SAW

### Kriteria:
1. **Nilai PBL** (60%, Benefit)
2. **Penilaian Teman** (40%, Benefit)

### Data Mahasiswa:
| Mahasiswa | Nilai PBL | Penilaian Teman |
|-----------|-----------|-----------------|
| Andi      | 90        | 85              |
| Budi      | 85        | 90              |
| Citra     | 80        | 80              |

### Langkah Perhitungan:

**1. Normalisasi (Benefit: r = x / max)**
- Nilai PBL (max=90):
  - Andi: 90/90 = 1.00
  - Budi: 85/90 = 0.94
  - Citra: 80/90 = 0.89

- Penilaian Teman (max=90):
  - Andi: 85/90 = 0.94
  - Budi: 90/90 = 1.00
  - Citra: 80/90 = 0.89

**2. Kalikan dengan Bobot**
- Andi: (1.00 × 0.6) + (0.94 × 0.4) = 0.60 + 0.38 = **0.98**
- Budi: (0.94 × 0.6) + (1.00 × 0.4) = 0.56 + 0.40 = **0.96**
- Citra: (0.89 × 0.6) + (0.89 × 0.4) = 0.53 + 0.36 = **0.89**

**3. Konversi ke Skala 0-100**
- Andi: 0.98 × 100 = **98.00**
- Budi: 0.96 × 100 = **96.00**
- Citra: 0.89 × 100 = **89.00**

**4. Ranking:**
1. 🥇 Andi: 98.00
2. 🥈 Budi: 96.00
3. 🥉 Citra: 89.00

---

## 🚀 Cara Menggunakan

### 1. **Setup Kriteria** (Admin)
```
Dashboard → Kelola Kriteria
- Tambah kriteria untuk kelompok (segment: group)
- Tambah kriteria untuk mahasiswa (segment: student)
- Set bobot (total 100% per segment)
- Set tipe (Benefit/Cost)
```

### 2. **Input Nilai Kelompok** (Dosen/Koordinator)
```
Dashboard → Manajemen Nilai & Ranking → Input Nilai
- Pilih kelompok
- Pilih kriteria
- Masukkan nilai (0-100)
- Simpan
```

### 3. **Input Nilai Mahasiswa** (Dosen/Koordinator)
```
Dashboard → Manajemen Nilai Mahasiswa & Ranking → Input Nilai Mahasiswa
- Pilih mahasiswa
- Pilih kriteria
- Masukkan nilai (0-100)
- Simpan
```

### 4. **Lihat Ranking**
```
Dashboard → Manajemen Nilai & Ranking
- Lihat matriks nilai kelompok
- Lihat ranking kelompok (SAW)
- Klik "Hitung Ulang Ranking" untuk update

Dashboard → Manajemen Nilai Mahasiswa & Ranking
- Lihat matriks nilai mahasiswa
- Lihat ranking mahasiswa (SAW)
- Lihat top 3 per kelas
```

---

## 🎓 Keunggulan Metode SAW

1. ✅ **Objektif** - Perhitungan matematis yang konsisten
2. ✅ **Fleksibel** - Mendukung kriteria Benefit dan Cost
3. ✅ **Transparan** - Formula jelas dan dapat diaudit
4. ✅ **Otomatis** - Normalisasi dan ranking otomatis
5. ✅ **Multi-Kriteria** - Dapat menggabungkan banyak kriteria
6. ✅ **Weighted** - Mempertimbangkan bobot setiap kriteria

---

## 📝 Catatan Penting

1. **Total Bobot** harus 100% (1.0) untuk setiap segment (group/student)
2. **Kecepatan Progres** untuk kelompok dihitung otomatis dari weekly targets
3. Kriteria **Benefit**: semakin tinggi nilai, semakin baik
4. Kriteria **Cost**: semakin rendah nilai, semakin baik
5. Nilai normalisasi selalu antara 0-1
6. Nilai akhir SAW dalam skala 0-100

---

## 🔄 Update Ranking

Ranking dapat dihitung ulang kapan saja dengan:
- Klik tombol "Hitung Ulang Ranking" di halaman nilai
- Sistem akan:
  1. Normalisasi semua nilai
  2. Kalikan dengan bobot
  3. Hitung total SAW
  4. Urutkan descending
  5. Update field `total_score` dan `ranking`

---

## 💡 Tips & Best Practices

1. **Input Nilai Konsisten** - Gunakan skala 0-100 untuk semua kriteria
2. **Review Bobot** - Pastikan bobot sesuai dengan kepentingan kriteria
3. **Validasi Data** - Cek nilai sebelum hitung ranking
4. **Dokumentasi** - Catat alasan pemilihan bobot dan tipe kriteria
5. **Transparansi** - Jelaskan metode SAW kepada mahasiswa

---

## 🎉 Fitur Tambahan

- 🏆 **Ranking Real-Time** - Update otomatis saat input nilai
- 📊 **Visualisasi** - Matriks nilai dan grafik ranking
- 🎨 **Color Coding** - Hijau (>80), Biru (70-80), Kuning (60-70), Merah (<60)
- 👥 **Top 3 Per Kelas** - Tampilan mahasiswa/kelompok terbaik
- 📈 **Progress Tracking** - Monitor kecepatan progres kelompok
- 💾 **History** - Semua nilai tersimpan dengan timestamp

---

## 🐛 Troubleshooting

**Q: Ranking tidak muncul?**
A: Pastikan sudah ada nilai untuk semua kriteria dan klik "Hitung Ulang Ranking"

**Q: Total bobot tidak 100%?**
A: Edit kriteria di menu Kelola Kriteria dan sesuaikan bobotnya

**Q: Nilai SAW selalu 0?**
A: Cek apakah sudah input nilai untuk semua kriteria

**Q: Kecepatan Progres tidak terupdate?**
A: Pastikan kelompok sudah submit weekly targets dan tandai sebagai completed

---

## 📞 Support

Jika ada pertanyaan atau masalah, silakan hubungi:
- Admin Sistem
- Koordinator PBL
- Dokumentasi: File ini 😊

---

**Dibuat pada:** 6 Oktober 2025  
**Status:** ✅ Production Ready  
**Metode:** Simple Additive Weighting (SAW)

