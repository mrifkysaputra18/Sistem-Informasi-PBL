# 🔑 Cara Mendapatkan Google OAuth Credentials

## ⚠️ Penting: API Key vs OAuth Credentials

**SALAH:** `AIzaSyAfiemVNqDQudQaYN8DST4JcmCS_qfxxyc` ← Ini API Key (bukan untuk OAuth!)

**BENAR:** Kita butuh **OAuth 2.0 Client Credentials**:
- Client ID: Format `123456789-abc.apps.googleusercontent.com`
- Client Secret: Format `GOCSPX-abc123def456`

---

## 📝 Step-by-Step Mendapatkan OAuth Credentials:

### **1. Buka Google Cloud Console**
👉 https://console.cloud.google.com/

Login dengan akun Google Anda (bebas, tidak harus Politala)

### **2. Buat atau Pilih Project**

**Jika Belum Ada Project:**
- Klik dropdown project (pojok kiri atas)
- Klik **"NEW PROJECT"**
- Project name: `Sistem-PBL-Politala`
- Klik **"CREATE"**
- Tunggu beberapa detik, lalu pilih project yang baru dibuat

**Jika Sudah Ada Project:**
- Pilih project yang mau digunakan

### **3. Setup OAuth Consent Screen (WAJIB!)**

**a) Buka OAuth Consent Screen:**
- Sidebar → **APIs & Services** → **OAuth consent screen**

**b) Pilih User Type:**
- Pilih **External**
- Klik **"CREATE"**

**c) Isi Form (App information):**
```
App name: Sistem Informasi PBL Politala
User support email: (email Anda)
App logo: (skip/kosong)
```

**d) Isi Form (Developer contact information):**
```
Email addresses: (email Anda)
```

**e) Scopes:**
- Klik **"ADD OR REMOVE SCOPES"**
- Centang:
  - ✅ `.../auth/userinfo.email`
  - ✅ `.../auth/userinfo.profile`
- Klik **"UPDATE"**
- Klik **"SAVE AND CONTINUE"**

**f) Test users (PENTING!):**
- Klik **"+ ADD USERS"**
- Masukkan email Politala Anda, contoh:
  ```
  muhammad.raihan@mhs.politala.ac.id
  ```
- Tambahkan email teman kelompok juga jika perlu
- Klik **"ADD"**
- Klik **"SAVE AND CONTINUE"**

**g) Summary:**
- Review → Klik **"BACK TO DASHBOARD"**

### **4. Buat OAuth Client ID**

**a) Buka Credentials:**
- Sidebar → **APIs & Services** → **Credentials**

**b) Create Credentials:**
- Klik **"+ CREATE CREDENTIALS"** (tombol biru di atas)
- Pilih **"OAuth client ID"**

**c) Application type:**
- Pilih: **Web application**

**d) Isi Form:**
```
Name: PBL Web Application

Authorized JavaScript origins:
  http://localhost:8000
  http://127.0.0.1:8000

Authorized redirect URIs:
  http://localhost:8000/auth/google/callback
  http://127.0.0.1:8000/auth/google/callback
```

**PENTING:**
- Redirect URI harus **PERSIS** seperti di atas
- Tidak boleh ada spasi
- Tidak boleh ada `/` di akhir
- Case-sensitive!

**e) Create:**
- Klik **"CREATE"**

### **5. Copy Credentials**

Setelah create, akan muncul popup dengan credentials:

```
Your Client ID
123456789-abc123def.apps.googleusercontent.com

Your Client Secret
GOCSPX-xyz123abc456
```

**COPY KEDUA-DUANYA!**

Atau bisa download JSON jika mau backup.

---

## 📝 Paste ke File .env

Buka file `.env` di project Anda, cari bagian Google OAuth:

```env
# Google OAuth Configuration (untuk Login SSO)
GOOGLE_CLIENT_ID=123456789-abc123def.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-xyz123abc456
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

**Ganti dengan credentials Anda!**

---

## ✅ Test Login

1. **Clear cache:**
   ```bash
   php artisan config:clear
   ```

2. **Buka browser:**
   ```
   http://localhost:8000/login
   ```

3. **Klik button "Login dengan Google Politala"**

4. **Pilih akun Google Politala:**
   - Jika diminta login, gunakan email & password Google kampus
   - Email harus: `@politala.ac.id` atau `@mhs.politala.ac.id`

5. **Allow Access:**
   - Google akan minta izin akses email & profile
   - Klik **"Allow"** atau **"Izinkan"**

6. **✅ Selesai!**
   - Otomatis login
   - Redirect ke Dashboard

---

## 🐛 Common Errors & Solutions

### **Error: "Access blocked: This app's request is invalid"**

**Penyebab:** Email Anda belum ditambahkan sebagai Test User

**Solusi:**
1. Google Cloud Console → OAuth consent screen
2. Tab **"Test users"**
3. **"+ ADD USERS"**
4. Masukkan email Politala Anda
5. Save → Coba login lagi

### **Error: "redirect_uri_mismatch"**

**Penyebab:** Redirect URI tidak sama

**Solusi:**
1. Cek URL di browser (localhost:8000 atau 127.0.0.1:8000)
2. Pastikan di Google Console ada:
   ```
   http://localhost:8000/auth/google/callback
   ```
3. Harus **sama persis** (tidak boleh beda 1 karakter pun!)

### **Error: "Hanya email Politala yang diperbolehkan"**

**Penyebab:** Login dengan email non-Politala (Gmail pribadi, dll)

**Solusi:** Login dengan email kampus:
- `nama.anda@politala.ac.id`
- `nama.anda@mhs.politala.ac.id`

---

## 🎓 Tips:

1. **Tambahkan semua email kelompok** sebagai Test Users
2. **Screenshot credentials** sebagai backup
3. **Jangan share Client Secret** di public (commit ke Git, dll)
4. **Untuk production:** Publish app di Google Console (tidak perlu Test Users)

---

## 📞 Butuh Bantuan?

Baca juga:
- `SETUP_GOOGLE_SSO.md` - Panduan detail lengkap
- `QUICK_START_GOOGLE_SSO.md` - Quick reference

Screenshot error dan share jika ada masalah!

---

**Selamat Setup! 🚀**

