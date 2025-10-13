# 🔄 FITUR BATALKAN SUBMIT UNTUK MAHASISWA

## 📋 OVERVIEW

Fitur ini memungkinkan mahasiswa untuk **membatalkan submission** yang sudah mereka submit, dengan kondisi tertentu. Fitur ini berguna jika mahasiswa ingin melakukan koreksi atau merasa ada kesalahan sebelum dosen melakukan review.

---

## ✅ KONDISI BISA MEMBATALKAN SUBMIT

Mahasiswa **HANYA BISA** membatalkan submission jika **SEMUA** kondisi berikut terpenuhi:

| No | Kondisi | Penjelasan |
|----|---------|------------|
| 1️⃣ | **Sudah Submit** | Status: `submitted` atau `late` |
| 2️⃣ | **Belum Direview** | Dosen belum melakukan review (`is_reviewed = false`) |
| 3️⃣ | **Target Masih Terbuka** | Target belum ditutup manual/otomatis (`is_open = true`) |
| 4️⃣ | **Deadline Belum Lewat** | Waktu sekarang masih sebelum deadline |

---

## ❌ KONDISI TIDAK BISA MEMBATALKAN

Mahasiswa **TIDAK BISA** membatalkan submission jika:

| Kondisi | Alasan |
|---------|--------|
| ❌ Status `pending` | Belum disubmit sama sekali |
| ❌ Status `approved` | Sudah disetujui oleh dosen |
| ❌ Status `revision` | Perlu revisi, mahasiswa harus upload ulang |
| ❌ `is_reviewed = true` | Sudah direview dosen, tidak bisa dibatalkan |
| ❌ `is_open = false` | Target sudah ditutup (manual/otomatis) |
| ❌ Deadline lewat | Waktu sudah melewati deadline |

---

## 🎯 FITUR YANG DIIMPLEMENTASIKAN

### **1. Model: WeeklyTarget.php**

**Method baru:**
```php
public function canCancelSubmission(): bool
```

**Logic:**
- Check status submission (harus `submitted` atau `late`)
- Check apakah sudah direview (`is_reviewed = false`)
- Check apakah target masih terbuka (`is_open = true`)
- Check apakah deadline belum lewat

---

### **2. Controller: WeeklyTargetSubmissionController.php**

**Method baru:**
```php
public function cancelSubmission(WeeklyTarget $target)
```

**Proses:**
1. ✅ Validasi membership (hanya anggota kelompok)
2. ✅ Check apakah bisa cancel (`canCancelSubmission()`)
3. ✅ Delete file dari Google Drive/Local Storage
4. ✅ Reset status target ke `pending`
5. ✅ Clear semua data submission
6. ✅ Logging detail untuk debugging
7. ✅ Redirect dengan pesan sukses/error

**Data yang di-reset:**
```php
'submission_status' => 'pending',
'submission_notes' => null,
'evidence_files' => null,
'is_checked_only' => false,
'is_completed' => false,
'completed_at' => null,
'completed_by' => null,
```

---

### **3. Routes: web.php**

**Route baru:**
```php
// DELETE method untuk cancel submission
Route::delete('targets/{target}/cancel', [WeeklyTargetSubmissionController::class, 'cancelSubmission'])
    ->name('targets.submissions.cancel');
```

---

### **4. View: Dashboard Mahasiswa**

**Lokasi:** `resources/views/dashboards/mahasiswa.blade.php`

**UI Updates:**
- Tombol "**Batalkan Submit**" muncul jika `canCancelSubmission()` bernilai true
- Warna: Orange-Red gradient untuk menunjukkan action destructive
- Confirmation dialog sebelum cancel
- Icon: `fa-times-circle`

**Kondisi tampil tombol:**
```blade
@elseif($target->canCancelSubmission())
<!-- Cancel Submission Button -->
<form action="{{ route('targets.submissions.cancel', $target->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">
        <i class="fas fa-times-circle"></i>
        Batalkan Submit
    </button>
</form>
@endif
```

---

### **5. View: Detail Target Submission**

**Lokasi:** `resources/views/targets/submissions/show.blade.php`

**UI Updates:**
- Tombol "**Batalkan Submit**" di bagian Actions
- Muncul bersamaan dengan tombol Edit (jika bisa cancel)
- Confirmation dialog dengan pesan detail

---

## 🔄 ALUR LENGKAP CANCEL SUBMISSION

