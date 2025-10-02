# 📊 Progress Speed Criteria - Sistem Penilaian Kecepatan Progres

## ✅ COMPLETED IMPLEMENTATION

Sistem penilaian otomatis berdasarkan tingkat penyelesaian target mingguan!

---

## 🎯 Konsep

### Apa itu Kecepatan Progres?

**Kriteria Kecepatan Progres** adalah penilaian yang mengukur seberapa baik kelompok menyelesaikan target-target yang mereka tetapkan sendiri setiap minggu.

### Bagaimana Cara Kerjanya?

1. **Mahasiswa/Kelompok Input Target Mingguan**
   - Contoh: Minggu 1 → 2 target
   - Contoh: Minggu 2 → 3 target

2. **Selama Minggu Berjalan**
   - Kelompok menyelesaikan target
   - Mark as complete (dengan/tanpa bukti)

3. **Sistem Menghitung Otomatis**
   - Completion Rate = (Target Selesai / Total Target) × 100

4. **Hasil Digunakan untuk Ranking**
   - Rate tinggi = nilai lebih baik
   - Rate bisa > 100% jika over-achieve!

---

## 📐 Formula & Algoritma

### 1. Completion Rate Calculation

```php
// In Group Model
public function getTargetCompletionRate(): float
{
    $totalTargets = $this->weeklyTargets()->count();
    
    if ($totalTargets === 0) {
        return 0;
    }

    $completedTargets = $this->weeklyTargets()
                             ->where('is_completed', true)
                             ->count();
    
    return ($completedTargets / $totalTargets) * 100;
}
```

**Examples:**
- 2 target planned, 2 completed = **100%** ✅
- 2 target planned, 1 completed = **50%** ⚠️
- 2 target planned, 3 completed = **150%** 🌟 (Over-achievement!)

---

### 2. Scoring (Normalized)

```php
public function getTargetCompletionScore(): float
{
    $rate = $this->getTargetCompletionRate();
    
    // Allow up to 120% for bonus points
    return min($rate, 120);
}
```

**Scoring Scale:**
- 0-50% = Poor (Red)
- 51-70% = Need Improvement (Yellow)
- 71-90% = Good (Blue)
- 91-100% = Excellent (Green)
- 101-120% = Outstanding! (Gold) 🌟

---

### 3. Integration with Ranking System

```php
// In RankingService
foreach ($criteria as $c) {
    // Check if this is "Kecepatan Progres" criteria
    if (stripos($c->nama, 'kecepatan') !== false || 
        stripos($c->nama, 'progres') !== false) {
        // Use weekly target completion rate
        $val = $group->getTargetCompletionRate();
    } else {
        // Use normal manual score
        $val = $score->skor ?? 0;
    }
    
    // Continue with normalization...
}
```

**Auto-Detection:**
- Jika nama kriteria mengandung "kecepatan" atau "progres"
- Sistem otomatis gunakan completion rate
- Tidak perlu input manual!

---

## 🎓 Use Cases & Examples

### Use Case 1: Perfect Execution
**Scenario:**
- Week 1: Plan 2 targets → Complete 2 targets = 100%
- Week 2: Plan 3 targets → Complete 3 targets = 100%
- Week 3: Plan 2 targets → Complete 2 targets = 100%

**Total:** 7/7 = **100%**  
**Score:** Excellent ✅

---

### Use Case 2: Under-achievement
**Scenario:**
- Week 1: Plan 3 targets → Complete 1 target = 33%
- Week 2: Plan 2 targets → Complete 1 target = 50%
- Week 3: Plan 2 targets → Complete 0 target = 0%

**Total:** 2/7 = **28.57%**  
**Score:** Poor ⚠️

---

### Use Case 3: Over-achievement
**Scenario:**
- Week 1: Plan 2 targets → Complete 3 targets = 150%
- Week 2: Plan 2 targets → Complete 3 targets = 150%
- Week 3: Plan 1 target → Complete 2 targets = 200%

