# 📘 Panduan Setup untuk Kelompok

Dokumen ini untuk membantu teman-teman kelompok yang baru clone repository ini.

## ⚡ Quick Start (5 Menit)

### 1. Clone Repository
```bash
git clone <URL_REPOSITORY>
cd Sistem-Informasi-PBL
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Database
**Pastikan MySQL sudah running!** (XAMPP, WAMP, Laragon, dll)

**Windows:**
```bash
setup.bat
```

**Linux/Mac:**
```bash
chmod +x setup.sh
./setup.sh
```

### 4. Buka Aplikasi
Server akan otomatis jalan, buka browser:
```
http://localhost:8000
```

## 🔑 Cara Login

### Auto-Register dengan Email Politala

Sistem ini **tidak perlu register manual**! Langsung login saja:

1. Buka halaman login
2. Masukkan email Politala Anda:
   - Mahasiswa: `nama.anda@mhs.politala.ac.id`
   - Dosen: `nama.anda@politala.ac.id`
3. Masukkan **password bebas** yang Anda inginkan
4. Klik Login

✅ **Sistem otomatis membuat akun dan login!**

**Login berikutnya**: Gunakan email dan password yang sama

### Contoh
```
Email: muhammad.raihan@mhs.politala.ac.id
Password: password123

(Password bebas apa saja, akan otomatis disimpan)
```

### Akun Demo (Untuk Testing)
Jika ingin coba fitur admin/dosen:
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
```

## 🐛 Troubleshooting

### Error: "could not find driver"
**Solusi:** Aktifkan extension di `php.ini`:
```ini
extension=pdo_mysql
extension=mysqli
```
Restart Apache/Server.

### Error: "Access denied for user 'root'"
**Solusi:** Ubah password di file `.env`:
```env
DB_PASSWORD=password_mysql_anda
```

### Error: "Database 'sistem_pbl' doesn't exist"
**Solusi:** Buat database manual:

**Via Command:**
```bash
mysql -u root -p
CREATE DATABASE sistem_pbl;
exit
```

**Via phpMyAdmin:**
1. Buka http://localhost/phpmyadmin
2. Klik "New" atau "Databases"
3. Nama: `sistem_pbl`
4. Collation: `utf8mb4_unicode_ci`
5. Create

Lalu jalankan:
```bash
php artisan migrate:fresh --seed
```

### Error: "No application encryption key"
**Solusi:**
```bash
php artisan key:generate
```

### Port 8000 Sudah Dipakai
**Solusi:** Gunakan port lain:
```bash
php artisan serve --port=8001
```
Lalu buka: http://localhost:8001

## 📁 Struktur File Penting

```
Sistem-Informasi-PBL/
├── .env                    # Konfigurasi (JANGAN di-commit!)
├── .env.example           # Template konfigurasi
├── setup.bat              # Script setup Windows
├── setup.sh               # Script setup Linux/Mac
├── app/
│   ├── Http/Controllers/  # Logic controller
│   ├── Models/           # Model database
│   └── Services/         # Business logic
├── database/
│   ├── migrations/       # Schema database
│   └── seeders/         # Data awal
└── resources/
    └── views/           # Tampilan (Blade)
```

## 🔄 Git Workflow

### Pull Update Terbaru
```bash
git pull origin main
composer install
php artisan migrate
php artisan cache:clear
```

### Push Changes
```bash
git add .
git commit -m "Deskripsi perubahan"
git push origin main
```

### Jika Ada Konflik
```bash
git stash              # Simpan perubahan lokal
git pull origin main   # Pull update
git stash pop          # Kembalikan perubahan lokal
# Resolve conflicts
git add .
git commit -m "Resolve conflicts"
git push origin main
```

## ⚙️ Commands Berguna

### Development
```bash
# Jalankan server
php artisan serve

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset database
php artisan migrate:fresh --seed
```

### Database
```bash
# Buat migration baru
php artisan make:migration create_table_name

# Jalankan migration
php artisan migrate

# Rollback migration
php artisan migrate:rollback

# Seed database
php artisan db:seed
```

### Generate Code
```bash
# Model
php artisan make:model NamaModel

# Controller
php artisan make:controller NamaController

# Migration
php artisan make:migration nama_migration

# Seeder
php artisan make:seeder NamaSeeder
```

## 📞 Butuh Bantuan?

1. **Baca error message** dengan teliti
2. **Cek dokumentasi** di README.md
3. **Tanya di grup** kelompok
4. **Buat issue** di GitHub

## 🎯 Tips Development

1. **Selalu pull** sebelum mulai coding
2. **Test** perubahan sebelum push
3. **Commit** secara berkala dengan pesan jelas
4. **Jangan commit** file `.env` atau `database.sqlite`
5. **Backup database** sebelum migrate:fresh

---

**Happy Coding! 🚀**
