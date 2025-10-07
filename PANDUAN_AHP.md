# 📊 Panduan Metode AHP (Analytical Hierarchy Process)

## ✅ IMPLEMENTASI LENGKAP

Sistem sudah dilengkapi dengan **metode AHP** untuk menghitung bobot kriteria secara objektif dan ilmiah!

---

## 🎯 Apa itu AHP?

**AHP (Analytical Hierarchy Process)** adalah metode pengambilan keputusan multi-kriteria yang dikembangkan oleh **Thomas L. Saaty** pada tahun 1970-an. Metode ini membantu menentukan bobot kriteria berdasarkan **perbandingan berpasangan** (pairwise comparison).

### **Keunggulan AHP:**
✅ **Objektif** - Berdasarkan perbandingan sistematis  
✅ **Konsisten** - Ada validasi konsistensi (CR)  
✅ **Terstruktur** - Proses yang jelas dan terukur  
✅ **Transparan** - Formula matematis yang jelas  
✅ **Akurat** - Hasil lebih presisi dari input manual  

---

## 📐 Formula dan Konsep

### **1. Skala Perbandingan (1-9)**

| Nilai | Keterangan | Penjelasan |
|-------|-----------|------------|
| **1** | Sama penting | Kedua kriteria sama pentingnya |
| **3** | Cukup penting | Kriteria A sedikit lebih penting dari B |
| **5** | Sangat penting | Kriteria A lebih penting dari B |
| **7** | Jauh lebih penting | Kriteria A jauh lebih penting dari B |
| **9** | Mutlak lebih penting | Kriteria A mutlak lebih penting dari B |
| **2, 4, 6, 8** | Nilai antara | Kompromi antara dua penilaian |

### **2. Matriks Perbandingan**

Untuk n kriteria, buat matriks n×n:

```
       K1   K2   K3
K1   [ 1    3    5  ]
K2   [1/3   1    3  ]
K3   [1/5  1/3   1  ]
```

### **3. Normalisasi Matriks**

Setiap elemen dibagi dengan jumlah kolomnya:

```
Normalisasi = nilai / Σ kolom
```

### **4. Hitung Bobot (Eigenvector)**

Bobot = rata-rata setiap baris matriks normalisasi:

```
w_i = (Σ nilai baris_i) / n
```

### **5. Uji Konsistensi**

**Consistency Ratio (CR):**
```
CR = CI / RI

dimana:
CI = (λmax - n) / (n - 1)
RI = Random Index (dari tabel)
```

**Kriteria:**
- CR ≤ 0.1 → **Konsisten** ✓
- CR > 0.1 → **Tidak Konsisten** ✗ (perlu revisi)

---

## 🚀 Cara Menggunakan

### **Step 1: Akses Menu AHP**

1. Login sebagai **Admin**
2. Menu **"Kriteria"**
3. Klik tombol **"Hitung Bobot AHP"**
4. Atau akses: `http://your-domain/ahp`

### **Step 2: Pilih Segment**

Pilih segment kriteria yang ingin dihitung:
- **Kriteria Kelompok** (group)
- **Kriteria Mahasiswa** (student)

### **Step 3: Input Perbandingan**

Untuk setiap pasang kriteria:
1. Geser **slider** dari 1 sampai 9
2. Nilai **1** = sama penting
3. Nilai **9** = kriteria A mutlak lebih penting dari B
4. Sistem **auto-save** setiap perubahan

### **Step 4: Hitung Bobot**

1. Klik tombol **"Hitung Bobot"**
2. Sistem akan menampilkan:
   - Bobot setiap kriteria (desimal & persen)
   - Lambda Max (λmax)
   - Consistency Index (CI)
   - Consistency Ratio (CR)
   - Status konsistensi

### **Step 5: Validasi Konsistensi**

Periksa nilai **CR**:
- ✅ **CR ≤ 0.1** → Konsisten, boleh diterapkan
- ✗ **CR > 0.1** → Tidak konsisten, perlu revisi

