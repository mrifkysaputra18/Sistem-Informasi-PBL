# 📋 PENJELASAN: Tombol Edit & Hapus Target Mingguan

## ❓ KENAPA TOMBOL EDIT/HAPUS TIDAK MUNCUL?

Dari screenshot yang Anda kirim, **kedua target** memiliki status **"Sudah Submit"**:

```
Target 1: "usecase" - Status: Sudah Submit
Target 2: "Test Target - Implementasi Fitur 2" - Status: Sudah Submit
```

### **Business Logic:**

```
✅ TOMBOL EDIT & HAPUS MUNCUL jika:
   - Status: "Belum Dikerjakan" (PENDING)
   - Mahasiswa belum mengumpulkan

❌ TOMBOL EDIT & HAPUS TIDAK MUNCUL jika:
   - Status: "Sudah Submit" ← KEDUA TARGET ANDA
   - Status: "Approved"
   - Status: "Perlu Revisi"
   - Sudah direview dosen
```

**Alasan:**
- Untuk **data integrity** dan **audit trail**
- Mencegah dosen menghapus target yang sudah dikerjakan mahasiswa
- Mencegah perubahan requirement setelah mahasiswa submit

---

## ✅ SOLUSI: 3 CARA

### **SOLUSI 1: Test dengan Target BARU (PENDING)**

Saya sudah membuat **target baru** dengan status **PENDING** untuk Anda:

```bash
# Sudah dibuat otomatis!
Target ID: 4
Title: "TEST TARGET - BELUM DISUBMIT (untuk test Edit/Hapus)"
Week: 5
Status: PENDING ← STATUS INI PENTING!
```

#### **Cara Lihat:**
1. **Refresh halaman** `http://localhost:8000/targets`
2. **Scroll tabel** sampai menemukan target baru (Week 5)
3. **Di kolom AKSI**, akan muncul:
   ```
   [Detail] [Edit] [Hapus]  ← TOMBOL INI MUNCUL!
   ```

#### **Visual Expected:**
```
┌────┬─────────────────────┬──────────┬────────┬─────────┬──────────────────┐
│ No │ Target              │ Kelompok │ Minggu │ Status  │ Aksi             │
├────┼─────────────────────┼──────────┼────────┼─────────┼──────────────────┤
│ 1  │ usecase             │ TI-3A/A  │ Week 1 │ Submit  │ [Detail]         │ ← TIDAK ADA EDIT/HAPUS
│ 2  │ Test Target Fitur 2 │ TI-3A/A  │ Week 2 │ Submit  │ [Detail][Review] │ ← TIDAK ADA EDIT/HAPUS
│ 3  │ TEST TARGET -       │ TI-3A/A  │ Week 5 │ Pending │ [Detail][Edit]   │ ← ADA EDIT/HAPUS!
│    │ BELUM DISUBMIT      │          │        │         │ [Hapus]          │
└────┴─────────────────────┴──────────┴────────┴─────────┴──────────────────┘
```

---

### **SOLUSI 2: Force Delete (ADMIN ONLY)**

Jika Anda perlu menghapus target yang **sudah disubmit** (untuk maintenance/cleanup):

#### **A. Login sebagai ADMIN**
```
URL: http://localhost:8000/login
Email: admin@politala.ac.id (jika ada)
atau ubah role user menjadi admin
```

#### **B. Tombol Force Delete Muncul**

Untuk target yang sudah submit, **ADMIN** akan melihat:

```
┌────────────────────────────────────────────────────────┐
│ [Terkunci] [Force Delete]  ← TOMBOL MERAH untuk ADMIN │
└────────────────────────────────────────────────────────┘
```

**Ciri tombol:**
- **Warna:** Merah gelap (bg-red-600)
- **Icon:** ⚠️ (Warning triangle)
- **Text:** "Force Delete"

#### **C. Konfirmasi Dialog**

Saat klik Force Delete, muncul warning:
```
⚠️ FORCE DELETE!

APAKAH ANDA YAKIN?

Target: usecase
Kelompok: TI-3A / Kelompok A
Status: Sudah Submit

❌ Tindakan ini akan MENGHAPUS target meskipun sudah ada submission!

✅ Lanjutkan hapus?

[Batal]  [OK]
```

#### **D. Log Warning**

Setiap force delete akan ter-log:
```log
[WARNING] WeeklyTarget FORCE DELETED
          target_id: 1
          title: "usecase"
          status: submitted
          was_submitted: true
          deleted_by: 1 (admin)
```

---

### **SOLUSI 3: Ubah Role User ke Admin (Temporary)**

Jika ingin test force delete:

```bash
cd "D:\3B\IT Project 1\Tugas\Sistem-Informasi-PBL"
php artisan tinker

# Di tinker:
$user = User::where('email', 'dosen@politala.ac.id')->first();
$user->role = 'admin';
$user->save();
exit

# Sekarang login ulang, tombol Force Delete akan muncul!
```

**Kembalikan ke dosen setelah test:**
```bash
php artisan tinker

$user = User::where('email', 'dosen@politala.ac.id')->first();
$user->role = 'dosen';
$user->save();
exit
```

---

