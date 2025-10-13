# 🚀 QUICK START: DOSEN CRUD TARGET MINGGUAN

## 📍 LOKASI MENU

### **Step 1: Login Dosen**
```
URL: http://localhost:8000/login
Email: dosen@politala.ac.id
Password: password
```

### **Step 2: Akses Menu Target Mingguan**

#### **Cara 1: Dari Navigation Bar (TOP MENU)**
```
┌──────────────────────────────────────────────────────┐
│ Logo  [Dashboard] [Target Mingguan] [Review Target] │  ← KLIK INI!
└──────────────────────────────────────────────────────┘
```

**Klik menu:** `Target Mingguan` (di navigation bar atas)

#### **Cara 2: Dari Dashboard Dosen**
```
Dashboard Dosen → Scroll ke bawah → 
Section "Target Mingguan Terbaru" → 
Tombol "Lihat Semua" (kanan atas)
```

**URL Direct:** `http://localhost:8000/targets`

---

## ✨ TOMBOL CRUD YANG TERSEDIA

### **Di Halaman Utama (Header)**

```
┌────────────────────────────────────────────────────────┐
│ Kelola Target Mingguan              [+ Buat Target]   │ ← TOMBOL PUTIH
└────────────────────────────────────────────────────────┘
```

**Tombol "Buat Target Baru":**
- **Lokasi:** Kanan atas (header)
- **Warna:** Putih dengan border
- **Icon:** ➕ (Plus icon)
- **Aksi:** Create new target

---

### **Di Tabel Target (Per Row)**

```
┌──────────────────────────────────────────────────────┐
│ Target │ Kelompok │ Status │ AKSI                    │
├──────────────────────────────────────────────────────┤
│ CRUD   │ TI-3A/A  │ Pending│ [Detail][Edit][Hapus]  │ ← TOMBOL DI SINI!
└──────────────────────────────────────────────────────┘
```

**Tombol yang tersedia:**
1. **[Detail]** - Biru (View)
2. **[Edit]** - Kuning (Update)
3. **[Hapus]** - Merah (Delete)
4. **[Review]** - Hijau (Jika sudah submit)
5. **[Buka]/[Tutup]** - Abu (Manage status)

---

## 🎯 CARA MENGGUNAKAN (STEP-BY-STEP)

### **1️⃣ CREATE (Buat Target Baru)**

#### **Step 1: Klik Tombol "Buat Target Baru"**
Lokasi: **Kanan atas** header

#### **Step 2: Pilih Tipe Target**
```
○ Single Group (1 Kelompok)
○ Multiple Groups (Beberapa Kelompok)  
● All Class (Semua Kelompok) ← RECOMMENDED!
```

#### **Step 3: Pilih Kelas**
```
Kelas: [Pilih Kelas ▼]
       TI-3A
       TI-3B
       SI-3A
```

#### **Step 4: Isi Form**
```
Minggu:    [3]
Judul:     [Implementasi CRUD Produk]
Deskripsi: [Detail lengkap target...]
Deadline:  [27/10/2025 23:59]
```

#### **Step 5: Submit**
Klik tombol: **"Simpan Target"** (hijau, bawah form)

✅ **Result:** Target dibuat untuk semua kelompok!

---

### **2️⃣ READ (Lihat Daftar Target)**

#### **Tampilan Otomatis:**
Setelah login & akses menu Target Mingguan, langsung muncul:

```
┌──────────────────────────────────────────────────────┐
│ STATISTIK                                            │
├──────────────────────────────────────────────────────┤
│ [Total: 25] [Submit: 18] [Approved: 12] [Pending: 7]│
└──────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────┐
│ FILTER                                               │
├──────────────────────────────────────────────────────┤
│ Kelas: [Semua ▼]  Minggu: [Semua ▼]  Status: [▼]   │
│ [Filter]                                             │
└──────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────┐
│ TABEL TARGET                                         │
├─────┬────────┬──────────┬────────┬──────────────────┤
│ No  │ Target │ Kelompok │ Status │ Aksi             │
├─────┼────────┼──────────┼────────┼──────────────────┤
│ 1   │ CRUD   │ TI-3A/A  │ Pending│ [Detail][Edit]   │
│ 2   │ CRUD   │ TI-3A/B  │ Submit │ [Detail][Review] │
└─────┴────────┴──────────┴────────┴──────────────────┘
```

#### **Filter Target:**
1. **By Kelas** - Pilih kelas tertentu
2. **By Minggu** - Pilih minggu 1-16
3. **By Status** - Pending/Submit/Approved/etc
4. Klik **[Filter]**

---

### **3️⃣ UPDATE (Edit Target)**

