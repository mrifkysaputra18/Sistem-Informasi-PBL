# Sistem Informasi PBL (Project-Based Learning)

Sistem informasi untuk mengelola proyek PBL di Politeknik Negeri Tanah Laut dengan fitur tracking progress, penilaian, dan ranking kelompok.

## 🚀 Cara Install (Untuk Kelompok)

### Prasyarat
- PHP 8.2 atau lebih baru
- Composer
- Node.js & NPM (untuk frontend assets)

### Setup Otomatis (Rekomendasi)

**Windows:**
```bash
setup.bat
```

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

### Setup Manual

Jika script otomatis tidak bekerja, ikuti langkah berikut:

```bash
# 1. Copy file .env
copy .env.example .env    # Windows
cp .env.example .env      # Linux/Mac

# 2. Generate application key
php artisan key:generate

# 3. Buat database SQLite
type nul > database\database.sqlite    # Windows
touch database/database.sqlite         # Linux/Mac

# 4. Jalankan migration
php artisan migrate:fresh

# 5. Isi data awal
php artisan db:seed

# 6. Clear cache
php artisan config:clear
php artisan cache:clear
```

### Cara Menjalankan Project

Ikuti langkah-langkah berikut untuk melakukan setup project setelah clone repository:

```bash
1. Clone Repository
git clone https://github.com/mrifkysaputra18/Sistem-Informasi-PBL.git
cd Sistem-Informasi-PBL

2. Setup Environment
git branch --show-current
copy .env.example .env


Jangan lupa sesuaikan konfigurasi database, mail, dan lainnya di file .env.

3. Install Dependencies
composer install
npm install

4. Konfigurasi Permission (Windows)
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
Get-ExecutionPolicy -List
mkdir storage\framework\views
icacls storage /grant Everyone:F /T

5. Clear Cache & Generate Key
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan key:generate

6. Migrasi & Seeder Database
php artisan migrate
php artisan db:seed

7. Jalankan Aplikasi
php artisan serve


Aplikasi sekarang dapat diakses di:
👉 http://localhost:8000
```

## 🔐 Cara Login

### Metode 1: Login dengan Google Politala (Recommended) 🔥

Sistem ini mendukung **Google OAuth SSO**. Login hanya dengan 1 klik!

**Cara Login:**
1. Buka halaman login
2. Klik button **"Login dengan Google Politala"** (button dengan logo Google)
3. Pilih akun Google kampus Anda (@politala.ac.id atau @mhs.politala.ac.id)
4. Klik **"Allow"** untuk memberi izin
5. ✅ **Otomatis login!** (auto-register jika user baru)

**Setup Google OAuth:**
- Lihat panduan lengkap: [`SETUP_GOOGLE_SSO.md`](SETUP_GOOGLE_SSO.md)
- Quick start: [`QUICK_START_GOOGLE_SSO.md`](QUICK_START_GOOGLE_SSO.md)

### Metode 2: Login Manual (Alternative)

Jika belum setup Google OAuth, bisa login manual:

**Login pertama kali:**
1. Masukkan email Politala Anda:
   - Mahasiswa: `nama.anda@mhs.politala.ac.id`
   - Dosen: `nama.anda@politala.ac.id`
2. Masukkan password (bebas, password yang Anda inginkan)
3. Sistem akan otomatis membuat akun Anda dan login

**Login berikutnya:**
- Gunakan email dan password yang sama

### Akun Demo (Sudah Ada di Database)

Jika ingin mencoba dengan akun yang sudah ada:

```
Admin:
Email: admin@politala.ac.id
Password: password

Koordinator:
Email: koordinator@politala.ac.id
Password: password

Dosen:
Email: dosen1@politala.ac.id
Password: password

Mahasiswa:
Email: mahasiswa1@politala.ac.id
Password: password
```

## 🏃 Menjalankan Aplikasi

```bash
# Jalankan server development
php artisan serve

# Buka browser
http://localhost:8000
```

## 📦 Database

Sistem ini menggunakan **MySQL** secara default.

### Konfigurasi Database

File `.env` sudah dikonfigurasi dengan:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_pbl
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:**
- Database `sistem_pbl` akan dibuat otomatis oleh script setup
- Jika menggunakan password MySQL, ubah `DB_PASSWORD` di file `.env`
- Jika menggunakan XAMPP, pastikan Apache & MySQL sudah running

### Membuat Database Manual (Jika Script Gagal)

**Via Command Line:**
```bash
mysql -u root -e "CREATE DATABASE sistem_pbl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

**Via phpMyAdmin:**
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Klik tab "Databases"
3. Buat database baru dengan nama: `sistem_pbl`
4. Charset: `utf8mb4_unicode_ci`

### Mengubah ke SQLite (Opsional)

Jika ingin menggunakan SQLite, edit file `.env`:
```env
DB_CONNECTION=sqlite
```

Lalu buat file database:
```bash
touch database/database.sqlite   # Linux/Mac
type nul > database\database.sqlite   # Windows
```

Dan jalankan migration:
```bash
php artisan migrate:fresh --seed
```

## 🌟 Fitur Utama

### Untuk Mahasiswa
- Dashboard progress kelompok
- Submit weekly progress
- Upload dokumen ke Google Drive
- Lihat feedback dari dosen
- Tracking ranking kelompok

### Untuk Dosen
- Review progress kelompok
- Beri penilaian dan feedback
- Kelola attendance
- Export ranking kelompok
- Monitor semua kelompok

### Untuk Admin/Koordinator
- Kelola users (mahasiswa, dosen)
- Kelola projects dan groups
- Atur kriteria penilaian
- Kelola academic terms
- Export data dan laporan

## 📱 Tech Stack

- **Backend**: Laravel 11
- **Frontend**: Blade Templates + Tailwind CSS
- **Database**: SQLite (default) / MySQL
- **Cloud Storage**: Google Drive Integration
- **Queue**: Database Queue
- **Mail**: SMTP / Log

## 🛠️ Troubleshooting

### Masalah: "Could not find driver"
Pastikan extension SQLite diaktifkan di `php.ini`:
```ini
extension=pdo_sqlite
extension=sqlite3
```

### Masalah: "No application encryption key has been specified"
Jalankan:
```bash
php artisan key:generate
```

### Masalah: "Database file not found"
Buat file database:
```bash
type nul > database\database.sqlite    # Windows
touch database/database.sqlite         # Linux/Mac
```

### Masalah: "Permission denied" di Linux/Mac
Berikan permission:
```bash
chmod 755 database/
chmod 664 database/database.sqlite
```

## 🤝 Kontribusi

Untuk kelompok yang ingin kontribusi:

1. Clone repository
2. Buat branch baru: `git checkout -b feature/nama-fitur`
3. Commit changes: `git commit -m 'Menambah fitur X'`
4. Push ke branch: `git push origin feature/nama-fitur`
5. Buat Pull Request

## 📝 Catatan Penting

- **Jangan commit file `.env`** - File ini berisi konfigurasi lokal dan rahasia
- **File `database.sqlite` jangan di-commit** - Sudah ada di `.gitignore`
- **Backup database secara berkala** jika berisi data penting
- **Gunakan email Politala** untuk login otomatis

## 📧 Kontak

Jika ada masalah atau pertanyaan:
- Buka issue di GitHub
- Hubungi koordinator kelompok

---

**Politeknik Negeri Tanah Laut**  
Project-Based Learning Management System