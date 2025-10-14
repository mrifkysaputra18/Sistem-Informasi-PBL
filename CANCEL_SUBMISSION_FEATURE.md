# ❌ FITUR BATALKAN SUBMISSION MAHASISWA

## 📋 OVERVIEW

Fitur ini memungkinkan mahasiswa untuk **membatalkan submission** yang sudah diupload jika ada kesalahan file atau ingin mengubah submission **SEBELUM**:
- ⏰ Deadline terlewati
- 👨‍🏫 Dosen mereview submission

---

## ✨ FITUR

### **1. Batalkan Submission**
- ✅ Mahasiswa bisa cancel submission yang sudah diupload
- ✅ File yang diupload akan **DIHAPUS** (Google Drive & Local)
- ✅ Status kembali ke **"Belum Dikerjakan"**
- ✅ Mahasiswa bisa upload ulang dari awal
- ✅ History submission dihapus

### **2. Syarat Bisa Batalkan**
Mahasiswa hanya bisa batalkan jika **SEMUA** kondisi ini terpenuhi:
- ✅ Status: **Submitted** atau **Late** (sudah submit)
- ✅ **Belum direview** oleh dosen
- ✅ **Belum melewati deadline**
- ✅ Target **masih terbuka** (is_open = true)

### **3. Visual Indicators**
- ⚠️ **Info Box Kuning** - Prominent warning box di atas submission
- 🔴 **Tombol Merah dengan Pulse** - Animate pulse effect
- ⏰ **Badge "Dapat dibatalkan"** - Di header submission
- 📝 **Deadline info** - Kapan batas waktu cancel

---

## 🎯 CARA MENGGUNAKAN

### **Step 1: Login Mahasiswa**
```
http://localhost:8000/login
Email: mahasiswa@politala.ac.id
Password: password
```

### **Step 2: Buka Target yang Sudah Disubmit**
1. Dashboard → Target Mingguan
2. Pilih target yang **sudah disubmit**
3. Klik **"Lihat Detail"**

### **Step 3: Check Info Box Kuning**
Jika bisa cancel, akan muncul **info box kuning** di atas submission:

```
┌──────────────────────────────────────────────────────┐
│ ⚠️ Anda dapat membatalkan submission ini             │
│                                                       │
│ Jika Anda salah upload file atau ingin mengubah      │
│ submission, Anda dapat membatalkan dan submit ulang  │
│ sebelum:                                             │
│  • Deadline: 20/10/2025 16:14                        │
│  • Target direview oleh dosen                        │
│                                                       │
│  [Batalkan Submission & Upload Ulang]                │
└──────────────────────────────────────────────────────┘
```

### **Step 4: Klik "Batalkan Submission"**
Ada 2 tempat tombol:
1. **Di Info Box** (atas) - Orange/Red gradient button
2. **Di Action Buttons** (bawah) - Red button dengan pulse

### **Step 5: Konfirmasi**
Akan muncul konfirmasi dialog:
```
⚠️ PERHATIAN!

Apakah Anda yakin ingin membatalkan submission ini?

❌ Yang akan terjadi:
- File yang diupload akan DIHAPUS
- Status kembali ke Belum Dikerjakan
- Anda harus upload ulang dari awal

✅ Lanjutkan batalkan submission?

[OK]  [Cancel]
```

### **Step 6: Submit Ulang**
Setelah cancel berhasil:
1. ✅ Status kembali ke **"Belum Dikerjakan"**
2. ✅ File terhapus dari Google Drive / Local
3. ✅ Bisa klik **"Submit Target"** lagi
4. ✅ Upload file yang benar
5. ✅ Submit ulang

---

## 🎨 UI DESIGN

### **Info Box (Yellow Warning)**
```css
Background: bg-yellow-50
Border: border-l-4 border-yellow-400
Icon: fa-info-circle (yellow)
Button: Orange to Red gradient with shadow
```

### **Badge Status**
```
Dapat dibatalkan    → Yellow badge (⏰)
Sudah direview     → Green badge (✅)
Tidak dapat dibatalkan → Gray badge (🔒)
```

