#!/bin/bash

echo "========================================"
echo "Setup Sistem Informasi PBL"
echo "========================================"
echo ""

# Check if .env exists
if [ ! -f .env ]; then
    echo "[1/6] Membuat file .env..."
    cp .env.example .env
    echo "[OK] File .env berhasil dibuat"
else
    echo "[1/6] File .env sudah ada"
fi
echo ""

# Generate app key if needed
echo "[2/6] Generate application key..."
php artisan key:generate
echo ""

# Create MySQL database
echo "[3/6] Membuat database MySQL..."
echo "Pastikan MySQL sudah running!"
echo "Membuat database 'sistem_pbl' jika belum ada..."
mysql -u root -e "CREATE DATABASE IF NOT EXISTS sistem_pbl CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null
if [ $? -eq 0 ]; then
    echo "[OK] Database berhasil dibuat/sudah ada"
else
    echo "[WARNING] Gagal membuat database otomatis"
    echo "Silakan buat database manual dengan:"
    echo "mysql -u root -e 'CREATE DATABASE sistem_pbl;'"
    echo ""
    echo "Atau buat di phpMyAdmin dengan nama: sistem_pbl"
    read -p "Press enter to continue..."
fi
echo ""

# Run migrations
echo "[4/6] Menjalankan migrations..."
php artisan migrate:fresh
echo ""

# Run seeders
echo "[5/6] Mengisi data awal (seeder)..."
php artisan db:seed
echo ""

# Clear cache
echo "[6/6] Membersihkan cache..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ""

echo "========================================"
echo "Setup selesai!"
echo "========================================"
echo ""
echo "Cara menjalankan aplikasi:"
echo "1. Buka terminal baru"
echo "2. Jalankan: php artisan serve"
echo "3. Buka browser: http://localhost:8000"
echo ""
echo "Login SSO Politala (Auto-Register):"
echo "- Email: muhammad.raihan@mhs.politala.ac.id"
echo "- Password: (bebas, password yang Anda inginkan)"
echo ""
echo "Sistem akan otomatis register akun pertama kali!"
echo ""
echo "Atau gunakan akun demo:"
echo "- Email: admin@politala.ac.id"
echo "- Password: password"
echo ""
