# 🔐 Setup Google OAuth SSO untuk Login

Panduan lengkap untuk setup login dengan Google Politala.

## 📋 Prerequisites

- Akun Google (untuk membuat OAuth credentials)
- Akses ke Google Cloud Console
- Domain email Politala (@politala.ac.id atau @mhs.politala.ac.id)

---

## 🚀 Cara Setup (Step-by-Step)

### **Step 1: Buat Project di Google Cloud Console**

1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Login dengan akun Google Anda
3. Klik dropdown project di pojok kiri atas
4. Klik **"New Project"**
5. Beri nama: **"Sistem Informasi PBL"**
6. Klik **"Create"**

### **Step 2: Enable Google+ API**

1. Di sidebar kiri, klik **"APIs & Services"** → **"Library"**
2. Cari **"Google+ API"** atau **"Google People API"**
3. Klik dan enable API tersebut

### **Step 3: Buat OAuth 2.0 Credentials**

1. Di sidebar kiri, klik **"APIs & Services"** → **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"** → **"OAuth client ID"**

3. **Configure Consent Screen** (jika diminta):
   - User Type: **External** (atau Internal jika punya Google Workspace)
   - App name: **Sistem Informasi PBL**
   - User support email: email Anda
   - Developer contact: email Anda
   - Scopes: Tambahkan `.../auth/userinfo.email` dan `.../auth/userinfo.profile`
   - Save and continue

4. **Create OAuth Client ID:**
   - Application type: **Web application**
   - Name: **Sistem PBL Web App**
   
   - **Authorized JavaScript origins:**
     ```
     http://localhost:8000
     http://127.0.0.1:8000
     ```
     (Tambahkan production URL jika deploy)
   
   - **Authorized redirect URIs:**
     ```
     http://localhost:8000/auth/google/callback
     http://127.0.0.1:8000/auth/google/callback
     ```
     (Tambahkan production URL jika deploy)
   
   - Klik **"Create"**

5. **Copy Credentials:**
   - **Client ID**: `123456789-abcdefg.apps.googleusercontent.com`
   - **Client Secret**: `GOCSPX-abc123def456`
   - **SIMPAN** credentials ini!

### **Step 4: Update File .env**

Buka file `.env` dan update:

```env
GOOGLE_CLIENT_ID=123456789-abcdefg.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abc123def456
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

Ganti dengan Client ID dan Secret yang Anda dapat dari Step 3!

### **Step 5: Clear Cache & Restart Server**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Restart server
php artisan serve
```

---

## ✅ **Cara Menggunakan:**

### **1. Buka Halaman Login**
```
http://localhost:8000/login
```

### **2. Klik "Login dengan Google Politala"**
- Button dengan logo Google di bagian atas
- Anda akan diarahkan ke halaman login Google

### **3. Login dengan Akun Google Politala**
- Pilih atau login dengan akun Google kampus Anda
- Email: `nama.anda@politala.ac.id` atau `@mhs.politala.ac.id`
- **Gunakan password email Google kampus Anda**

### **4. Allow Access**
- Google akan minta izin akses email dan profile
- Klik **"Allow"** atau **"Izinkan"**

### **5. Otomatis Login & Redirect**
- Sistem otomatis buat akun (jika baru)
- Otomatis login
- Redirect ke Dashboard

---

## 🔒 **Keamanan & Validasi:**

### **Yang Sudah Diimplementasi:**

✅ **Domain Whitelist**
- Hanya email `@politala.ac.id` dan `@mhs.politala.ac.id` yang bisa login
- Email lain akan ditolak

✅ **Auto Role Assignment**
- Email `@mhs.politala.ac.id` → Role: **Mahasiswa**
- Email `@politala.ac.id` → Role: **Dosen**

✅ **Auto-Register**
- User baru otomatis didaftarkan
- Data diambil dari Google profile:
  - Nama lengkap
  - Email
  - Email verified (otomatis)

✅ **Email Verification**
- Email otomatis terverifikasi (karena sudah verified by Google)

---

## 🐛 **Troubleshooting:**

### **Error: "Hanya email Politala yang diperbolehkan"**
**Solusi:** Pastikan Anda login dengan email yang berakhiran:
- `@politala.ac.id` atau
- `@mhs.politala.ac.id`

### **Error: "redirect_uri_mismatch"**
**Solusi:**
1. Cek URL di browser (http://localhost:8000 atau http://127.0.0.1:8000)
2. Pastikan Redirect URI di Google Console sama persis:
   ```
   http://localhost:8000/auth/google/callback
   ```
3. Tidak boleh ada trailing slash `/` di akhir
4. Harus sama persis (case-sensitive)

### **Error: "Client ID not found"**
**Solusi:**
1. Pastikan `.env` sudah diisi dengan benar
2. Jalankan: `php artisan config:clear`
3. Restart server: `php artisan serve`

### **Error: "Access blocked: This app's request is invalid"**
**Solusi:**
1. Pastikan OAuth Consent Screen sudah dikonfigurasi
2. Tambahkan email Anda sebagai Test User (di Google Console)
3. Status app: **Testing** atau **Published**

---

## 🌐 **Untuk Production/Deployment:**

### **Update Redirect URI di Google Console:**
Tambahkan production URL Anda:
```
https://sisteminfopbl.politala.ac.id/auth/google/callback
```

### **Update .env Production:**
```env
APP_URL=https://sisteminfopbl.politala.ac.id
GOOGLE_REDIRECT_URI=https://sisteminfopbl.politala.ac.id/auth/google/callback
```

### **HTTPS Required:**
Google OAuth membutuhkan HTTPS untuk production!

---

## 📱 **Login Flow:**

```
User → Klik "Login dengan Google Politala"
     ↓
Redirect ke Google Login
     ↓
User login dengan email kampus (@politala.ac.id)
     ↓
Google minta izin akses (Allow)
     ↓
Redirect kembali ke sistem (/auth/google/callback)
     ↓
Sistem validasi email domain
     ↓
Cari user di database
     ↓
Jika tidak ada → Auto register
     ↓
Login user ke sistem
     ↓
Redirect ke Dashboard ✅
```

---

## 🎯 **Keuntungan Google OAuth:**

✅ **Lebih Aman**
- Tidak perlu manage password
- Password disimpan di Google (enkripsi tingkat enterprise)

✅ **User Friendly**
- One-click login
- Tidak perlu remember password

✅ **Auto Sync**
- Nama & email selalu update dari Google

✅ **Verified Email**
- Email otomatis terverifikasi

✅ **Single Sign-On**
- Sekali login Google, bisa akses semua app

---

## 📞 **Butuh Bantuan?**

Jika ada masalah saat setup:
1. Screenshot error message
2. Cek console browser (F12)
3. Cek log Laravel: `storage/logs/laravel.log`
4. Hubungi tim IT kampus untuk bantuan Google Workspace

---

**Selamat menggunakan Google OAuth SSO! 🎉**

