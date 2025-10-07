# 🔐 Permissions Target Mingguan - Update

**Tanggal:** 7 Oktober 2025  
**Status:** ✅ Implemented

---

## 📋 Ringkasan Perubahan

Berdasarkan feedback klien, **hanya DOSEN** yang bisa membuat, mengedit, dan menghapus target mingguan untuk mahasiswa. Koordinator hanya bisa melihat untuk monitoring.

---

## 👥 Permissions Matrix

| Role | Buat Target | Edit Target | Hapus Target | Lihat Target | Review Target |
|------|:----------:|:----------:|:------------:|:------------:|:-------------:|
| **Dosen** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Admin** | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Koordinator** | ❌ | ❌ | ❌ | ✅ | ✅ |
| **Mahasiswa** | ❌ | ❌ | ❌ | ✅ (own only) | ❌ |

---

## 🛠️ Implementasi Teknis

### 1. **Routes** (`routes/web.php`)

#### CREATE/EDIT/DELETE - Hanya Dosen & Admin
```php
Route::middleware(['role:dosen,admin'])->group(function () {
    Route::get('targets/create', [WeeklyTargetController::class, 'create']);
    Route::post('targets', [WeeklyTargetController::class, 'store']);
    Route::get('targets/{target}/edit', [WeeklyTargetController::class, 'edit']);
    Route::put('targets/{target}', [WeeklyTargetController::class, 'update']);
    Route::delete('targets/{target}', [WeeklyTargetController::class, 'destroy']);
});
```

#### VIEW & REVIEW - Dosen, Koordinator, & Admin
```php
Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
    Route::get('targets', [WeeklyTargetController::class, 'index']);
    Route::get('targets/{target}/show', [WeeklyTargetController::class, 'show']);
    Route::get('targets/{target}/review', [WeeklyTargetController::class, 'review']);
    Route::post('targets/{target}/review', [WeeklyTargetController::class, 'storeReview']);
});
```

#### SUBMIT - Hanya Mahasiswa
```php
Route::middleware(['role:mahasiswa'])->group(function () {
    Route::get('my-targets', [WeeklyTargetSubmissionController::class, 'index']);
    Route::get('targets/{target}', [WeeklyTargetSubmissionController::class, 'show']);
    Route::get('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'submitForm']);
    Route::post('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'storeSubmission']);
});
```

---

### 2. **View Updates** (`resources/views/targets/index.blade.php`)

#### Tombol "Buat Target Baru" (Header)
```blade
@if(in_array(auth()->user()->role, ['dosen', 'admin']))
<a href="{{ route('targets.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    <i class="fas fa-plus mr-2"></i>Buat Target Baru
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
    <p class="text-sm mb-4">Silakan buat target mingguan untuk kelompok</p>
    <a href="{{ route('targets.create') }}">Buat Target Pertama</a>
@else
    <p class="text-sm">Target mingguan akan dibuat oleh dosen</p>
@endif
```

---

## 📱 User Experience

### Untuk **DOSEN**
1. ✅ Bisa membuat target baru untuk kelompok
2. ✅ Bisa edit target yang dia buat (sebelum ada submission)
3. ✅ Bisa hapus target yang dia buat (sebelum ada submission)
4. ✅ Bisa review submission mahasiswa
5. ✅ Melihat semua targets di kelasnya

### Untuk **KOORDINATOR**
1. ❌ **TIDAK** bisa membuat target baru
2. ❌ **TIDAK** bisa edit/hapus target
3. ✅ Bisa melihat semua targets untuk monitoring
4. ✅ Bisa review submission mahasiswa
5. 💡 Pesan: "Target mingguan akan dibuat oleh dosen"

### Untuk **ADMIN**
1. ✅ Full access (sama seperti dosen)
2. ✅ Bisa edit/hapus target siapapun
3. ✅ Override semua permissions

### Untuk **MAHASISWA**
1. ❌ **TIDAK** bisa membuat target
2. ❌ **TIDAK** bisa edit/hapus target
3. ✅ Bisa melihat target kelompoknya sendiri
4. ✅ Bisa submit/complete target
5. ✅ Bisa upload evidence files

---

