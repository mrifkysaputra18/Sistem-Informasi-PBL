# 🧭 Dynamic Navigation System - Sistem Informasi PBL

## ✅ COMPLETED IMPLEMENTATION

Navigation menu sekarang **dynamic** berdasarkan role user yang login!

---

## 📱 Navigation Structure by Role

### 🔴 ADMIN
**Menu yang terlihat:**
- Dashboard
- **Mata Kuliah** (Admin only!)
- Kelas
- Kelompok
- Kriteria
- **Nilai & Ranking** (dengan tombol "Hitung Ulang Ranking")

**Special Features:**
- ✅ Tombol "Hitung Ulang Ranking" visible (orange button)
- ✅ Tombol "Input Nilai" visible
- ✅ Access ke semua menu

---

### 🟣 KOORDINATOR
**Menu yang terlihat:**
- Dashboard
- Kelas (view)
- Kelompok (can manage members)
- Kriteria (view)
- **Nilai & Ranking** (view only, NO calculate button)

**Special Features:**
- ✅ Dapat manage group members
- ✅ Lihat ranking
- ❌ TIDAK ada tombol "Hitung Ulang Ranking"
- ❌ TIDAK ada tombol "Input Nilai"

---

### 🔵 DOSEN
**Menu yang terlihat:**
- Dashboard
- Kelas (view)
- Kelompok (view)
- Kriteria (view)
- **Nilai & Ranking** (with "Input Nilai" button)

**Special Features:**
- ✅ Tombol "Input Nilai" visible
- ✅ Lihat ranking
- ❌ TIDAK ada tombol "Hitung Ulang Ranking"
- ❌ TIDAK bisa manage members

---

### 🟢 MAHASISWA
**Menu yang terlihat:**
- Dashboard
- **Kelompok Saya** (own group only)

**Special Features:**
- ✅ Hanya lihat kelompok sendiri
- ❌ **TIDAK ADA menu "Nilai & Ranking"**
- ❌ TIDAK ada access ke scores
- ❌ TIDAK bisa lihat peringkat

---

## 🎨 Visual Features

### Role Badge
Setiap user sekarang memiliki **badge role** yang ditampilkan di:
- Navigation dropdown (desktop)
- Mobile menu (responsive)

**Badge Colors:**
- 🔴 **Admin** - Red badge (bg-red-100 text-red-800)
- 🟣 **Koordinator** - Purple badge (bg-purple-100 text-purple-800)
- 🔵 **Dosen** - Blue badge (bg-blue-100 text-blue-800)
- 🟢 **Mahasiswa** - Green badge (bg-green-100 text-green-800)

---

## 🔒 Access Control in Views

### Scores Index (`scores/index.blade.php`)

**Header Buttons:**
```php
@if(auth()->user()->isAdmin())
    <!-- Admin Only: Recalculate Ranking -->
    <button>Hitung Ulang Ranking</button>
@endif

@if(auth()->user()->isDosen() || auth()->user()->isAdmin())
    <!-- Dosen & Admin: Input Score -->
    <a href="scores.create">Input Nilai</a>
@endif
```

**Who can see what:**
| Button | Admin | Koordinator | Dosen | Mahasiswa |
|--------|:-----:|:-----------:|:-----:|:---------:|
| Hitung Ulang Ranking | ✅ | ❌ | ❌ | ❌ |
| Input Nilai | ✅ | ❌ | ✅ | ❌ |
| View Ranking | ✅ | ✅ | ✅ | ❌ |

---

## 📝 Navigation Code Structure

### Desktop Navigation
```blade
@if(auth()->user()->isAdmin())
    <!-- Admin Menu -->
    <x-nav-link href="subjects.index">Mata Kuliah</x-nav-link>
@endif

@if(auth()->user()->isDosen() || auth()->user()->isKoordinator() || auth()->user()->isAdmin())
    <!-- Dosen, Koordinator, Admin Menu -->
    <x-nav-link href="classrooms.index">Kelas</x-nav-link>
    <x-nav-link href="groups.index">Kelompok</x-nav-link>
    <x-nav-link href="criteria.index">Kriteria</x-nav-link>
    <x-nav-link href="scores.index">Nilai & Ranking</x-nav-link>
@endif

@if(auth()->user()->isMahasiswa())
    <!-- Mahasiswa Menu - NO RANKINGS! -->
    <x-nav-link href="mahasiswa.dashboard">Kelompok Saya</x-nav-link>
@endif
```

