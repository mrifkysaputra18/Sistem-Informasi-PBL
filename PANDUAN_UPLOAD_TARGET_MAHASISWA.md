# 📚 Panduan Upload Target Mingguan untuk Mahasiswa

**Tanggal:** 6 Oktober 2025  
**Status:** ✅ **LENGKAP & SIAP DIGUNAKAN**

---

## 🎯 **Apa itu Target Mingguan?**

Target Mingguan adalah tugas atau target yang dibuat oleh **Dosen** untuk setiap kelompok mahasiswa. Mahasiswa kemudian harus **mengupload/submit** target tersebut sebagai bukti penyelesaian.

**Target yang di-submit akan masuk ke penilaian** pada kriteria **"Kecepatan Progres"** dan mempengaruhi **ranking kelompok**.

---

## 🔄 **Alur Kerja Sistem**

### **1. Dosen Membuat Target** 👨‍🏫
```
Dosen Login
    ↓
Menu "Kelola Target Mingguan"
    ↓
Klik "Buat Target Baru"
    ↓
Form:
  - Pilih Kelompok
  - Minggu ke-berapa (1-16)
  - Judul Target
  - Deskripsi Detail
  - Deadline (tanggal & jam)
    ↓
Submit → Target dibuat untuk kelompok
```

### **2. Mahasiswa Melihat Target** 👨‍🎓
```
Mahasiswa Login
    ↓
Dashboard Mahasiswa
    ↓
Bagian "Target Mingguan dari Dosen"
    ↓
Lihat list semua target:
  - Status (Pending/Submitted/Approved/dll)
  - Deadline
  - Minggu ke-berapa
```

### **3. Mahasiswa Submit Target** 📤
```
Klik "Lihat" pada target tertentu
    ↓
Lihat detail target (judul, deskripsi, deadline)
    ↓
Klik "Submit Target"
    ↓
Pilih cara submit:
  Option A: Upload File (bukti/tugas)
  Option B: Centang "Selesai tanpa file"
    ↓
Tambah catatan (opsional)
    ↓
Submit → Target tersimpan
    ↓
Menunggu review dosen
```

### **4. Dosen Review Submission** ✅
```
Dosen Login
    ↓
Menu "Review Submission"
    ↓
Lihat submission mahasiswa
    ↓
Beri review:
  - Approve (Disetujui)
  - Revision (Perlu revisi)
  - Feedback/Catatan
    ↓
Submit Review
    ↓
Mahasiswa dapat notifikasi
```

### **5. Sistem Hitung Penilaian** 🏆
```
Sistem otomatis menghitung:
  - Total target yang dibuat dosen
  - Target yang sudah di-submit mahasiswa
  - Completion Rate = (Submit / Total) × 100
    ↓
Kriteria "Kecepatan Progres" menggunakan completion rate
    ↓
Ranking kelompok otomatis di-update
```

---

## 📖 **Cara Menggunakan (Detail)**

### **A. Untuk Mahasiswa:**

#### **1. Login ke Sistem**
- Buka aplikasi
- Login dengan akun Google (@politala.ac.id)

#### **2. Lihat Target di Dashboard**
Di dashboard mahasiswa, Anda akan melihat:

**Statistik:**
- 📊 Total Target: Jumlah semua target dari dosen
- ✅ Sudah Submit: Target yang sudah Anda submit
- 📈 Tingkat Submit: Persentase completion
- ⏳ Belum Submit: Target yang belum dikerjakan

**Tabel Target:**
| Minggu | Target | Deadline | Status | Aksi |
|--------|--------|----------|--------|------|
| Minggu 1 | Desain Database | 08/10/2025 | Pending | Lihat |
| Minggu 2 | Backend API | 15/10/2025 | Submitted | Lihat |

#### **3. Submit Target**

**Langkah-langkah:**

1. **Klik "Lihat"** pada target yang ingin dikerjakan
2. Anda akan melihat **Detail Target**:
   - Judul
   - Deskripsi
   - Deadline
   - Dibuat oleh (nama dosen)
   