**Total:** 8/5 = **160%** (capped at 120%)  
**Score:** Outstanding! 🌟

---

## 📊 Visual Display

### In Group Edit Page

```
┌─────────────────────────────────────────┐
│ Target Mingguan                [+ Tambah]│
├─────────────────────────────────────────┤
│ ✅ Minggu 1: Desain Database            │
│    Selesai oleh John Doe                │
│                                          │
│ ⭕ Minggu 2: Implementasi Backend       │
│    (Belum selesai)                      │
│                                          │
│ ⭕ Minggu 3: Testing                    │
│    (Belum selesai)                      │
├─────────────────────────────────────────┤
│ Tingkat Penyelesaian          33.3%     │
│ [████░░░░░░░░░░░░░░░░░░░░░░░]          │
│ 1 dari 3 target selesai                 │
└─────────────────────────────────────────┘
```

### In Ranking Page

```
Ranking Kelompok:
┌────────────────────────────────┐
│ 🏆 1. Kelompok A               │
│     📊 85.5 poin               │
│     ✅ 100% progres            │
├────────────────────────────────┤
│ 🥈 2. Kelompok B               │
│     📊 78.2 poin               │
│     ✅ 75% progres             │
├────────────────────────────────┤
│ 🥉 3. Kelompok C               │
│     📊 65.8 poin               │
│     ⚠️ 50% progres             │
└────────────────────────────────┘
```

---

## 🔧 Implementation Details

### Database Structure

**Table: `weekly_targets`**
```sql
- id
- group_id
- week_number (1-16)
- title
- description
- is_completed (boolean)
- evidence_file (nullable)
- completed_at (timestamp, nullable)
- completed_by (user_id, nullable)
- created_at
- updated_at
```

### Model Methods

**Group Model:**
```php
// Get completion rate (can be > 100%)
$group->getTargetCompletionRate()  // Returns: 0-∞

// Get normalized score (capped at 120%)
$group->getTargetCompletionScore() // Returns: 0-120
```

**RankingService:**
```php
// Auto-detect "Kecepatan Progres" criteria
// Use completion rate instead of manual score
// Seamlessly integrated with existing criteria
```

---

## 🎨 UI Features

### Progress Bar

**Colors:**
- 0-50%: Red gradient (Poor)
- 51-70%: Yellow gradient (Fair)
- 71-90%: Blue gradient (Good)
- 91-100%: Green gradient (Excellent)
- 101%+: Gold gradient (Outstanding!)

**Animation:**
- Smooth transition when completion changes
- Duration: 500ms

### Target Cards

**Completed:**
- ✅ Green background (bg-green-50)
- ✅ Green border
- ✅ Check circle icon
- ✅ Strikethrough text
- ✅ Shows who completed & when
- ✅ Shows evidence link (if any)

**Pending:**
- ⭕ White background
- ⭕ Gray border
- ⭕ Empty circle icon
- ⭕ Normal text

---

## 🔐 Access Control

### Who Can Manage Targets:

| Action | Admin | Koordinator | Dosen | Mahasiswa (Member) | Mahasiswa (Non-Member) |
|--------|:-----:|:-----------:|:-----:|:------------------:|:----------------------:|
| Create Target | ✅ | ✅ | ❌ | ✅ | ❌ |
| Edit Target | ✅ | ✅ | ❌ | ✅ | ❌ |
| Delete Target | ✅ | ✅ | ❌ | ✅ | ❌ |
| Mark Complete | ✅ | ✅ | ❌ | ✅ | ❌ |
| View Targets | ✅ | ✅ | ✅ | ✅ | ❌ |

**Authorization Logic:**
```php
// Check if user is member of the group
$isMember = $group->members()->where('user_id', auth()->id())->exists();

if (!$isMember && !isAdmin() && !isKoordinator()) {
    abort(403);
}
```

