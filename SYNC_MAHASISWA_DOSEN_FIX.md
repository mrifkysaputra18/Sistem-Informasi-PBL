# 🔄 FIX SINKRONISASI MAHASISWA & DOSEN

## ❌ MASALAH SEBELUMNYA

Dosen tidak bisa melihat submission mahasiswa di menu Target Review karena:

1. **Field `is_completed` tidak ter-update** saat mahasiswa submit
2. **Query di dosen mencari `is_completed = true`** tapi mahasiswa submit tidak set field ini
3. **Disconnect antara WeeklyProgress dan WeeklyTarget**

---

## ✅ SOLUSI YANG DITERAPKAN

### **1. Update WeeklyProgressController.php**

**File:** `app/Http/Controllers/WeeklyProgressController.php`

**Sebelum:**
```php
$target->update([
    'submission_status' => 'submitted',
    'submitted_at' => now(),
]);
```

**Sesudah:**
```php
$target->update([
    'submission_status' => 'submitted',
    'submitted_at' => now(),
    'is_completed' => true,  // ✅ SET is_completed
    'completed_at' => now(),
    'completed_by' => auth()->id(),
    'evidence_files' => $evidencePaths,
    'submission_notes' => $validated['description'] ?? null,
    'is_checked_only' => $request->is_checked_only ?? false,
]);
```

---

### **2. Update WeeklyTargetReviewController.php**

**File:** `app/Http/Controllers/WeeklyTargetReviewController.php`

**Sebelum:**
```php
$pendingTargets = WeeklyTarget::with(['group.classRoom', 'group.members'])
    ->where('is_completed', true)
    ->where('is_reviewed', false)
    ->orderBy('completed_at', 'asc')
    ->paginate(20);
```

**Sesudah:**
```php
$pendingTargets = WeeklyTarget::with(['group.classRoom', 'group.members', 'completedByUser'])
    ->whereIn('submission_status', ['submitted', 'late'])  // ✅ Lebih akurat
    ->where('is_reviewed', false)
    ->orderBy('completed_at', 'desc')  // ✅ Terbaru dulu
    ->paginate(20);
```

---

### **3. Debug Command**

**File:** `app/Console/Commands/DebugWeeklyTargets.php`

Command untuk debugging:
```bash
php artisan debug:weekly-targets
```

Output menunjukkan:
- Status weekly_targets
- Status weekly_progress
- Targets pending review

---

## 🧪 CARA TESTING

### **Step 1: Login Sebagai Dosen**
```
URL: http://localhost:8000
Email: dosen@politala.ac.id
Password: password
```

1. Buka menu **"Review Target Mingguan"**
2. Pastikan list kosong jika belum ada submission baru

---

### **Step 2: Login Sebagai Mahasiswa**
```
URL: http://localhost:8000/login
Email: mahasiswa@politala.ac.id
Password: password
```

1. Buka **Dashboard Mahasiswa**
2. Lihat **"Target Mingguan"**
3. Pilih target dengan status **"Belum Dikerjakan"**
4. Klik **"Upload Progress"**
5. Isi form:
   - Title: (auto-filled)
   - Description: "Testing submission sync"
   - Upload file atau centang "Selesai tanpa file"
6. Klik **"Submit Progress"**
7. Cek log:
   ```bash
   tail -f storage/logs/laravel.log
   ```
   Harus ada log:
   ```
   [INFO] Weekly target updated with submission
         target_id: X
         user_id: Y
   ```

---

### **Step 3: Kembali ke Dosen**

1. Refresh halaman **"Review Target Mingguan"**
2. Submission mahasiswa **HARUS MUNCUL** di list
3. Klik **"Review & Nilai"**
4. Isi review dan nilai
5. Submit review

---

### **Step 4: Verify Database**

Check database langsung:
```sql
-- Check weekly_targets yang pending review
SELECT 
    id,
    title,
    week_number,
    submission_status,
    is_completed,
    is_reviewed,
    completed_at
FROM weekly_targets
WHERE submission_status IN ('submitted', 'late')
  AND is_reviewed = 0;

-- Check weekly_progress
SELECT 
    id,
    title,
    week_number,
    status,
    submitted_at
FROM weekly_progress
ORDER BY submitted_at DESC
LIMIT 5;
```

---

## 🔍 DEBUG JIKA MASIH TIDAK MUNCUL

### **1. Check Log**
```bash
# Check Laravel log
tail -100 storage/logs/laravel.log | grep -i "weekly target"

# Check error log
tail -50 storage/logs/laravel.log | grep -i "error"
```

### **2. Run Debug Command**
```bash
php artisan debug:weekly-targets
```

