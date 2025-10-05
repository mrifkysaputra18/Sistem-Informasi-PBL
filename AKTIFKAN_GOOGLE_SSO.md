# ⚡ Cara Aktifkan Google SSO (3 Langkah Cepat)

**Status: Sistem sudah 90% siap! Tinggal konfigurasi!** 🎯

---

## 📊 **Status Implementasi:**

| Komponen | Status | Keterangan |
|----------|--------|------------|
| Controller Google OAuth | ✅ READY | `GoogleAuthController.php` |
| Routes OAuth | ✅ READY | `/auth/google` & `/callback` |
| Button Login Google | ✅ READY | Di halaman login |
| Package Socialite | ✅ READY | Sudah terinstall |
| Auto-register | ✅ READY | Mahasiswa/Dosen otomatis |
| Domain Whitelist | ✅ READY | Hanya `@politala.ac.id` |
| **Google Credentials** | ⚠️ **PERLU SETUP** | Perlu credentials dari Google |

---

## 🚀 **SETUP (3 LANGKAH):**

### **LANGKAH 1: Buat Google OAuth Credentials**

#### **1.1. Buka Google Cloud Console**
```
https://console.cloud.google.com/
```

#### **1.2. Buat Project Baru**
- Klik **"New Project"**
- Nama: `Sistem PBL Politala`
- Klik **"Create"**

#### **1.3. Create OAuth Client ID**
1. Sidebar → **"APIs & Services"** → **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"** → **"OAuth client ID"**

3. **Jika diminta Configure Consent Screen:**
   - User Type: **External**
   - App name: `Sistem PBL Politala`
   - Email: (email Anda)
   - Save and Continue

4. **Create OAuth Client ID:**
   - Type: **Web application**
   - Name: `PBL Web Client`
   
   **Authorized redirect URIs:** (COPY INI!)
   ```
   http://localhost:8000/auth/google/callback
   ```
   
   - Klik **"CREATE"**

5. **COPY CREDENTIALS INI:**
   ```
   Client ID: 123456789-abcdefg.apps.googleusercontent.com
   Client Secret: GOCSPX-abc123xyz
   ```

---

### **LANGKAH 2: Update File `.env`**

#### **2.1. Copy File `.env.example`**

Jika belum ada file `.env`, copy dari example:

```bash
copy .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

#### **2.2. Tambahkan Google Credentials**

Buka file `.env` dan **tambahkan di bagian paling bawah:**

```env
# Google OAuth SSO
GOOGLE_CLIENT_ID=paste-client-id-anda-disini
GOOGLE_CLIENT_SECRET=paste-client-secret-anda-disini
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Ganti dengan credentials Anda:**