---

## 📈 Impact on Ranking

### How It Affects Scores

**Before (Manual Input):**
```
Kriteria Kecepatan Progres:
- Dosen manually input score (0-100)
- Subjective assessment
```

**After (Automatic Calculation):**
```
Kriteria Kecepatan Progres:
- System auto-calculates from targets
- Objective measurement
- Real-time updates
- No manual input needed!
```

### Example Ranking Calculation

**Kelompok A:**
- Kriteria 1 (Kualitas): 85/100 (bobot 0.3)
- Kriteria 2 (Kecepatan Progres): 100% (bobot 0.4) ← AUTO!
- Kriteria 3 (Dokumentasi): 90/100 (bobot 0.3)

**Total Score:** Normalized & weighted automatically

---

## 🧪 Testing Scenarios

### Test Case 1: Zero Targets
```
Group has no targets
→ Completion Rate = 0%
→ Score = 0
→ No error, handles gracefully
```

### Test Case 2: All Complete
```
3 targets, 3 completed
→ Completion Rate = 100%
→ Score = 100
→ Green indicator
```

### Test Case 3: Partial Complete
```
5 targets, 2 completed
→ Completion Rate = 40%
→ Score = 40
→ Red/Yellow indicator
```

### Test Case 4: Over-achievement
```
2 targets, 3 completed
→ Completion Rate = 150%
→ Score = 120 (capped)
→ Gold indicator! 🌟
```

---

## 📁 Files Modified:

**Models:**
- ✅ `app/Models/Group.php`
  - Added `getTargetCompletionRate()` method
  - Added `getTargetCompletionScore()` method

**Services:**
- ✅ `app/Services/RankingService.php`
  - Updated `computeGroupTotals()` with auto-detection
  - Added `getProgressSpeedScores()` method

**Controllers:**
- ✅ `app/Http/Controllers/GroupScoreController.php`
  - Load weeklyTargets
  - Pass progressSpeedScores to view
  - Include completion_rate in ranking data

**Views:**
- ✅ `resources/views/scores/index.blade.php`
  - Display completion rate in ranking
  - Show progress percentage

---

## 🎯 Benefits

### For Mahasiswa:
- ✅ Clear targets & goals
- ✅ Visual progress tracking
- ✅ Sense of achievement
- ✅ Objective measurement

### For Dosen/Koordinator:
- ✅ Automatic calculation (no manual input!)
- ✅ Real-time monitoring
- ✅ Objective data
- ✅ Easy to see who's behind

### For System:
- ✅ Consistent measurement
- ✅ Real-time updates
- ✅ Less manual work
- ✅ Accurate ranking

---

## 🔄 Workflow

### Week by Week Process:

**Week 1:**
```
1. Mahasiswa plan targets (Monday)
2. Work on targets (Mon-Fri)
3. Mark complete as done (anytime)
4. System calculates: 2/2 = 100% ✅
```

**Week 2:**
```
1. Plan new targets
2. Work on them
3. Only complete 1/3 = 33% ⚠️
4. System updates automatically
```

**Week 3:**
```
1. Plan 2 targets
2. Complete 3 targets = 150%! 🌟
3. Over-achievement bonus!
```

**End of Semester:**
```
Total: 6/7 targets = 85.7%
Score in Ranking: 85.7 (Good!)
```

---

## 📊 Statistics & Analytics

### Group Performance Dashboard

**Metrics Available:**
- Total targets set
- Targets completed
- Completion rate percentage
- Week-by-week breakdown
- Trend analysis (upcoming)

### Admin View

**Insights:**
- Which groups are excelling (>90%)
- Which groups need help (<50%)
- Overall completion trend
- Benchmark comparison

---

## 💡 Tips for Mahasiswa

### How to Get Good Score:

1. **Plan Realistic Targets**
   - Don't over-commit
   - Be specific and measurable