### Mobile Navigation (Responsive)
Same logic as desktop, using `<x-responsive-nav-link>` component.

---

## 🎯 Key Implementation Points

### 1. **Role Helpers Used**
```php
auth()->user()->isAdmin()       // true/false
auth()->user()->isKoordinator() // true/false
auth()->user()->isDosen()       // true/false
auth()->user()->isMahasiswa()   // true/false
```

### 2. **Menu Visibility Logic**
- Admin: Sees everything
- Koordinator + Dosen + Admin: See classes, groups, criteria, scores
- Mahasiswa: Only see own group (NO scores/rankings)

### 3. **Button Visibility Logic**
- "Hitung Ulang Ranking": Admin only
- "Input Nilai": Admin + Dosen
- "Kelola Anggota": Admin + Koordinator

---

## 🧪 Testing Checklist

### Navigation Menu:
- [ ] Admin sees "Mata Kuliah" menu
- [ ] Koordinator does NOT see "Mata Kuliah"
- [ ] Mahasiswa does NOT see "Nilai & Ranking"
- [ ] Role badge shows correct color
- [ ] Mobile menu shows correct items

### Scores Page:
- [ ] Admin sees "Hitung Ulang Ranking" button
- [ ] Koordinator does NOT see "Hitung Ulang Ranking"
- [ ] Dosen sees "Input Nilai" button
- [ ] Koordinator does NOT see "Input Nilai"
- [ ] Mahasiswa gets 403 when trying to access

### Dashboard Redirect:
- [ ] Admin → /admin/dashboard
- [ ] Koordinator → /koordinator/dashboard
- [ ] Dosen → /dosen/dashboard
- [ ] Mahasiswa → /mahasiswa/dashboard

---

## 📁 Files Modified

### Navigation Files:
- ✅ `resources/views/layouts/navigation.blade.php`
  - Added role-based menu visibility
  - Added role badge to user dropdown
  - Updated responsive menu

### Scores Files:
- ✅ `resources/views/scores/index.blade.php`
  - Added admin-only "Hitung Ulang Ranking" button
  - Restricted "Input Nilai" to admin + dosen

---

## 🚀 What's Next?

### Immediate Priority:
1. ✅ Navigation Menu - **DONE!**
2. ⏳ Update Group Edit page - Hide member management for non-coordinators
3. ⏳ Create Subject CRUD views
4. ⏳ Create Weekly Target views

### Future Enhancements:
- Add notification indicators
- Add quick actions in navigation
- Add search functionality
- Add breadcrumbs

---

## 💡 Usage Examples

### Check role in any view:
```blade
@if(auth()->user()->isAdmin())
    <p>This is visible to admin only</p>
@endif

@if(auth()->user()->isDosen() || auth()->user()->isAdmin())
    <button>Input Score</button>
@endif

@unless(auth()->user()->isMahasiswa())
    <a href="{{ route('scores.index') }}">View Rankings</a>
@endunless
```

### Show different content per role:
```blade
@if(auth()->user()->isAdmin())
    <h1>Admin Dashboard</h1>
@elseif(auth()->user()->isKoordinator())
    <h1>Koordinator Dashboard</h1>
@elseif(auth()->user()->isDosen())
    <h1>Dosen Dashboard</h1>
@else
    <h1>Mahasiswa Dashboard</h1>
@endif
```

---

## 🎨 Style Guide

### Role Badge Classes:
```html
<!-- Admin -->
<span class="bg-red-100 text-red-800">Admin</span>

<!-- Koordinator -->
<span class="bg-purple-100 text-purple-800">Koordinator</span>

<!-- Dosen -->
<span class="bg-blue-100 text-blue-800">Dosen</span>

<!-- Mahasiswa -->
<span class="bg-green-100 text-green-800">Mahasiswa</span>
```

### Button Colors by Action:
- **Primary Action** (Input Nilai): Purple (bg-purple-500)
- **Admin Action** (Hitung Ulang): Orange (bg-orange-500)
- **Success Action**: Green (bg-green-500)
- **Danger Action**: Red (bg-red-500)

---

**Last Updated:** 2025-10-01  
**Status:** Complete ✅  
**Impact:** All users now see appropriate menu based on their role


