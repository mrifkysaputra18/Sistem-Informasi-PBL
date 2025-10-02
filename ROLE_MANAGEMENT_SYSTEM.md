# 🔐 Role Management System - Sistem Informasi PBL

## 📋 Overview

Sistem role management dengan 4 role terpisah:
1. **Admin** - Full access
2. **Koordinator** - Manage members + Monitor
3. **Dosen** - View & Input scores
4. **Mahasiswa** - Limited access (no rankings)

---

## 👥 Role Definitions

### 1. ADMIN
**Access Level:** Full Control

**Capabilities:**
- ✅ Manage mata kuliah (CRUD subjects)
- ✅ Manage all class rooms
- ✅ Manage all groups & members
- ✅ Input & view all scores
- ✅ **Calculate rankings** (ADMIN ONLY)
- ✅ View all rankings
- ✅ Manage criteria
- ✅ Import Excel (upcoming feature)
- ✅ Manage users (upcoming feature)

**Dashboard:**
- Total users (by role)
- Total subjects
- Total class rooms
- Total groups
- Total criteria
- Total scores
- Recent groups
- Recent users

**Routes:**
```
/admin/dashboard
/subjects (CRUD)
/classrooms (CRUD)
/groups (CRUD + manage members)
/scores (CRUD + recalculate)
/criteria (CRUD)
```

---

### 2. KOORDINATOR
**Access Level:** Manager

**Capabilities:**
- ✅ Manage group members (add/remove)
- ✅ Change group leaders
- ✅ Monitor all groups
- ✅ View progress submissions
- ✅ View rankings
- ❌ Cannot input scores
- ❌ Cannot calculate rankings
- ❌ Cannot manage subjects

**Dashboard:**
- Total class rooms
- Total groups
- Active groups (with members)
- Total progress submissions
- Pending reviews
- Groups needing attention
- Recent progress submissions

**Routes:**
```
/koordinator/dashboard
/classrooms (view)
/groups (view + manage members)
/scores (view only)
```

---

### 3. DOSEN
**Access Level:** Lecturer

**Capabilities:**
- ✅ View class rooms
- ✅ View groups
- ✅ Input scores for groups
- ✅ View rankings
- ✅ Review progress submissions
- ❌ Cannot manage members
- ❌ Cannot calculate rankings
- ❌ Cannot manage subjects

**Dashboard:**
- Total class rooms
- Total groups
- Total scores
- Pending reviews
- Classes with groups
- Progress to review

**Routes:**
```
/dosen/dashboard
/classrooms (view)
/groups (view)
/scores (view + create)
/criteria (view)
```

---

### 4. MAHASISWA
**Access Level:** Student

**Capabilities:**
- ✅ View own group info
- ✅ Manage weekly targets
- ✅ Submit weekly progress
- ✅ Upload evidence (optional)
- ✅ Check completion status
- ❌ **Cannot view rankings** (HIDDEN)
- ❌ Cannot view scores
- ❌ Cannot manage members
- ❌ Cannot view other groups

**Dashboard:**
- My group info
- Weekly targets list
- Completion rate
- Recent progress
- Pending targets
- No rankings visible

**Routes:**
```
/mahasiswa/dashboard
/groups/{group}/targets (CRUD)
/targets/{target}/complete
/targets/{target}/uncomplete
```

---

## 🛡️ Implementation

### Middleware: `CheckRole`

**Location:** `app/Http/Middleware/CheckRole.php`

**Usage:**
```php
Route::middleware(['role:admin'])->group(function () {
    // Admin only routes
});

Route::middleware(['role:koordinator,admin'])->group(function () {
    // Koordinator OR Admin
});

Route::middleware(['role:dosen,koordinator,admin'])->group(function () {
    // Dosen OR Koordinator OR Admin
});
```

**How it works:**
1. Check if user is authenticated
2. Check if user's role is in allowed roles
3. If not allowed → 403 Forbidden
4. If allowed → proceed

---

## 🚀 Dashboard Routing

### Main Dashboard (Redirector)
**Route:** `/dashboard`  
**Controller:** `DashboardController`

**Logic:**
```php
switch ($user->role) {
    case 'admin':       redirect to /admin/dashboard
    case 'koordinator': redirect to /koordinator/dashboard
    case 'dosen':       redirect to /dosen/dashboard
    case 'mahasiswa':   redirect to /mahasiswa/dashboard
}
```

### Role-Specific Dashboards

| Role | Route | Controller |
|------|-------|------------|
| Admin | `/admin/dashboard` | `AdminDashboardController` |
| Koordinator | `/koordinator/dashboard` | `KoordinatorDashboardController` |
| Dosen | `/dosen/dashboard` | `DosenDashboardController` |
| Mahasiswa | `/mahasiswa/dashboard` | `MahasiswaDashboardController` |

---

## 📊 Access Control Matrix

