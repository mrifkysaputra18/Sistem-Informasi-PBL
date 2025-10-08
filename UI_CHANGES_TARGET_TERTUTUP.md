# Perubahan UI: Target Tertutup untuk Mahasiswa

## 📱 Perubahan Tampilan untuk Mahasiswa

### ✅ Yang Telah Diupdate:

1. **Dashboard Mahasiswa** (`/mahasiswa/dashboard`)
2. **Halaman Daftar Target** (`/my-targets`)
3. **Halaman Detail Target** (`/targets/{id}`)

---

## 1️⃣ Dashboard Mahasiswa

### SEBELUM:
```
┌─────────────────────────────────────────┐
│ Target      | Deadline   | Status | Aksi│
│ Use Case    | 07/10/2025 | Pending| [👁 Lihat]│
│             | 23:59      |        |           │
│             | ⚠️ Terlambat|      |           │
└─────────────────────────────────────────┘
```

### SEKARANG:
```
┌─────────────────────────────────────────┐
│ Target      | Deadline   | Status | Aksi│
│ Use Case    | 07/10/2025 | Pending| [👁 Lihat]│
│             | 23:59      |        |           │
│             | 🔒 Tertutup|        |           │  ← BADGE BARU!
└─────────────────────────────────────────┘
```

**Fitur:**
- ✅ Badge merah "🔒 Tertutup" muncul di kolom deadline
- ✅ Badge "Terlambat" diganti dengan "Tertutup" jika target sudah ditutup
- ✅ Visual yang jelas untuk mahasiswa

---

## 2️⃣ Halaman Daftar Target Mahasiswa

### Scenario A: Target Belum Tertutup

```
┌──────────────────────────────────────────────────────┐
│ 📝 Membuat Use Case                                  │
│ Minggu 1 | Deadline: 10/10/2025 23:59               │
│ Status: [⚪ Belum Dikerjakan]                        │
│                                                      │
│ [👁 Lihat Detail] [✅ Submit Target]                │
│                                  [⏰ Deadline Mendekati]│
└──────────────────────────────────────────────────────┘
```

**Tombol Tersedia:**
- ✅ "Lihat Detail"
- ✅ "Submit Target" (hijau)

---

### Scenario B: Target Tertutup (Belum Submit)

```
┌──────────────────────────────────────────────────────┐
│ 📝 Membuat Use Case                                  │
│ Minggu 1 | Deadline: 07/10/2025 23:59               │
│ Status: [⚪ Belum Dikerjakan]                        │
│                                                      │
│ [👁 Lihat Detail] [🔒 Target Tertutup]              │ ← TOMBOL SUBMIT HILANG!
│                                            [🔒 Tertutup]│
└──────────────────────────────────────────────────────┘
```

**Perubahan:**
- ❌ Tombol "Submit Target" **HILANG**
- ✅ Muncul badge "🔒 Target Tertutup" (merah)
- ✅ Badge "Tertutup" di kanan atas

---

### Scenario C: Target Tertutup (Sudah Submit)

```
┌──────────────────────────────────────────────────────┐
│ 📝 Membuat ERD                                       │
│ Minggu 2 | Deadline: 07/10/2025 23:59               │
│ Status: [🔵 Sudah Submit]                           │
│                                                      │
│ ✅ Sudah disubmit: 07/10/2025 20:00                 │
│ Catatan: Sudah selesai                              │
│ File: 2 file                                         │
│                                                      │
│ [👁 Lihat Detail] [🔒 Target Tertutup]              │ ← TOMBOL EDIT HILANG!
└──────────────────────────────────────────────────────┘
```

**Perubahan:**
- ❌ Tombol "Edit Submission" **HILANG**
- ✅ Muncul badge "🔒 Target Tertutup" (merah)
- ✅ Submission tetap tersimpan dan terlihat

---

### Scenario D: Target Perlu Revisi (Tapi Tertutup)

```
┌──────────────────────────────────────────────────────┐
│ 📝 Membuat Sequence Diagram                          │
│ Minggu 3 | Deadline: 06/10/2025 23:59               │
│ Status: [🟡 Perlu Revisi]                           │
│                                                      │
│ ✅ Sudah disubmit: 06/10/2025 22:00                 │
│ 👨‍🏫 Direview oleh: Pak Dosen                        │
│                                                      │
│ [👁 Lihat Detail] [🔒 Target Tertutup]              │ ← TOMBOL REVISI HILANG!
└──────────────────────────────────────────────────────┘
```

**Perubahan:**
- ❌ Tombol "Revisi" **HILANG** (karena tertutup)
- ✅ Badge "🔒 Target Tertutup"
- 💡 Mahasiswa harus minta dosen buka kembali

---

### Scenario E: Target Terbuka Normal (Bisa Edit)

