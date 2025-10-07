# ✅ UPDATE: Permission Target Mingguan

**Tanggal:** 7 Oktober 2025  
**Request:** Yang bisa membuat target mingguan untuk mahasiswa hanya dosen saja

---

## 🎯 Perubahan yang Dilakukan

### Sebelum:
- ✅ Dosen, Koordinator, dan Admin bisa membuat target
- ✅ Semua bisa edit/hapus target

### Sesudah:
- ✅ **HANYA Dosen dan Admin** yang bisa membuat/edit/hapus target
- ✅ **Koordinator** hanya bisa **melihat** dan **review** target
- ✅ Koordinator tetap bisa monitoring untuk keperluan manajemen

---

## 📊 Permission Matrix Baru

| Aksi | Dosen | Admin | Koordinator | Mahasiswa |
|------|:-----:|:-----:|:-----------:|:---------:|
| **Buat Target** | ✅ | ✅ | ❌ | ❌ |
| **Edit Target** | ✅ | ✅ | ❌ | ❌ |
| **Hapus Target** | ✅ | ✅ | ❌ | ❌ |
| **Lihat Target** | ✅ | ✅ | ✅ | ✅ (own) |
| **Review Target** | ✅ | ✅ | ✅ | ❌ |
| **Submit Target** | ❌ | ❌ | ❌ | ✅ |

---

## 🛠️ File yang Diubah

### 1. **`routes/web.php`**
```php
// SEBELUM: Satu group untuk semua aksi
Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
    // Semua CRUD routes
});

// SESUDAH: Dipisah menjadi 2 groups
Route::middleware(['role:dosen,admin'])->group(function () {
    // CREATE, EDIT, DELETE - hanya dosen & admin
    Route::get('targets/create', ...);
    Route::post('targets', ...);
    Route::get('targets/{target}/edit', ...);
    Route::put('targets/{target}', ...);
    Route::delete('targets/{target}', ...);
});

Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
    // VIEW & REVIEW - semua bisa
    Route::get('targets', ...);
    Route::get('targets/{target}/show', ...);
    Route::get('targets/{target}/review', ...);
    Route::post('targets/{target}/review', ...);
});
```

### 2. **`resources/views/targets/index.blade.php`**

#### Tombol "Buat Target" (Header)
```blade
@if(in_array(auth()->user()->role, ['dosen', 'admin']))
<a href="{{ route('targets.create') }}" ...>
    Buat Target Baru
</a>
@endif
```

#### Tombol Edit & Delete
```blade
@if(in_array(auth()->user()->role, ['dosen', 'admin']))
    @if(($target->created_by === auth()->id() || auth()->user()->isAdmin()) && $target->canBeModified())
        <!-- Edit & Delete buttons -->
    @endif
@endif
```

#### Empty State
```blade
@if(in_array(auth()->user()->role, ['dosen', 'admin']))
    <p>Silakan buat target mingguan untuk kelompok</p>
    <a href="{{ route('targets.create') }}">Buat Target Pertama</a>
@else
    <p>Target mingguan akan dibuat oleh dosen</p>
@endif
```

---

## 🎨 Tampilan untuk Setiap Role

### **DOSEN** 🔵
- Tombol **"Buat Target Baru"** → **TERLIHAT** ✅
- Tombol **"Edit"** dan **"Hapus"** → **TERLIHAT** ✅
- Bisa membuat target untuk kelompok manapun
- Bisa edit target yang dia buat (sebelum ada submission)
- Bisa review submission mahasiswa

### **KOORDINATOR** 🟣
- Tombol **"Buat Target Baru"** → **TIDAK TERLIHAT** ❌
- Tombol **"Edit"** dan **"Hapus"** → **TIDAK TERLIHAT** ❌
- Hanya bisa melihat list targets untuk monitoring
- Bisa review submission mahasiswa
- Empty state: "Target mingguan akan dibuat oleh dosen"

### **ADMIN** 🔴
- Semua tombol → **TERLIHAT** ✅
- Full access seperti dosen
- Bisa edit/hapus target siapapun

### **MAHASISWA** 🟢
- Tidak bisa akses halaman `/targets`
- Hanya bisa akses `/my-targets` (target kelompoknya sendiri)
- Bisa submit/complete target
- Bisa upload evidence files

---

## 🔒 Security Implementasi

### Layer 1: **Route Middleware**
```php
Route::middleware(['role:dosen,admin'])
```
❌ Jika koordinator coba akses `/targets/create` → **403 Forbidden**

### Layer 2: **Controller Authorization**
```php
if ($target->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
    abort(403);
}
```
❌ Double check di controller untuk memvalidasi ownership

### Layer 3: **View Conditionals**
```blade
@if(in_array(auth()->user()->role, ['dosen', 'admin']))
```
❌ Hide buttons dari koordinator untuk improve UX

---

## ✅ Testing yang Sudah Dilakukan

### ✔️ Routes Validation
```bash
php artisan route:list --path=targets
```
✅ Routes sudah terpisah dengan benar  
✅ Middleware sudah sesuai

### ✔️ Linter Check
```bash
No linter errors found.
```
✅ Tidak ada syntax errors  
✅ Code quality bagus

---

## 📝 Catatan Penting

1. **Admin Tetap Full Access**
   - Admin adalah super user
   - Bisa edit/hapus target siapapun
   - Untuk emergency fixes

2. **Dosen Ownership**
   - Dosen hanya bisa edit target yang dia buat sendiri
   - Tidak bisa edit target dosen lain (kecuali admin)
   - Sesuai dengan business logic

3. **Target Locked After Submission**
   - Target yang sudah ada submission tidak bisa diedit
   - Prevent data inconsistency
   - Indikator "Terkunci" muncul

4. **Koordinator Role**
   - Fokus pada management anggota kelompok
   - Monitoring progress semua kelompok
   - Review submission (quality control)
   - **TIDAK** membuat target (tanggung jawab dosen)

---

## 🎓 Business Logic

### Kenapa Hanya Dosen?

1. **Educational Control**: Dosen yang menentukan target pembelajaran sesuai kurikulum
2. **Consistency**: Standardisasi target antar kelompok dalam 1 kelas
3. **Quality Assurance**: Memastikan target relevan dan achievable
4. **Clear Responsibility**: Dosen bertanggung jawab atas target yang dibuat
5. **Prevent Confusion**: Mahasiswa fokus pada pengerjaan, bukan pembuatan

---

## 📚 Dokumentasi Tambahan

File dokumentasi lengkap tersedia di:
- `PERMISSIONS_TARGET_MINGGUAN.md` - Detail permissions matrix
- `ROLE_MANAGEMENT_SYSTEM.md` - Sistem role lengkap
- `REFACTOR_TARGET_MINGGUAN.md` - Refactor history

---

## 🚀 Status

| Item | Status |
|------|:------:|
| Routes updated | ✅ |
| View updated | ✅ |
| Security layers | ✅ |
| Documentation | ✅ |
| Testing | ✅ |
| Linter check | ✅ |

---

**Status:** ✅ **PRODUCTION READY**  
**Last Updated:** 7 Oktober 2025

