# 🔄 REFACTOR: Target Mingguan - Requirement Baru dari Klien

**Tanggal:** 6 Oktober 2025  
**Status:** 📋 **PLANNING & REFACTORING**

---

## 📢 **PERUBAHAN REQUIREMENT DARI KLIEN:**

### **WORKFLOW LAMA (SEBELUMNYA):**
```
Mahasiswa/Kelompok:
  ├─→ Membuat target mingguan sendiri
  ├─→ Upload tugas/bukti
  └─→ Menunggu review dari dosen

Dosen:
  └─→ Hanya me-review target yang dibuat mahasiswa
```

### **WORKFLOW BARU (SEKARANG):** ✅
```
Dosen:
  ├─→ Membuat target mingguan untuk setiap kelompok/kelas
  ├─→ Set judul, deskripsi, minggu ke berapa
  ├─→ Set deadline
  └─→ Review submission mahasiswa

Mahasiswa/Kelompok:
  ├─→ Melihat target yang dibuat dosen
  ├─→ Option 1: Upload tugas/bukti (file)
  ├─→ Option 2: Centang "selesai" tanpa file
  └─→ Menunggu review dari dosen
```

---

## 🎯 **PERBEDAAN UTAMA:**

| Aspek | Lama | Baru |
|-------|------|------|
| **Yang buat target** | Mahasiswa | **Dosen** |
| **Mahasiswa** | Buat + Upload | **Hanya Upload/Centang** |
| **Dosen** | Review saja | **Buat target + Review** |
| **Target mingguan** | Per kelompok | **Per kelompok/kelas** |

---

## 📊 **STRUKTUR DATABASE YANG ADA:**

### **1. WeeklyTarget (Model)**
```php
- group_id
- week_number (minggu ke berapa)
- title (judul target)
- description (deskripsi)
- is_completed (sudah selesai?)
- evidence_files (file bukti - JSON)
- is_checked_only (hanya centang tanpa file?)
- completed_at
- completed_by (mahasiswa yang submit)
- is_reviewed (sudah direview dosen?)
- reviewed_at
- reviewer_id (dosen yang review)
```

**Status:** ✅ **STRUKTUR SUDAH SESUAI!**
- Perlu tambah: `created_by` (dosen yang buat target)
- Perlu tambah: `deadline` (batas waktu submit)

---

### **2. WeeklyProgress (Model)**
```php
- group_id
- week_number
- title
- description
- activities
- achievements
- challenges
- next_week_plan
- documents (JSON)
- status (draft/submitted/reviewed)
- submitted_at
- deadline
- is_locked
- is_checked_only
```

**Status:** ⚠️ **ADA DUPLIKASI dengan WeeklyTarget!**

---

## 🔍 **ANALISIS:**

### **Masalah Saat Ini:**
1. Ada **2 model** yang fungsinya overlap:
   - `WeeklyTarget` - untuk target mingguan
   - `WeeklyProgress` - untuk progress mingguan

2. **WeeklyProgress** sepertinya tidak terpakai atau redundant

3. **WeeklyTarget** sudah punya:
   - Target (title, description)
   - Submission (evidence_files, is_checked_only)
   - Review (is_reviewed, reviewer_id)

### **Keputusan:**
- ✅ **Tetap gunakan WeeklyTarget** untuk semua (target + submission)
- ✅ **WeeklyProgress bisa di-archive atau dipakai untuk hal lain**

---

## 🔧 **REFACTORING PLAN:**

### **FASE 1: Update Database Schema** ✅

#### **Migration: Add fields to weekly_targets**
```php
Schema::table('weekly_targets', function (Blueprint $table) {
    $table->foreignId('created_by')->nullable()
          ->after('group_id')
          ->constrained('users')
          ->comment('Dosen yang membuat target');
    
    $table->timestamp('deadline')->nullable()
          ->after('week_number')
          ->comment('Batas waktu submit');
    
    $table->text('submission_notes')->nullable()
          ->after('description')
          ->comment('Catatan dari mahasiswa saat submit');
});
```