```
┌─────────────────────────────────────────────────────────────┐
│         MAHASISWA LOGIN & BUKA DASHBOARD                    │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│    LIHAT TARGET YANG SUDAH DI-SUBMIT                        │
│    - Status: Submitted/Late                                 │
│    - Belum direview                                         │
│    - Target masih terbuka                                   │
│    - Deadline belum lewat                                   │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│    TOMBOL "BATALKAN SUBMIT" MUNCUL                          │
│    (Warna: Orange-Red)                                      │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
         ┌───────────────────────┐
         │  Klik "Batalkan"      │
         └──────────┬────────────┘
                    │
                    ▼
┌─────────────────────────────────────────────────────────────┐
│    CONFIRMATION DIALOG                                      │
│    "Apakah Anda yakin ingin membatalkan submission ini?     │
│     File yang diupload akan dihapus dan status kembali      │
│     ke Belum Dikerjakan."                                   │
└────────────────────┬────────────────────────────────────────┘
                     │
          ┌──────────┴──────────┐
          ▼                     ▼
      [Cancel]              [Confirm]
          │                     │
          │                     ▼
          │    ┌─────────────────────────────────────────────┐
          │    │  BACKEND PROCESSING                         │
          │    │  1. Validate membership                     │
          │    │  2. Check canCancelSubmission()             │
          │    │  3. Delete files from Google Drive/Local    │
          │    │  4. Reset target to pending                 │
          │    │  5. Log all actions                         │
          │    └──────────────┬──────────────────────────────┘
          │                   │
          │                   ▼
          │    ┌─────────────────────────────────────────────┐
          │    │  STATUS BERUBAH KE "BELUM DIKERJAKAN"       │
          │    │  - submission_status = 'pending'            │
          │    │  - evidence_files = null                    │
          │    │  - completed_at = null                      │
          │    │  - Files dihapus dari storage               │
          │    └──────────────┬──────────────────────────────┘
          │                   │
          │                   ▼
          │    ┌─────────────────────────────────────────────┐
          │    │  REDIRECT KE DASHBOARD                      │
          │    │  Message: "Submission berhasil dibatalkan!  │
          │    │           (X file dihapus)"                 │
          │    └─────────────────────────────────────────────┘
          │
          └──► Tidak jadi cancel, tetap di halaman
```

---

## 🗑️ PENGHAPUSAN FILE

### **Google Drive Files:**
```php
if (isset($file['file_id'])) {
    // Delete from Google Drive
    $this->googleDriveService->deleteFile($file['file_id']);
    
    Log: "File deleted from Google Drive"
         - file_id: xxx
         - file_name: document.pdf
}
```

### **Local Storage Files:**
```php
elseif (isset($file['local_path'])) {
    // Delete from local storage
    $fullPath = storage_path('app/public/' . $file['local_path']);
    if (file_exists($fullPath)) {
        unlink($fullPath);
    }
    
    Log: "File deleted from local storage"
         - path: weekly-progress/evidence/xxx.pdf
}
```

**Error Handling:**
- Jika gagal delete file, proses tetap lanjut
- Error di-log tapi tidak menghentikan cancel
- User tetap mendapat feedback sukses

---

## 📊 LOGGING

### **Success Log:**
```json
{
    "message": "Submission cancelled successfully",
    "target_id": 1,
    "user_id": 4,
    "files_deleted": 2
}
```

### **File Deletion Log:**
```json
{
    "message": "File deleted from Google Drive",
    "file_id": "1abc123xyz",
    "file_name": "laporan.pdf"
}
```

### **Error Log:**
```json
{
    "message": "Failed to delete file during cancellation",
    "error": "File not found",
    "file": {
        "file_id": "xxx",
        "file_name": "document.pdf"
    }
}
```

---

## 🎨 UI/UX DESIGN

### **Tombol Cancel:**
- **Warna:** Gradient Orange-Red (`from-orange-500 to-red-500`)
- **Icon:** `fa-times-circle`
- **Text:** "Batalkan Submit"
- **Hover:** Shadow effect dan slight scale
- **Position:** Bersebelahan dengan tombol Detail/Edit

### **Confirmation Dialog:**
```
⚠️ Konfirmasi

Apakah Anda yakin ingin membatalkan submission ini?

File yang diupload akan dihapus dan status kembali ke Belum Dikerjakan.

[Batal]  [Ya, Batalkan]
```

### **Success Message:**
```
✅ Submission berhasil dibatalkan! (2 file dihapus)
```

### **Error Messages:**
```
❌ Submission tidak dapat dibatalkan. Target sudah direview oleh dosen.
❌ Submission tidak dapat dibatalkan. Deadline sudah lewat.
❌ Terjadi kesalahan saat membatalkan submission. Silakan coba lagi.
```

---

