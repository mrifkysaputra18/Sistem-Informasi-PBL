# Fitur Auto-Close & Reopen Target Mingguan

## 📋 Deskripsi Fitur

Fitur ini memungkinkan sistem untuk **secara otomatis menutup target mingguan** yang sudah melewati deadline dan belum disubmit oleh mahasiswa. Dosen juga dapat **membuka kembali target** yang sudah tertutup jika diperlukan.

## 🎯 Tujuan

1. **Mencegah submission terlambat**: Mahasiswa tidak dapat mensubmit target yang sudah melewati deadline
2. **Fleksibilitas untuk dosen**: Dosen dapat membuka kembali target jika mahasiswa memiliki alasan yang valid
3. **Otomatis**: Sistem akan menutup target secara otomatis setiap hari

## 🔧 Fitur yang Ditambahkan

### 1. Database Changes

**Migration**: `2025_10_08_115257_add_is_open_to_weekly_targets_table.php`

Menambahkan field baru ke tabel `weekly_targets`:
- `is_open` (boolean): Apakah target masih bisa disubmit
- `reopened_by` (foreign key): ID dosen yang membuka kembali target
- `reopened_at` (timestamp): Waktu target dibuka kembali

### 2. Model Updates

**File**: `app/Models/WeeklyTarget.php`

Method baru yang ditambahkan:
- `canAcceptSubmission()`: Cek apakah target masih bisa menerima submission
- `isClosed()`: Cek apakah target sudah tertutup
- `shouldBeClosed()`: Cek apakah target seharusnya ditutup
- `closeTarget()`: Menutup target
- `reopenTarget($dosenId)`: Membuka kembali target
- `getClosureReason()`: Mendapatkan alasan kenapa target ditutup

### 3. Controller Updates

**File**: `app/Http/Controllers/WeeklyTargetController.php`

Method baru untuk dosen:
- `reopen(WeeklyTarget $target)`: Membuka kembali target yang tertutup
- `close(WeeklyTarget $target)`: Menutup target secara manual
- `autoCloseOverdueTargets()`: Trigger manual untuk menutup target yang lewat deadline

**File**: `app/Http/Controllers/WeeklyTargetSubmissionController.php`

Semua method submission sekarang validasi `canAcceptSubmission()` sebelum mengizinkan mahasiswa submit/edit.

### 4. Routes

**File**: `routes/web.php`

Route baru untuk dosen:
```php
// Reopen/Close targets
Route::post('targets/{target}/reopen', [WeeklyTargetController::class, 'reopen'])
    ->name('targets.reopen');
Route::post('targets/{target}/close', [WeeklyTargetController::class, 'close'])
    ->name('targets.close');

// Auto-close overdue targets (manual trigger)
Route::post('targets/auto-close-overdue', [WeeklyTargetController::class, 'autoCloseOverdueTargets'])
    ->name('targets.auto-close-overdue');
```

### 5. Views

**Updated Files**:
- `resources/views/targets/index.blade.php`: Menampilkan tombol Buka/Tutup target
- `resources/views/targets/show.blade.php`: Menampilkan status target (Terbuka/Tertutup)
- `resources/views/targets/submissions/show.blade.php`: Menampilkan pesan jika target tertutup

### 6. Artisan Command

**File**: `app/Console/Commands/AutoCloseOverdueTargets.php`

Command untuk menutup target yang sudah lewat deadline:
```bash
php artisan targets:auto-close
```

### 7. Scheduler

**File**: `routes/console.php`

Command di-schedule untuk berjalan otomatis setiap hari jam 00:01:
```php
Schedule::command('targets:auto-close')->dailyAt('00:01');
```

## 📖 Cara Penggunaan

### Untuk Mahasiswa

1. **Melihat Status Target**
   - Buka halaman "Target Mingguan dari Dosen"
   - Target yang tertutup akan ditandai dengan badge merah "Tertutup"
   - Mahasiswa tidak dapat mensubmit target yang tertutup