```
┌──────────────────────────────────────────────────────┐
│ 📝 Membuat Class Diagram                             │
│ Minggu 4 | Deadline: 15/10/2025 23:59               │
│ Status: [🔵 Sudah Submit]                           │
│                                                      │
│ ✅ Sudah disubmit: 10/10/2025 14:00                 │
│                                                      │
│ [👁 Lihat Detail] [📝 Edit Submission]              │ ← TOMBOL EDIT TERSEDIA
└──────────────────────────────────────────────────────┘
```

**Normal:**
- ✅ Deadline belum lewat → Target masih terbuka
- ✅ Tombol "Edit Submission" tersedia

---

## 3️⃣ Halaman Detail Target

### Target Tertutup - Tampilan Detail

```
╔════════════════════════════════════════════════════════╗
║  📋 Detail Target: Membuat Use Case                    ║
╚════════════════════════════════════════════════════════╝

┌─ Informasi Target ─────────────────────────────────────┐
│ Judul: Membuat Use Case                                │
│ Minggu: Minggu 1                                       │
│                                                        │
│ Deadline: 07/10/2025 23:59                            │
│ [🔒 Target Tertutup]                                   │ ← BADGE TERTUTUP
│ Target ditutup karena melewati deadline                │ ← ALASAN
└────────────────────────────────────────────────────────┘

┌─ Aksi ────────────────────────────────────────────────┐
│ [⬅️ Kembali]                                           │
│                                                        │
│ ⚠️ Target sudah tertutup. Tidak dapat mensubmit lagi. │ ← PESAN ERROR
│ 💡 Hubungi dosen jika diperlukan.                     │
└────────────────────────────────────────────────────────┘
```

**Fitur:**
- ✅ Badge "🔒 Target Tertutup" (merah)
- ✅ Alasan penutupan ditampilkan
- ✅ Pesan error yang jelas
- ❌ Tombol "Submit Target" **TIDAK MUNCUL**
- ❌ Tombol "Edit Submission" **TIDAK MUNCUL**

---

### Target Terbuka - Tampilan Detail

```
╔════════════════════════════════════════════════════════╗
║  📋 Detail Target: Membuat Class Diagram                ║
╚════════════════════════════════════════════════════════╝

┌─ Informasi Target ─────────────────────────────────────┐
│ Judul: Membuat Class Diagram                           │
│ Minggu: Minggu 4                                       │
│                                                        │
│ Deadline: 15/10/2025 23:59                            │
│ [🔓 Target Terbuka]                                    │ ← BADGE TERBUKA
└────────────────────────────────────────────────────────┘

┌─ Aksi ────────────────────────────────────────────────┐
│ [⬅️ Kembali]      [✅ Submit Target]                  │ ← TOMBOL TERSEDIA
└────────────────────────────────────────────────────────┘
```

**Fitur:**
- ✅ Badge "🔓 Target Terbuka" (hijau)
- ✅ Tombol "Submit Target" tersedia

---

## 🎨 Warna & Styling

### Badge Status Target

| Status | Warna | Background | Icon |
|--------|-------|------------|------|
| 🔓 Terbuka | Hijau | `bg-green-100 text-green-800` | `fa-unlock` |
| 🔒 Tertutup | Merah | `bg-red-100 text-red-800` | `fa-lock` |
| ⚠️ Terlambat | Orange | `bg-orange-100 text-orange-800` | `fa-exclamation-triangle` |
| ⏰ Deadline Mendekati | Orange | `bg-orange-100 text-orange-800` | `fa-clock` |

### Button Status

| Tombol | Kondisi | Warna | Display |
|--------|---------|-------|---------|
| Submit Target | Target Terbuka + Pending | Hijau `bg-green-600` | Tampil |
| Submit Target | Target Tertutup | - | **HILANG** |
| Edit Submission | Target Terbuka + Submitted | Biru `bg-blue-600` | Tampil |
| Edit Submission | Target Tertutup | - | **HILANG** |
| Revisi | Target Terbuka + Revision | Kuning `bg-yellow-600` | Tampil |
| Revisi | Target Tertutup | - | **HILANG** |
| Target Tertutup Badge | Target Tertutup | Merah `bg-red-50 text-red-600` | Tampil |
| Lihat Detail | Selalu | Biru `bg-blue-50 text-blue-600` | Tampil |

---

## 📊 Comparison Table

| Kondisi | Deadline Lewat? | Target Tertutup? | Tombol Submit | Tombol Edit | Badge Tertutup |
|---------|----------------|------------------|---------------|-------------|----------------|
| Normal - Belum Deadline | ❌ | ❌ | ✅ Tampil | ✅ Tampil* | ❌ |
| Lewat Deadline - Belum Submit | ✅ | ✅ | ❌ Hilang | ❌ | ✅ Tampil |
| Lewat Deadline - Sudah Submit | ✅ | ✅ | ❌ | ❌ Hilang | ✅ Tampil |
| Dibuka Kembali Dosen | ✅ | ❌ | ✅ Tampil | ✅ Tampil* | ❌ |
| Sudah Direview | ✅/❌ | ✅ | ❌ | ❌ Hilang | ✅ Tampil |

