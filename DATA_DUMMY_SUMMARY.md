# 📊 Data Dummy - Sistem PBL

**Generated:** 2025-10-05  
**Purpose:** Data untuk testing sistem pengambilan keputusan kelompok dan mahasiswa terbaik PBL

---

## ✅ Data Yang Dibuat

### 📚 **5 Kelas Teknik Informatika**
- TI-3A, TI-3B, TI-3C, TI-3D, TI-3E
- Semester 3, Program Studi: Teknik Informatika

### 👥 **25 Kelompok** (5 per kelas)
- Kelompok 1 - 5 di setiap kelas
- Masing-masing 5 anggota per kelompok
- Total 125 mahasiswa baru

### 🎓 **125 Mahasiswa**
- **Email:** mhs100@mhs.politala.ac.id sampai mhs224@mhs.politala.ac.id
- **Password:** `password` (semua sama)
- **Politala ID:** 2341080100 - 2341080224
- **Nama:** Random realistic Indonesian names
  - Contoh: "Candra Firmansyah", "Andi Utomo", "Yanti Santoso", dll
  - Format: [Nama Depan] [Nama Belakang]
  - Jika ada duplikat, ditambah suffix (A, B, C, dst)

### 📋 **208 Weekly Targets** (8 minggu × 25+ kelompok)
Target mingguan meliputi:
1. Minggu 1: Analisis Kebutuhan Sistem
2. Minggu 2: Perancangan Database  
3. Minggu 3: Pembuatan ERD dan Use Case
4. Minggu 4: Setup Project dan Framework
5. Minggu 5: Implementasi Backend API
6. Minggu 6: Implementasi Frontend
7. Minggu 7: Testing dan Bug Fixing
8. Minggu 8: Dokumentasi dan Deployment

### ✅ **~180 Weekly Progress** 
- Progress dari target yang completed
- Status: reviewed
- Ada yang dengan evidence, ada yang check-only

### 📊 **100 Group Scores** (4 kriteria × 25 kelompok)

**Kriteria Penilaian:**

| Kriteria | Bobot | Tipe | Range |
|----------|-------|------|-------|
| 🚀 Kecepatan Progres | 25% | Benefit | 40-100 |
| 📝 Hasil Review Dosen | 35% | Benefit | 40-100 |
| ⏰ Ketepatan Waktu | 20% | Benefit | 40-100 |
| 🤝 Kolaborasi Anggota | 20% | Benefit | 40-100 |

---

## 📈 Karakteristik Data

### Distribusi Nilai (Realistic)
- **Kelompok 1:** Nilai tertinggi (85-100) ⭐⭐⭐⭐⭐
- **Kelompok 2:** Nilai tinggi (78-95) ⭐⭐⭐⭐
- **Kelompok 3:** Nilai sedang (72-90) ⭐⭐⭐
- **Kelompok 4:** Nilai sedang-rendah (65-85) ⭐⭐
- **Kelompok 5:** Nilai rendah (60-80) ⭐

**Note:** Ada variasi random ±10 poin untuk realisme

### Completion Rate Pattern
- Kelompok 1: 80-100% completion
- Kelompok 2: 70-90% completion
- Kelompok 3: 60-80% completion
- Kelompok 4: 50-70% completion
- Kelompok 5: 40-60% completion

---

## 🚀 Cara Menggunakan

### 1. **Jalankan Seeder**

```bash
cd "D:\3B\IT Project 1\Tugas\Sistem-Informasi-PBL"

# Fresh install (hapus data lama)
php artisan migrate:fresh --seed
php artisan db:seed --class=CompletePBLDataSeeder

# Atau tanpa hapus data lama
php artisan db:seed --class=CompletePBLDataSeeder
```

### 2. **Login sebagai Admin**

```
URL: http://localhost:8000
Email: admin@politala.ac.id
Password: password
```

### 3. **Akses Data**