3. **Klik "Submit Target"**

4. **Pilih Cara Submit:**

   **Option A: Upload File**
   - Pilih radio button "Upload File"
   - Klik area upload atau drag & drop file
   - File yang didukung: JPG, PNG, PDF, DOC, DOCX
   - Max size: 5MB per file
   - Bisa upload multiple files
   - File akan diupload ke **Google Drive** otomatis

   **Option B: Tanpa File (Checklist)**
   - Pilih radio button "Tanpa File"
   - Centang "Saya menyatakan target ini sudah selesai"
   - Gunakan jika target tidak memerlukan file bukti

5. **Tambah Catatan (Opsional)**
   - Jelaskan apa yang sudah dikerjakan
   - Catatan khusus untuk dosen

6. **Klik "Submit Target"**

#### **4. Edit Submission (Sebelum Direview)**

Jika Anda sudah submit tapi ingin mengubah:

1. Buka detail target yang sudah disubmit
2. Klik "Edit Submission"
3. Ubah file atau catatan
4. Submit ulang

**CATATAN:** Anda **TIDAK BISA** edit submission setelah dosen sudah mereview!

#### **5. Lihat Review Dosen**

Setelah dosen review:
- Buka detail target
- Lihat bagian "Review Dosen"
- Status akan berubah:
  - **Approved**: Disetujui ✅
  - **Revision**: Perlu revisi ⚠️

Jika status **Revision**:
- Klik "Revisi"
- Upload ulang atau perbaiki
- Submit ulang

---

### **B. Untuk Dosen:**

#### **1. Membuat Target Mingguan**

1. Login ke sistem
2. Menu "Kelola Target Mingguan"
3. Klik "Buat Target Baru"
4. Isi form:
   
   **Pilih Tipe Target:**
   - Single Group: Untuk 1 kelompok
   - Multiple Groups: Untuk beberapa kelompok
   - All Class: Untuk semua kelompok di kelas

   **Detail Target:**
   - Minggu ke-berapa (1-16)
   - Judul target (contoh: "Desain Database")
   - Deskripsi detail
   - Deadline (tanggal & jam)

5. Submit → Target otomatis dibuat

#### **2. Review Submission Mahasiswa**

1. Menu "Review Submission"
2. Lihat list submission yang perlu direview
3. Klik submission tertentu
4. Lihat:
   - File yang diupload (jika ada)
   - Catatan mahasiswa
   - Waktu submit (terlambat atau tidak)

5. Beri Review:
   - **Approve**: Jika sudah sesuai
   - **Revision**: Jika perlu perbaikan
   - Tambah feedback/catatan

6. Submit Review
7. Mahasiswa akan mendapat notifikasi

---

## 🎯 **Integrasi dengan Penilaian**

### **Kriteria "Kecepatan Progres"**

Sistem **OTOMATIS** menghitung nilai berdasarkan:

```
Completion Rate = (Target yang Di-submit / Total Target) × 100
```

**Contoh Perhitungan:**

**Kelompok A:**
- Total target dari dosen: 10
- Yang sudah di-submit: 8
- Completion Rate: 8/10 × 100 = **80%**

**Kelompok B:**
- Total target dari dosen: 10
- Yang sudah di-submit: 10
- Completion Rate: 10/10 × 100 = **100%** ✨

**Kelompok C:**
- Total target dari dosen: 10
- Yang sudah di-submit: 5
- Completion Rate: 5/10 × 100 = **50%** ⚠️

### **Bobot Kriteria**

Di sistem, kriteria penilaian sudah dibuat:

| Kriteria | Bobot | Tipe |
|----------|-------|------|
| **Kecepatan Progres** | 25% | Benefit (otomatis) |
| Hasil Review Dosen | 35% | Benefit (manual) |
| Ketepatan Waktu | 20% | Benefit (manual) |
| Kolaborasi Anggota | 20% | Benefit (manual) |

**Total: 100%**

**"Kecepatan Progres"** → Otomatis dihitung dari completion rate target mingguan!

### **Ranking Otomatis**