### **Cancel Button**
```css
Colors: from-orange-500 to-red-500
Animation: animate-pulse (attention-grabbing)
Icon: fa-times-circle
Effect: Shadow-lg, hover scale
```

---

## 🔧 TECHNICAL IMPLEMENTATION

### **Model Method: `canCancelSubmission()`**

**File:** `app/Models/WeeklyTarget.php`

```php
public function canCancelSubmission(): bool
{
    // Hanya bisa cancel jika sudah submit tapi belum direview
    if (!in_array($this->submission_status, ['submitted', 'late'])) {
        return false;
    }

    // Tidak bisa cancel jika sudah direview
    if ($this->is_reviewed) {
        return false;
    }

    // Tidak bisa cancel jika target sudah ditutup
    if ($this->isClosed()) {
        return false;
    }

    // Tidak bisa cancel jika deadline sudah lewat
    if ($this->deadline && now()->gt($this->deadline)) {
        return false;
    }

    return true;
}
```

**Logic Tree:**
```
Is Submitted? (status = submitted/late)
  ↓ NO → Cannot Cancel
  ↓ YES
Is Reviewed?
  ↓ YES → Cannot Cancel
  ↓ NO
Is Closed?
  ↓ YES → Cannot Cancel
  ↓ NO
Deadline Passed?
  ↓ YES → Cannot Cancel
  ↓ NO
✅ CAN CANCEL!
```

---

### **Controller Method: `cancelSubmission()`**

**File:** `app/Http/Controllers/WeeklyTargetSubmissionController.php`

```php
public function cancelSubmission(WeeklyTarget $target)
{
    // 1. Authorization check
    $user = auth()->user();
    $isMember = $target->group->members()->where('user_id', $user->id)->exists();
    
    if (!$isMember) {
        abort(403);
    }

    // 2. Validation
    if (!$target->canCancelSubmission()) {
        return redirect()->back()->with('error', 'Tidak dapat dibatalkan');
    }

    // 3. Delete files from Google Drive / Local
    $evidenceFiles = $target->evidence_files ?? [];
    foreach ($evidenceFiles as $file) {
        if (isset($file['file_id'])) {
            // Delete from Google Drive
            $this->googleDriveService->deleteFile($file['file_id']);
        } elseif (isset($file['local_path'])) {
            // Delete from local storage
            $fullPath = storage_path('app/public/' . $file['local_path']);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    // 4. Reset target to pending state
    $target->update([
        'submission_status' => 'pending',
        'submission_notes' => null,
        'evidence_files' => null,
        'is_checked_only' => false,
        'is_completed' => false,
        'completed_at' => null,
        'completed_by' => null,
        'submitted_at' => null,
    ]);

    return redirect()->route('mahasiswa.dashboard')
        ->with('success', 'Submission berhasil dibatalkan!');
}
```

**Process Flow:**
```
1. Check Authorization (is member?)
   ↓
2. Check canCancelSubmission()
   ↓
3. Loop through evidence_files
   ├─ Google Drive file? → Delete via API
   └─ Local file? → Delete via unlink()
   ↓
4. Reset target fields to pending state
   ↓
5. Redirect to dashboard with success message
```

---

### **Route**

**File:** `routes/web.php`

```php
// Cancel submission (DELETE method)
Route::delete('targets/{target}/cancel', 
    [WeeklyTargetSubmissionController::class, 'cancelSubmission'])
    ->name('targets.submissions.cancel');
```

**Method:** `DELETE`  
**URL:** `/targets/{id}/cancel`  
**Middleware:** `auth`, `role:mahasiswa`

---

### **View Components**

**File:** `resources/views/targets/submissions/show.blade.php`

**1. Info Box (Above Submission)**
```blade
@if($target->canCancelSubmission())
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
    <div class="flex">
        <i class="fas fa-info-circle text-yellow-600"></i>
        <div class="ml-3">
            <h3>Anda dapat membatalkan submission ini</h3>
            <p>Jika salah upload file...</p>
            <button>Batalkan Submission & Upload Ulang</button>
        </div>
    </div>
</div>
@endif
```