| Feature | Admin | Koordinator | Dosen | Mahasiswa |
|---------|-------|-------------|-------|-----------|
| Manage Subjects | ✅ | ❌ | ❌ | ❌ |
| Manage Class Rooms | ✅ | View | View | ❌ |
| Manage Groups | ✅ | View | View | ❌ |
| Add/Remove Members | ✅ | ✅ | ❌ | ❌ |
| Input Scores | ✅ | ❌ | ✅ | ❌ |
| **Calculate Ranking** | ✅ | ❌ | ❌ | ❌ |
| View Rankings | ✅ | ✅ | ✅ | ❌ |
| Manage Criteria | ✅ | View | View | ❌ |
| Manage Targets | ✅ | ✅ | ✅ | ✅ |
| Submit Progress | ❌ | ❌ | ❌ | ✅ |
| Import Excel | ✅ | ❌ | ❌ | ❌ |

---

## 🎨 UI/UX Guidelines

### Navigation Menu (Dynamic)

**Admin sees:**
```
Dashboard
├── Mata Kuliah
├── Kelas
├── Kelompok
├── Kriteria
├── Nilai & Ranking [+ Hitung Ulang]
└── Users (upcoming)
```

**Koordinator sees:**
```
Dashboard
├── Kelas (view)
├── Kelompok (+ manage members)
├── Progress Monitor
└── Ranking (view only)
```

**Dosen sees:**
```
Dashboard
├── Kelas (view)
├── Kelompok (view)
├── Input Nilai
├── Ranking (view)
└── Review Progress
```

**Mahasiswa sees:**
```
Dashboard
├── Kelompok Saya
├── Target Mingguan
└── Upload Progress

NO RANKING MENU!
```

---

## 🔧 Helper Methods

**In User Model:**
```php
// Check role
$user->isAdmin()       // true/false
$user->isKoordinator() // true/false
$user->isDosen()       // true/false
$user->isMahasiswa()   // true/false
```

**In Blade Templates:**
```blade
@if(auth()->user()->isAdmin())
    <!-- Admin only content -->
@endif

@if(auth()->user()->isKoordinator() || auth()->user()->isAdmin())
    <!-- Koordinator or Admin content -->
@endif

@unless(auth()->user()->isMahasiswa())
    <!-- Hide from students -->
@endunless
```

---

## 🚨 Important Rules

### 1. No Multiple Roles
- ✅ One user = One role
- ❌ User cannot be both Dosen AND Koordinator

### 2. Koordinator ≠ Dosen
- Separated roles with different permissions
- Koordinator manages, Dosen teaches & scores

### 3. No Approval System
- Koordinator & Admin directly manage groups
- No student request/approval flow

### 4. Mahasiswa Cannot See Rankings
- Rankings completely hidden from student UI
- No access to scores page
- Focus on task completion only

### 5. Admin-Only Ranking Calculation
- Only admin can trigger ranking recalculation
- Button hidden for all other roles
- Ensures data integrity

---

## 📁 Files Created/Modified

### New Files:
- `app/Http/Middleware/CheckRole.php`
- `app/Http/Controllers/AdminDashboardController.php`
- `app/Http/Controllers/KoordinatorDashboardController.php`
- `app/Http/Controllers/DosenDashboardController.php`
- `app/Http/Controllers/MahasiswaDashboardController.php`
- `ROLE_MANAGEMENT_SYSTEM.md` (this file)

### Modified Files:
- `bootstrap/app.php` - Register middleware
- `routes/web.php` - Role-based route grouping
- `app/Http/Controllers/DashboardController.php` - Role redirector

### To Create (Views):
- `resources/views/dashboards/admin.blade.php`
- `resources/views/dashboards/koordinator.blade.php`
- `resources/views/dashboards/dosen.blade.php`
- `resources/views/dashboards/mahasiswa.blade.php`

---

## 🧪 Testing Checklist

### Admin
- [ ] Can access /admin/dashboard
- [ ] Can manage subjects
- [ ] Can calculate rankings
- [ ] Cannot access other role dashboards

### Koordinator
- [ ] Can access /koordinator/dashboard
- [ ] Can add/remove group members
- [ ] Can view rankings
- [ ] Cannot input scores
- [ ] Cannot calculate rankings

### Dosen
- [ ] Can access /dosen/dashboard
- [ ] Can input scores
- [ ] Can view rankings
- [ ] Cannot manage members
- [ ] Cannot calculate rankings

### Mahasiswa
- [ ] Can access /mahasiswa/dashboard
- [ ] Can manage weekly targets
- [ ] Cannot see rankings menu
- [ ] Cannot access scores page
- [ ] Gets 403 if trying to access forbidden routes

---

## 🔄 Next Steps

1. ✅ Middleware created
2. ✅ Dashboard controllers created
3. ✅ Routes organized by role
4. ⏳ Create dashboard views
5. ⏳ Update navigation menu (dynamic)
6. ⏳ Update existing views to hide features by role
7. ⏳ Test all role permissions
8. ⏳ Add visual indicators for role-specific features

---

**Last Updated:** 2025-10-01  
**Status:** Backend Complete, Views Pending