### **Step 6: Terapkan Bobot**

Jika konsisten:
1. Klik **"Terapkan Bobot ke Kriteria"**
2. Bobot otomatis terupdate di database
3. Langsung bisa digunakan untuk SAW

---

## 📊 Contoh Kasus

### **Kriteria PBL (Kelompok):**

1. **Kecepatan Progres** (K1)
2. **Kualitas Output** (K2)
3. **Keterlambatan** (K3)

### **Perbandingan:**

- K1 vs K2 = **3** (Kecepatan lebih penting dari Kualitas)
- K1 vs K3 = **5** (Kecepatan jauh lebih penting dari Keterlambatan)
- K2 vs K3 = **3** (Kualitas lebih penting dari Keterlambatan)

### **Matriks Perbandingan:**

```
       K1    K2    K3
K1   [ 1     3     5   ]
K2   [1/3    1     3   ]
K3   [1/5   1/3    1   ]
```

### **Hasil Perhitungan:**

```
Bobot:
- K1 (Kecepatan): 0.6333 (63.33%)
- K2 (Kualitas):  0.2605 (26.05%)
- K3 (Keterlambatan): 0.1062 (10.62%)

λmax = 3.0385
CI = 0.0193
CR = 0.0332 ≤ 0.1 ✓ KONSISTEN
```

---

## 🎨 Fitur-Fitur

### **1. Interactive Slider**
- Geser untuk memilih nilai 1-9
- Real-time update deskripsi
- Auto-save ke database

### **2. Visual Matrix**
- Matriks perbandingan lengkap
- Matriks normalisasi
- Visualisasi bobot (bar chart)

### **3. Consistency Check**
- Hitung CR otomatis
- Validasi konsistensi
- Feedback jelas (konsisten/tidak)

### **4. Apply Weights**
- Update bobot ke kriteria otomatis
- Hanya bisa jika konsisten
- Konfirmasi sebelum apply

### **5. Reset Function**
- Reset semua perbandingan
- Mulai dari awal
- Konfirmasi sebelum reset

---

## 📁 File-File yang Dibuat

### **Database:**
- `database/migrations/2025_10_07_022114_create_ahp_comparisons_table.php`

### **Models:**
- `app/Models/AhpComparison.php`

### **Services:**
- `app/Services/AhpService.php`
  - `calculateWeights()` - Hitung bobot AHP
  - `buildComparisonMatrix()` - Build matriks
  - `normalizeMatrix()` - Normalisasi
  - `calculatePriorityVector()` - Hitung eigenvector
  - `calculateConsistency()` - Hitung CR
  - `saveComparison()` - Save perbandingan
  - `applyWeightsToCriteria()` - Apply bobot

### **Controllers:**
- `app/Http/Controllers/AhpController.php`
  - `index()` - Halaman utama
  - `saveComparison()` - Save via AJAX
  - `calculate()` - Hitung bobot
  - `applyWeights()` - Apply ke kriteria
  - `reset()` - Reset perbandingan
  - `help()` - Panduan

### **Views:**
- `resources/views/ahp/index.blade.php` - Halaman utama
- `resources/views/ahp/help.blade.php` - Panduan lengkap

### **Routes:**
- `GET /ahp` - Halaman utama
- `POST /ahp/save` - Save perbandingan
- `GET /ahp/calculate` - Hitung bobot
- `POST /ahp/apply` - Apply bobot
- `POST /ahp/reset` - Reset
- `GET /ahp/help` - Panduan

---

## 🔄 Integrasi dengan SAW

Setelah bobot dihitung dengan AHP:

1. ✅ Bobot otomatis tersimpan di tabel `criteria`
2. ✅ Metode SAW langsung menggunakan bobot ini
3. ✅ Ranking kelompok/mahasiswa terupdate otomatis
4. ✅ Tidak perlu input bobot manual lagi!

**Workflow:**
```
AHP → Hitung Bobot → Apply ke Criteria 
→ SAW gunakan bobot ini → Ranking Update
```