**Menu yang tersedia:**
- **Scores/Penilaian:** Lihat nilai semua kelompok
- **Groups/Kelompok:** Lihat detail kelompok dan anggota
- **Criteria/Kriteria:** Lihat kriteria penilaian
- **Classes/Kelas:** Lihat daftar kelas

### 4. **Hitung Ranking**

- Buka menu **Scores**
- Klik tombol **"Hitung Ulang Peringkat"** (button orange, hanya Admin)
- Sistem akan calculate ranking otomatis

### 5. **Filter Per Kelas**

- Gunakan dropdown filter untuk melihat ranking per kelas
- Compare kelompok terbaik antar kelas

---

## 🔍 Query SQL untuk Analisis

### Top 5 Kelompok Keseluruhan
```sql
SELECT 
    g.name as kelompok,
    c.name as kelas,
    g.total_score,
    g.ranking
FROM groups g
JOIN class_rooms c ON g.class_room_id = c.id
WHERE g.total_score IS NOT NULL
ORDER BY g.total_score DESC
LIMIT 5;
```

### Kelompok Terbaik Per Kelas
```sql
SELECT 
    c.name as kelas,
    g.name as kelompok,
    g.total_score,
    g.ranking
FROM groups g
JOIN class_rooms c ON g.class_room_id = c.id
WHERE g.ranking IS NOT NULL
ORDER BY c.name, g.ranking ASC;
```

### Completion Rate per Kelompok
```sql
SELECT 
    g.name as kelompok,
    c.name as kelas,
    COUNT(wt.id) as total_targets,
    SUM(CASE WHEN wt.is_completed = 1 THEN 1 ELSE 0 END) as completed,
    ROUND(SUM(CASE WHEN wt.is_completed = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(wt.id), 2) as completion_rate
FROM groups g
JOIN class_rooms c ON g.class_room_id = c.id
LEFT JOIN weekly_targets wt ON g.id = wt.group_id
GROUP BY g.id
ORDER BY completion_rate DESC;
```

### Detail Nilai per Kelompok
```sql
SELECT 
    g.name as kelompok,
    cr.nama as kriteria,
    gs.skor,
    cr.bobot,
    ROUND(gs.skor * cr.bobot, 2) as weighted_score
FROM group_scores gs
JOIN groups g ON gs.group_id = g.id
JOIN criteria cr ON gs.criterion_id = cr.id
WHERE g.id = 1
ORDER BY cr.bobot DESC;
```

---

## 🎯 Use Cases

### Use Case 1: Cari Kelompok Terbaik di TI-3A
1. Login sebagai admin
2. Menu Scores → Filter kelas "TI-3A"
3. Klik "Hitung Ulang Peringkat"
4. Lihat ranking 1-5

### Use Case 2: Bandingkan Kelompok Antar Kelas
1. Export data semua kelas
2. Lihat kelompok dengan total_score tertinggi
3. Bandingkan completion rate vs score

### Use Case 3: Analisis Mahasiswa Terbaik
1. Lihat ketua kelompok dengan score tertinggi
2. Cek completion rate kelompok mereka
3. Cek attendance dan participation

---

## 🔧 Modifikasi Data

### Ubah Pola Nilai

Edit file: `database/seeders/CompletePBLDataSeeder.php`

```php
// Line ~185 - Ubah base score
private function generateScore($criteriaName, $groupNum, $classIndex): float
{
    $baseScore = 100 - (($groupNum - 1) * 8); // Ubah angka 8 ini
    // ...
}
```

### Ubah Jumlah Target

```php
// Line ~127 - Ubah total targets
$totalTargets = 8; // Ubah jadi 10, 12, dst
```

### Ubah Completion Rate

```php
// Line ~125 - Ubah completion rate
$completionRate = max(30, min(100, $baseScore + rand(-20, 20)));
// Ubah range -20, 20 untuk variasi lebih besar/kecil
```