**Catatan**: *Tombol Edit hanya tampil jika sudah submit dan belum direview

---

## 🔄 User Flow Mahasiswa

### Flow 1: Target Tertutup, Mahasiswa Terlambat

```
Mahasiswa Login
    ↓
Dashboard → Lihat Target
    ↓
Badge [🔒 Tertutup] muncul
    ↓
Klik "Lihat Detail"
    ↓
Tombol Submit TIDAK ADA
Pesan: "Target sudah tertutup"
    ↓
💡 Mahasiswa hubungi dosen
    ↓
Dosen klik "Buka" 🔓
    ↓
Mahasiswa refresh halaman
    ↓
✅ Tombol Submit MUNCUL kembali!
```

### Flow 2: Target Tertutup, Mahasiswa Ingin Edit

```
Mahasiswa sudah submit
    ↓
Deadline lewat → Target tertutup
    ↓
Mahasiswa ingin edit submission
    ↓
Dashboard → Tombol Edit HILANG
    ↓
Badge [🔒 Target Tertutup]
    ↓
Klik "Lihat Detail"
    ↓
Pesan: "Target sudah tertutup. Tidak dapat mengedit lagi."
    ↓
💡 Hubungi dosen
    ↓
Dosen evaluasi → Buka target
    ↓
✅ Mahasiswa bisa edit lagi
```

---

## 🎯 Key Features

### ✅ Yang Berhasil Diimplementasikan:

1. **Badge Tertutup di Dashboard**
   - Muncul badge merah "🔒 Tertutup" di kolom deadline
   - Menggantikan badge "Terlambat" jika target tertutup

2. **Tombol Submit HILANG**
   - Tombol "Submit Target" tidak muncul jika target tertutup
   - Diganti dengan badge "🔒 Target Tertutup"

3. **Tombol Edit HILANG**
   - Tombol "Edit Submission" tidak muncul jika target tertutup
   - Mencegah mahasiswa edit setelah deadline

4. **Tombol Revisi HILANG**
   - Tombol "Revisi" tidak muncul jika target tertutup
   - Mahasiswa harus minta dosen buka kembali

5. **Pesan Error yang Jelas**
   - Tampil pesan: "Target sudah tertutup. Tidak dapat mensubmit lagi."
   - Saran: "Hubungi dosen jika diperlukan"

6. **Visual Feedback**
   - Badge merah untuk tertutup
   - Badge hijau untuk terbuka
   - Icon yang sesuai (lock/unlock)

---

## 📝 Testing Checklist

### Dashboard Mahasiswa
- [ ] Badge "Tertutup" muncul untuk target yang tertutup
- [ ] Badge "Terlambat" muncul untuk target yang belum tertutup tapi lewat deadline
- [ ] Tombol tetap "Lihat Detail" selalu ada

### Halaman Daftar Target
- [ ] Tombol "Submit Target" HILANG jika tertutup
- [ ] Tombol "Edit Submission" HILANG jika tertutup
- [ ] Tombol "Revisi" HILANG jika tertutup
- [ ] Badge "Target Tertutup" muncul
- [ ] Badge "Tertutup" muncul di kanan atas (untuk pending)

### Halaman Detail
- [ ] Badge status tertutup/terbuka tampil
- [ ] Tombol submit/edit TIDAK ADA jika tertutup
- [ ] Pesan error tampil dengan jelas
- [ ] Alasan penutupan ditampilkan

### Validasi Controller
- [ ] Redirect dengan error jika coba akses URL submit langsung
- [ ] Redirect dengan error jika coba akses URL edit langsung

---

## 🚀 Deployment Notes

### Files Yang Diupdate:
- `resources/views/dashboards/mahasiswa.blade.php`
- `resources/views/targets/submissions/index.blade.php`
- `resources/views/targets/submissions/show.blade.php`

### Tidak Perlu:
- ❌ Tidak perlu migration baru
- ❌ Tidak perlu clear cache
- ✅ Langsung bisa digunakan setelah update view

### Cara Test:
```bash
# 1. Tutup target yang sudah lewat deadline
php artisan targets:auto-close

# 2. Login sebagai mahasiswa
# 3. Lihat dashboard → badge tertutup muncul
# 4. Lihat daftar target → tombol submit/edit hilang
# 5. Lihat detail → pesan error tampil
```

---

**Dibuat**: 8 Oktober 2025  
**Update Terakhir**: 8 Oktober 2025