---

## 💡 Tips & Best Practices

### **1. Konsistensi Perbandingan**
- Berpikir logis: Jika A > B dan B > C, maka A > C
- Gunakan skala ganjil (1, 3, 5, 7, 9)
- Jangan terlalu ekstrem tanpa alasan kuat

### **2. Mengatasi CR > 0.1**
- Review perbandingan yang tidak logis
- Cek apakah ada kontradiksi
- Sesuaikan nilai yang terlalu ekstrem
- Diskusikan dengan tim jika ragu

### **3. Dokumentasi**
- Catat alasan setiap perbandingan
- Simpan screenshot hasil
- Dokumentasikan keputusan

### **4. Update Berkala**
- Review bobot setiap semester
- Sesuaikan jika prioritas berubah
- Lakukan konsensus dengan tim

---

## ❓ FAQ

### **Q: Berapa minimal kriteria untuk AHP?**
**A:** Minimal 2 kriteria. Tapi idealnya 3-9 kriteria.

### **Q: Kenapa CR saya > 0.1?**
**A:** Perbandingan Anda tidak konsisten. Review dan revisi perbandingan yang kontradiktif.

### **Q: Bisakah saya ubah nilai setelah di-apply?**
**A:** Bisa! Input ulang perbandingan, hitung lagi, lalu apply lagi.

### **Q: Apa beda AHP dan input manual?**
**A:** 
- AHP: Objektif, terukur, ada validasi konsistensi
- Manual: Subjektif, no validasi, bisa bias

### **Q: Apakah hasil AHP pasti benar?**
**A:** AHP membantu keputusan lebih objektif, tapi tetap perlu judgement expert.

### **Q: Bisakah nilai 1.5 atau 6.7?**
**A:** Bisa, tapi sistem merekomendasikan nilai integer (1-9) untuk kemudahan.

---

## 🎓 Referensi

### **Paper Asli:**
- Saaty, T.L. (1980). "The Analytic Hierarchy Process". McGraw-Hill.

### **Konsep:**
- Pairwise Comparison
- Eigenvector Method
- Consistency Ratio
- Random Index

### **Aplikasi:**
- Multi-Criteria Decision Making (MCDM)
- Weight Determination
- Priority Setting

---

## 🔧 Troubleshooting

### **Error: "Minimal 2 kriteria"**
**Solusi:** Tambahkan kriteria di menu "Kelola Kriteria"

### **Slider tidak save**
**Solusi:** 
- Cek koneksi internet
- Refresh browser
- Cek console (F12) untuk error

### **CR selalu > 0.1**
**Solusi:**
- Review perbandingan dengan hati-hati
- Gunakan nilai yang lebih moderat
- Konsultasikan dengan ahli

### **Bobot tidak update**
**Solusi:**
- Pastikan sudah klik "Terapkan Bobot"
- Cek apakah CR ≤ 0.1
- Refresh halaman kriteria

---

## 📞 Support

Jika butuh bantuan:
1. Baca file **`PANDUAN_AHP.md`** (ini)
2. Klik tombol **"Panduan AHP"** di halaman AHP
3. Tanya Admin sistem
4. Konsultasi dengan koordinator

---

## 🎉 Kesimpulan

Metode AHP sudah **100% siap digunakan**!

**Keuntungan:**
✅ Bobot objektif & terukur  
✅ Validasi konsistensi otomatis  
✅ Integrasi seamless dengan SAW  
✅ UI modern & user-friendly  
✅ Dokumentasi lengkap  

**Next Steps:**
1. Setup kriteria (jika belum)
2. Buka menu AHP
3. Input perbandingan
4. Hitung & apply bobot
5. Gunakan untuk SAW!

---

**Dibuat:** 7 Oktober 2025  
**Metode:** AHP (Analytical Hierarchy Process)  
**Status:** ✅ Production Ready  
**Integrasi:** SAW ✓

---

**Selamat menggunakan AHP!** 🚀📊

