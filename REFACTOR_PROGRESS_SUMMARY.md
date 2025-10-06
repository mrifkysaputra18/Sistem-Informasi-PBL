# 📊 REFACTOR TARGET MINGGUAN - PROGRESS SUMMARY

**Tanggal:** 6 Oktober 2025  
**Status:** ✅ **50% SELESAI** (Backend Done, Views & Routes Next)

---

## ✅ **YANG SUDAH SELESAI:**

### **1. Database Migration** ✅ (10 menit)
```php
// Added to weekly_targets table:
- created_by (foreignId)      → Dosen yang buat target
- deadline (timestamp)         → Deadline submit
- submission_notes (text)      → Catatan mahasiswa saat submit
- submission_status (enum)     → pending|submitted|late|approved|revision
```

**Migration file:** `2025_10_06_084216_add_dosen_fields_to_weekly_targets_table.php`

**Status:** ✅ **MIGRATED SUCCESSFULLY**

---

### **2. WeeklyTarget Model Update** ✅ (15 menit)
```php
// New fillable fields
- created_by, deadline, submission_notes, submission_status

// New relationship
- creator() → Dosen yang buat target

// New helper methods
- isPending()         → Cek status pending
- isSubmitted()       → Cek sudah submit
- isLate()            → Cek submit terlambat
- isOverdue()         → Cek deadline lewat
- getStatusColor()    → Warna badge status
- getStatusLabel()    → Label status Indonesia
```

**Status:** ✅ **MODEL UPDATED**

---

### **3. WeeklyTargetSubmissionController** ✅ (45 menit)
**Purpose:** Controller untuk MAHASISWA submit tugas

**Methods:**
```php
✅ index()              → List target untuk mahasiswa
✅ show()               → Detail target
✅ submitForm()         → Form submit
✅ storeSubmission()    → Save submission (dengan file/checklist)
✅ editSubmission()     → Form edit submission
✅ updateSubmission()   → Update submission
```

**Features:**
- ✅ Upload file ke Google Drive (dengan fallback local storage)
- ✅ Option: Upload file ATAU centang "selesai tanpa file"
- ✅ Auto-detect late submission
- ✅ Permission checking (harus anggota kelompok)
- ✅ Validation (tidak bisa edit setelah direview)

**File:** `app/Http/Controllers/WeeklyTargetSubmissionController.php`

---

### **4. WeeklyTargetController (Refactored)** ✅ (45 menit)
**Purpose:** Controller untuk DOSEN kelola target

**Methods:**
```php
✅ index()          → List semua target (dengan filter)
✅ create()         → Form buat target
✅ store()          → Save target (single/multiple/all class)
✅ show()           → Detail target
✅ edit()           → Form edit target
✅ update()         → Update target
✅ destroy()        → Hapus target
✅ review()         → Form review submission
✅ storeReview()    → Save review (approve/revision)
```

**Features:**
- ✅ Buat target untuk 1 kelompok
- ✅ Buat target untuk multiple kelompok sekaligus
- ✅ Buat target untuk semua kelompok di kelas
- ✅ Filter by kelas, minggu, status
- ✅ Edit target (sebelum ada submission)
- ✅ Review dengan approve/revision
- ✅ Permission checking (hanya creator atau admin)

**File:** `app/Http/Controllers/WeeklyTargetController.php`

---

## 🔄 **YANG SEDANG DIKERJAKAN:**

### **5. Views untuk Dosen** ⏳ (NEXT)
```
resources/views/targets/
├── index.blade.php         → List targets (dosen)
├── create.blade.php        → Form buat target
├── edit.blade.php          → Form edit target
├── show.blade.php          → Detail target + submissions
└── review.blade.php        → Form review submission
```

### **6. Views untuk Mahasiswa** ⏳ (NEXT)
```
resources/views/targets/submissions/
├── index.blade.php         → List targets (mahasiswa)
├── show.blade.php          → Detail target
├── submit.blade.php        → Form submit
└── edit.blade.php          → Form edit submission
```

---

## 📋 **REMAINING TASKS:**

| No | Task | Status | Estimasi |
|----|------|--------|----------|
| 5 | Views Dosen | ⏳ Pending | 1.5 jam |
| 6 | Views Mahasiswa | ⏳ Pending | 1 jam |
| 7 | Update Routes | ⏳ Pending | 30 menit |
| 8 | Update Dashboard | ⏳ Pending | 1 jam |
| 9 | Testing | ⏳ Pending | 30 menit |

**Total remaining:** ~4.5 jam

---

## 🎯 **WORKFLOW SUMMARY:**