2. **Ketika Target Tertutup**
   - Tombol "Submit Target" akan hilang
   - Muncul pesan: "Target sudah tertutup. Tidak dapat mensubmit lagi."
   - Hubungi dosen jika memerlukan target dibuka kembali

### Untuk Dosen

1. **Melihat Status Target**
   - Buka halaman "Kelola Target Mingguan" (`/targets`)
   - Target yang tertutup akan ditandai dengan badge "Tertutup"
   - Target yang terbuka ditandai dengan badge "Target Terbuka"

2. **Membuka Kembali Target**
   - Klik tombol **"Buka"** (warna ungu) di halaman daftar target
   - Atau klik **"Buka Kembali Target"** di halaman detail target
   - Konfirmasi aksi
   - Mahasiswa sekarang dapat mensubmit target tersebut

3. **Menutup Target Secara Manual**
   - Klik tombol **"Tutup"** (warna abu-abu) di halaman daftar target
   - Atau klik **"Tutup Target"** (warna merah) di halaman detail target
   - Konfirmasi aksi
   - Target akan tertutup dan mahasiswa tidak dapat submit

4. **Auto-Close Manual Trigger**
   - Jalankan command:
     ```bash
     php artisan targets:auto-close
     ```
   - Semua target yang melewati deadline akan otomatis ditutup

### Untuk Admin/Koordinator

Admin dan Koordinator memiliki akses yang sama dengan Dosen untuk membuka/menutup target.

## 🔄 Proses Auto-Close

### Kapan Target Ditutup Otomatis?

Target akan ditutup otomatis jika:
1. ✅ Target masih terbuka (`is_open = true`)
2. ✅ Target belum direview oleh dosen
3. ✅ Memiliki deadline
4. ✅ Deadline sudah terlewati
5. ✅ **Berlaku untuk SEMUA status** (baik yang sudah submit maupun belum)

### Target TIDAK akan ditutup jika:
- ❌ Sudah direview dosen (sudah final)
- ❌ Tidak memiliki deadline

### Perubahan Penting:
⚠️ **Target yang sudah disubmit mahasiswa juga akan tertutup** setelah deadline lewat. Ini berarti:
- Mahasiswa tidak bisa mengedit submission mereka setelah deadline
- Mencegah mahasiswa mengubah jawaban setelah deadline terlewati
- Dosen tetap bisa membuka kembali target jika diperlukan

### Jadwal Auto-Close

Command `targets:auto-close` dijadwalkan berjalan:
- **Setiap hari jam 00:01 WIB**
- Otomatis menutup semua target yang memenuhi kriteria

## 🔐 Permission & Authorization

### Siapa yang Bisa Membuka/Menutup Target?

- ✅ **Dosen**: Dapat membuka/menutup semua target
- ✅ **Admin**: Dapat membuka/menutup semua target
- ✅ **Koordinator**: Dapat membuka/menutup semua target
- ❌ **Mahasiswa**: Tidak dapat membuka/menutup target

### Batasan

- Target yang sudah **direview** tidak dapat dibuka kembali
- Target yang sudah **direview** tidak dapat ditutup manual

## 📊 Status & Badge

### Badge Status di Interface

| Status | Warna | Icon | Keterangan |
|--------|-------|------|------------|
| Target Terbuka | Hijau | 🔓 | Mahasiswa dapat submit |
| Target Tertutup | Merah | 🔒 | Mahasiswa tidak dapat submit |
| Lewat Deadline | Orange | ⚠️ | Deadline terlewati tapi belum ditutup |

## 🚀 Setup & Deployment

### 1. Jalankan Migration

```bash
php artisan migrate
```

### 2. Update Data Existing (Optional)

Jika ada target lama yang perlu ditutup:

```bash
php artisan targets:auto-close
```

### 3. Aktifkan Scheduler

