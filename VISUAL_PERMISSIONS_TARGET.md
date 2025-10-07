# 🎨 Visual Guide: Permissions Target Mingguan

**Update:** 7 Oktober 2025

---

## 🔄 Perubahan Sebelum vs Sesudah

```
╔═══════════════════════════════════════════════════════════════════╗
║                          SEBELUM UPDATE                           ║
╠═══════════════════════════════════════════════════════════════════╣
║                                                                   ║
║   👨‍🏫 DOSEN          → ✅ Buat/Edit/Hapus Target                   ║
║   👨‍💼 KOORDINATOR    → ✅ Buat/Edit/Hapus Target                   ║
║   🔴 ADMIN          → ✅ Buat/Edit/Hapus Target                   ║
║                                                                   ║
║   ⚠️ MASALAH: Koordinator tidak perlu membuat target             ║
║                                                                   ║
╚═══════════════════════════════════════════════════════════════════╝

╔═══════════════════════════════════════════════════════════════════╗
║                         SESUDAH UPDATE                            ║
╠═══════════════════════════════════════════════════════════════════╣
║                                                                   ║
║   👨‍🏫 DOSEN          → ✅ Buat/Edit/Hapus Target                   ║
║   👨‍💼 KOORDINATOR    → ❌ Hanya Lihat & Review                    ║
║   🔴 ADMIN          → ✅ Buat/Edit/Hapus Target (Full Access)     ║
║                                                                   ║
║   ✅ SOLUSI: Clear separation of responsibilities                ║
║                                                                   ║
╚═══════════════════════════════════════════════════════════════════╝
```

---

## 👥 Role-Based Access Visualization

```
┌─────────────────────────────────────────────────────────────────┐
│                      PERMISSION MATRIX                          │
├─────────────────┬──────────┬──────────┬──────────────┬──────────┤
│     AKSI        │  DOSEN   │  ADMIN   │ KOORDINATOR  │ MHASISWA │
├─────────────────┼──────────┼──────────┼──────────────┼──────────┤
│ Buat Target     │    ✅    │    ✅    │      ❌      │    ❌    │
│ Edit Target     │    ✅    │    ✅    │      ❌      │    ❌    │
│ Hapus Target    │    ✅    │    ✅    │      ❌      │    ❌    │
│ Lihat Target    │    ✅    │    ✅    │      ✅      │ ✅ (own) │
│ Review Target   │    ✅    │    ✅    │      ✅      │    ❌    │
│ Submit Target   │    ❌    │    ❌    │      ❌      │    ✅    │
└─────────────────┴──────────┴──────────┴──────────────┴──────────┘
```

---

## 🎭 User Journey untuk Setiap Role

### 👨‍🏫 **DOSEN** - Pembuat Target

```
┌─────────────────────────────────────────────────────────────────┐
│  1. Login sebagai Dosen                                         │
│     ↓                                                           │
│  2. Akses "Target Mingguan"                                     │
│     ↓                                                           │
│  3. 🔵 Lihat tombol "Buat Target Baru" ✅                       │
│     ↓                                                           │
│  4. Pilih kelompok & buat target                                │
│     ↓                                                           │
│  5. Target dibuat untuk kelompok                                │
│     ↓                                                           │
│  6. Bisa edit/hapus (sebelum submission) ✅                     │
│     ↓                                                           │
│  7. Review submission mahasiswa ✅                              │
└─────────────────────────────────────────────────────────────────┘
```

### 👨‍💼 **KOORDINATOR** - Monitor Only

```
┌─────────────────────────────────────────────────────────────────┐
│  1. Login sebagai Koordinator                                   │
│     ↓                                                           │
│  2. Akses "Target Mingguan"                                     │
│     ↓                                                           │
│  3. 🟣 TIDAK ada tombol "Buat Target Baru" ❌                   │
│     ↓                                                           │
│  4. Hanya bisa lihat list targets (monitoring)                  │
│     ↓                                                           │
│  5. Bisa lihat detail target ✅                                 │
│     ↓                                                           │
│  6. Bisa review submission ✅                                   │
│     ↓                                                           │
│  7. TIDAK bisa edit/hapus target ❌                             │
└─────────────────────────────────────────────────────────────────┘
```

### 🔴 **ADMIN** - Super User

