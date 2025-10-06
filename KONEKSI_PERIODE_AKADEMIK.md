# 🔗 Koneksi Periode Akademik dengan Semua Komponen

**Status:** ✅ **SEMUA TERKONEKSI!**

---

## 📊 **STRUKTUR KONEKSI:**

```
Periode Akademik (Academic Period)
    │
    ├─→ Subjects (Mata Kuliah)
    │
    ├─→ Class Rooms (Kelas)
    │       │
    │       ├─→ Students (Mahasiswa)
    │       │       └─→ Group Members (via Groups)
    │       │
    │       └─→ Groups (Kelompok)
    │               ├─→ Group Members (Anggota)
    │               ├─→ Weekly Progress (Progress Mingguan)
    │               ├─→ Weekly Targets (Target Mingguan)
    │               ├─→ Group Scores (Nilai Kelompok)
    │               └─→ Group Rubric Scores (Nilai Rubrik)
    │
    └─→ Projects (via Subjects)
```

---

## 🔗 **RELASI YANG SUDAH DIBUAT:**

### **1. Academic Period → Class Rooms**
```php
// AcademicPeriod.php
public function classrooms()
{
    return $this->hasMany(ClassRoom::class);
}
```

**Query:**
```php
$period = AcademicPeriod::find(1);
$classes = $period->classrooms;
```

---

### **2. Academic Period → Groups (Through Classrooms)**
```php
// AcademicPeriod.php
public function groups()
{
    return $this->hasManyThrough(Group::class, ClassRoom::class);
}
```

**Query:**
```php
$period = AcademicPeriod::find(1);
$groups = $period->groups;
```

---

### **3. Academic Period → Students (Through Classrooms)**
```php
// AcademicPeriod.php (BARU!)
public function students()
{
    return $this->hasManyThrough(User::class, ClassRoom::class, ...)
                ->where('users.role', 'mahasiswa');
}
```

**Query:**
```php
$period = AcademicPeriod::find(1);
$students = $period->students;
```

---

### **4. Academic Period → Group Scores**
```php
// AcademicPeriod.php (BARU!)
public function groupScores()
{
    return $this->hasManyThrough(GroupScore::class, Group::class, ...)
                ->whereHas('group.classRoom', ...);
}
```

**Query:**
```php
$period = AcademicPeriod::find(1);
$scores = $period->groupScores;
```

---

### **5. Academic Period → Weekly Progress**
```php
// AcademicPeriod.php (BARU!)
public function weeklyProgress()
{
    return $this->hasManyThrough(WeeklyProgress::class, Group::class, ...)
                ->whereHas('group.classRoom', ...);
}
```

**Query:**
```php
$period = AcademicPeriod::find(1);
$progress = $period->weeklyProgress;
```

---

### **6. Class Room → Academic Period**
```php
// ClassRoom.php
public function academicPeriod()
{
    return $this->belongsTo(AcademicPeriod::class);
}
```

**Query:**
```php
$class = ClassRoom::find(1);
$period = $class->academicPeriod;
```

---

### **7. Group → Academic Period (Through Classroom)**
```php
// Group.php (BARU!)
public function academicPeriod()
{
    return $this->hasOneThrough(AcademicPeriod::class, ClassRoom::class, ...);
}
```

**Query:**
```php
$group = Group::find(1);
$period = $group->academicPeriod;
```

---

### **8. Student → Academic Period (Through Classroom)**
```php
// User.php (BARU!)
public function academicPeriod()
{
    return $this->hasOneThrough(AcademicPeriod::class, ClassRoom::class, ...);
}
```

**Query:**
```php
$student = User::find(1);
$period = $student->academicPeriod;
```

---

## 🎯 **ATTRIBUTE HELPERS (BARU!):**

### **AcademicPeriod:**
```php
$period->total_students  // Total mahasiswa
$period->total_groups    // Total kelompok
$period->total_classes   // Total kelas
```

### **User (Mahasiswa):**
```php
$student->currentGroup()              // Kelompok saat ini
$student->hasGroupInCurrentPeriod()   // Punya kelompok?
$student->academicPeriod              // Periode akademik
```

---

## 📝 **CONTOH PENGGUNAAN:**

### **1. Dapatkan Semua Mahasiswa di Periode Tertentu:**
```php
$period = AcademicPeriod::find(1);
$students = $period->students;

foreach ($students as $student) {
    echo $student->name . " - " . $student->classRoom->name;
}
```

---

### **2. Dapatkan Semua Kelompok di Periode Tertentu:**
```php
$period = AcademicPeriod::getCurrent(); // Periode aktif
$groups = $period->groups;

foreach ($groups as $group) {
    echo $group->name . " (" . $group->classRoom->name . ")";
}
```

---

### **3. Dapatkan Semua Nilai di Periode Tertentu:**
```php
$period = AcademicPeriod::find(1);
$scores = $period->groupScores;

foreach ($scores as $score) {
    echo $score->group->name . ": " . $score->total_score;
}
```

---

