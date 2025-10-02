# 🔍 CARA VERIFIKASI ROLE KOORDINATOR

## 1. Login sebagai Koordinator
```
Email: koordinator@politala.ac.id
Password: password
```

## 2. Cek Role di Browser
Setelah login, buka Developer Tools (F12) dan jalankan di Console:
```javascript
// Cek apakah user adalah koordinator
fetch('/api/user-info') // atau endpoint yang menampilkan user info
```

## 3. Cek Menu Navigation
Koordinator seharusnya melihat menu:
- ✅ Dashboard
- ✅ Kelas  
- ✅ Kelompok
- ✅ Kriteria ← INI YANG HARUS ADA
- ✅ Nilai & Ranking

## 4. Test Akses Kriteria
Koordinator seharusnya bisa:
- ✅ Buka: http://127.0.0.1:8000/criteria
- ✅ Lihat tombol "Tambah Kriteria"
- ✅ Klik tombol "Tambah Kriteria"
- ✅ Buka form create kriteria
- ✅ Submit form kriteria

## 5. Jika Masih Tidak Bisa
Coba langkah berikut:

### A. Clear All Caches
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### B. Restart Server
```bash
# Stop server (Ctrl+C)
php artisan serve
```

### C. Test dengan User Lain
Coba login sebagai:
- Admin: admin@politala.ac.id
- Dosen: dosen1@politala.ac.id

Jika admin/dosen bisa akses kriteria tapi koordinator tidak, maka ada masalah dengan role koordinator.

## 6. Debug Role
Jika masih tidak bisa, tambahkan debug di navigation:
```php
// Di resources/views/layouts/navigation.blade.php
@if(auth()->user()->isKoordinator())
    <div class="bg-yellow-100 p-2">
        DEBUG: User role = {{ auth()->user()->role }}
    </div>
@endif
```

---

## 🎯 KESIMPULAN

**Koordinator SEHARUSNYA bisa akses kriteria CRUD** karena:
- ✅ Route ada dengan middleware yang benar
- ✅ Controller ada dengan semua method
- ✅ View ada dan tidak ada kondisi khusus
- ✅ Navigation menampilkan menu kriteria

**Jika tidak bisa, kemungkinan masalah:**
1. Browser cache
2. Session issue  
3. Role tidak terdeteksi dengan benar

**Solusi:** Clear cache, logout/login ulang, atau restart server.