```
┌─────────────────────────────────────────────────────────────────┐
│  1. Login sebagai Admin                                         │
│     ↓                                                           │
│  2. Akses "Target Mingguan"                                     │
│     ↓                                                           │
│  3. 🔴 Lihat tombol "Buat Target Baru" ✅                       │
│     ↓                                                           │
│  4. FULL ACCESS - bisa semua ✅✅✅                              │
│     ↓                                                           │
│  5. Bisa edit target SIAPAPUN ✅                                │
│     ↓                                                           │
│  6. Override semua permissions (untuk emergency)                │
└─────────────────────────────────────────────────────────────────┘
```

### 🟢 **MAHASISWA** - Submit Only

```
┌─────────────────────────────────────────────────────────────────┐
│  1. Login sebagai Mahasiswa                                     │
│     ↓                                                           │
│  2. Akses "Target Saya"                                         │
│     ↓                                                           │
│  3. Lihat target yang dibuat dosen                              │
│     ↓                                                           │
│  4. Submit/Complete target ✅                                   │
│     ↓                                                           │
│  5. Upload evidence files ✅                                    │
│     ↓                                                           │
│  6. TIDAK bisa buat/edit/hapus target ❌                        │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🔐 Security Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                     3-LAYER SECURITY                            │
└─────────────────────────────────────────────────────────────────┘

Layer 1: ROUTE MIDDLEWARE
┌─────────────────────────────────────────────────────────────────┐
│  Route::middleware(['role:dosen,admin'])                        │
│                                                                 │
│  Koordinator → /targets/create → ❌ 403 Forbidden              │
│  Dosen       → /targets/create → ✅ Allowed                    │
└─────────────────────────────────────────────────────────────────┘
                            ↓
Layer 2: CONTROLLER AUTHORIZATION
┌─────────────────────────────────────────────────────────────────┐
│  if ($target->created_by !== auth()->id() && !isAdmin()) {     │
│      abort(403);                                                │
│  }                                                              │
│                                                                 │
│  Dosen A edit target Dosen B → ❌ Blocked                      │
│  Dosen A edit target Dosen A → ✅ Allowed                      │
│  Admin edit target Dosen A   → ✅ Allowed                      │
└─────────────────────────────────────────────────────────────────┘
                            ↓
Layer 3: VIEW CONDITIONALS
┌─────────────────────────────────────────────────────────────────┐
│  @if(in_array(auth()->user()->role, ['dosen', 'admin']))       │
│      <button>Buat Target</button>                               │
│  @endif                                                         │
│                                                                 │
│  Koordinator → Button TIDAK terlihat ❌                        │
│  Dosen       → Button terlihat ✅                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📱 UI Changes - Before & After

### Header Section

#### ❌ BEFORE (Koordinator bisa lihat tombol)
```
┌─────────────────────────────────────────────────────────────────┐
│  Kelola Target Mingguan                [+ Buat Target Baru]     │
└─────────────────────────────────────────────────────────────────┘
     ↑ Koordinator bisa lihat & klik - CONFUSING!
```

#### ✅ AFTER (Koordinator tidak lihat tombol)
```
┌─────────────────────────────────────────────────────────────────┐
│  Kelola Target Mingguan                                         │
└─────────────────────────────────────────────────────────────────┘
     ↑ Koordinator hanya lihat title - CLEAN!

┌─────────────────────────────────────────────────────────────────┐
│  Kelola Target Mingguan                [+ Buat Target Baru]     │
└─────────────────────────────────────────────────────────────────┘
     ↑ Dosen & Admin lihat tombol - CLEAR!
```

### Action Buttons

#### ❌ BEFORE (Koordinator lihat semua tombol)
```
┌─────────────────────────────────────────────────────────────────┐
│  Target: Minggu 1                                               │
│  [Detail] [Edit] [Hapus] [Review]                               │
└─────────────────────────────────────────────────────────────────┘
     ↑ Koordinator klik Edit → 403 Error - BAD UX!
```

#### ✅ AFTER (Koordinator hanya lihat yang relevan)
```
┌─────────────────────────────────────────────────────────────────┐
│  KOORDINATOR VIEW:                                              │
│  Target: Minggu 1                                               │
│  [Detail] [Review]                                              │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│  DOSEN VIEW:                                                    │
│  Target: Minggu 1                                               │
│  [Detail] [Edit] [Hapus] [Review]                               │
└─────────────────────────────────────────────────────────────────┘
     ↑ Dosen lihat semua tombol - GOOD UX!
```

### Empty State

#### ❌ BEFORE (Koordinator bingung)
```
┌─────────────────────────────────────────────────────────────────┐
│             📋 Belum ada target mingguan                        │
│                                                                 │
│         Silakan buat target mingguan untuk kelompok             │
│                                                                 │
│              [+ Buat Target Pertama]                            │
└─────────────────────────────────────────────────────────────────┘
     ↑ Koordinator klik → 403 Error - CONFUSING!