Pastikan Laravel Scheduler berjalan di server:

**Linux/Mac** - Tambahkan ke crontab:
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Windows** - Gunakan Task Scheduler atau jalankan manual:
```bash
php artisan schedule:work
```

## 📝 Logging

Semua aksi auto-close akan dicatat di log Laravel:

```php
\Log::info('Auto-closed overdue targets', [
    'count' => $closedCount,
    'skipped' => $skippedCount,
]);
```

Aksi manual reopen/close juga dicatat:

```php
\Log::info('Target Reopened', [
    'target_id' => $target->id,
    'reopened_by' => auth()->id(),
    'group_id' => $target->group_id,
]);
```

## 🧪 Testing

### Manual Testing

1. **Test Auto-Close**:
   ```bash
   # Buat target dengan deadline kemarin
   # Jalankan command
   php artisan targets:auto-close
   # Verifikasi target tertutup
   ```

2. **Test Reopen**:
   - Buka halaman detail target yang tertutup sebagai dosen
   - Klik "Buka Kembali Target"
   - Verifikasi mahasiswa dapat submit

3. **Test Validation**:
   - Login sebagai mahasiswa
   - Coba akses form submit untuk target tertutup
   - Seharusnya dapat error/redirect

## 🎨 UI/UX Features

### Untuk Mahasiswa
- Badge merah "Target Tertutup" di kolom deadline
- Pesan error yang jelas jika target tertutup
- Informasi alasan penutupan target

### Untuk Dosen
- Tombol "Buka" dan "Tutup" yang jelas dengan warna berbeda
- Konfirmasi sebelum aksi
- History: menampilkan siapa dan kapan target dibuka kembali
- Badge status yang mudah dibedakan

## 📱 Responsive Design

Semua fitur berfungsi dengan baik di:
- ✅ Desktop
- ✅ Tablet
- ✅ Mobile

## 🔍 Troubleshooting

### Target tidak tertutup otomatis?

1. Cek apakah scheduler berjalan:
   ```bash
   php artisan schedule:list
   ```

2. Jalankan manual:
   ```bash
   php artisan targets:auto-close
   ```

3. Periksa log untuk error

### Mahasiswa masih bisa submit target tertutup?

1. Periksa nilai `is_open` di database
2. Pastikan validation `canAcceptSubmission()` terpanggil
3. Clear cache:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

### Tombol Buka/Tutup tidak muncul?

1. Pastikan user login sebagai dosen/admin/koordinator
2. Periksa role di database
3. Clear view cache:
   ```bash
   php artisan view:clear
   ```

## 📚 References

- Migration: `database/migrations/2025_10_08_115257_add_is_open_to_weekly_targets_table.php`
- Model: `app/Models/WeeklyTarget.php`
- Controller: `app/Http/Controllers/WeeklyTargetController.php`
- Command: `app/Console/Commands/AutoCloseOverdueTargets.php`
- Views: `resources/views/targets/`

## ✅ Checklist Implementasi

- [x] Database migration
- [x] Model methods
- [x] Controller methods
- [x] Routes
- [x] Views (dosen & mahasiswa)
- [x] Artisan command
- [x] Scheduler setup
- [x] Validation & authorization
- [x] UI/UX improvements
- [x] Dokumentasi

## 🎉 Hasil Akhir

Fitur auto-close target mingguan telah berhasil diimplementasikan dengan lengkap:

1. ✅ Target otomatis tertutup setelah melewati deadline
2. ✅ Mahasiswa tidak dapat submit target yang tertutup
3. ✅ Dosen dapat membuka kembali target jika diperlukan
4. ✅ Dosen dapat menutup target secara manual
5. ✅ UI yang informatif dan user-friendly
6. ✅ Logging untuk tracking
7. ✅ Command untuk maintenance

---

**Dibuat**: 8 Oktober 2025
**Update Terakhir**: 8 Oktober 2025

