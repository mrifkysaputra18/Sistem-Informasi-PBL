# 📊 Fitur Tracking Submission Target Mingguan

**Tanggal:** 7 Oktober 2025  
**Request:** Dosen ingin melihat kelompok siapa saja yang sudah submit target mingguan  
**Status:** ✅ Implemented

---

## 🎯 Tujuan Fitur

Memudahkan **dosen** dan **koordinator** untuk:
1. ✅ Melihat statistik submission secara keseluruhan
2. ✅ Mengetahui kelompok mana saja yang sudah submit
3. ✅ Mengetahui kelompok mana yang belum submit
4. ✅ Melihat progress submission dengan visual yang jelas
5. ✅ Mengetahui siapa yang submit dan kapan submit

---

## 🛠️ Implementasi

### 1. **Controller Update** (`app/Http/Controllers/WeeklyTargetController.php`)

#### Statistik yang Dihitung:
```php
$stats = [
    'total' => ...,           // Total semua target
    'submitted' => ...,       // Target yang sudah submit
    'approved' => ...,        // Target yang disetujui
    'revision' => ...,        // Target yang perlu revisi
    'pending' => ...,         // Target yang belum dikerjakan
    'late' => ...,            // Target yang terlambat
    'submitted_percentage' => ... // Persentase yang sudah submit
];
```

#### Formula Persentase:
```php
submitted_percentage = (submitted + approved + revision) / total * 100
```

---

### 2. **View Update** (`resources/views/targets/index.blade.php`)

#### A. **Summary Cards** (5 Cards)

```
┌──────────────┬──────────────┬──────────────┬──────────────┬──────────────┐
│ Total Target │ Sudah Submit │  Disetujui   │ Perlu Revisi │ Belum Submit │
│      10      │      6       │      3       │      1       │      4       │
│  📋 Gray     │  ✓ Blue      │  ✓✓ Green    │  ✏️ Yellow   │  ⏰ Red      │
└──────────────┴──────────────┴──────────────┴──────────────┴──────────────┘
```

**Informasi yang Ditampilkan:**
- **Total Target**: Total semua target (semua status)
- **Sudah Submit**: Status "submitted" (baru submit, belum direview)
- **Disetujui**: Status "approved" (sudah direview & disetujui)
- **Perlu Revisi**: Status "revision" (perlu perbaikan)
- **Belum Submit**: Status "pending" + "late"

#### B. **Progress Bar**

```
┌─────────────────────────────────────────────────────────────────┐
│ Progress Submission                                         60% │
│ ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │
│ 6 dari 10 kelompok sudah submit target                         │
└─────────────────────────────────────────────────────────────────┘
```

**Features:**
- ✅ Gradient color (blue → green)
- ✅ Percentage display
- ✅ Detailed count text
- ✅ Smooth animation

#### C. **Visual Highlights di Table**

**Row Coloring:**
```php
$rowClass = match($target->submission_status) {
    'submitted' => 'bg-blue-50 border-l-4 border-blue-500',
    'approved' => 'bg-green-50 border-l-4 border-green-500',
    'revision' => 'bg-yellow-50 border-l-4 border-yellow-500',
    'late' => 'bg-orange-50 border-l-4 border-orange-500',
    default => '', // Pending = no highlight
};
```

**Visual Indicators:**
- ✅ **Check circle icon** (hijau) untuk submitted/approved/revision
- ⚠️ **Warning icon** (orange) untuk late
- ⏰ **Clock icon** (gray) untuk pending

**Additional Info di Table:**
1. **Kelompok Column:**
   - Nama kelompok (bold)
   - Nama kelas
   - **"Submit oleh: [Nama Mahasiswa]"** (biru) ✨

2. **Status Column:**
   - Status badge
   - **Waktu submit** (format: dd/mm/yyyy HH:mm) ✨
   - **Relative time** (e.g., "3 hours ago") ✨

---

## 🎨 Tampilan Visual

### Header Dashboard
```
┌─────────────────────────────────────────────────────────────────┐
│  Kelola Target Mingguan                    [+ Buat Target Baru] │
└─────────────────────────────────────────────────────────────────┘
```

### Summary Cards
```
┌─────────────────────────────────────────────────────────────────┐
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐│
│  │📋       │  │✓        │  │✓✓       │  │✏️       │  │⏰       ││
│  │Total    │  │Sudah    │  │Disetujui│  │Perlu    │  │Belum    ││
│  │Target   │  │Submit   │  │         │  │Revisi   │  │Submit   ││
│  │   10    │  │    6    │  │    3    │  │    1    │  │    4    ││
│  │─────────│  │─────────│  │─────────│  │─────────│  │─────────││
│  └─────────┘  └─────────┘  └─────────┘  └─────────┘  └─────────┘│
└─────────────────────────────────────────────────────────────────┘
```

### Progress Bar
```
┌─────────────────────────────────────────────────────────────────┐
│ Progress Submission                                         60% │
│ ████████████████████████░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░░ │
│ 6 dari 10 kelompok sudah submit target                         │
└─────────────────────────────────────────────────────────────────┘
```