**2. Status Badge (In Submission Header)**
```blade
@if($target->canCancelSubmission())
    <span class="badge yellow">⏰ Dapat dibatalkan</span>
@elseif($target->isReviewed())
    <span class="badge green">✅ Sudah direview</span>
@else
    <span class="badge gray">🔒 Tidak dapat dibatalkan</span>
@endif
```

**3. Cancel Button (In Actions Section)**
```blade
@if($target->canCancelSubmission())
<form action="{{ route('targets.submissions.cancel', $target->id) }}" 
      method="POST"
      onsubmit="return confirm('⚠️ PERHATIAN! ...')">
    @csrf
    @method('DELETE')
    <button class="btn-red-gradient animate-pulse">
        <i class="fas fa-times-circle"></i>
        Batalkan Submission
    </button>
</form>
@endif
```

---

## 🧪 TESTING SCENARIOS

### **Scenario 1: Cancel Submission Before Deadline**
```
GIVEN mahasiswa sudah submit target dengan file
  AND deadline belum lewat
  AND dosen belum review
WHEN mahasiswa buka detail target
THEN muncul info box kuning "Dapat dibatalkan"
  AND muncul tombol "Batalkan Submission"
WHEN mahasiswa klik tombol cancel
  AND confirm dialog
THEN submission dibatalkan
  AND file terhapus dari Google Drive/Local
  AND status kembali ke "Belum Dikerjakan"
  AND mahasiswa bisa submit ulang
```

### **Scenario 2: Cannot Cancel After Review**
```
GIVEN mahasiswa sudah submit target
  AND dosen sudah review target
WHEN mahasiswa buka detail target
THEN TIDAK muncul info box kuning
  AND TIDAK muncul tombol "Batalkan Submission"
  AND muncul badge "Sudah direview"
  AND muncul info "Target sudah direview dosen"
```

### **Scenario 3: Cannot Cancel After Deadline**
```
GIVEN mahasiswa sudah submit target
  AND deadline sudah lewat
  AND dosen belum review
WHEN mahasiswa buka detail target
THEN TIDAK muncul tombol "Batalkan Submission"
  AND muncul info "Deadline sudah lewat"
```

### **Scenario 4: Cannot Cancel if Target Closed**
```
GIVEN mahasiswa sudah submit target
  AND dosen tutup target manually (is_open = false)
WHEN mahasiswa buka detail target
THEN TIDAK muncul tombol "Batalkan Submission"
  AND muncul info "Target sudah ditutup"
```

### **Scenario 5: Cancel & Re-submit**
```
GIVEN mahasiswa cancel submission successfully
WHEN mahasiswa buka detail target lagi
THEN status = "Belum Dikerjakan"
  AND muncul tombol "Submit Target"
WHEN mahasiswa klik "Submit Target"
  AND upload file baru
  AND submit
THEN submission berhasil dengan file baru
  AND file lama sudah terhapus
```

---

## 📊 DATABASE CHANGES

### **Before Cancel:**
```
weekly_targets:
  id: 3
  submission_status: 'submitted'
  is_completed: true
  completed_at: '2025-10-13 16:30:00'
  completed_by: 4
  evidence_files: [{file_id: '...', file_name: 'laporan.pdf'}]
  submission_notes: 'Sudah selesai'
  is_reviewed: false
```

### **After Cancel:**
```
weekly_targets:
  id: 3
  submission_status: 'pending'     ← Changed
  is_completed: false              ← Changed
  completed_at: null               ← Changed
  completed_by: null               ← Changed
  evidence_files: null             ← Changed
  submission_notes: null           ← Changed
  is_reviewed: false
```

---

## 📝 LOGGING

### **Success Log:**
```
[INFO] Submission cancelled successfully
       target_id: 3
       user_id: 4
       files_deleted: 2
```

### **File Deletion Log:**
```
[INFO] File deleted from Google Drive
       file_id: 1AbCdEf...
       file_name: laporan.pdf

[INFO] File deleted from local storage
       path: weekly-progress/evidence/filename.pdf
```

### **Error Log:**
```
[ERROR] Failed to delete file during cancellation
        error: File not found in Google Drive
        file: {...}
```

---

## 🐛 ERROR HANDLING

