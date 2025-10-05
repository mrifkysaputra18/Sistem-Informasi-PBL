# 🚀 Setup Google SSO - Panduan Super Mudah

**Sistem sudah 90% siap!** Tinggal konfigurasi Google OAuth saja. ⚡

---

## ✅ Yang Sudah Tersedia:

1. ✅ **Controller Google OAuth** → `GoogleAuthController.php` (lengkap!)
2. ✅ **Routes** → `/auth/google` dan `/auth/google/callback`
3. ✅ **Button Login** → "Login dengan Google Politala" di halaman login
4. ✅ **Package Laravel Socialite** → sudah terinstall
5. ✅ **Auto-register** → mahasiswa/dosen otomatis terdaftar
6. ✅ **Domain whitelist** → hanya `@politala.ac.id` dan `@mhs.politala.ac.id`

**Tinggal setup credentials Google saja!** 🎯

---

## 🔧 Setup Cepat (4 Langkah)

### **LANGKAH 1: Buat File `.env`**

Jika belum ada file `.env` di root project:

```bash
copy .env.example .env
```

Lalu generate app key:

```bash
php artisan key:generate
```

---

### **LANGKAH 2: Buat Google OAuth Credentials**

#### **A. Buka Google Cloud Console**
👉 https://console.cloud.google.com/

#### **B. Buat Project Baru**
1. Klik **"Select a project"** (pojok kiri atas)
2. Klik **"NEW PROJECT"**
3. Project name: `Sistem PBL Politala`
4. Klik **"CREATE"**

#### **C. Enable Google+ API**
1. Sidebar → **"APIs & Services"** → **"Library"**
2. Cari: **"Google+ API"**
3. Klik dan **"ENABLE"**

#### **D. Create OAuth Credentials**
1. Sidebar → **"APIs & Services"** → **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"** → **"OAuth client ID"**

3. **Jika diminta Configure Consent Screen:**
   - User Type: **External** → Next
   - App name: `Sistem PBL Politala`
   - User support email: (email Anda)
   - Developer contact email: (email Anda)
   - Klik **"Save and Continue"** sampai selesai

4. **Kembali ke Create OAuth Client ID:**
   - Application type: **Web application**
   - Name: `PBL Web Client`
   
   - **Authorized JavaScript origins:**
     ```
     http://localhost:8000
     ```
   
   - **Authorized redirect URIs:** (PENTING!)
     ```
     http://localhost:8000/auth/google/callback
     ```
   
   - Klik **"CREATE"**

5. **Copy Credentials:**
   ```
   Client ID: 123456789-abcdefghijk.apps.googleusercontent.com
   Client Secret: GOCSPX-xyz123abc456
   ```
   
   **SIMPAN INI!** ⚠️

---

### **LANGKAH 3: Update File `.env`**

Buka file `.env` dan cari bagian Google OAuth, lalu ganti:

```env
GOOGLE_CLIENT_ID=paste-client-id-anda-disini
GOOGLE_CLIENT_SECRET=paste-client-secret-anda-disini
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Contoh:**

```env
GOOGLE_CLIENT_ID=123456789-abcdefghijk.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xyz123abc456def
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**⚠️ PENTING:**
- Tidak boleh ada spasi sebelum atau sesudah `=`
- Copy-paste dengan teliti, jangan sampai ada karakter tambahan
- Redirect URI harus **sama persis** (huruf besar/kecil, tidak ada `/` di akhir)

---

### **LANGKAH 4: Clear Cache & Test**

Jalankan perintah ini:

```bash
php artisan config:clear
php artisan cache:clear
php artisan serve
```

---

## ✅ **Test Login!**

1. Buka browser: http://localhost:8000/login

2. Anda akan melihat:
   ```
   ┌─────────────────────────────────────────┐
   │  [🔵 Login dengan Google Politala]     │  ← Klik ini!
   │  Login menggunakan akun Google kampus   │
   │                                         │
   │  ─────────── atau ───────────           │
   │                                         │
   │  Email    [__________________]          │
   │  Password [__________________]          │
   └─────────────────────────────────────────┘
   ```

3. **Klik button "Login dengan Google Politala"**

4. **Pilih akun Google Politala Anda**
   - Email: `nama.anda@politala.ac.id` atau `@mhs.politala.ac.id`
   - Masukkan password Google kampus

5. **Klik "Allow" / "Izinkan"**
   - Google minta izin akses email & profile
   - Klik "Allow"

6. ✅ **Otomatis login dan masuk Dashboard!**

---

## 🎉 **Fitur yang Tersedia:**

### **1. Auto-Register**
- User baru **otomatis terdaftar** saat pertama kali login
- Data diambil dari Google (nama, email)
- Email otomatis terverifikasi

### **2. Role Assignment Otomatis**
- Email `@mhs.politala.ac.id` → Role: **Mahasiswa**
- Email `@politala.ac.id` → Role: **Dosen**
- Admin bisa ubah role manual di "Kelola User"

### **3. Domain Whitelist**
- Hanya email Politala yang bisa login
- Email lain akan ditolak dengan pesan error

### **4. Security**
- Password user SSO disimpan random (tidak digunakan)
- OAuth token di-handle oleh Google (lebih aman)
- Session management tetap menggunakan Laravel

---

## 🐛 **Troubleshooting Umum**