### Table dengan Highlight
```
┌──────────────────────────────────────────────────────────────────┐
│ Target          │ Kelompok      │ Minggu │ Status              │
├──────────────────────────────────────────────────────────────────┤
│ ✓ ERD           │ Kelompok 1    │   1    │ ✓ Sudah Submit      │ ← BLUE BG
│   IF 4A         │               │        │ 07/10/2025 11:22    │
│                 │ Submit: Rifky │        │ 3 jam yang lalu     │
├──────────────────────────────────────────────────────────────────┤
│ ✓ Use Case      │ Kelompok 2    │   1    │ ✓✓ Disetujui        │ ← GREEN BG
│   IF 4A         │               │        │ 06/10/2025 15:30    │
│                 │ Submit: Ahmad │        │ 20 jam yang lalu    │
├──────────────────────────────────────────────────────────────────┤
│ ⏰ Class Diagram│ Kelompok 3    │   1    │ Belum Dikerjakan    │ ← WHITE BG
│   IF 4A         │               │        │                     │
└──────────────────────────────────────────────────────────────────┘
```

---

## 📊 Data Flow

### 1. **Load Page**
```
Dosen → GET /targets
    ↓
WeeklyTargetController@index()
    ↓
Query all targets dengan filter (kelas, minggu, status)
    ↓
Calculate statistics:
- Count total targets
- Count by status (submitted, approved, revision, pending, late)
- Calculate percentage
    ↓
Return view dengan $targets, $classRooms, $stats
    ↓
View menampilkan:
- Summary cards (5 cards)
- Progress bar
- Filter form
- Table dengan highlights
```

### 2. **Filter by Status**
```
Dosen → Pilih filter "Sudah Submit"
    ↓
GET /targets?status=submitted
    ↓
Query filter: WHERE submission_status = 'submitted'
    ↓
Stats tetap dihitung dari total (tidak terfilter)
    ↓
Table hanya menampilkan yang submitted
    ↓
Dosen bisa lihat daftar lengkap kelompok yang sudah submit
```

---

## 🎯 Use Cases

### Use Case 1: **Monitoring Overall Progress**
**Actor:** Dosen  
**Goal:** Melihat berapa persen kelompok yang sudah submit

**Flow:**
1. Dosen login & akses "Target Mingguan"
2. Lihat summary cards di bagian atas
3. Lihat progress bar dengan persentase
4. Dapat gambaran cepat tanpa scroll

**Result:** 
```
Total: 10 target
Sudah Submit: 6 target (60%)
Belum Submit: 4 target (40%)
```

### Use Case 2: **Lihat Kelompok yang Sudah Submit**
**Actor:** Dosen  
**Goal:** Mengetahui kelompok mana saja yang sudah submit

**Flow:**
1. Dosen akses "Target Mingguan"
2. Scroll ke table
3. Lihat row dengan **background biru/hijau/kuning** = sudah submit
4. Lihat row dengan **background putih** = belum submit
5. Bisa lihat "Submit oleh: [Nama]" di kolom kelompok
6. Bisa lihat waktu submit di kolom status

**Result:**
```
✓ Kelompok 1 - Submit oleh: Rifky - 3 jam yang lalu
✓ Kelompok 2 - Submit oleh: Ahmad - 20 jam yang lalu
⏰ Kelompok 3 - Belum submit
```

### Use Case 3: **Filter Hanya yang Sudah Submit**
**Actor:** Dosen  
**Goal:** Lihat daftar lengkap kelompok yang sudah submit untuk review

**Flow:**
1. Dosen akses "Target Mingguan"
2. Pilih filter Status: "Sudah Submit"
3. Klik "Filter"
4. Table hanya menampilkan yang sudah submit
5. Dosen bisa review satu per satu

**Result:**
```
Menampilkan 6 dari 10 target
(Hanya yang status = submitted)
```

### Use Case 4: **Filter by Kelas & Minggu**
**Actor:** Dosen  
**Goal:** Lihat progress submission untuk kelas & minggu tertentu

**Flow:**
1. Dosen akses "Target Mingguan"
2. Pilih filter Kelas: "IF 4A"
3. Pilih filter Minggu: "1"
4. Klik "Filter"
5. Stats dan table update sesuai filter

**Result:**
```
Stats untuk IF 4A - Minggu 1:
Total: 5 target
Sudah Submit: 3 (60%)
Belum Submit: 2 (40%)
```

---

## 🚀 Fitur-Fitur

### ✅ Real-time Stats
- Total target
- Sudah submit
- Disetujui
- Perlu revisi
- Belum submit
- Persentase completion

### ✅ Visual Indicators
- **Color-coded rows** untuk status yang berbeda
- **Icon indicators** (✓ / ⚠️ / ⏰)
- **Border-left highlight** untuk submitted targets
- **Progress bar** dengan gradient

