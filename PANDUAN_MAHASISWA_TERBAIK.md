# 🎓 PANDUAN LENGKAP: Mencari Mahasiswa Terbaik dengan Metode SAW

## ✅ STATUS SISTEM
- ✅ Kriteria mahasiswa sudah tersedia (2 kriteria)
- ✅ Total mahasiswa: 11 orang
- ✅ Total kelompok: 1 kelompok
- ✅ Sistem SAW sudah aktif

---

## 🎯 LANGKAH-LANGKAH MENCARI MAHASISWA TERBAIK

### **Langkah 1: Login sebagai Dosen/Koordinator**

1. **Buka browser** dan akses sistem PBL
2. **Login** dengan akun yang memiliki role:
   - `dosen`
   - `koordinator` 
   - `admin`

### **Langkah 2: Akses Menu Input Nilai Mahasiswa**

1. **Setelah login**, cari menu **"Manajemen Nilai Mahasiswa & Ranking"**
2. **Klik tombol** `Input Nilai Mahasiswa` (tombol biru)
3. **Atau akses langsung:** `/student-scores/create`

### **Langkah 3: Input Nilai untuk Setiap Mahasiswa**

Pada halaman input, Anda akan melihat:

#### **A. Pilih Mahasiswa**
- **Dropdown** berisi semua mahasiswa
- **Format:** `NIM - Nama (Kelas) - Kelompok: Nama Kelompok`
- **Contoh:** `2021001 - Ahmad Fauzi (TI-3A) - Kelompok: Kelompok 1`

#### **B. Pilih Kriteria**
Sistem menyediakan **2 kriteria** untuk mahasiswa:

1. **Nilai PBL Mahasiswa** (60%, Benefit)
   - Penilaian dosen terhadap performa individual mahasiswa
   - Skala: 0-100
   - Semakin tinggi semakin baik

2. **Penilaian Teman Sekelompok** (40%, Benefit)
   - Peer assessment dari anggota kelompok
   - Skala: 0-100
   - Semakin tinggi semakin baik

#### **C. Input Nilai**
- **Skala:** 0-100
- **Format:** Angka desimal (contoh: 85.5)
- **Validasi:** Otomatis cek range 0-100

### **Langkah 4: Contoh Input Nilai**

Mari input nilai untuk beberapa mahasiswa:

#### **Mahasiswa 1: Ahmad Fauzi**
```
Pilih Mahasiswa: 2021001 - Ahmad Fauzi (TI-3A) - Kelompok: Kelompok 1
Pilih Kriteria: Nilai PBL Mahasiswa
Input Nilai: 90
→ Simpan
```

```
Pilih Mahasiswa: 2021001 - Ahmad Fauzi (TI-3A) - Kelompok: Kelompok 1
Pilih Kriteria: Penilaian Teman Sekelompok
Input Nilai: 85
→ Simpan
```

#### **Mahasiswa 2: Siti Nurhaliza**
```
Pilih Mahasiswa: 2021002 - Siti Nurhaliza (TI-3A) - Kelompok: Kelompok 1
Pilih Kriteria: Nilai PBL Mahasiswa
Input Nilai: 85
→ Simpan
```

```
Pilih Mahasiswa: 2021002 - Siti Nurhaliza (TI-3A) - Kelompok: Kelompok 1
Pilih Kriteria: Penilaian Teman Sekelompok
Input Nilai: 95
→ Simpan
```

#### **Mahasiswa 3: Budi Santoso**
```
Pilih Mahasiswa: 2021003 - Budi Santoso (TI-3A) - Kelompok: Kelompok 1
Pilih Kriteria: Nilai PBL Mahasiswa
Input Nilai: 80
→ Simpan
```

```
Pilih Mahasiswa: 2021003 - Budi Santoso (TI-3A) - Kelompok: Kelompok 1
Pilih Kriteria: Penilaian Teman Sekelompok
Input Nilai: 80
→ Simpan
```

### **Langkah 5: Lihat Ranking Mahasiswa**

Setelah input semua nilai:

1. **Kembali ke halaman utama:** `Manajemen Nilai Mahasiswa & Ranking`
2. **Lihat bagian "Ranking Mahasiswa"** (sidebar kanan)
3. **Atau klik "Hitung Ulang Ranking"** untuk memastikan perhitungan terbaru

### **Langkah 6: Hasil Ranking SAW**

Berdasarkan input di atas, sistem akan menghitung:

#### **Perhitungan SAW:**

**Ahmad Fauzi:**
- Nilai PBL: 90 → Normalisasi: 90/90 = 1.00
- Penilaian Teman: 85 → Normalisasi: 85/95 = 0.89
- **Total SAW:** (1.00 × 0.6) + (0.89 × 0.4) = 0.60 + 0.36 = **96.0**

**Siti Nurhaliza:**
- Nilai PBL: 85 → Normalisasi: 85/90 = 0.94
- Penilaian Teman: 95 → Normalisasi: 95/95 = 1.00
- **Total SAW:** (0.94 × 0.6) + (1.00 × 0.4) = 0.56 + 0.40 = **96.4**

**Budi Santoso:**
- Nilai PBL: 80 → Normalisasi: 80/90 = 0.89
- Penilaian Teman: 80 → Normalisasi: 80/95 = 0.84
- **Total SAW:** (0.89 × 0.6) + (0.84 × 0.4) = 0.53 + 0.34 = **87.0**