#### **Update Model WeeklyTarget:**
```php
protected $fillable = [
    'group_id',
    'created_by',        // NEW
    'week_number',
    'title',
    'description',
    'deadline',          // NEW
    'submission_notes',  // NEW
    'is_completed',
    'evidence_files',
    'is_checked_only',
    'completed_at',
    'completed_by',
    'is_reviewed',
    'reviewed_at',
    'reviewer_id',
];

// NEW RELATION
public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}
```

---

### **FASE 2: Refactor Controllers** ✅

#### **WeeklyTargetController - Untuk DOSEN:**
```php
// DOSEN: CRUD Target Mingguan
- index()       // List semua target (per kelas/periode)
- create()      // Form buat target baru
- store()       // Save target baru
- edit()        // Form edit target
- update()      // Update target
- destroy()     // Hapus target (jika belum ada submission)
```

#### **NEW: WeeklyTargetSubmissionController - Untuk MAHASISWA:**
```php
// MAHASISWA: Submit/Upload Tugas
- show()        // Lihat detail target
- submit()      // Form submit (upload atau centang)
- storeSubmission()  // Save submission
- editSubmission()   // Edit submission (sebelum direview)
- updateSubmission() // Update submission
```

#### **WeeklyTargetReviewController - Untuk DOSEN:**
```php
// DOSEN: Review Submission
- index()       // List submission yang perlu direview
- show()        // Lihat detail submission
- store()       // Submit review (approval/feedback)
```

---

### **FASE 3: Update Views** ✅

#### **Untuk DOSEN:**
```
resources/views/targets/
├── index.blade.php           // List semua target
├── create.blade.php          // Form buat target baru
├── edit.blade.php            // Form edit target
├── show.blade.php            // Detail target + submissions
└── reviews/
    ├── index.blade.php       // List submissions untuk direview
    └── show.blade.php        // Form review
```

#### **Untuk MAHASISWA:**
```
resources/views/targets/
├── index.blade.php           // List target yang harus dikerjakan
├── show.blade.php            // Detail target
└── submissions/
    ├── submit.blade.php      // Form submit (upload/centang)
    └── edit.blade.php        // Edit submission
```

---

### **FASE 4: Update Routes** ✅

```php
// DOSEN Routes - Kelola Target
Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
    // CRUD Target Mingguan (Dosen membuat)
    Route::resource('targets', WeeklyTargetController::class);
    
    // Review Submissions
    Route::get('targets/{target}/reviews', [WeeklyTargetReviewController::class, 'index']);
    Route::post('targets/{target}/reviews', [WeeklyTargetReviewController::class, 'store']);
});

// MAHASISWA Routes - Submit Tugas
Route::middleware(['role:mahasiswa'])->group(function () {
    // Lihat target
    Route::get('my-targets', [WeeklyTargetSubmissionController::class, 'index']);
    Route::get('targets/{target}', [WeeklyTargetSubmissionController::class, 'show']);
    
    // Submit tugas
    Route::post('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'storeSubmission']);
    Route::put('targets/{target}/submit', [WeeklyTargetSubmissionController::class, 'updateSubmission']);
});
```

---

## 🎨 **UI/UX FLOW:**

### **DOSEN: Membuat Target**
```
1. Dashboard Dosen
   ↓
2. Menu "Kelola Target Mingguan"
   ↓
3. Pilih Kelas (TI-3A, TI-3B, dll)
   ↓
4. Form:
   - Pilih Kelompok (atau "Semua Kelompok di kelas ini")
   - Minggu ke berapa (1-16)
   - Judul target
   - Deskripsi detail
   - Deadline (tanggal + jam)
   ↓
5. Click "Buat Target"
   ↓
6. Target tersimpan untuk kelompok yang dipilih
```

### **MAHASISWA: Submit Tugas**
```
1. Dashboard Mahasiswa
   ↓
2. Section "Target Mingguan"
   - List target dari dosen
   - Status: Belum Dikerjakan / Sudah Submit / Sudah Direview
   ↓
3. Click target tertentu
   ↓
4. Detail Target:
   - Judul
   - Deskripsi
   - Deadline
   - Status
   ↓
5. Form Submit:
   Option A: Upload File (bukti/tugas)
   Option B: Centang "Selesai tanpa file"
   + Catatan (opsional)
   ↓
6. Click "Submit"
   ↓
7. Menunggu review dosen
```