```

#### ✅ AFTER (Koordinator paham rolenya)
```
┌─────────────────────────────────────────────────────────────────┐
│  KOORDINATOR VIEW:                                              │
│             📋 Belum ada target mingguan                        │
│                                                                 │
│         Target mingguan akan dibuat oleh dosen                  │
└─────────────────────────────────────────────────────────────────┘
     ↑ Clear message - GOOD UX!

┌─────────────────────────────────────────────────────────────────┐
│  DOSEN VIEW:                                                    │
│             📋 Belum ada target mingguan                        │
│                                                                 │
│         Silakan buat target mingguan untuk kelompok             │
│                                                                 │
│              [+ Buat Target Pertama]                            │
└─────────────────────────────────────────────────────────────────┘
     ↑ Tombol tersedia - CLEAR!
```

---

## 🎯 Flow Diagram: Create Target

```
┌─────────────────────────────────────────────────────────────────┐
│                 FLOW: SIAPA BISA BUAT TARGET?                   │
└─────────────────────────────────────────────────────────────────┘

                    User Login
                        │
        ┌───────────────┼───────────────┬───────────────┐
        │               │               │               │
    👨‍🏫 Dosen      👨‍💼 Koordinator  🔴 Admin     🟢 Mahasiswa
        │               │               │               │
        ↓               ↓               ↓               ↓
   Akses /targets  Akses /targets  Akses /targets  Akses /my-targets
        │               │               │               │
        ↓               ↓               ↓               ↓
   ✅ Bisa akses   ✅ Bisa akses   ✅ Bisa akses   ✅ Bisa akses
        │               │               │               │
        ↓               ↓               ↓               ↓
   Lihat tombol    TIDAK lihat     Lihat tombol    TIDAK lihat
   "Buat Target"   "Buat Target"   "Buat Target"   "Buat Target"
        │               │               │               │
        ↓               ↓               ↓               ↓
   Klik tombol     Hanya lihat     Klik tombol     Hanya submit
        │           list targets        │           existing targets
        ↓                               ↓
   /targets/create              /targets/create
        │                               │
        ↓                               ↓
   ✅ Form muncul              ✅ Form muncul
        │                               │
        ↓                               ↓
   Submit form                  Submit form
        │                               │
        ↓                               ↓
   ✅ Target dibuat            ✅ Target dibuat
        │                               │
        ↓                               ↓
   Bisa edit/hapus             Bisa edit/hapus
   target sendiri              target siapapun


    KOORDINATOR mencoba akses /targets/create:
                    │
                    ↓
            ❌ 403 Forbidden
            (Blocked by middleware)
```

---

## 📊 Statistics & Impact

```
┌─────────────────────────────────────────────────────────────────┐
│                    PERMISSION CHANGES                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Routes Updated:        2 groups (split from 1)                 │
│  View Updates:          3 conditionals added                    │
│  Security Layers:       3 layers implemented                    │
│  Roles Affected:        Koordinator (restricted)                │
│  Backward Compatible:   Yes (admin + dosen still full access)   │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## ✅ Quality Checklist

```
┌────────┬──────────────────────────────────┬────────────────────┐
│ STATUS │ ITEM                             │ DETAIL             │
├────────┼──────────────────────────────────┼────────────────────┤
│   ✅   │ Routes separated                 │ 2 middleware groups│
│   ✅   │ Views updated                    │ 3 conditionals     │
│   ✅   │ Security implemented             │ 3 layers           │
│   ✅   │ Documentation created            │ 3 MD files         │
│   ✅   │ Linter check passed              │ No errors          │
│   ✅   │ Route validation done            │ 31 routes listed   │
│   ✅   │ Backward compatible              │ Yes                │
│   ✅   │ Production ready                 │ Yes                │
└────────┴──────────────────────────────────┴────────────────────┘
```

---

## 🎓 Key Takeaways

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│  1️⃣  DOSEN      → Pembuat & pengelola target                   │
│  2️⃣  ADMIN      → Full access (emergency/override)             │
│  3️⃣  KOORDINATOR → Monitor & review only                       │
│  4️⃣  MAHASISWA  → Submit & complete only                       │
│                                                                 │
│  ✅ Clear separation of responsibilities                        │
│  ✅ Better user experience                                      │
│  ✅ Improved security                                           │
│  ✅ Educational logic preserved                                 │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

**Created:** 7 Oktober 2025  
**Status:** ✅ Production Ready  
**Version:** 1.0