Sistem akan:
1. Hitung completion rate setiap kelompok
2. Normalisasi nilai (0-100)
3. Kalikan dengan bobot (25%)
4. Gabung dengan kriteria lain
5. **Update ranking kelompok otomatis**

---

## 📊 **Status Submission**

### **Pending** (Abu-abu)
- Target belum dikerjakan
- Belum ada submission
- **Aksi**: Submit sekarang

### **Submitted** (Biru)
- Target sudah disubmit tepat waktu
- Menunggu review dosen
- **Aksi**: Bisa edit sebelum direview

### **Late** (Orange)
- Target disubmit **setelah deadline**
- Menunggu review dosen
- Mungkin ada pengurangan nilai
- **Aksi**: Bisa edit sebelum direview

### **Approved** (Hijau)
- Target disetujui dosen ✅
- Submission selesai
- **Aksi**: Tidak bisa diubah lagi

### **Revision** (Kuning)
- Perlu revisi/perbaikan ⚠️
- Dosen sudah memberi feedback
- **Aksi**: Upload ulang/perbaiki

---

## 💡 **Tips untuk Mahasiswa**

### **1. Submit Tepat Waktu**
- Cek deadline setiap target
- Jangan submit mepet deadline
- Status "Late" bisa mempengaruhi nilai

### **2. Upload File yang Jelas**
- File harus sesuai dengan target
- Pastikan file tidak corrupt
- Beri nama file yang deskriptif
  - ❌ `file1.pdf`
  - ✅ `Kelompok1_DesainDatabase_Minggu1.pdf`

### **3. Tulis Catatan yang Informatif**
- Jelaskan apa yang sudah dikerjakan
- Jika ada kendala, tulis di catatan
- Bantu dosen memahami progress Anda

### **4. Cek Review Dosen Secara Rutin**
- Login minimal 2x seminggu
- Cek apakah ada feedback dari dosen
- Segera perbaiki jika ada revision

### **5. Koordinasi dengan Kelompok**
- Pastikan semua anggota tahu deadline
- Diskusikan siapa yang akan submit
- Ketua kelompok sebaiknya yang submit

### **6. Manfaatkan Opsi "Tanpa File"**
- Untuk target yang tidak perlu bukti file
- Contoh: "Meeting kelompok", "Diskusi konsep"
- Tetap tulis catatan apa yang sudah dilakukan

---

## 🔧 **Troubleshooting**

### **Problem: File Gagal Diupload**

**Solusi:**
1. Cek ukuran file (max 5MB per file)
2. Cek format file (harus JPG, PNG, PDF, DOC, DOCX)
3. Cek koneksi internet
4. Jika masih gagal, sistem akan otomatis simpan ke local storage

### **Problem: Tidak Bisa Submit**

**Kemungkinan:**
- Bukan anggota kelompok ❌
- Target sudah direview dosen (tidak bisa edit lagi) ❌

**Solusi:**
- Hubungi koordinator/admin jika bukan anggota
- Jika sudah direview, tidak bisa diubah

### **Problem: Tombol "Submit" Tidak Muncul**

**Penyebab:**
- Target sudah disubmit → Tombol berubah jadi "Edit Submission"
- Target sudah direview → Tombol tidak muncul

### **Problem: Upload File Lambat**

**Solusi:**
- Sistem sedang upload ke Google Drive
- Tunggu sampai selesai (jangan tutup browser)
- Jika timeout, sistem akan fallback ke local storage

---

## 📈 **Monitoring Progress**

### **Dashboard Mahasiswa**

Statistik yang ditampilkan:

1. **Total Target**: Semua target dari dosen
2. **Sudah Submit**: Target yang sudah Anda kerjakan
3. **Tingkat Submit**: Persentase completion Anda
4. **Belum Submit**: Target yang masih pending

**Contoh:**
```
Total Target: 10
Sudah Submit: 7
Tingkat Submit: 70%
Belum Submit: 3
```

Artinya: Dari 10 target yang diberikan dosen, Anda sudah submit 7 (70%), masih ada 3 yang perlu dikerjakan.