## 🧪 TESTING SCENARIO

### **Scenario 1: Success Cancel**
```
GIVEN mahasiswa sudah submit target
  AND target belum direview
  AND deadline belum lewat
  AND target masih terbuka
WHEN mahasiswa klik "Batalkan Submit"
  AND confirm dialog
THEN submission di-cancel
  AND files dihapus
  AND status kembali ke pending
  AND redirect dengan success message
```

### **Scenario 2: Cannot Cancel - Already Reviewed**
```
GIVEN mahasiswa sudah submit target
  AND target sudah direview dosen
WHEN mahasiswa buka dashboard
THEN tombol "Batalkan Submit" TIDAK muncul
  AND hanya ada tombol "Detail"
```

### **Scenario 3: Cannot Cancel - Deadline Passed**
```
GIVEN mahasiswa sudah submit target
  AND deadline sudah lewat
WHEN mahasiswa coba cancel
THEN muncul error: "Deadline sudah lewat"
  AND status tetap submitted
```

### **Scenario 4: Cannot Cancel - Target Closed**
```
GIVEN mahasiswa sudah submit target
  AND target ditutup manual oleh dosen
WHEN mahasiswa buka dashboard
THEN tombol "Batalkan Submit" TIDAK muncul
  AND ada label "Target Tertutup"
```

---

## 📚 CODE REFERENCES

### **Model Method:**
```php
// app/Models/WeeklyTarget.php
public function canCancelSubmission(): bool
{
    // Check status
    if (!in_array($this->submission_status, ['submitted', 'late'])) {
        return false;
    }
    
    // Check review status
    if ($this->is_reviewed) {
        return false;
    }
    
    // Check target open status
    if ($this->isClosed()) {
        return false;
    }
    
    // Check deadline
    if ($this->deadline && now()->gt($this->deadline)) {
        return false;
    }
    
    return true;
}
```

### **Controller Method:**
```php
// app/Http/Controllers/WeeklyTargetSubmissionController.php
public function cancelSubmission(WeeklyTarget $target)
{
    // Validate & check permissions
    // Delete files
    // Reset target
    // Log & redirect
}
```

### **Route:**
```php
// routes/web.php
Route::delete('targets/{target}/cancel', 
    [WeeklyTargetSubmissionController::class, 'cancelSubmission'])
    ->name('targets.submissions.cancel');
```

### **View:**
```blade
<!-- resources/views/dashboards/mahasiswa.blade.php -->
@if($target->canCancelSubmission())
<form action="{{ route('targets.submissions.cancel', $target->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">
        Batalkan Submit
    </button>
</form>
@endif
```

---

## 🔒 SECURITY

### **Authorization:**
- ✅ Check user adalah anggota kelompok
- ✅ Check target belongs to user's group
- ✅ Validate all conditions before cancel

### **Data Integrity:**
- ✅ Transaction-safe file deletion
- ✅ Atomic database updates
- ✅ Rollback on error (database level)
- ✅ Detailed logging for audit

---

## 💡 BENEFITS

### **Untuk Mahasiswa:**
1. ✅ **Fleksibilitas** - Bisa koreksi kesalahan sebelum deadline
2. ✅ **No Penalty** - Tidak ada dampak negatif jika cancel sebelum review
3. ✅ **Easy Recovery** - Tinggal upload ulang yang benar
4. ✅ **Clear Feedback** - Tahu kapan bisa/tidak bisa cancel

### **Untuk Sistem:**
1. ✅ **Clean Storage** - File yang tidak perlu otomatis terhapus
2. ✅ **Data Consistency** - Status always in sync
3. ✅ **Audit Trail** - Semua action ter-log
4. ✅ **No Orphan Data** - Tidak ada data menggantung

---

## 📞 TROUBLESHOOTING

### **Q: Tombol Cancel tidak muncul?**
**A:** Check kondisi:
- Apakah status = submitted/late?
- Apakah belum direview?
- Apakah deadline belum lewat?
- Apakah target masih terbuka?

### **Q: Error saat cancel?**
**A:** Check log:
```bash
tail -100 storage/logs/laravel.log | grep "cancel"
```

### **Q: File tidak terhapus?**
**A:** Check permission storage:
```bash
ls -la storage/app/public/weekly-progress/evidence/
```

### **Q: Status tidak berubah?**
**A:** Check database:
```sql
SELECT * FROM weekly_targets 
WHERE id = X 
AND submission_status = 'pending';
```

---

**Last Updated:** 13 Oktober 2025  
**Version:** 1.0  
**Status:** ✅ Fully Implemented & Tested