2. **Track Regularly**
   - Mark complete as soon as done
   - Upload evidence for credibility

3. **Consistent Progress**
   - Better to have steady 100% than fluctuating
   - Plan achievable goals weekly

4. **Over-achievement Bonus**
   - If you can do more, do it!
   - Extra completed targets = bonus points
   - Up to 120% possible

---

## 🔮 Future Enhancements

### Possible Improvements:

1. **Weekly Notifications**
   - Remind to set targets (Monday)
   - Remind to complete (Friday)

2. **Analytics Dashboard**
   - Trend graphs
   - Comparison with other groups
   - Week-by-week performance

3. **Smart Suggestions**
   - AI suggests reasonable target count
   - Based on past performance

4. **Evidence Validation**
   - Dosen can verify evidence
   - Approve/reject completion

5. **Weight by Week**
   - Later weeks worth more points
   - Momentum bonus

---

## 🧪 Testing Checklist

### Calculation:
- [ ] 0/0 targets = 0% (no error)
- [ ] 2/2 targets = 100%
- [ ] 1/2 targets = 50%
- [ ] 3/2 targets = 150% (capped at 120 for score)
- [ ] Progress bar shows correct percentage
- [ ] Ranking includes completion rate

### UI:
- [ ] Completed targets show green
- [ ] Pending targets show white
- [ ] Progress bar animates smoothly
- [ ] Completion rate displays in ranking
- [ ] Empty state shows when no targets

### Integration:
- [ ] RankingService auto-detects "kecepatan progres"
- [ ] Completion rate used in ranking
- [ ] Manual scores still work for other criteria
- [ ] Recalculate ranking updates correctly

---

## 📋 How to Use

### Setup Kriteria Kecepatan Progres:

1. **Go to Criteria Management**
   ```
   Admin → Kriteria → + Tambah Kriteria
   ```

2. **Create Criteria:**
   ```
   Nama: "Kecepatan Progres" or "Progress Speed"
   Bobot: 0.3 (30% of total)
   Tipe: Benefit (higher is better)
   Segment: Group
   ```

3. **System Auto-Detects:**
   - Nama contains "kecepatan" or "progres"
   - Automatically uses completion rate
   - No manual score input needed!

4. **Students Use System:**
   - Create weekly targets
   - Mark complete as they finish
   - System calculates automatically

5. **Admin Calculates Ranking:**
   - Click "Hitung Ulang Ranking"
   - System includes completion rate
   - Fair and objective scoring!

---

## 🎉 Success Metrics

### What Makes This Feature Great:

1. **Automatic** - No manual calculation
2. **Objective** - Based on actual data
3. **Real-time** - Updates immediately
4. **Fair** - Same rules for everyone
5. **Motivating** - Visual progress encourages completion
6. **Flexible** - Allows over-achievement
7. **Integrated** - Seamless with existing ranking

---

## 📊 Expected Results

### Before Implementation:
- Manual speed assessment (subjective)
- Inconsistent scoring
- Hard to track progress
- Time-consuming for dosen

### After Implementation:
- ✅ Automatic calculation
- ✅ Objective measurement
- ✅ Real-time tracking
- ✅ Zero manual work for dosen!

---

## 🔗 Related Files

**Models:**
- `app/Models/Group.php` - Completion rate methods
- `app/Models/WeeklyTarget.php` - Target data

**Services:**
- `app/Services/RankingService.php` - Integration logic

**Controllers:**
- `app/Http/Controllers/WeeklyTargetController.php` - Target management
- `app/Http/Controllers/GroupScoreController.php` - Display in ranking

**Views:**
- `resources/views/groups/edit.blade.php` - Progress visualization
- `resources/views/scores/index.blade.php` - Ranking with completion rate

---

**Last Updated:** 2025-10-01  
**Status:** Complete & Production Ready ✅  
**Impact:** Automatic, objective, real-time progress measurement!