### ✅ Detailed Info
- Nama kelompok
- Nama kelas
- **Siapa yang submit** (nama mahasiswa) ✨
- **Kapan submit** (tanggal & waktu) ✨
- **Relative time** (e.g., "3 hours ago") ✨

### ✅ Filter Options
- Filter by kelas
- Filter by minggu
- Filter by status
- Stats auto-update sesuai filter

### ✅ Responsive Design
- Cards stack on mobile
- Table scrollable horizontal
- Icons responsive

---

## 📱 Responsive Behavior

### Desktop (> 768px):
```
┌──────┬──────┬──────┬──────┬──────┐
│ Card │ Card │ Card │ Card │ Card │
└──────┴──────┴──────┴──────┴──────┘
```

### Mobile (< 768px):
```
┌──────┐
│ Card │
├──────┤
│ Card │
├──────┤
│ Card │
├──────┤
│ Card │
├──────┤
│ Card │
└──────┘
```

---

## 🧪 Testing Checklist

### ✅ Display Tests
- [x] Summary cards menampilkan angka yang benar
- [x] Progress bar menampilkan persentase yang benar
- [x] Row highlight sesuai dengan status
- [x] Icon muncul sesuai status
- [x] Info "Submit oleh" muncul untuk yang sudah submit
- [x] Waktu submit ditampilkan dengan benar
- [x] Relative time akurat (e.g., "3 hours ago")

### ✅ Filter Tests
- [x] Filter by kelas → Stats & table update
- [x] Filter by minggu → Stats & table update
- [x] Filter by status → Table update (stats tetap)
- [x] Kombinasi filter bekerja
- [x] Reset filter kembali ke all

### ✅ Edge Cases
- [x] Tidak ada target → Empty state
- [x] Semua sudah submit → 100% progress
- [x] Tidak ada yang submit → 0% progress
- [x] Target tanpa completed_by → Tidak crash
- [x] Target tanpa completed_at → Tidak crash

---

## 📊 Statistics Formula

```php
// Total target (sesuai filter kelas & minggu)
$total = WeeklyTarget::...->count();

// Submitted (baru submit, belum review)
$submitted = WeeklyTarget::where('submission_status', 'submitted')->count();

// Approved (sudah direview & disetujui)
$approved = WeeklyTarget::where('submission_status', 'approved')->count();

// Revision (perlu perbaikan)
$revision = WeeklyTarget::where('submission_status', 'revision')->count();

// Pending (belum dikerjakan)
$pending = WeeklyTarget::where('submission_status', 'pending')->count();

// Late (terlambat)
$late = WeeklyTarget::where('submission_status', 'late')->count();

// Persentase yang sudah submit
$submitted_percentage = ($submitted + $approved + $revision) / $total * 100;

// Belum submit
$not_submitted = $pending + $late;
```

---

## 🎨 Color Scheme

| Status | Card Border | Row Background | Badge | Meaning |
|--------|-------------|----------------|-------|---------|
| **Total** | Gray | - | Gray | All targets |
| **Submitted** | Blue | Blue-50 | Blue | Baru submit |
| **Approved** | Green | Green-50 | Green | Disetujui |
| **Revision** | Yellow | Yellow-50 | Yellow | Perlu revisi |
| **Pending** | Red | White | Gray | Belum dikerjakan |
| **Late** | Red | Orange-50 | Orange | Terlambat |

---

## 📝 Notes

1. **Stats Scope**: 
   - Stats dihitung berdasarkan filter kelas & minggu
   - Stats **TIDAK** terfilter by status (tetap menampilkan total)

2. **Submission Info**:
   - "Submit oleh" diambil dari `completedByUser` relationship
   - "Waktu submit" diambil dari `completed_at` field
   - Relative time menggunakan Carbon `diffForHumans()`

3. **Performance**:
   - Query optimized dengan `with()` untuk eager loading
   - Stats dihitung sekali (cloned query)
   - Pagination untuk table (20 items per page)

4. **Permissions**:
   - Dosen: Full access
   - Koordinator: Full access (monitoring)
   - Admin: Full access

---

## 📁 Files Changed

| File | Changes |
|------|---------|
| `app/Http/Controllers/WeeklyTargetController.php` | Added statistics calculation |
| `resources/views/targets/index.blade.php` | Added summary cards, progress bar, highlights |

---

## ✅ Status

| Feature | Status |
|---------|:------:|
| Summary cards | ✅ |
| Progress bar | ✅ |
| Row highlights | ✅ |
| Icon indicators | ✅ |
| Submit info | ✅ |
| Time display | ✅ |
| Filters | ✅ |
| Responsive | ✅ |
| Testing | ✅ |

---

**Status:** ✅ **PRODUCTION READY**  
**Last Updated:** 7 Oktober 2025

Dosen sekarang bisa dengan mudah melihat:
1. ✅ Berapa kelompok yang sudah submit (dengan persentase)
2. ✅ Kelompok mana saja yang sudah submit (visual highlight)
3. ✅ Siapa yang submit dan kapan (detail info)
4. ✅ Progress overall dengan progress bar yang jelas! 🎉