## 🔒 Security Layers

Sistem menggunakan **3 layer security**:

### Layer 1: **Route Middleware**
```php
Route::middleware(['role:dosen,admin'])->group(...)
```
- Mencegah akses langsung via URL
- Return 403 Forbidden untuk unauthorized users

### Layer 2: **Controller Authorization**
```php
if ($target->created_by !== auth()->id() && !auth()->user()->isAdmin()) {
    abort(403, 'Anda tidak memiliki akses untuk mengedit target ini.');
}
```
- Double check di controller
- Memvalidasi ownership

### Layer 3: **View Conditionals**
```blade
@if(in_array(auth()->user()->role, ['dosen', 'admin']))
    <!-- Buttons visible -->
@endif
```
- Hide buttons dari user yang tidak berwenang
- Improve UX & prevent confusion

---

## 🧪 Testing Checklist

### ✅ Test as DOSEN
- [ ] Bisa akses `/targets/create`
- [ ] Bisa submit form create target
- [ ] Bisa edit target yang dia buat
- [ ] Bisa hapus target yang dia buat
- [ ] Tombol "Buat Target Baru" **terlihat**
- [ ] Tombol "Edit" dan "Hapus" **terlihat** (untuk target sendiri)

### ✅ Test as KOORDINATOR
- [ ] **TIDAK** bisa akses `/targets/create` (403 Forbidden)
- [ ] Bisa akses `/targets` (view list)
- [ ] Bisa akses `/targets/{id}/show` (view detail)
- [ ] Bisa akses `/targets/{id}/review` (review submission)
- [ ] Tombol "Buat Target Baru" **TIDAK** terlihat
- [ ] Tombol "Edit" dan "Hapus" **TIDAK** terlihat
- [ ] Empty state menampilkan: "Target mingguan akan dibuat oleh dosen"

### ✅ Test as ADMIN
- [ ] Bisa akses semua routes (full access)
- [ ] Bisa edit/hapus target siapapun
- [ ] Semua tombol **terlihat**

### ✅ Test as MAHASISWA
- [ ] **TIDAK** bisa akses `/targets` (403 Forbidden)
- [ ] Bisa akses `/my-targets` (own targets only)
- [ ] Bisa submit target
- [ ] **TIDAK** ada tombol create/edit/delete

---

## 📊 Files Changed

| File | Changes |
|------|---------|
| `routes/web.php` | Split routes: create/edit/delete untuk dosen+admin, view/review untuk dosen+koordinator+admin |
| `resources/views/targets/index.blade.php` | Add role checks untuk tombol create/edit/delete |

---

## 🎯 Business Logic

### Kenapa Hanya Dosen?

1. **Educational Control**: Dosen yang menentukan target pembelajaran
2. **Consistency**: Standardisasi target antar kelompok
3. **Quality Assurance**: Memastikan target relevan dengan kurikulum
4. **Prevent Confusion**: Mahasiswa fokus pada pengerjaan, bukan pembuatan target
5. **Clear Responsibility**: Dosen bertanggung jawab atas target yang dibuat

### Role Koordinator

Koordinator tetap penting untuk:
- ✅ **Monitoring**: Melihat progress semua kelompok
- ✅ **Review**: Membantu review submission
- ✅ **Management**: Mengatur anggota kelompok (add/remove members)
- ❌ **NOT**: Membuat target (ini tanggung jawab dosen)

---

## 📝 Notes

- ✅ Admin tetap memiliki full access (super user)
- ✅ Dosen hanya bisa edit/hapus target yang dia buat sendiri
- ✅ Target yang sudah ada submission tidak bisa diedit/dihapus
- ✅ Koordinator bisa melihat semua targets untuk monitoring
- ✅ System menggunakan 3-layer security untuk robustness

---

## 📞 Support

Jika ada pertanyaan tentang permissions:
1. Cek role user dengan `auth()->user()->role`
2. Cek apakah middleware sudah benar di routes
3. Cek kondisi `@if` di blade views
4. Test dengan browser incognito untuk force fresh session

---

**Last Updated:** 7 Oktober 2025  
**Author:** AI Assistant  
**Status:** ✅ Production Ready