```env
# Google OAuth SSO
GOOGLE_CLIENT_ID=123456789-abcdefg.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-abc123xyz
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**⚠️ PERHATIKAN:**
- Tidak boleh ada **spasi** sebelum/sesudah `=`
- **Copy-paste** dengan teliti
- **Redirect URI** harus sama persis (tidak ada `/` di akhir)

---

### **LANGKAH 3: Clear Cache & Test**

```bash
php artisan config:clear
php artisan cache:clear
php artisan serve
```

---

## ✅ **CARA MENGGUNAKAN:**

### **1. Buka Halaman Login**
```
http://localhost:8000/login
```

### **2. Klik "Login dengan Google Politala"**
- Button putih dengan logo Google di bagian atas

### **3. Pilih Akun Google Politala**
- Email: `nama@politala.ac.id` atau `@mhs.politala.ac.id`

### **4. Klik "Allow"**
- Google minta izin akses email & profile

### **5. ✅ Otomatis Login!**
- Langsung masuk ke Dashboard
- Jika user baru, otomatis terdaftar

---

## 🎯 **FITUR AUTO:**

### **Auto-Register**
User baru otomatis terdaftar saat pertama kali login

### **Auto-Role Assignment**
- `@mhs.politala.ac.id` → **Mahasiswa**
- `@politala.ac.id` → **Dosen**

### **Domain Whitelist**
Hanya email Politala yang bisa login

### **Email Verified**
Email otomatis terverifikasi (verified by Google)

---

## 🐛 **TROUBLESHOOTING CEPAT:**

### **❌ Error: "redirect_uri_mismatch"**

**Solusi:**
1. Pastikan Redirect URI di Google Console:
   ```
   http://localhost:8000/auth/google/callback
   ```
2. Pastikan di `.env` sama persis
3. Jalankan: `php artisan config:clear`

---

### **❌ Error: "invalid_client"**

**Solusi:**
1. Cek Client ID & Secret di `.env`
2. Pastikan tidak ada spasi atau karakter tambahan
3. Jalankan: `php artisan config:clear`
4. Restart: `php artisan serve`

---

### **❌ Error: "Hanya email Politala yang diperbolehkan"**

**Solusi:**
Gunakan email kampus: `@politala.ac.id` atau `@mhs.politala.ac.id`

---

### **❌ Button Google tidak muncul**

**Solusi:**
1. Hard refresh: `Ctrl + Shift + R`
2. Clear browser cache
3. Gunakan Incognito mode

---

## 📸 **SCREENSHOT HALAMAN LOGIN:**

Button "Login dengan Google Politala" akan muncul di bagian atas form login:

```
┌─────────────────────────────────────────────────┐
│                                                 │
│    [🔵 Login dengan Google Politala]           │
│    Login menggunakan akun Google kampus Anda   │
│    (@politala.ac.id atau @mhs.politala.ac.id)  │
│                                                 │
│    ─────────────── atau ───────────────         │
│                                                 │
│    Email    [_____________________________]    │
│    Password [_____________________________]    │
│    □ Remember me                               │
│                                [Log in]        │
│                                                 │
└─────────────────────────────────────────────────┘
```

---

## 📚 **DOKUMENTASI LENGKAP:**

File yang bisa dibaca untuk detail lebih lanjut:

1. **`SETUP_GOOGLE_SSO_MUDAH.md`** → Panduan lengkap (baru dibuat)
2. **`SETUP_GOOGLE_SSO.md`** → Panduan detail
3. **`QUICK_START_GOOGLE_SSO.md`** → Quick start guide
4. **`CARA_DAPAT_GOOGLE_OAUTH.md`** → Cara dapat credentials

---

## 🔧 **FILE TEKNIS:**

| File | Lokasi |
|------|--------|
| Controller | `app/Http/Controllers/Auth/GoogleAuthController.php` |
| Routes | `routes/web.php` (line 26-27) |
| View | `resources/views/auth/login.blade.php` (line 18-34) |
| Config | `config/services.php` (line 38-42) |

---

## 💡 **TIPS:**

1. ✅ Gunakan **Incognito** untuk testing (hindari cache)
2. ✅ Test dengan **2 akun berbeda** (mahasiswa & dosen)
3. ✅ Screenshot error jika ada masalah
4. ✅ Cek `storage/logs/laravel.log` jika error

---

## 🎉 **KEUNTUNGAN GOOGLE SSO:**

| Keuntungan | Deskripsi |
|-----------|-----------|
| 🔐 **Lebih Aman** | Password di-manage Google |
| ⚡ **One-Click Login** | Tidak perlu remember password |
| 🤖 **Auto-Register** | User baru otomatis terdaftar |
| ✅ **Email Verified** | Otomatis terverifikasi |
| 🔄 **Auto-Sync** | Data selalu update dari Google |

---

**Setup sekarang dan nikmati kemudahan login! 🚀**

---

## 📞 **BUTUH BANTUAN?**

Jika ada masalah saat setup, cek:
1. Browser console (F12 → Console)
2. Laravel log: `storage/logs/laravel.log`
3. Screenshot error dan tanya ke tim

---

**Selamat mencoba! 🎊**