### **Error 1: Cannot Cancel (Deadline Passed)**
```php
if ($target->deadline && now()->gt($target->deadline)) {
    return redirect()->back()
        ->with('error', 'Submission tidak dapat dibatalkan. Deadline sudah lewat.');
}
```

**User sees:** Flash error message

### **Error 2: File Deletion Failed**
```php
try {
    $this->googleDriveService->deleteFile($file['file_id']);
} catch (\Exception $e) {
    \Log::error('Failed to delete file', ['error' => $e->getMessage()]);
    // Continue with other files
}
```

**Behavior:** Continue cancellation even if file delete fails

### **Error 3: Unauthorized**
```php
if (!$isMember) {
    abort(403, 'Anda bukan anggota kelompok ini.');
}
```

**User sees:** 403 Forbidden page

---

## 💡 USE CASES

### **Use Case 1: Salah Upload File**
```
Mahasiswa: "Waduh, saya upload file yang salah!"
Action: Cancel submission → Upload file yang benar → Submit ulang
Result: ✅ File yang benar tersubmit
```

### **Use Case 2: File Corrupt**
```
Mahasiswa: "File nya corrupt, saya mau upload ulang"
Action: Cancel submission → Fix file → Upload ulang
Result: ✅ File yang benar tersubmit
```

### **Use Case 3: Lupa Upload File Penting**
```
Mahasiswa: "Saya lupa upload screenshot, mau tambah file"
Action: Cancel submission → Upload semua file (termasuk yang lupa) → Submit
Result: ✅ Submission lengkap dengan semua file
```

### **Use Case 4: Mau Revisi Sebelum Dosen Review**
```
Mahasiswa: "Saya mau revisi dulu sebelum dosen review"
Action: Cancel submission → Revisi file → Upload ulang
Result: ✅ Submission ter-revisi tersubmit
```

---

## ⚠️ WARNINGS & LIMITATIONS

### **Warnings:**
1. ⚠️ **File akan DIHAPUS** - Tidak bisa recover setelah cancel
2. ⚠️ **Harus upload ulang** - Submit history terhapus
3. ⚠️ **Deadline ketat** - Setelah deadline lewat, tidak bisa cancel
4. ⚠️ **Setelah review** - Jika dosen sudah review, tidak bisa cancel lagi

### **Limitations:**
1. ❌ Tidak bisa cancel jika sudah direview
2. ❌ Tidak bisa cancel jika deadline lewat
3. ❌ Tidak bisa cancel jika target ditutup
4. ❌ Tidak ada "undo" setelah cancel

---

## 📋 CHECKLIST TESTING

Sebelum deploy, pastikan semua ini ✅:

- [ ] Info box kuning muncul jika bisa cancel
- [ ] Badge status muncul di header submission
- [ ] Tombol cancel ada di 2 tempat (info box & actions)
- [ ] Konfirmasi dialog muncul dengan pesan jelas
- [ ] File terhapus dari Google Drive setelah cancel
- [ ] File terhapus dari local storage setelah cancel
- [ ] Status kembali ke "Belum Dikerjakan" setelah cancel
- [ ] Bisa submit ulang setelah cancel
- [ ] Log tercatat untuk audit
- [ ] Tidak bisa cancel setelah deadline
- [ ] Tidak bisa cancel setelah direview
- [ ] Error handling work dengan baik

---

## 🎯 SUMMARY

| Feature | Status |
|---------|--------|
| **Cancel Submission** | ✅ Implemented |
| **File Deletion (Google Drive)** | ✅ Implemented |
| **File Deletion (Local)** | ✅ Implemented |
| **Reset to Pending** | ✅ Implemented |
| **Authorization Check** | ✅ Implemented |
| **Deadline Validation** | ✅ Implemented |
| **Review Status Check** | ✅ Implemented |
| **Visual Indicators** | ✅ Enhanced |
| **Confirmation Dialog** | ✅ Implemented |
| **Logging** | ✅ Implemented |
| **Error Handling** | ✅ Implemented |

---

**Last Updated:** 13 Oktober 2025  
**Version:** 2.0 (Enhanced UI)  
**Status:** ✅ Fully Implemented & Enhanced