### **Impact ke Ranking**

Tingkat submit 70% → Nilai "Kecepatan Progres" = 70/100 × 25% = **17.5 poin**

Jika target submit 100% → Nilai "Kecepatan Progres" = 100/100 × 25% = **25 poin** ✨

**Selisih: 7.5 poin!** (sangat signifikan untuk ranking)

---

## 🎓 **Best Practices**

### **Untuk Kelompok yang Ingin Ranking Tinggi:**

1. **Submit Semua Target** (100% completion)
2. **Submit Tepat Waktu** (tidak late)
3. **Upload File Bukti yang Jelas**
4. **Tulis Catatan yang Detail**
5. **Revisi Cepat jika Diminta Dosen**
6. **Koordinasi Baik antar Anggota**

### **Untuk Dosen:**

1. **Buat Target yang Jelas dan Terukur**
2. **Set Deadline yang Realistis**
3. **Review Submission Secara Rutin**
4. **Beri Feedback yang Konstruktif**
5. **Gunakan Status Revision untuk Perbaikan**

---

## 🚀 **Fitur Unggulan**

### **✅ Upload ke Google Drive**
- Semua file otomatis tersimpan di Google Drive
- Tidak perlu khawatir server penuh
- File aman dan tersinkronisasi

### **✅ Fallback Local Storage**
- Jika Google Drive gagal, sistem otomatis simpan lokal
- Tidak ada data yang hilang
- Upload tetap berjalan lancar

### **✅ Multiple File Upload**
- Bisa upload banyak file sekaligus
- Tidak perlu submit berkali-kali
- Drag & drop support

### **✅ Penilaian Otomatis**
- Kriteria "Kecepatan Progres" otomatis dihitung
- Dosen tidak perlu input manual
- Ranking terupdate real-time

### **✅ Status Tracking**
- Lihat status setiap target
- Tahu mana yang pending/submitted/approved
- Deadline reminder visual

### **✅ Revision System**
- Dosen bisa minta revisi
- Mahasiswa bisa upload ulang
- Feedback loop yang jelas

---

## 📋 **Checklist Sebelum Submit**

Sebelum submit target, pastikan:

- [ ] Target sudah selesai dikerjakan
- [ ] File bukti sudah siap (jika perlu)
- [ ] File format benar (JPG, PNG, PDF, DOC, DOCX)
- [ ] File size di bawah 5MB per file
- [ ] Sudah tulis catatan penjelasan
- [ ] Koordinasi dengan anggota kelompok
- [ ] Cek deadline (jangan terlambat)
- [ ] Pastikan Anda anggota kelompok yang benar

---

## 🔗 **Link & Resources**

### **Menu Penting:**

**Untuk Mahasiswa:**
- Dashboard: `/mahasiswa/dashboard`
- List Target: `/my-targets`
- Submit Target: Klik "Lihat" → "Submit Target"

**Untuk Dosen:**
- Kelola Target: `/targets`
- Buat Target: `/targets/create`
- Review Submission: `/targets` → Lihat submission

---

## 📞 **Bantuan**

Jika mengalami kendala:

1. **Hubungi Koordinator/Admin**
2. **Cek dokumentasi ini**
3. **Tanya anggota kelompok yang sudah berhasil**

---

## 🎯 **Summary**

### **Sistem Upload Target Mingguan:**

✅ **Dosen** membuat target untuk kelompok  
✅ **Mahasiswa** submit target (file/checklist)  
✅ **Sistem** otomatis hitung completion rate  
✅ **Kriteria "Kecepatan Progres"** otomatis terupdate  
✅ **Ranking kelompok** otomatis di-update  

### **Benefit:**

- 🎯 Target jelas dari dosen
- 📤 Upload mudah (file atau checklist)
- 🤖 Penilaian otomatis
- 📊 Transparan (lihat progress real-time)
- 🏆 Fair ranking system

---

**Selamat menggunakan sistem! Semangat mencapai target! 🚀**

---

**Last Updated:** 6 Oktober 2025  
**Status:** Production Ready ✅  
**Version:** 1.0

