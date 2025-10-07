# 🎓 Panduan Input Nilai Mahasiswa & Mencari Mahasiswa Terbaik

## ✅ Fitur Sudah Tersedia!

Sistem sudah dilengkapi dengan fitur lengkap untuk **input nilai mahasiswa** dan **mencari mahasiswa terbaik** menggunakan metode SAW (Simple Additive Weighting).

---

## 📍 Cara Mengakses

### **Untuk Dosen / Koordinator / Admin:**

Setelah login, Anda akan melihat **2 menu terpisah** di navigasi atas:

1. **📊 Nilai Kelompok** 
   - Untuk input nilai kelompok
   - Ranking kelompok terbaik
   
2. **🎓 Nilai Mahasiswa** ⬅️ **INI YANG BARU!**
   - Untuk input nilai mahasiswa
   - Ranking mahasiswa terbaik
   - Mahasiswa terbaik per kelas

---

## 🚀 Langkah-Langkah Lengkap

### **Step 1: Setup Kriteria Mahasiswa** (Khusus Admin)

Sebelum bisa input nilai, Admin harus setup kriteria terlebih dahulu:

1. Login sebagai **Admin**
2. Klik menu **"Kriteria"**
3. Klik **"Tambah Kriteria"**
4. Isi form:
   ```
   Nama: Nilai PBL Mahasiswa
   Bobot: 0.6 (60%)
   Tipe: benefit
   Segment: student ⬅️ PENTING!
   ```
5. Tambah kriteria lain, contoh:
   ```
   Nama: Penilaian Teman Sekelompok
   Bobot: 0.4 (40%)
   Tipe: benefit
   Segment: student
   ```

> **Catatan:** Total bobot untuk segment "student" harus = 1.0 (100%)

---

### **Step 2: Input Nilai Mahasiswa** (Dosen/Koordinator)

1. Login sebagai **Dosen** atau **Koordinator**
2. Klik menu **"Nilai Mahasiswa"** di navigasi atas
3. Klik tombol **"Input Nilai Mahasiswa"** (tombol biru)
4. Pilih **Mahasiswa** dari dropdown
   - Akan muncul: NIM - Nama (Kelas) - Kelompok
5. Pilih **Kriteria** yang akan dinilai
   - Info tipe kriteria akan muncul otomatis
6. Masukkan **Skor** (0-100)
7. Klik **"Simpan Nilai"**

**Ulangi** untuk semua mahasiswa dan semua kriteria!

---

### **Step 3: Lihat Ranking Mahasiswa Terbaik**

Setelah input nilai, ranking akan **otomatis terhitung** dengan metode SAW!

1. Klik menu **"Nilai Mahasiswa"**
2. Anda akan melihat:
   - 📊 **Matriks Nilai** (mahasiswa × kriteria)
   - 🏆 **Ranking Mahasiswa** (urutan terbaik)
   - ⭐ **Top 3 Mahasiswa per Kelas**
   - 📈 **Total Skor SAW** untuk setiap mahasiswa

---

## 🎯 Contoh Tampilan

### **Halaman Nilai Mahasiswa**
```
┌─────────────────────────────────────────────────────┐
│  Manajemen Nilai Mahasiswa & Ranking (SAW)          │
│  [Input Nilai Mahasiswa] [Hitung Ulang Ranking]    │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  MATRIKS NILAI                                       │
├────────────┬─────────────┬──────────────┬──────────┤
│ Mahasiswa  │ Nilai PBL   │ Penilaian    │ Total    │
│            │   (60%)     │ Teman (40%)  │   SAW    │
├────────────┼─────────────┼──────────────┼──────────┤
│ Andi       │    90       │     85       │  88.00   │
│ Budi       │    85       │     90       │  87.00   │
│ Citra      │    80       │     80       │  80.00   │
└────────────┴─────────────┴──────────────┴──────────┘

┌─────────────────────────────────────────────────────┐
│  RANKING MAHASISWA                                   │
├────────────────────────────────────────────────────┤
│  🥇 1. Andi - 88.00 poin                           │
│  🥈 2. Budi - 87.00 poin                           │
│  🥉 3. Citra - 80.00 poin                          │
└────────────────────────────────────────────────────┘
```

---

## 🔢 Perhitungan SAW (Otomatis!)

Sistem akan **otomatis menghitung** ranking menggunakan formula SAW:

### **1. Normalisasi**
- **Benefit**: `r = nilai / max(nilai)`
- **Cost**: `r = min(nilai) / nilai`

### **2. Total SAW**
```
Total = Σ(Bobot × Nilai Normalisasi)
```

### **Contoh Perhitungan:**

**Data:**
- Andi: Nilai PBL = 90, Penilaian Teman = 85
- Budi: Nilai PBL = 85, Penilaian Teman = 90

**Normalisasi (Benefit):**
- Andi: (90/90) = 1.00, (85/90) = 0.94
- Budi: (85/90) = 0.94, (90/90) = 1.00

**Total SAW:**
- Andi: (0.6 × 1.00) + (0.4 × 0.94) = **0.976** → **97.6 poin**
- Budi: (0.6 × 0.94) + (0.4 × 1.00) = **0.964** → **96.4 poin**

**Ranking:**
1. 🥇 Andi: 97.6
2. 🥈 Budi: 96.4

---

## 💡 Tips & Trik

### **1. Input Nilai Bertahap**
Tidak perlu input semua nilai sekaligus. Anda bisa:
- Input per kriteria untuk semua mahasiswa, ATAU
- Input semua kriteria untuk satu mahasiswa