### **4. Cek Mahasiswa Punya Kelompok di Periode Aktif:**
```php
$student = User::find(1);

if ($student->hasGroupInCurrentPeriod()) {
    echo "Sudah punya kelompok!";
    $group = $student->currentGroup();
} else {
    echo "Belum punya kelompok";
}
```

---

### **5. Filter Data Berdasarkan Periode:**
```php
// Di Controller
$currentPeriod = AcademicPeriod::getCurrent();

// Semua kelas di periode ini
$classes = $currentPeriod->classrooms;

// Semua kelompok di periode ini
$groups = $currentPeriod->groups;

// Semua mahasiswa di periode ini
$students = $currentPeriod->students;

// Statistik
$stats = [
    'total_classes' => $currentPeriod->total_classes,
    'total_groups' => $currentPeriod->total_groups,
    'total_students' => $currentPeriod->total_students,
];
```

---

## 🎨 **CONTOH DASHBOARD DENGAN FILTER PERIODE:**

```php
// DashboardController.php

public function index(Request $request)
{
    // Ambil periode aktif atau yang dipilih
    $currentPeriod = AcademicPeriod::getCurrent();
    
    if ($request->has('period_id')) {
        $currentPeriod = AcademicPeriod::find($request->period_id);
    }
    
    // Semua periode untuk dropdown
    $periods = AcademicPeriod::orderBy('start_date', 'desc')->get();
    
    // Data untuk dashboard (filtered by period)
    $stats = [
        'total_classes' => $currentPeriod->classrooms()->count(),
        'total_groups' => $currentPeriod->groups()->count(),
        'total_students' => $currentPeriod->students()->count(),
        'avg_score' => $currentPeriod->groupScores()->avg('total_score'),
    ];
    
    $classes = $currentPeriod->classrooms()->with('groups')->get();
    
    return view('dashboard', compact('currentPeriod', 'periods', 'stats', 'classes'));
}
```

---

## 📑 **CONTOH VIEW DENGAN DROPDOWN PERIODE:**

```blade
<!-- Dropdown Pilih Periode -->
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Periode Akademik
    </label>
    <select name="period_id" onchange="this.form.submit()" class="...">
        @foreach($periods as $period)
            <option value="{{ $period->id }}" 
                    {{ $currentPeriod->id == $period->id ? 'selected' : '' }}>
                {{ $period->display_name }}
            </option>
        @endforeach
    </select>
</div>

<!-- Stats untuk periode terpilih -->
<div class="grid grid-cols-4 gap-4">
    <div class="stat-card">
        <h3>Total Kelas</h3>
        <p>{{ $currentPeriod->total_classes }}</p>
    </div>
    <div class="stat-card">
        <h3>Total Kelompok</h3>
        <p>{{ $currentPeriod->total_groups }}</p>
    </div>
    <div class="stat-card">
        <h3>Total Mahasiswa</h3>
        <p>{{ $currentPeriod->total_students }}</p>
    </div>
    <div class="stat-card">
        <h3>Periode</h3>
        <p>{{ $currentPeriod->name }}</p>
    </div>
</div>
```

---

## 🔍 **QUERY EXAMPLES:**

### **Mahasiswa di Periode Tertentu:**
```php
$period = AcademicPeriod::find(1);

// Yang sudah punya kelompok
$withGroup = $period->students()->whereHas('groupMembers')->get();

// Yang belum punya kelompok
$withoutGroup = $period->students()->whereDoesntHave('groupMembers')->get();
```

### **Kelompok dengan Nilai di Periode:**
```php
$period = AcademicPeriod::getCurrent();

$groups = $period->groups()
    ->with(['scores', 'members.user', 'classRoom'])
    ->orderBy('total_score', 'desc')
    ->get();
```

### **Progress Mingguan di Periode:**
```php
$period = AcademicPeriod::find(1);

$progress = $period->weeklyProgress()
    ->with(['group', 'review'])
    ->orderBy('week_number')
    ->get();
```

---

## ✅ **SEMUA KONEKSI:**

| Dari | Ke | Melalui | Method |
|------|----|----|--------|
| **Periode** → Kelas | Direct | - | `classrooms()` |
| **Periode** → Kelompok | Through | ClassRoom | `groups()` |
| **Periode** → Mahasiswa | Through | ClassRoom | `students()` |
| **Periode** → Nilai | Through | Group | `groupScores()` |
| **Periode** → Progress | Through | Group | `weeklyProgress()` |
| **Kelas** → Periode | Direct | - | `academicPeriod()` |
| **Kelompok** → Periode | Through | ClassRoom | `academicPeriod()` |
| **Mahasiswa** → Periode | Through | ClassRoom | `academicPeriod()` |

---

## 🎉 **KESIMPULAN:**

**Semua komponen sekarang TERKONEKSI dengan Periode Akademik!** ✅

Anda bisa:
- ✅ Filter mahasiswa berdasarkan periode
- ✅ Filter kelompok berdasarkan periode
- ✅ Filter nilai berdasarkan periode
- ✅ Filter progress berdasarkan periode
- ✅ Lihat statistik per periode
- ✅ Switch antar periode dengan mudah

**Sistem sekarang multi-period ready!** 🚀

