# 🧪 TEST PLAN: Edit & Hapus Kelas

**Tanggal:** 6 Oktober 2025  
**Status:** ✅ **READY TO TEST**

---

## 🔧 **PERUBAHAN YANG DILAKUKAN:**

### **1. Route Model Binding (AppServiceProvider.php)** ✅
```php
// Tambahkan explicit binding
Route::bind('classroom', function ($value) {
    return ClassRoom::findOrFail($value);
});
```

**Kenapa Perlu:**
- Route parameter: `{classroom}` (lowercase)
- Model name: `ClassRoom` (camelCase)
- Laravel perlu explicit mapping

---

### **2. Ganti route() ke url()** ✅

**SEBELUM:**
```blade
{{ route('classrooms.edit', $classRoom->id) }}
{{ route('classrooms.update', $classRoom->id) }}
{{ route('classrooms.destroy', $classRoom->id) }}
```

**SESUDAH:**
```blade
{{ url('/classrooms/' . $classRoom->id . '/edit') }}
{{ url('/classrooms/' . $classRoom->id) }}
```

**Kenapa:**
- `url()` helper lebih eksplisit
- Tidak bergantung pada route name resolution
- Langsung generate URL

---

### **3. File yang Diubah:**

1. ✅ `app/Providers/AppServiceProvider.php`
   - Explicit route model binding

2. ✅ `resources/views/classrooms/edit.blade.php`
   - Form action: `url('/classrooms/' . $classRoom->id)`

3. ✅ `resources/views/classrooms/index.blade.php`
   - Edit link: `url('/classrooms/' . $classRoom->id . '/edit')`
   - Delete: JavaScript dengan `form.action = '/classrooms/' + id`

4. ✅ `resources/views/subjects/show.blade.php`
   - Link: `url('/classrooms/' . $classRoom->id)`

5. ✅ `resources/views/semesters/show.blade.php`
   - Link: `url('/classrooms/' . $classroom->id)`

6. ✅ `resources/views/groups/show.blade.php`
   - Link: `url('/classrooms/' . $group->classRoom->id)`

7. ✅ `resources/views/dashboards/dosen.blade.php`
   - Link: `url('/classrooms/' . $classRoom->id)`

---

## 🧪 **LANGKAH TEST:**

### **STEP 1: Clear Cache** ⚠️ **WAJIB!**
```bash
cd "D:\3B\IT Project 1\Tugas\Sistem-Informasi-PBL"
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

### **STEP 2: Restart Server**
```bash
# Tutup server yang lama (Ctrl+C)
php artisan serve
```

---

### **STEP 3: Clear Browser Cache** ⚠️ **SANGAT PENTING!**

**Option 1: Hard Refresh**
```
Windows: Ctrl + Shift + R atau Ctrl + F5
Mac: Cmd + Shift + R
```

**Option 2: Clear Cache Completely**
```
Chrome:
1. Ctrl + Shift + Delete
2. Pilih "Cached images and files"
3. Time range: "All time"
4. Click "Clear data"
```

**Option 3: Incognito Mode** (RECOMMENDED!)
```
Chrome: Ctrl + Shift + N
Edge: Ctrl + Shift + P
Firefox: Ctrl + Shift + P
```

---

### **STEP 4: Test Edit**

**URL Test:** http://localhost:8000/classrooms

**Steps:**
1. ✅ Login sebagai Admin
2. ✅ Klik tombol "Edit" (kuning) pada salah satu kelas
3. ✅ **Expected:** Form edit muncul TANPA ERROR
4. ✅ Ubah nama kelas (misal: TI-3A → TI-3A Updated)
5. ✅ Klik "Update Kelas"
6. ✅ **Expected:**
   - Redirect ke `/classrooms`
   - Muncul notifikasi "Kelas berhasil diupdate!"
   - Nama kelas berubah di list

**Jika Error:**
- Screenshot error message
- Check browser console (F12)
- Check Laravel log: `storage/logs/laravel.log`

---

### **STEP 5: Test Hapus**

**Prerequisites:**
- Pastikan ada kelas dengan **0 kelompok**
- Jangan test pada kelas yang punya kelompok!

**Steps:**
1. ✅ Buka: http://localhost:8000/classrooms
2. ✅ Cari kelas dengan "Kelompok: 0 / 5"
3. ✅ Klik tombol "Hapus" (merah)
4. ✅ **Expected:** Muncul konfirmasi JavaScript
5. ✅ Klik "OK" pada dialog
6. ✅ **Expected:**
   - Redirect ke `/classrooms`
   - Muncul notifikasi "Kelas berhasil dihapus!"
   - Kelas **HILANG** dari list
   - Data **TERHAPUS** dari database

**Verifikasi Hapus Berhasil:**
```bash
# Check di database
php artisan tinker