### **Error: "redirect_uri_mismatch"**

**Penyebab:** Redirect URI di Google Console tidak sama dengan di `.env`

**Solusi:**
1. Pastikan di Google Console, Redirect URI adalah:
   ```
   http://localhost:8000/auth/google/callback
   ```
2. Pastikan di `.env`:
   ```env
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```
3. **Harus sama persis!** (tidak boleh ada `/` di akhir)
4. Jalankan: `php artisan config:clear`

---

### **Error: "invalid_client"**

**Penyebab:** Client ID atau Secret salah

**Solusi:**
1. Cek kembali Client ID & Secret di Google Console
2. Copy-paste ulang ke `.env` (pastikan tidak ada spasi)
3. Jalankan: `php artisan config:clear`
4. Restart server: `php artisan serve`

---

### **Error: "Hanya email Politala yang diperbolehkan"**

**Penyebab:** Login dengan email non-Politala (Gmail pribadi, dll)

**Solusi:**
1. Gunakan email kampus: `@politala.ac.id` atau `@mhs.politala.ac.id`
2. Pastikan akun Google kampus sudah aktif

---

### **Error: "Access blocked: This app's request is invalid"**

**Penyebab:** OAuth Consent Screen belum dikonfigurasi

**Solusi:**
1. Di Google Console → **OAuth consent screen**
2. Lengkapi semua field yang required
3. Tambahkan email Anda sebagai **Test User**
4. Status: **Testing** (untuk development)

---

### **Button Google tidak muncul di halaman login**

**Penyebab:** Browser cache

**Solusi:**
1. Hard refresh: `Ctrl + Shift + R`
2. Clear cache browser
3. Atau gunakan Incognito mode

---

## 📱 **Alur Login Google OAuth**

```
User klik "Login dengan Google Politala"
          ↓
Redirect ke halaman login Google
          ↓
User login dengan email kampus (@politala.ac.id)
          ↓
Google minta izin akses (Allow)
          ↓
Redirect kembali ke sistem (/auth/google/callback)
          ↓
GoogleAuthController::handleGoogleCallback()
          ↓
Validasi email domain (harus Politala)
          ↓
Cek user di database
          ↓
Jika tidak ada → Auto register dengan role sesuai domain
          ↓
Login user ke sistem (Auth::login)
          ↓
Redirect ke Dashboard dengan pesan sukses ✅
```

---

## 🔐 **Keamanan**

### **Validasi yang Sudah Diimplementasi:**

✅ **Domain Whitelist**
```php
protected function isPolitalaEmail(string $email): bool
{
    return Str::endsWith($email, ['@politala.ac.id', '@mhs.politala.ac.id']);
}
```

✅ **Role Based on Domain**
```php
protected function determineRole(string $email): string
{
    if (Str::endsWith($email, '@mhs.politala.ac.id')) {
        return 'mahasiswa';
    }
    return 'dosen'; // Default untuk @politala.ac.id
}
```

✅ **Random Password for SSO Users**
```php
'password' => Hash::make(Str::random(32)),
```

✅ **Email Verified**
```php
'email_verified_at' => now(), // Email sudah verified by Google
```

---

## 🌐 **Untuk Production/Deployment**

Jika akan deploy ke server production:

### **1. Update Redirect URI di Google Console**
Tambahkan production URL:
```
https://sisteminfopbl.politala.ac.id/auth/google/callback
```

### **2. Update `.env` Production**
```env
APP_URL=https://sisteminfopbl.politala.ac.id
GOOGLE_REDIRECT_URI=https://sisteminfopbl.politala.ac.id/auth/google/callback
```

### **3. HTTPS Required**
⚠️ Google OAuth membutuhkan **HTTPS** untuk production!

---

## 📚 **File-File Terkait**

| File | Deskripsi |
|------|-----------|
| `app/Http/Controllers/Auth/GoogleAuthController.php` | Controller untuk handle OAuth |
| `routes/web.php` | Route `/auth/google` dan callback |
| `resources/views/auth/login.blade.php` | Button "Login dengan Google" |
| `config/services.php` | Konfigurasi Google OAuth |
| `.env` | Credentials (Client ID, Secret) |

---

## 💡 **Tips**

1. **Gunakan Incognito** untuk testing agar tidak terkena cache
2. **Test dengan akun berbeda** untuk validasi role assignment
3. **Aktifkan error reporting** di `.env`: `APP_DEBUG=true`
4. **Cek log** jika ada error: `storage/logs/laravel.log`

---

## 🎯 **Keuntungan Google SSO**

✅ **Lebih Aman**
- Password di-manage oleh Google (enkripsi enterprise-level)
- Tidak perlu khawatir password leak

✅ **User Friendly**
- One-click login
- Tidak perlu remember password

✅ **Auto-Sync**
- Nama & email selalu update dari Google account

✅ **Faster Onboarding**
- Mahasiswa/dosen baru tinggal login, langsung terdaftar

✅ **Compliance**
- Mengikuti standar OAuth 2.0
- GDPR compliant

---

## 📞 **Butuh Bantuan?**

Jika ada masalah:

1. **Cek log Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Cek browser console:** (F12 → Console)

3. **Screenshot error** dan kirim ke tim support

---

**Selamat! Sistem Anda sekarang support Google SSO! 🎉**

Login jadi lebih mudah dan aman! 🔐✨