---

## 📝 Testing Scenarios

### Scenario 1: SAW (Simple Additive Weighting)
✅ Sudah diimplementasikan di sistem
- Score × Bobot per kriteria
- Sum total = Final Score
- Ranking berdasarkan final score

### Scenario 2: TOPSIS (belum diimplementasikan)
Bisa ditambahkan dengan:
1. Normalize matrix
2. Weight normalized matrix  
3. Hitung ideal positive & negative
4. Hitung distance
5. Calculate preference value

### Scenario 3: AHP (belum diimplementasikan)
Bisa ditambahkan dengan:
1. Pairwise comparison
2. Calculate eigenvalues
3. Consistency check
4. Final weights

---

## 💡 Tips

### 1. Reset Data
```bash
# Hapus semua dan buat ulang
php artisan migrate:fresh --seed
php artisan db:seed --class=CompletePBLDataSeeder
```

### 2. Cek Data di Tinker
```bash
php artisan tinker

# Cek jumlah data
>>> \App\Models\Group::count();
>>> \App\Models\User::where('role', 'mahasiswa')->count();
>>> \App\Models\WeeklyTarget::count();

# Lihat data kelompok
>>> \App\Models\Group::with('members', 'scores')->first();
```

### 3. Debug Scoring
```bash
# Lihat detail scoring kelompok ID 1
>>> $group = \App\Models\Group::with('scores.criterion')->find(1);
>>> $group->scores->map(fn($s) => [
    'kriteria' => $s->criterion->nama,
    'skor' => $s->skor,
    'bobot' => $s->criterion->bobot,
    'weighted' => $s->skor * $s->criterion->bobot
]);
```

---

## 📧 Akun Demo

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| Admin | admin@politala.ac.id | password | Full access |
| Koordinator | koordinator@politala.ac.id | password | Manage members |
| Dosen | dosen1@politala.ac.id | password | Input scores |
| Mahasiswa | mhs100@mhs.politala.ac.id | password | View own group |

---

## 🎓 Struktur Hierarki

```
Subject: Project Based Learning 3 (PBL301)
  └── Project: Sistem Informasi Akademik
       └── ClassRoom: TI-3A, TI-3B, TI-3C, TI-3D, TI-3E
            └── Groups: Kelompok 1-5
                 └── Members: 5 mahasiswa/kelompok
                      └── Leader: Member pertama
                           └── Weekly Targets: 8 minggu
                                └── Weekly Progress: Sesuai completion
                                     └── Scores: 4 kriteria/kelompok
```

---

## 📊 Expected Results

Setelah seeder berhasil:
- ✅ 5 Kelas  
- ✅ 25-28 Kelompok (termasuk yang sudah ada)
- ✅ 125 Mahasiswa baru (140+ total)
- ✅ ~200 Weekly Targets
- ✅ ~180 Weekly Progress  
- ✅ ~100 Group Scores

**Database:** SQLite di `database/database.sqlite`  
**Server:** http://localhost:8000

---

## 🚨 Troubleshooting

### Error: "UNIQUE constraint failed"
```bash
# Reset database
php artisan migrate:fresh --seed
php artisan db:seed --class=CompletePBLDataSeeder
```

### Error: "Class not found"
```bash
# Clear cache
composer dump-autoload
php artisan optimize:clear
```

### Data tidak muncul
```bash
# Cek di tinker
php artisan tinker
>>> \App\Models\Group::count();
>>> \App\Models\GroupScore::count();
```

---

**🎉 Data dummy siap digunakan untuk testing sistem pengambilan keputusan PBL!**

Untuk dokumentasi lebih lengkap tentang sistem, lihat file:
- `README.md` - Panduan umum
- `FINAL_IMPLEMENTATION_SUMMARY.md` - Detail implementasi
- `ROLE_MANAGEMENT_SYSTEM.md` - Sistem role & permission
