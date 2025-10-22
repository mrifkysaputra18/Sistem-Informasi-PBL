# 📝 Fitur Input Nilai Mahasiswa dengan Perhitungan Otomatis

## 📋 Deskripsi
Fitur ini memungkinkan **Dosen** untuk menginput nilai mahasiswa melalui form tabel interaktif dengan perhitungan ranking otomatis menggunakan metode **SAW (Simple Additive Weighting)**.

## ✨ Fitur Utama

### 1. **Info Dosen**
- Menampilkan informasi dosen yang sedang login
- Nama, email, dan role dosen

### 2. **Pilih Kelas**
- Dropdown untuk memilih kelas yang akan dinilai
- Tombol "Tampilkan Mahasiswa" untuk load data

### 3. **Tabel Form Input Nilai**
Tabel interaktif dengan kolom:
- **No**: Nomor urut
- **NIM**: Nomor Induk Mahasiswa
- **Nama Mahasiswa**: Nama lengkap dengan avatar dan email
- **Kriteria**: Kolom input untuk setiap kriteria (dengan bobot)
  - Input field untuk nilai 0-100
  - Menampilkan nilai existing jika sudah ada

### 4. **Tombol Aksi**
- **Simpan Nilai**: Menyimpan semua nilai yang diinput ke database
- **Hitung Ranking Otomatis**: Menghitung dan menampilkan ranking berdasarkan nilai yang diinput

### 5. **Hasil Perhitungan Ranking**
Tabel hasil dengan informasi:
- **Rank**: Peringkat dengan medal icons (🥇🥈🥉)
- **NIM & Nama**: Identitas mahasiswa
- **Nilai per Kriteria**: 
  - Raw score (nilai asli)
  - Weighted score (W)
- **Total Skor**: Skor akhir hasil perhitungan SAW

### 6. **Keterangan Perhitungan**
Panel informasi yang menjelaskan metode SAW dan formula perhitungan

## 🎯 Alur Penggunaan

```
1. Dosen Login
   ↓
2. Akses Menu "Input Nilai Mahasiswa"
   ↓
3. Pilih Kelas dari Dropdown
   ↓
4. Klik "Tampilkan Mahasiswa"
   ↓
5. Tabel Form Muncul dengan Daftar Mahasiswa
   ↓
6. Input Nilai untuk Setiap Kriteria (0-100)
   ↓
7. Klik "Simpan Nilai" (opsional)
   ↓
8. Klik "Hitung Ranking Otomatis"
   ↓
9. Sistem Otomatis:
   - Simpan nilai ke database
   - Hitung ranking dengan metode SAW
   - Tampilkan hasil ranking
```

## 🔧 Implementasi Teknis

### File yang Dibuat/Dimodifikasi:

#### 1. **Controller**: `app/Http/Controllers/StudentScoreInputController.php`
**Methods:**
- `index()`: Tampilkan form input nilai
- `getStudentsByClass()`: Get mahasiswa berdasarkan kelas (AJAX)
- `store()`: Simpan nilai mahasiswa ke database
- `calculate()`: Hitung ranking dengan metode SAW

#### 2. **View**: `resources/views/scores/student-input.blade.php`
**Sections:**
- Info Dosen
- Form Pilih Kelas
- Loading State
- Tabel Form Input Nilai
- Tombol Aksi
- Hasil Perhitungan Ranking
- JavaScript untuk interaktivitas

#### 3. **Routes**: `routes/web.php`
**Endpoints:**
```php
// Middleware: role:dosen,admin
GET  /scores/student-input              -> Form input nilai
GET  /scores/students-by-class          -> Get mahasiswa (AJAX)
POST /scores/student-input/store        -> Simpan nilai
POST /scores/student-input/calculate    -> Hitung ranking
```

## 📊 Metode SAW (Simple Additive Weighting)

### Formula Perhitungan:
```
1. Normalisasi = Nilai / 100
2. Weighted Score (W) = Normalisasi × Bobot Kriteria
3. Total Skor = Σ (Weighted Score semua kriteria)
4. Ranking = Sort by Total Skor (descending)
```

### Contoh Perhitungan:
**Mahasiswa: Ahmad**
- Kriteria A (Bobot 30%): Nilai 85
  - Normalisasi: 85/100 = 0.85
  - W = 0.85 × 0.30 = 0.255

- Kriteria B (Bobot 40%): Nilai 90
  - Normalisasi: 90/100 = 0.90
  - W = 0.90 × 0.40 = 0.360

- Kriteria C (Bobot 30%): Nilai 80
  - Normalisasi: 80/100 = 0.80
  - W = 0.80 × 0.30 = 0.240

**Total Skor = 0.255 + 0.360 + 0.240 = 0.855**

## 🚀 Cara Menggunakan

### 1. Akses Fitur
```
URL: /scores/student-input
Role: Dosen atau Admin
```

### 2. Pilih Kelas
- Pilih kelas dari dropdown
- Klik "Tampilkan Mahasiswa"

### 3. Input Nilai
- Masukkan nilai untuk setiap mahasiswa di setiap kriteria
- Nilai harus antara 0-100
- Bisa input desimal (contoh: 85.5)

### 4. Simpan Nilai
- Klik "Simpan Nilai" untuk menyimpan ke database
- Notifikasi sukses akan muncul

