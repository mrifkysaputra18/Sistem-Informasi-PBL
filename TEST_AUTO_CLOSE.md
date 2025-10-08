# Testing Auto-Close Target Mingguan

## 🧪 Cara Test Fitur

### Opsi 1: Test Manual Via Command

```bash
# Jalankan command auto-close
php artisan targets:auto-close
```

Command ini akan:
- Mencari semua target yang deadline-nya sudah lewat
- Otomatis menutup target yang belum disubmit
- Menampilkan jumlah target yang ditutup

### Opsi 2: Test Via Browser

#### A. Test Auto-Close (Sebagai Dosen)

1. **Login sebagai Dosen**
2. **Buat Target Baru**:
   - Buka menu "Kelola Target Mingguan"
   - Klik "Buat Target Baru"
   - Isi form dengan deadline **kemarin** atau **hari ini jam yang sudah lewat**
   - Submit

3. **Jalankan Auto-Close**:
   ```bash
   php artisan targets:auto-close
   ```

4. **Cek Hasilnya**:
   - Refresh halaman "Kelola Target Mingguan"
   - Target seharusnya ada badge merah "Tertutup"

#### B. Test Sebagai Mahasiswa (Tidak Bisa Submit)

1. **Login sebagai Mahasiswa**
2. **Lihat Target yang Tertutup**:
   - Buka dashboard mahasiswa
   - Target tertutup akan ada badge merah
   - Tombol "Submit" tidak akan muncul

3. **Coba Akses Form Submit**:
   - Jika coba akses via URL langsung
   - Akan redirect dengan error: "Target ditutup karena melewati deadline"

#### C. Test Reopen oleh Dosen

1. **Login sebagai Dosen**
2. **Buka Target yang Tertutup**:
   - Klik target yang ada badge "Tertutup"
   - Atau buka halaman detail target

3. **Klik Tombol "Buka Kembali Target"** (warna ungu)
4. **Konfirmasi**
5. **Target Terbuka Kembali**:
   - Badge berubah jadi hijau "Target Terbuka"
   - Mahasiswa sekarang bisa submit

#### D. Test Close Manual oleh Dosen

1. **Login sebagai Dosen**
2. **Pilih Target yang Terbuka**
3. **Klik Tombol "Tutup Target"** (warna merah/abu-abu)
4. **Konfirmasi**
5. **Target Tertutup**:
   - Mahasiswa tidak bisa submit

## ✅ Checklist Pengujian

### Setup Awal
- [ ] Migration sudah dijalankan (`php artisan migrate`)
- [ ] Tidak ada error di console

### Test Auto-Close
- [ ] Buat target dengan deadline kemarin
- [ ] Jalankan `php artisan targets:auto-close`
- [ ] Target berhasil tertutup (badge merah muncul)
- [ ] Log tercatat di `storage/logs/laravel.log`

### Test Mahasiswa (Target Tertutup)
- [ ] Login sebagai mahasiswa
- [ ] Lihat target tertutup
- [ ] Tombol submit tidak ada
- [ ] Pesan "Target sudah tertutup" muncul
- [ ] Coba akses URL submit langsung → redirect dengan error

### Test Dosen Reopen
- [ ] Login sebagai dosen
- [ ] Lihat tombol "Buka" (ungu) di target tertutup
- [ ] Klik tombol "Buka"
- [ ] Konfirmasi
- [ ] Badge berubah jadi "Target Terbuka" (hijau)
- [ ] Info "Pernah dibuka kembali oleh [nama dosen]" muncul

### Test Mahasiswa (Setelah Reopen)
- [ ] Login sebagai mahasiswa
- [ ] Target yang dibuka kembali sekarang bisa disubmit
- [ ] Tombol "Submit Target" muncul
- [ ] Bisa submit dengan sukses

### Test Dosen Close Manual
- [ ] Login sebagai dosen
- [ ] Pilih target yang terbuka
- [ ] Klik tombol "Tutup Target" (merah)
- [ ] Konfirmasi
- [ ] Target tertutup
- [ ] Mahasiswa tidak bisa submit lagi

## 🔍 Troubleshooting

### Target tidak tertutup otomatis?

**Solusi 1**: Jalankan manual
```bash
php artisan targets:auto-close
```

**Solusi 2**: Aktifkan scheduler
```bash
# Windows
php artisan schedule:work

# Linux/Mac - tambah ke crontab
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

### Mahasiswa masih bisa submit target tertutup?

**Cek di database**:
```sql
SELECT id, title, deadline, is_open, submission_status 
FROM weekly_targets 
WHERE deadline < NOW() AND is_open = 1;
```

**Clear cache**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Tombol Buka/Tutup tidak muncul?

**Pastikan**:
- User login sebagai dosen/admin/koordinator
- Target belum direview
- Clear view cache: `php artisan view:clear`

## 📊 Expected Results

### Scenario 1: Target Lewat Deadline (Belum Submit)
```
Status: Tertutup
Badge: [🔒 Tertutup] (merah)
Aksi Dosen: [🔓 Buka] (ungu)
Aksi Mahasiswa: Tidak bisa submit
```

### Scenario 2: Target Dibuka Kembali oleh Dosen
```
Status: Terbuka
Badge: [🔓 Target Terbuka] (hijau)
Info: "Pernah dibuka kembali oleh Pak Budi pada 08/10/2025 10:30"
Aksi Dosen: [🔒 Tutup] (abu-abu/merah)
Aksi Mahasiswa: Bisa submit
```

### Scenario 3: Target Lewat Deadline (Sudah Submit)
```
Status: Terbuka (tetap)
Badge: [Sudah Submit] (biru)
Note: Target TIDAK tertutup karena sudah disubmit
Aksi Dosen: Bisa review
```

## 🎯 Quick Test Script

Untuk test cepat, jalankan command berikut:

```bash
# 1. Clear cache
php artisan cache:clear
php artisan config:clear

# 2. Jalankan auto-close
php artisan targets:auto-close

# 3. Cek hasilnya di browser
```

---

**Happy Testing!** 🚀

