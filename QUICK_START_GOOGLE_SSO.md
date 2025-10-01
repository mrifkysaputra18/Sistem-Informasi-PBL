# ⚡ Quick Start - Google SSO (5 Menit)

Panduan cepat untuk mengaktifkan Login dengan Google Politala.

## 🎯 Yang Akan Anda Dapatkan:

✅ Login dengan button **"Login dengan Google Politala"**  
✅ Tidak perlu input password manual  
✅ Auto-register otomatis  
✅ Lebih aman (password di Google)  

---

## 🚀 Setup Cepat (5 Langkah)

### **1. Buka Google Cloud Console**
👉 https://console.cloud.google.com/

### **2. Buat Project Baru**
- Klik **"New Project"**
- Nama: `Sistem PBL Politala`
- Klik **Create**

### **3. Buat OAuth Credentials**
1. Sidebar → **APIs & Services** → **Credentials**
2. **"+ CREATE CREDENTIALS"** → **OAuth client ID**
3. Jika diminta setup Consent Screen:
   - External → Next
   - App name: `Sistem PBL Politala`
   - Email: (email Anda)
   - Save → Back to Credentials

4. **Create OAuth Client ID:**
   - Type: **Web application**
   - Name: `PBL Web`
   
   **Authorized redirect URIs:** (PENTING!)
   ```
   http://localhost:8000/auth/google/callback
   ```
   
   - Klik **Create**

5. **Copy Credentials yang muncul:**
   - Client ID: `123456-abc.apps.googleusercontent.com`
   - Client Secret: `GOCSPX-xyz123`

### **4. Update File .env**

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

### **5. Clear Cache & Restart**

```bash
php artisan config:clear
php artisan serve
```

---

## ✅ **Test Login!**

1. Buka: http://localhost:8000/login
2. Klik button **"Login dengan Google Politala"** (button putih dengan logo Google)
3. Pilih akun Google Politala Anda
4. Klik **"Allow"** untuk memberi izin
5. ✅ **Otomatis login dan masuk ke Dashboard!**

---

## 🎨 **Tampilan Login:**

```
┌────────────────────────────────────────┐
│                                        │
│   [🔵 Login dengan Google Politala]   │  ← Klik ini!
│   Login menggunakan akun Google        │
│   kampus Anda                          │
│                                        │
│   ────────── atau ──────────           │
│                                        │
│   Email    [________________]          │
│   Password [________________]          │
│   □ Remember me                        │
│                        [Log in]        │
│                                        │
└────────────────────────────────────────┘
```

---

## 📝 **Catatan Penting:**

### **✅ DO:**
- Gunakan email `@politala.ac.id` atau `@mhs.politala.ac.id`
- Copy-paste Client ID & Secret dengan teliti
- Pastikan Redirect URI **sama persis**

### **❌ DON'T:**
- Jangan gunakan email pribadi (Gmail, Yahoo, dll)
- Jangan tambahkan spasi di Client ID/Secret
- Jangan typo di Redirect URI

---

## 🔧 **Troubleshooting Cepat:**

### **Error: "redirect_uri_mismatch"**
**Fix:** URL redirect di Google Console harus **sama persis**:
```
http://localhost:8000/auth/google/callback
```
Tidak boleh ada `/` di akhir!

### **Error: "Hanya email Politala yang diperbolehkan"**
**Fix:** Anda login dengan email non-Politala. Gunakan email kampus!

### **Error: "invalid_client"**
**Fix:** 
1. Cek Client ID & Secret di `.env`
2. Jalankan: `php artisan config:clear`
3. Restart server

---

## 🎉 **Selesai!**

Sekarang sistem Anda sudah pakai **Google OAuth SSO**!

**Kelebihan:**
- ✅ Tidak perlu manage password sendiri
- ✅ Lebih aman (password di Google)
- ✅ User experience lebih baik
- ✅ Auto-register otomatis

**Login cukup 1 klik!** 🚀

---

## 📚 **Dokumentasi Lengkap:**

Baca: `SETUP_GOOGLE_SSO.md` untuk panduan detail dan troubleshooting lengkap.

---

**Happy coding! 🎊**