### **DOSEN: Review Submission**
```
1. Dashboard Dosen
   ↓
2. Menu "Review Submission"
   ↓
3. List submission yang perlu direview
   - Kelompok
   - Target
   - Status
   - Tanggal submit
   ↓
4. Click submission
   ↓
5. Detail:
   - Lihat file yang diupload (jika ada)
   - Lihat catatan mahasiswa
   ↓
6. Form Review:
   - Approval (Approve/Revisi)
   - Feedback/Catatan
   - Nilai (opsional)
   ↓
7. Click "Submit Review"
   ↓
8. Mahasiswa mendapat notifikasi
```

---

## 🔗 **SINKRONISASI DENGAN FITUR LAIN:**

### **1. Dashboard**
- ✅ Dosen: Section "Target Mingguan" dengan stats
- ✅ Mahasiswa: Section "Target yang Harus Dikerjakan"
- ✅ Show deadline terdekat
- ✅ Show jumlah belum dikerjakan

### **2. Penilaian (Ranking)**
- ✅ Kecepatan submit target bisa jadi kriteria
- ✅ Keterlambatan deadline bisa jadi penalty
- ✅ Jumlah target selesai tepat waktu

### **3. Notifikasi**
- ✅ Mahasiswa: "Target baru dari dosen"
- ✅ Mahasiswa: "Deadline mendekati"
- ✅ Dosen: "Ada submission baru"
- ✅ Mahasiswa: "Target sudah direview"

### **4. Laporan**
- ✅ Export target per kelas
- ✅ Export submission rate
- ✅ Export keterlambatan

---

## 📋 **TASK BREAKDOWN:**

### **Database (30 menit)**
- [ ] Create migration untuk add fields
- [ ] Update Model WeeklyTarget
- [ ] Test migration

### **Controllers (2 jam)**
- [ ] Refactor WeeklyTargetController (untuk dosen)
- [ ] Create WeeklyTargetSubmissionController (untuk mahasiswa)
- [ ] Create/Update WeeklyTargetReviewController (untuk dosen)

### **Views (3 jam)**
- [ ] Dosen: CRUD target views
- [ ] Mahasiswa: Submission views
- [ ] Dosen: Review views
- [ ] Update dashboard (dosen & mahasiswa)

### **Routes (30 menit)**
- [ ] Update routes untuk dosen
- [ ] Add routes untuk mahasiswa submission
- [ ] Add routes untuk review

### **Testing (1 jam)**
- [ ] Test dosen create target
- [ ] Test mahasiswa submit
- [ ] Test dosen review
- [ ] Test notifications
- [ ] Test permissions

**Total Estimasi: ~7 jam**

---

## ✅ **ACCEPTANCE CRITERIA:**

### **Dosen dapat:**
- [ ] Membuat target mingguan untuk kelompok tertentu
- [ ] Membuat target mingguan untuk semua kelompok di kelas
- [ ] Edit target (sebelum ada submission)
- [ ] Hapus target (sebelum ada submission)
- [ ] Lihat list submission
- [ ] Review submission dengan feedback
- [ ] Export data target & submission

### **Mahasiswa dapat:**
- [ ] Lihat target yang dibuat dosen
- [ ] Submit tugas dengan upload file
- [ ] Submit tugas dengan centang tanpa file
- [ ] Edit submission (sebelum direview)
- [ ] Lihat feedback dari dosen
- [ ] Lihat deadline dan status

### **System:**
- [ ] Validasi permission (dosen vs mahasiswa)
- [ ] Validasi deadline (tidak bisa submit setelah deadline?)
- [ ] Notifikasi otomatis
- [ ] Integration dengan dashboard
- [ ] Integration dengan ranking system

---

## 🚀 **NEXT STEPS:**

**Apa yang ingin dikerjakan PERTAMA?**

1. **Start Refactoring** → Langsung kerjakan migration + controller + views
2. **Review Plan Dulu** → Diskusi apakah plan ini sudah sesuai
3. **Test Fitur Yang Ada** → Test dulu fitur yang sudah ada

**Silakan konfirmasi untuk lanjut!** 🎯