#### **Syarat:**
- ✅ **Bisa edit** jika status **PENDING** (belum disubmit)
- ❌ **Tidak bisa** jika sudah **SUBMITTED**

#### **Step 1: Cari Target yang Mau Diedit**
Scroll tabel, cari target dengan status **"Belum Dikerjakan"**

#### **Step 2: Klik Tombol [Edit]**
```
┌──────────────────────────────────────────┐
│ [Detail] [Edit] [Hapus]                  │
│           ↑ KLIK INI (Kuning)            │
└──────────────────────────────────────────┘
```

#### **Step 3: Update Form**
Ubah data yang perlu diubah:
- Minggu
- Judul
- Deskripsi
- Deadline

#### **Step 4: Save**
Klik: **"Update Target"**

✅ **Result:** Target berhasil diupdate!

#### **Jika Tidak Bisa Edit:**
Muncul indicator **[Terkunci]** (abu-abu) → Artinya sudah ada submission

---

### **4️⃣ DELETE (Hapus Target)**

#### **Syarat:**
- ✅ **Bisa hapus** jika status **PENDING**
- ❌ **Tidak bisa** jika sudah **SUBMITTED**

#### **Step 1: Cari Target yang Mau Dihapus**
Scroll tabel, cari target dengan status **"Belum Dikerjakan"**

#### **Step 2: Klik Tombol [Hapus]**
```
┌──────────────────────────────────────────┐
│ [Detail] [Edit] [Hapus]                  │
│                  ↑ KLIK INI (Merah)      │
└──────────────────────────────────────────┘
```

#### **Step 3: Konfirmasi**
```
⚠️ PERHATIAN!

Yakin ingin menghapus target ini?

Target: Implementasi CRUD Produk
Kelompok: TI-3A / Kelompok A

[Batal]  [OK]
```

Klik **[OK]**

✅ **Result:** Target terhapus!

---

## 🔍 TROUBLESHOOTING

### **Problem 1: Menu "Target Mingguan" Tidak Ada**

**Check:**
1. Pastikan login sebagai **dosen** (bukan mahasiswa)
2. Refresh page (F5)
3. Clear cache browser (Ctrl+Shift+Delete)

**Solution:**
```bash
# Clear Laravel cache
cd "D:\3B\IT Project 1\Tugas\Sistem-Informasi-PBL"
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### **Problem 2: Tombol "Buat Target" Tidak Muncul**

**Check:**
1. Role: Harus **dosen** atau **admin**
2. Lokasi tombol: **Kanan atas** header (warna putih)

**Ciri tombol:**
- Icon: ➕
- Text: "Buat Target Baru"
- Warna: Putih dengan border

### **Problem 3: Tombol Edit/Hapus Tidak Ada**

**Reason:**
Target sudah ada submission (mahasiswa sudah submit)

**Indicator:**
- Status: **"Sudah Submit"** (bukan "Belum Dikerjakan")
- Tombol: **[Terkunci]** (abu-abu)

**Solution:**
- Tidak bisa edit/hapus target yang sudah disubmit
- Buat target baru jika perlu

### **Problem 4: Error 403 Forbidden**

**Reason:**
Mencoba edit/hapus target yang dibuat dosen lain

**Solution:**
- Dosen hanya bisa edit/hapus target **yang dia buat sendiri**
- Atau login sebagai **admin** (bisa edit semua)

---

## 📊 VISUAL GUIDE

### **Tampilan Halaman Target Mingguan:**

```
┌────────────────────────────────────────────────────────────┐
│ 🏠 Sistem Informasi PBL                                    │
├────────────────────────────────────────────────────────────┤
│ [Dashboard] [Target Mingguan] [Review Target] [Ranking]   │ ← NAVIGATION
├────────────────────────────────────────────────────────────┤
│                                                            │
│ ┌────────────────────────────────────────────────────┐   │
│ │ Kelola Target Mingguan        [+ Buat Target Baru] │   │ ← HEADER
│ └────────────────────────────────────────────────────┘   │
│                                                            │
│ ✅ Success message (jika ada)                             │
│                                                            │
│ ┌────────────────────────────────────────────────────┐   │
│ │ STATISTIK SUBMISSION                               │   │
│ │ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐    │   │
│ │ │Total │ │Submit│ │Approve│ │Revisi│ │Pending│    │   │
│ │ │  25  │ │  18  │ │  12  │ │   3  │ │   4  │    │   │
│ │ └──────┘ └──────┘ └──────┘ └──────┘ └──────┘    │   │
│ └────────────────────────────────────────────────────┘   │
│                                                            │
│ ┌────────────────────────────────────────────────────┐   │
│ │ FILTER & SEARCH                                    │   │
│ │ Kelas: [▼] Minggu: [▼] Status: [▼] [Filter]      │   │
│ └────────────────────────────────────────────────────┘   │
│                                                            │
│ ┌────────────────────────────────────────────────────┐   │
│ │ DAFTAR TARGET MINGGUAN                             │   │
│ ├───┬──────┬─────────┬────────┬───────────────────┤   │
│ │No │Target│Kelompok │Status  │Aksi               │   │
│ ├───┼──────┼─────────┼────────┼───────────────────┤   │
│ │1  │CRUD  │TI-3A/A  │Pending │[Detail][Edit][❌] │   │ ← TOMBOL CRUD
│ │2  │CRUD  │TI-3A/B  │Submit  │[Detail][Review]   │   │
│ │3  │DB    │TI-3A/A  │Approved│[Detail]           │   │
│ └───┴──────┴─────────┴────────┴───────────────────┘   │
│                                                            │
│ ← 1 2 3 4 5 → (Pagination)                               │
└────────────────────────────────────────────────────────────┘
```

---

## 🎨 WARNA TOMBOL (Visual Reference)

```
┌──────────────────────────────────────────┐
│ TOMBOL CREATE                            │
│ ┌──────────────────────────────────────┐ │
│ │ ➕ Buat Target Baru                  │ │ ← Putih
│ └──────────────────────────────────────┘ │
└──────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ TOMBOL READ                              │
│ ┌───────────┐                            │
│ │ 👁️ Detail │                            │ ← Biru
│ └───────────┘                            │
└──────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ TOMBOL UPDATE                            │
│ ┌──────────┐                             │
│ │ ✏️ Edit  │                             │ ← Kuning
│ └──────────┘                             │
└──────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ TOMBOL DELETE                            │
│ ┌───────────┐                            │
│ │ 🗑️ Hapus  │                            │ ← Merah
│ └───────────┘                            │
└──────────────────────────────────────────┘