### **2. Update Nilai**
Jika ingin mengubah nilai:
- Input ulang dengan mahasiswa dan kriteria yang sama
- Sistem akan **otomatis update** nilai lama

### **3. Cek Ranking Real-Time**
- Klik **"Hitung Ulang Ranking"** untuk update
- Ranking akan langsung berubah

### **4. Export Ranking** (Coming Soon!)
Saat ini bisa screenshot atau copy manual. Fitur export Excel/PDF akan ditambahkan nanti.

---

## 🏆 Fitur Mahasiswa Terbaik

### **Top 3 per Kelas**
Sistem otomatis menampilkan **3 mahasiswa terbaik** untuk setiap kelas:

```
┌──────────────────────────────────────┐
│  Kelas TI-3A                         │
├──────────────────────────────────────┤
│  🥇 1. Andi      - 97.6 poin         │
│  🥈 2. Budi      - 96.4 poin         │
│  🥉 3. Citra     - 89.0 poin         │
└──────────────────────────────────────┘
```

### **Ranking Keseluruhan**
Di sidebar kanan, ada **ranking lengkap** semua mahasiswa dari semua kelas, diurutkan dari tertinggi.

---

## ❓ FAQ (Pertanyaan Sering Diajukan)

### **Q: Dimana menu Nilai Mahasiswa?**
**A:** Di navigasi atas, ada 2 menu terpisah:
- "Nilai Kelompok" ← untuk kelompok
- "Nilai Mahasiswa" ← untuk mahasiswa (INI!)

### **Q: Kenapa tidak ada kriteria mahasiswa?**
**A:** Admin belum setup kriteria dengan segment="student". Minta Admin untuk tambah kriteria di menu "Kriteria".

### **Q: Kenapa ranking tidak muncul?**
**A:** Pastikan sudah input nilai untuk **semua kriteria** minimal 1 mahasiswa. Lalu klik "Hitung Ulang Ranking".

### **Q: Bisakah input nilai untuk 1 mahasiswa saja?**
**A:** Bisa! Tapi ranking hanya akan akurat jika semua mahasiswa sudah dinilai.

### **Q: Apa perbedaan Benefit dan Cost?**
**A:** 
- **Benefit**: Semakin tinggi nilai, semakin baik (contoh: Nilai PBL)
- **Cost**: Semakin rendah nilai, semakin baik (contoh: Jumlah keterlambatan)

### **Q: Total bobot tidak 100%, kenapa error?**
**A:** Untuk segment "student", total bobot semua kriteria harus = 1.0 (atau 100%). Cek di menu "Kriteria" dan sesuaikan.

### **Q: Bagaimana cara ubah nilai yang sudah diinput?**
**A:** Input ulang dengan mahasiswa dan kriteria yang sama. Sistem akan otomatis replace nilai lama.

---

## 📊 Perbedaan Nilai Kelompok vs Mahasiswa

| Aspek | Nilai Kelompok | Nilai Mahasiswa |
|-------|---------------|-----------------|
| **Menu** | "Nilai Kelompok" | "Nilai Mahasiswa" |
| **Segment** | group | student |
| **Unit** | Per kelompok | Per mahasiswa |
| **Contoh Kriteria** | Kecepatan Progres, Kualitas Output | Nilai PBL, Penilaian Teman |
| **Ranking** | Kelompok terbaik | Mahasiswa terbaik |

---

## 🎨 Screenshot Lokasi Menu

**Navigasi Desktop:**
```
┌──────────────────────────────────────────────────────┐
│ Dashboard | Kelas | Kelompok | Kriteria | Target ... │
│ ... | Nilai Kelompok | Nilai Mahasiswa ⬅️ INI!      │
└──────────────────────────────────────────────────────┘
```

**Navigasi Mobile:**
- Klik hamburger menu (☰)
- Scroll ke bawah
- Lihat "Nilai Kelompok" dan "Nilai Mahasiswa"

---

## ✅ Checklist Input Nilai

Gunakan checklist ini untuk memastikan semua langkah sudah dilakukan:

- [ ] Admin sudah setup kriteria dengan segment="student"
- [ ] Total bobot kriteria = 100%
- [ ] Login sebagai Dosen/Koordinator
- [ ] Buka menu "Nilai Mahasiswa"
- [ ] Input nilai untuk setiap mahasiswa
- [ ] Input nilai untuk setiap kriteria
- [ ] Klik "Hitung Ulang Ranking"
- [ ] Cek ranking mahasiswa terbaik
- [ ] Cek top 3 per kelas

---

## 📞 Butuh Bantuan?

Jika masih bingung atau ada error:

1. **Cek Dokumentasi:** Baca file `IMPLEMENTASI_SAW.md`
2. **Tanya Admin:** Koordinator atau Admin sistem
3. **Screenshot Error:** Ambil screenshot dan laporkan

---

## 🎉 Kesimpulan

Fitur **input nilai mahasiswa** dan **mencari mahasiswa terbaik** sudah **100% siap digunakan**! 

Tidak perlu coding lagi, cukup:
1. Setup kriteria (Admin)
2. Input nilai (Dosen/Koordinator)
3. Lihat ranking terbaik!

Selamat menggunakan! 🚀

---

**Dibuat:** 6 Oktober 2025  
**Metode:** SAW (Simple Additive Weighting)  
**Status:** ✅ Siap Pakai