### 5. Hitung Ranking
- Klik "Hitung Ranking Otomatis"
- Sistem akan:
  - Otomatis simpan nilai (jika belum)
  - Hitung ranking
  - Tampilkan hasil di bawah

### 6. Lihat Hasil
- Tabel ranking akan muncul
- Top 3 mendapat highlight khusus
- Medal icons untuk juara 1, 2, 3

## 🎨 Fitur UI/UX

### Visual Features:
- **Gradient Headers**: Header dengan gradient warna
- **Avatar Mahasiswa**: Initial name dalam circle
- **Input Validation**: Min 0, Max 100
- **Loading State**: Spinner saat load data
- **Notifications**: Toast notification untuk feedback
- **Smooth Scroll**: Auto scroll ke hasil ranking
- **Hover Effects**: Interactive hover pada tabel
- **Medal Icons**: 🥇🥈🥉 untuk top 3
- **Highlight**: Background kuning untuk top 3

### Responsive Design:
- Mobile friendly
- Tabel scrollable horizontal
- Grid responsive untuk form

## 📝 Database Schema

### Table: `student_scores`
```sql
CREATE TABLE student_scores (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    criterion_id BIGINT NOT NULL,
    skor DECIMAL(5,2) NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (criterion_id) REFERENCES criteria(id),
    UNIQUE KEY unique_score (user_id, criterion_id)
);
```

### Update or Create Logic:
- Jika nilai sudah ada → Update
- Jika nilai belum ada → Insert
- Menggunakan `updateOrCreate()` Laravel

## 🔐 Security & Validation

### Middleware:
- `auth`: User harus login
- `role:dosen,admin`: Hanya dosen dan admin yang bisa akses

### Validation Rules:
```php
'scores' => 'required|array',
'scores.*.user_id' => 'required|exists:users,id',
'scores.*.criterion_id' => 'required|exists:criteria,id',
'scores.*.skor' => 'required|numeric|min:0|max:100',
```

### CSRF Protection:
- Semua POST request menggunakan CSRF token
- Token otomatis di-inject via Blade

## 🐛 Error Handling

### Frontend:
- Alert jika kelas belum dipilih
- Alert jika tidak ada nilai valid
- Notification untuk error
- Console.log untuk debugging

### Backend:
- Try-catch untuk database operations
- DB transaction untuk data consistency
- Validation error messages
- HTTP status codes

## 📱 API Endpoints

### 1. Get Students by Class
```
GET /scores/students-by-class?class_room_id={id}
Response: {
    "success": true,
    "students": [...]
}
```

### 2. Store Scores
```
POST /scores/student-input/store
Body: {
    "scores": [
        {
            "user_id": 1,
            "criterion_id": 1,
            "skor": 85.5
        },
        ...
    ]
}
Response: {
    "success": true,
    "message": "Nilai berhasil disimpan!"
}
```

### 3. Calculate Ranking
```
POST /scores/student-input/calculate
Body: {
    "class_room_id": 1
}
Response: {
    "success": true,
    "rankings": [...]
}
```

## 🎯 Fitur JavaScript

### Functions:
- `loadStudents()`: Load mahasiswa dari server
- `renderScoreTable()`: Render tabel input nilai
- `saveScores()`: Simpan nilai ke server
- `calculateRanking()`: Hitung dan tampilkan ranking
- `renderRankingTable()`: Render tabel hasil ranking
- `showNotification()`: Tampilkan notifikasi

### AJAX Calls:
- Fetch API untuk komunikasi dengan server
- Async/await untuk handling promises
- Error handling dengan try-catch

## 📊 Performance

### Optimizations:
- Eager loading untuk relasi (with)
- Query hanya mahasiswa dari kelas tertentu
- JavaScript rendering untuk dynamic content
- CSS animations dengan GPU acceleration

## 🔄 Future Enhancements

- [ ] Export ranking ke PDF/Excel
- [ ] Bulk import nilai dari CSV
- [ ] History perubahan nilai
- [ ] Grafik visualisasi distribusi nilai
- [ ] Perbandingan ranking antar kelas
- [ ] Email notification untuk mahasiswa
- [ ] Real-time collaboration (multiple dosen)

## 🧪 Testing

### Manual Testing Steps:
1. Login sebagai dosen
2. Akses `/scores/student-input`
3. Pilih kelas
4. Verifikasi mahasiswa muncul
5. Input nilai (valid & invalid)
6. Test simpan nilai
7. Test hitung ranking
8. Verifikasi hasil di database

### Test Cases:
- ✅ Nilai valid (0-100)
- ✅ Nilai invalid (<0 atau >100)
- ✅ Nilai desimal
- ✅ Kelas kosong (tidak ada mahasiswa)
- ✅ Kriteria kosong
- ✅ Update nilai existing
- ✅ Insert nilai baru

## 📞 Support

Jika ada masalah:
1. Cek console browser (F12)
2. Cek log Laravel: `storage/logs/laravel.log`
3. Pastikan MySQL running
4. Clear cache: `php artisan cache:clear`

---

**Dibuat oleh**: Development Team  
**Tanggal**: 2025-10-22  
**Versi**: 1.0.0  
**Status**: ✅ Ready for Production