## 🎯 SUMMARY TABLE

| Status Target | Edit? | Hapus? | Force Delete? |
|---------------|-------|--------|---------------|
| **Pending (Belum Submit)** | ✅ Ya | ✅ Ya | - |
| **Submitted (Sudah Submit)** | ❌ Tidak | ❌ Tidak | ✅ Ya (Admin) |
| **Approved** | ❌ Tidak | ❌ Tidak | ✅ Ya (Admin) |
| **Revision** | ❌ Tidak | ❌ Tidak | ✅ Ya (Admin) |
| **Reviewed** | ❌ Tidak | ❌ Tidak | ✅ Ya (Admin) |

---

## 🔍 VERIFICATION

### **Check 1: Refresh & Lihat Target Baru**
```bash
# URL
http://localhost:8000/targets

# Expected: Muncul target Week 5 dengan tombol [Edit][Hapus]
```

### **Check 2: Filter by Status**
```bash
# Di halaman /targets
# Filter Status: Pilih "Belum Dikerjakan"
# Klik [Filter]

# Expected: Hanya muncul target dengan status PENDING
# Dan semua ada tombol [Edit][Hapus]
```

### **Check 3: Verify Force Delete (Admin)**
```bash
# Login sebagai admin
# Lihat target dengan status "Sudah Submit"
# Expected: Ada tombol [Terkunci] [Force Delete]
```

---

## 📊 CURRENT DATABASE STATUS

```
Total Targets: 4

1. usecase (Week 1)
   Status: Sudah Submit
   Buttons: [Detail] only
   
2. Test Target - Implementasi Fitur 2 (Week 2)
   Status: Sudah Submit
   Buttons: [Detail] [Review]
   
3. (Any other existing targets)
   
4. TEST TARGET - BELUM DISUBMIT (Week 5) ← NEW!
   Status: PENDING
   Buttons: [Detail] [Edit] [Hapus] ← TOMBOL CRUD LENGKAP!
```

---

## ⚠️ WARNING: Force Delete

**Use with EXTREME caution!**

Force Delete akan:
- ❌ Menghapus target meskipun ada submission
- ❌ Menghapus history pengerjaan mahasiswa
- ❌ Menghapus review dosen (jika ada)
- ⚠️ Tidak bisa di-undo

**Gunakan hanya untuk:**
- Cleanup data test
- Remove duplicate targets
- Emergency maintenance

**JANGAN gunakan untuk:**
- Target yang sedang berjalan
- Target dengan submission valid
- Target yang sudah direview

---

## 📝 BEST PRACTICES

### **Untuk Testing CRUD:**
1. ✅ Selalu buat target baru dengan status PENDING
2. ✅ Test Edit/Hapus pada target PENDING
3. ✅ Jangan hapus target yang sudah disubmit mahasiswa

### **Untuk Production:**
1. ✅ Close target setelah deadline lewat
2. ✅ Review semua submission
3. ✅ Jangan hapus target yang sudah direview
4. ✅ Backup database sebelum force delete

### **Untuk Admin:**
1. ✅ Log semua force delete actions
2. ✅ Konfirmasi dengan user sebelum force delete
3. ✅ Backup data sebelum delete
4. ✅ Document alasan force delete

---

## 🧪 QUICK TEST STEPS

### **Test 1: Verify Target PENDING**
```
1. Go to: http://localhost:8000/targets
2. Refresh page (F5)
3. Look for: "TEST TARGET - BELUM DISUBMIT (Week 5)"
4. Check Aksi column:
   ✅ Should have: [Detail] [Edit] [Hapus]
```

### **Test 2: Test Edit**
```
1. Click [Edit] on Week 5 target
2. Change title to: "EDITED - Test Target"
3. Click "Update Target"
4. ✅ Should see success message
5. ✅ Title updated in table
```

### **Test 3: Test Delete**
```
1. Click [Hapus] on Week 5 target
2. Confirm dialog
3. ✅ Should see success message
4. ✅ Target removed from table
```

### **Test 4: Test Force Delete (Admin)**
```
1. Change role to admin (see Solusi 3)
2. Login ulang
3. Go to: http://localhost:8000/targets
4. Look at target with "Sudah Submit" status
5. ✅ Should see [Force Delete] button (red)
6. Click to test (careful!)
```

---

## 🎉 CONCLUSION

**Tombol Edit & Hapus SUDAH ADA dan BERFUNGSI!**

Hanya saja **tidak muncul** untuk target yang **sudah disubmit** karena:
- ✅ **Business logic** yang benar
- ✅ **Data integrity** protection
- ✅ **Audit trail** preservation

**Untuk test CRUD:**
1. ✅ Gunakan target baru (Week 5) yang sudah dibuat
2. ✅ Atau buat target baru sendiri
3. ✅ Pastikan status **PENDING** (belum disubmit)

**Untuk hapus target submitted:**
1. ✅ Login sebagai **admin**
2. ✅ Gunakan tombol **Force Delete** (merah)
3. ⚠️ **Hati-hati!** Tidak bisa di-undo

---

**Last Updated:** 13 Oktober 2025  
**Version:** 1.0  
**Status:** ✅ Fully Explained & Implemented