┌──────────────────────────────────────────┐
│ TOMBOL REVIEW                            │
│ ┌────────────┐                           │
│ │ ✅ Review  │                           │ ← Hijau
│ └────────────┘                           │
└──────────────────────────────────────────┘
```

---

## 🎯 CHECKLIST VERIFIKASI

Sebelum menyatakan "tidak bisa melihat", pastikan:

- [ ] Sudah login sebagai **dosen** (bukan mahasiswa)
- [ ] Menu **"Target Mingguan"** ada di **navigation bar** (atas)
- [ ] Sudah klik menu "Target Mingguan"
- [ ] URL sekarang: `http://localhost:8000/targets`
- [ ] Tombol **"+ Buat Target Baru"** terlihat di **kanan atas**
- [ ] Tabel target muncul dengan kolom **"Aksi"**
- [ ] Di kolom "Aksi" ada tombol: **Detail, Edit, Hapus**
- [ ] Sudah clear cache (browser & Laravel)

---

## 🚀 QUICK TEST

### **Test 1: Verify Menu**
```bash
1. Login: dosen@politala.ac.id / password
2. Lihat navigation bar atas
3. ✅ Ada menu "Target Mingguan"? 
4. Klik menu tersebut
5. ✅ URL berubah ke /targets?
```

### **Test 2: Verify Create Button**
```bash
1. Di halaman /targets
2. Lihat header (kanan atas)
3. ✅ Ada tombol "Buat Target Baru"?
4. Klik tombol tersebut
5. ✅ Muncul form create target?
```

### **Test 3: Verify CRUD Buttons**
```bash
1. Di halaman /targets
2. Scroll ke tabel target
3. Lihat kolom "Aksi" (paling kanan)
4. ✅ Ada tombol "Detail" (biru)?
5. ✅ Ada tombol "Edit" (kuning)?
6. ✅ Ada tombol "Hapus" (merah)?
```

---

## 📞 NEED HELP?

Jika masih tidak bisa melihat fitur CRUD:

### **Step 1: Screenshot**
- Ambil screenshot halaman `/targets` Anda
- Perhatikan: apakah tombol CRUD muncul?

### **Step 2: Check Console**
```bash
# Browser Console (F12)
- Ada error? Screenshot error message

# Laravel Log
tail -50 storage/logs/laravel.log
```

### **Step 3: Verify User Role**
```bash
# Akses: php artisan tinker
App\Models\User::where('email', 'dosen@politala.ac.id')->first()->role
# Output harus: "dosen"
```

---

**Last Updated:** 13 Oktober 2025  
**Version:** 1.0  
**Status:** ✅ Ready to Use
