# Kriteria Penilaian - Sistem Informasi PBL

## 📊 Kriteria Penilaian Kelompok (Group)

Sistem ini menggunakan 4 kriteria untuk menilai kelompok PBL dengan bobot yang telah dihitung menggunakan metode AHP (Analytical Hierarchy Process):

| No | Nilai Kriteria | Bobot Prioritas | Dalam Persen | Tipe |
|----|----------------|-----------------|--------------|------|
| 1  | Kecepatan Progres | 0.244465446 | 24.45% | Benefit |
| 2  | Nilai Akhir PBL | 0.530599135 | 53.06% | Benefit |
| 3  | Ketepatan Waktu | 0.153145735 | 15.31% | Benefit |
| 4  | Penilaian Teman (Group) | 0.071789684 | 7.18% | Benefit |

**Total Bobot: 1.000000000 (100%)**

## 👨‍🎓 Kriteria Penilaian Mahasiswa (Student)

Sistem ini menggunakan 3 kriteria untuk menilai mahasiswa dengan bobot yang telah dihitung menggunakan metode AHP:

| No | Nilai Kriteria | Bobot Prioritas | Dalam Persen | Tipe |
|----|----------------|-----------------|--------------|------|
| 1  | Nilai Akhir PBL | 0.63334572 | 63.33% | Benefit |
| 2  | Penilaian Teman | 0.260497956 | 26.05% | Benefit |
| 3  | Kehadiran | 0.106156324 | 10.62% | Benefit |

**Total Bobot: 1.000000000 (100%)**

## 🔧 Implementasi Teknis

### 1. Database Schema
Kolom `bobot` di tabel `kriteria` menggunakan tipe data `DECIMAL(10,9)` untuk menyimpan nilai dengan presisi tinggi (9 digit desimal).

### 2. Seeder
File: `database/seeders/CriterionSeeder.php`

**Kriteria Kelompok:**
```php
$group = [
    ['nama' => 'Kecepatan Progres', 'bobot' => 0.244465446, 'tipe' => 'benefit', 'segment' => 'group'],
    ['nama' => 'Nilai Akhir PBL', 'bobot' => 0.530599135, 'tipe' => 'benefit', 'segment' => 'group'],
    ['nama' => 'Ketepatan Waktu', 'bobot' => 0.153145735, 'tipe' => 'benefit', 'segment' => 'group'],
    ['nama' => 'Penilaian Teman (Group)', 'bobot' => 0.071789684, 'tipe' => 'benefit', 'segment' => 'group'],
];
```

**Kriteria Mahasiswa:**
```php
$student = [
    ['nama' => 'Nilai Akhir PBL', 'bobot' => 0.63334572, 'tipe' => 'benefit', 'segment' => 'student'],
    ['nama' => 'Penilaian Teman', 'bobot' => 0.260497956, 'tipe' => 'benefit', 'segment' => 'student'],
    ['nama' => 'Kehadiran', 'bobot' => 0.106156324, 'tipe' => 'benefit', 'segment' => 'student'],
];
```

### 3. Migration
File: `database/migrations/2025_10_30_031937_ubah_presisi_bobot_kriteria.php`

Migration ini mengubah presisi kolom bobot dari `DECIMAL(5,2)` menjadi `DECIMAL(10,9)`.

## 📍 Penggunaan di Sistem

### 1. Input Nilai Kelompok
- **Controller**: `App\Http\Controllers\NilaiKelompokController`
- **View**: `resources/views/nilai/tambah.blade.php`
- Mengambil kriteria dengan: `Kriteria::where('segment', 'group')->get()`

### 2. Input Nilai Mahasiswa
- **Controller**: `App\Http\Controllers\InputNilaiMahasiswaController`
- **View**: `resources/views/nilai/input-mahasiswa.blade.php`
- Mengambil kriteria dengan: `Kriteria::where('segment', 'student')->get()`

### 3. Perhitungan Ranking
- **Service**: `App\Services\RankingService`
- **Method**: `computeGroupTotals()` untuk kelompok
- Menggunakan metode SAW (Simple Additive Weighting) dengan bobot kriteria

### 4. AHP (Analytical Hierarchy Process)
- **Controller**: `App\Http\Controllers\AhpController`
- **Service**: `App\Services\AhpService`
- Untuk perhitungan ulang bobot kriteria jika diperlukan
- Mendukung segment 'group' dan 'student'

### 5. Dashboard
- **Controller**: `App\Http\Controllers\DasborController`
- Menampilkan total kriteria kelompok dan mahasiswa

## 🎯 Cara Menggunakan

### Seed Kriteria
```bash
php artisan db:seed --class=CriterionSeeder
```

### Migrate Database
```bash
php artisan migrate
```

### Verifikasi Kriteria
Kriteria dapat dilihat di menu:
- **Admin** → **Kriteria** → Filter by "Group" atau "Student"
- **Admin** → **AHP** → Pilih "Kriteria Kelompok" atau "Kriteria Mahasiswa"

## 📝 Catatan Penting

1. **Bobot Total Harus 100%**: Total bobot semua kriteria (baik kelompok maupun mahasiswa) harus sama dengan 1.0 (100%)
2. **Presisi Tinggi**: Gunakan 9 digit desimal untuk akurasi perhitungan
3. **Tipe Benefit**: Semua kriteria menggunakan tipe "benefit" (semakin tinggi semakin baik)
4. **Segment**: 
   - `segment = 'group'` untuk kriteria kelompok
   - `segment = 'student'` untuk kriteria mahasiswa

## 🔄 Update Bobot (Jika Diperlukan)

Jika perlu mengupdate bobot kriteria:

1. Edit file `database/seeders/CriterionSeeder.php`
2. Jalankan: `php artisan db:seed --class=CriterionSeeder`
3. Atau gunakan menu AHP untuk perhitungan ulang

---

**Terakhir diupdate**: 30 Oktober 2025
**Status**: ✅ Aktif dan Terintegrasi