Output harus menunjukkan:
```
🔔 TARGETS PENDING REVIEW:
   Found X target(s) pending review:
   +----+-------+------+-------+---------------+------------------+
   | ID | Group | Week | Title | Submitted By  | Submitted At     |
   +----+-------+------+-------+---------------+------------------+
   | X  | ...   | 1    | ...   | Mahasiswa     | 2025-10-13 ...   |
   +----+-------+------+-------+---------------+------------------+
```

### **3. Manual Database Check**
```bash
php artisan tinker
```

```php
// Check targets
$targets = \App\Models\WeeklyTarget::whereIn('submission_status', ['submitted', 'late'])
    ->where('is_reviewed', false)
    ->with('group', 'completedByUser')
    ->get();

echo "Found: " . $targets->count() . " targets\n";

foreach ($targets as $t) {
    echo "ID: {$t->id}, Title: {$t->title}, Status: {$t->submission_status}, Completed: " . ($t->is_completed ? 'Yes' : 'No') . "\n";
}

// Check progress
$progress = \App\Models\WeeklyProgress::where('status', 'submitted')->get();
echo "\nProgress count: " . $progress->count() . "\n";
```

### **4. Check Routes**
```bash
php artisan route:list | grep target-review
```

Harus ada:
```
GET|HEAD  target-reviews .............. target-reviews.index › WeeklyTargetReviewController@index
GET|HEAD  target-reviews/{weeklyTarget} target-reviews.show › WeeklyTargetReviewController@show
```

---

## 📊 DATA FLOW DIAGRAM

```
┌─────────────────────────────────────────────────────────────┐
│              MAHASISWA SUBMIT TARGET                        │
│  Route: POST /weekly-progress/store                         │
│  Controller: WeeklyProgressController@store                 │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│         1. INSERT ke weekly_progress table                  │
│            - status = 'submitted'                           │
│            - submitted_at = now()                           │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│         2. UPDATE weekly_targets table                      │
│            ✅ submission_status = 'submitted'               │
│            ✅ submitted_at = now()                          │
│            ✅ is_completed = true         ← FIX!           │
│            ✅ completed_at = now()                          │
│            ✅ completed_by = user_id                        │
│            ✅ evidence_files = [...]                        │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│              DOSEN BUKA MENU REVIEW                         │
│  Route: GET /target-reviews                                 │
│  Controller: WeeklyTargetReviewController@index             │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│         QUERY ke weekly_targets table                       │
│  WHERE submission_status IN ('submitted', 'late')           │
│    AND is_reviewed = false                                  │
│  ORDER BY completed_at DESC                                 │
└────────────────────┬────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────┐
│         TAMPILKAN LIST SUBMISSIONS                          │
│  - Kelompok                                                 │
│  - Minggu                                                   │
│  - Target Title                                             │
│  - Diselesaikan (tanggal)                                   │
│  - Tombol "Review & Nilai"                                  │
└─────────────────────────────────────────────────────────────┘
```

---

## 🎯 EXPECTED RESULTS

### **Mahasiswa Side:**
1. ✅ Bisa upload progress
2. ✅ File masuk ke Google Drive
3. ✅ Status berubah jadi "Sudah Submit"
4. ✅ Data masuk ke `weekly_progress` table
5. ✅ Data masuk ke `weekly_targets` table dengan `is_completed = true`

### **Dosen Side:**
1. ✅ Menu "Review Target Mingguan" menampilkan submissions
2. ✅ Bisa klik "Review & Nilai"
3. ✅ Bisa lihat detail submission
4. ✅ Bisa lihat file yang diupload
5. ✅ Bisa memberikan nilai dan feedback

---

## 📝 CHECKLIST

Sebelum testing, pastikan:
- [x] Code sudah di-update (WeeklyProgressController)
- [x] Code sudah di-update (WeeklyTargetReviewController)
- [x] Cache sudah di-clear: `php artisan cache:clear`
- [x] View sudah di-clear: `php artisan view:clear`
- [x] Config sudah di-clear: `php artisan config:clear`
- [x] Server Laravel running: `php artisan serve`
- [ ] Ada target yang belum di-submit untuk testing
- [ ] Browser sudah di-refresh (Ctrl+F5)

---

## 💡 TIPS

1. **Gunakan Incognito Mode** untuk testing multi-role (dosen & mahasiswa)
2. **Buka 2 browser berbeda** (Chrome untuk mahasiswa, Edge untuk dosen)
3. **Monitor log real-time**:
   ```bash
   tail -f storage/logs/laravel.log
   ```
4. **Check database real-time** dengan TablePlus atau phpMyAdmin

---

## 🐛 KNOWN ISSUES & SOLUTIONS

### Issue 1: Target tidak muncul di dosen
**Solution:** Check `is_completed` dan `submission_status` di database

### Issue 2: File tidak terupload
**Solution:** Check Google Drive config dan permissions

### Issue 3: Error saat submit
**Solution:** Check `activities` field nullable di migration

---

**Last Updated:** 13 Oktober 2025  
**Status:** ✅ Fixed & Ready for Testing