# Di tinker:
\App\Models\ClassRoom::count()  // Cek total kelas
\App\Models\ClassRoom::all()->pluck('name')  // Lihat nama-nama kelas
```

---

### **STEP 6: Test Hapus Kelas dengan Kelompok**

**Steps:**
1. ✅ Pilih kelas yang **punya kelompok** (Kelompok: 1-5 / 5)
2. ✅ Klik "Hapus"
3. ✅ Klik "OK"
4. ✅ **Expected:**
   - Muncul notifikasi ERROR: "Tidak dapat menghapus kelas yang masih memiliki kelompok!"
   - Kelas **TIDAK** terhapus
   - Tetap ada di list

---

## 🐛 **TROUBLESHOOTING:**

### **Problem 1: Error "Missing required parameter"**

**Solusi:**
```bash
# Clear semua cache
php artisan optimize:clear

# Restart server
# Tutup terminal
# Buka terminal baru
php artisan serve

# Gunakan Incognito Mode di browser
```

---

### **Problem 2: Form Submit Tidak Jalan**

**Check:**
1. Buka browser console (F12)
2. Lihat ada error JavaScript?
3. Check Network tab (F12 → Network)
4. Klik "Hapus", lihat request terkirim?

**Fix:**
- Pastikan JavaScript function `deleteClass()` ter-load
- View source (Ctrl+U), cari `function deleteClass`
- Jika tidak ada, clear view cache: `php artisan view:clear`

---

### **Problem 3: Notifikasi "Berhasil" tapi Data Tidak Terhapus**

**Check Database:**
```bash
php artisan tinker

# Check data masih ada
\App\Models\ClassRoom::find(1)  // Ganti 1 dengan ID yang dihapus

# Jika masih ada, ada masalah di Controller
```

**Solusi:**
- Check Controller `destroy` method
- Pastikan `$classRoom->delete()` dipanggil
- Check log: `storage/logs/laravel.log`

---

## ✅ **CHECKLIST TESTING:**

### **Edit Kelas:**
- [ ] Tombol "Edit" bisa diklik
- [ ] Form edit muncul tanpa error
- [ ] Bisa ubah nama kelas
- [ ] Klik "Update Kelas" berhasil
- [ ] Redirect ke list kelas
- [ ] Muncul notifikasi sukses
- [ ] Perubahan tersimpan

### **Hapus Kelas (Tanpa Kelompok):**
- [ ] Tombol "Hapus" bisa diklik
- [ ] Muncul konfirmasi dialog
- [ ] Klik "OK"
- [ ] Redirect ke list kelas
- [ ] Muncul notifikasi sukses
- [ ] Data terhapus dari list
- [ ] Data terhapus dari database

### **Hapus Kelas (Dengan Kelompok):**
- [ ] Tombol "Hapus" bisa diklik
- [ ] Muncul konfirmasi dialog
- [ ] Klik "OK"
- [ ] Muncul notifikasi ERROR
- [ ] Data TIDAK terhapus
- [ ] Tetap ada di list

---

## 📸 **YANG PERLU DIFOTO JIKA ERROR:**

1. Screenshot halaman error
2. Screenshot browser console (F12)
3. Screenshot Network tab (F12 → Network)
4. Copy error dari `storage/logs/laravel.log`

---

## 🎯 **EXPECTED RESULT:**

**✅ Edit Kelas:**
- URL: `/classrooms/1/edit` → Form edit muncul
- Submit form → Redirect ke `/classrooms`
- Data terupdate

**✅ Hapus Kelas:**
- Klik "Hapus" → Konfirmasi muncul
- OK → Request ke `DELETE /classrooms/1`
- Redirect ke `/classrooms`
- Data terhapus

---

**Silakan test dan laporkan hasilnya!** 🚀

**Jika masih error, kirim:**
1. Screenshot error
2. Browser console (F12)
3. Error dari `storage/logs/laravel.log`