#### **Ranking Hasil:**
1. 🥇 **Siti Nurhaliza**: 96.4 (terbaik)
2. 🥈 **Ahmad Fauzi**: 96.0
3. 🥉 **Budi Santoso**: 87.0

---

## 📊 FITUR YANG TERSEDIA

### **1. Matriks Nilai Mahasiswa**
- **Tabel:** Mahasiswa × Kriteria
- **Menampilkan:** Semua nilai yang sudah diinput
- **Color Coding:**
  - 🟢 Hijau: 80-100 (Excellent)
  - 🔵 Biru: 70-79 (Good)
  - 🟡 Kuning: 60-69 (Fair)
  - 🔴 Merah: 0-59 (Poor)

### **2. Ranking Mahasiswa (SAW)**
- **Urutan:** Terbaik ke terburuk
- **Badge:** 🥇🥈🥉 untuk top 3
- **Info:** Nama, NIM, Kelompok, Skor SAW
- **Color Coding:** Berdasarkan skor SAW

### **3. Top 3 Mahasiswa Per Kelas**
- **Kartu:** Mahasiswa terbaik per kelas
- **Detail:** Nama, NIM, Skor, Kelompok
- **Visual:** Card design dengan gradient

### **4. Info Metode SAW**
- **Formula:** Ditampilkan di halaman
- **Penjelasan:** Benefit vs Cost
- **Transparansi:** Proses perhitungan jelas

---

## 🔧 TROUBLESHOOTING

### **Q: Menu "Manajemen Nilai Mahasiswa" tidak muncul?**
**A:** Pastikan:
- Login dengan role `dosen`, `koordinator`, atau `admin`
- Bukan role `mahasiswa`
- Routes sudah terdaftar di `routes/web.php`

### **Q: Dropdown mahasiswa kosong?**
**A:** Pastikan:
- Ada data mahasiswa di database
- Mahasiswa memiliki role `mahasiswa`
- Mahasiswa terdaftar di kelas

### **Q: Kriteria tidak muncul?**
**A:** Jalankan seeder:
```bash
php artisan db:seed --class=CriterionSeeder
```

### **Q: Ranking tidak terupdate?**
**A:** Klik tombol "Hitung Ulang Ranking" untuk recalculate

### **Q: Nilai tidak tersimpan?**
**A:** Cek:
- Validasi input (0-100)
- Mahasiswa dan kriteria sudah dipilih
- Tidak ada error di form

---

## 📱 SCREENSHOT YANG DIHARAPKAN

### **Halaman Input Nilai Mahasiswa:**
```
┌─────────────────────────────────────┐
│ 🎓 Input Nilai Mahasiswa            │
├─────────────────────────────────────┤
│ Pilih Mahasiswa: [Dropdown ▼]       │
│ Pilih Kriteria: [Dropdown ▼]        │
│ Input Nilai: [____] / 100           │
│                                     │
│ [Batal] [Simpan Nilai]              │
└─────────────────────────────────────┘
```

### **Halaman Ranking Mahasiswa:**
```
┌─────────────────────────────────────┐
│ 🏆 Ranking Mahasiswa (SAW)          │
├─────────────────────────────────────┤
│ 🥇 Siti Nurhaliza    96.4 poin      │
│ 🥈 Ahmad Fauzi       96.0 poin      │
│ 🥉 Budi Santoso      87.0 poin      │
│ 4. Citra Dewi        85.5 poin      │
│ 5. Dedi Kurniawan    82.3 poin      │
└─────────────────────────────────────┘
```

---

## 🎯 TIPS PENGGUNAAN

### **1. Input Nilai Konsisten**
- Gunakan skala 0-100 untuk semua kriteria
- Berikan nilai yang realistis
- Pertimbangkan performa keseluruhan

### **2. Kriteria Penilaian**
- **Nilai PBL Mahasiswa:** Berdasarkan tugas, presentasi, partisipasi
- **Penilaian Teman:** Peer assessment, kolaborasi, kontribusi

### **3. Review Ranking**
- Cek apakah ranking sesuai ekspektasi
- Bandingkan dengan performa aktual
- Gunakan "Hitung Ulang" jika ada perubahan

### **4. Dokumentasi**
- Catat alasan pemberian nilai
- Simpan backup data ranking
- Laporkan hasil ke koordinator

---

## 🚀 NEXT STEPS

Setelah mahasiswa terbaik ditemukan, Anda bisa:

1. **📊 Export Ranking** ke Excel/PDF
2. **📧 Kirim Sertifikat** ke mahasiswa terbaik
3. **📈 Analisis Trend** performa mahasiswa
4. **🎯 Set Target** untuk semester berikutnya
5. **📝 Laporan Akhir** untuk koordinator

---

## 📞 SUPPORT

Jika ada masalah atau pertanyaan:
- **Admin Sistem:** Cek log error
- **Koordinator PBL:** Review konfigurasi
- **Dokumentasi:** File `IMPLEMENTASI_SAW.md`

---

**Status:** ✅ **READY TO USE**  
**Update:** 6 Oktober 2025  
**Metode:** Simple Additive Weighting (SAW)