### **DOSEN:**
```
1. Login → Dashboard
2. Menu "Kelola Target Mingguan"
3. Click "Buat Target Baru"
4. Form:
   - Pilih tipe: Single Group / Multiple Groups / All Class
   - Pilih kelas (jika all class)
   - Pilih kelompok
   - Minggu ke berapa (1-16)
   - Judul & Deskripsi
   - Deadline
5. Submit → Target dibuat untuk kelompok yang dipilih
6. Menu "Review Submission"
7. Lihat submission mahasiswa
8. Beri review: Approve/Revision dengan feedback
```

### **MAHASISWA:**
```
1. Login → Dashboard
2. Section "Target Mingguan"
3. Lihat list target dari dosen
4. Click target → Detail
5. Click "Submit Target"
6. Form:
   Option A: Upload file (bukti/tugas)
   Option B: Centang "Selesai tanpa file"
   + Catatan (opsional)
7. Submit → Menunggu review dosen
8. Lihat feedback dari dosen
```

---

## 🔧 **TECHNICAL DETAILS:**

### **Submission Status Flow:**
```
pending      → Belum dikerjakan (default)
    ↓
submitted    → Sudah submit tepat waktu
late         → Submit terlambat (after deadline)
    ↓
approved     → Disetujui dosen
revision     → Perlu revisi (mahasiswa bisa re-submit)
```

### **Permission Matrix:**

| Action | Mahasiswa | Dosen | Admin |
|--------|-----------|-------|-------|
| Create Target | ❌ | ✅ | ✅ |
| Edit Target | ❌ | ✅ (before submission) | ✅ |
| Delete Target | ❌ | ✅ (before submission) | ✅ |
| Submit Target | ✅ (anggota) | ❌ | ❌ |
| Edit Submission | ✅ (before review) | ❌ | ❌ |
| Review Submission | ❌ | ✅ | ✅ |

### **File Upload:**
- ✅ Primary: Google Drive (via Service Account)
- ✅ Fallback: Local Storage (public/storage)
- ✅ Max size: 5MB per file
- ✅ Allowed: jpg, jpeg, png, pdf, doc, docx
- ✅ Multiple files supported

---

## 📈 **PROGRESS:**

```
[████████████░░░░░░░░░░] 50%

✅ Database Migration
✅ Model Update
✅ Controllers (Dosen + Mahasiswa)
⏳ Views (Dosen + Mahasiswa)
⏳ Routes
⏳ Dashboard Update
⏳ Testing
```

---

## 🚀 **NEXT STEPS:**

**Sekarang akan lanjut:**
1. ✅ Buat Views untuk Dosen (create, edit, review)
2. ✅ Buat Views untuk Mahasiswa (submit, edit)
3. ✅ Update Routes dengan permission yang benar
4. ✅ Update Dashboard (dosen & mahasiswa)
5. ✅ Testing flow lengkap

**Estimasi completion:** 4-5 jam lagi

---

## 📝 **NOTES:**

### **Changes dari Requirement Lama:**
- ❌ **LAMA:** Mahasiswa buat target sendiri
- ✅ **BARU:** Dosen yang buat target
- ❌ **LAMA:** WeeklyTargetController untuk mahasiswa
- ✅ **BARU:** WeeklyTargetController untuk dosen
- ✅ **BARU:** WeeklyTargetSubmissionController untuk mahasiswa

### **Backward Compatibility:**
- ⚠️ Existing data: Target yang dibuat mahasiswa akan punya `created_by = NULL`
- ⚠️ Views lama perlu diupdate/replace
- ⚠️ Routes lama perlu adjustment

---

## ✅ **TESTING CHECKLIST:**

### **Dosen:**
- [ ] Buat target untuk 1 kelompok
- [ ] Buat target untuk multiple kelompok
- [ ] Buat target untuk semua kelompok di kelas
- [ ] Edit target (sebelum submission)
- [ ] Hapus target (sebelum submission)
- [ ] Review submission dengan approve
- [ ] Review submission dengan revision

### **Mahasiswa:**
- [ ] Lihat list target
- [ ] Submit dengan upload file
- [ ] Submit dengan checklist tanpa file
- [ ] Edit submission (sebelum review)
- [ ] Tidak bisa edit setelah review
- [ ] Lihat feedback dari dosen

### **System:**
- [ ] Permission validation (dosen vs mahasiswa)
- [ ] Late submission detection
- [ ] Google Drive upload
- [ ] Fallback local storage
- [ ] Notifications (optional)

---

**Status:** ✅ **Backend 100% Done!**  
**Next:** Views & Routes  
**Time invested so far:** ~2 jam  
**Time remaining:** ~4-5 jam

🚀 **Ready to continue with Views!**

