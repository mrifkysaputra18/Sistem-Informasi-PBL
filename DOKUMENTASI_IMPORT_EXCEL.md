# 📋 Dokumentasi Fitur Import Excel

## 🎯 Tujuan
Dokumentasi ini menjelaskan implementasi fitur import Excel untuk:
1. **Import User/Mahasiswa** - Upload data mahasiswa secara massal
2. **Import Kelompok** - Upload data kelompok dan anggotanya secara massal

---

## 📦 Library Yang Digunakan

**Laravel Excel (Maatwebsite/Excel)**
- Package untuk membaca dan menulis file Excel di Laravel
- Mendukung format: `.xlsx`, `.xls`, `.csv`
- Install: `composer require maatwebsite/excel`

---

## 🔹 1. IMPORT USER/MAHASISWA

### 📂 File-File Terkait

| Jenis | Path File | Fungsi |
|-------|-----------|--------|
| **Controller** | `app/Http/Controllers/UserImportController.php` | Menangani request, validasi file, dan koordinasi proses import |
| **Import Class** | `app/Imports/UsersImport.php` | Membaca Excel, validasi data, simpan ke database |
| **Import Multi-Sheet** | `app/Imports/UsersMultiSheetImport.php` | Support import dari beberapa sheet sekaligus |
| **View** | `resources/views/users/import.blade.php` | Form upload file Excel |
| **Template** | `storage/app/templates/template-import-mahasiswa.xlsx` | Template Excel untuk panduan user |

---

### 🎬 Alur Kerja Import User

```
1. User upload file Excel (bisa multiple files, max 10)
   ↓
2. UserImportController::import() 
   - Validasi format file (.xlsx, .xls, .csv)
   - Validasi ukuran (max 5MB per file)
   ↓
3. UsersImport::collection()
   - Loop setiap baris Excel
   - Validasi data (email domain @mhs.politala.ac.id, dll)
   - Cek duplikasi (email, NIM)
   - Cari ClassRoom berdasarkan kode kelas
   - Generate Politala ID unik
   - Simpan ke database (tabel users)
   ↓
4. Response
   - Berhasil: redirect ke halaman user dengan notifikasi sukses
   - Gagal: redirect back dengan error message
```

---

### 📝 Format Excel untuk Import User

**Kolom yang diperlukan:**

| Kolom Excel | Tipe Data | Required | Keterangan |
|-------------|-----------|----------|------------|
| `nim` | String | Optional | Nomor Induk Mahasiswa |
| `nama_lengkap` | String | Required | Nama lengkap mahasiswa |
| `email_sso` | Email | Required | Harus @mhs.politala.ac.id |
| `program_studi` | String | Required | Nama program studi |
| `kelas` | String | Required | Kode kelas (harus ada di database) |

**Contoh data:**
```
NIM         | nama_lengkap      | email_sso                      | program_studi           | kelas
2301010001  | Ahmad Fauzi       | 2301010001@mhs.politala.ac.id  | Teknik Informatika      | TI-3B
2301010002  | Siti Nurhaliza    | 2301010002@mhs.politala.ac.id  | Teknik Informatika      | TI-3B
```

---

### 🔍 Validasi & Error Handling

**Validasi yang dilakukan:**
1. ✅ Email wajib diisi dan format valid
2. ✅ Email harus domain `@mhs.politala.ac.id`
3. ✅ Cek duplikasi email dan NIM
4. ✅ Kelas harus ada di database
5. ✅ Baris kosong akan dilewati (skip)

**Error yang mungkin terjadi:**
- Baris X: Email SSO wajib diisi
- Baris X: Email harus menggunakan domain @mhs.politala.ac.id
- Baris X: Kelas 'TI-3B' tidak ditemukan di database
- Baris X: Email xxx@mhs.politala.ac.id sudah terdaftar
- Baris X: NIM 2301010001 sudah terdaftar

---

### 💻 Kode Penting

#### Controller Method (UserImportController.php)

```php
public function import(Request $request)
{
    // Validasi multiple files
    $request->validate([
        'files' => 'required|array|min:1|max:10',
        'files.*' => 'required|mimes:xlsx,xls,csv|max:5120'
    ]);

    // Loop setiap file
    foreach ($files as $file) {
        $import = new UsersMultiSheetImport();
        Excel::import($import, $file);
        
        // Ambil statistik
        $totalImported += $import->getImportedCount();
        $totalSkipped += $import->getSkippedCount();
    }
    
    return redirect()->route('admin.users.index')
        ->with('success', "Import berhasil: {$totalImported} mahasiswa");
}
```

#### Import Class (UsersImport.php)

```php
public function collection(Collection $rows)
{
    foreach ($rows as $index => $row) {
        // Skip baris kosong
        if (empty($row['email_sso'])) {
            $this->skippedCount++;
            continue;
        }

        // Validasi email domain
        if (!Str::endsWith($row['email_sso'], '@mhs.politala.ac.id')) {
            $this->skippedCount++;
            $this->errors[] = "Email harus @mhs.politala.ac.id";
            continue;
        }

        // Cari kelas
        $classRoom = ClassRoom::where('code', $row['kelas'])->first();
        
        // Cek duplikasi
        $existingUser = User::where('email', $row['email_sso'])->first();
        if ($existingUser) {
            $this->skippedCount++;
            continue;
        }

        // Buat user baru
        User::create([
            'nim' => $row['nim'],
            'name' => $row['nama_lengkap'],
            'email' => $row['email_sso'],
            'politala_id' => $this->generatePolitalaId($row['nama_lengkap']),
            'program_studi' => $row['program_studi'],
            'class_room_id' => $classRoom->id,
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);

        $this->importedCount++;
    }
}
```

---

## 🔹 2. IMPORT KELOMPOK

### 📂 File-File Terkait

| Jenis | Path File | Fungsi |
|-------|-----------|--------|
| **Controller** | `app/Http/Controllers/ImportController.php` | Menangani request import kelompok |
| **Import Class** | `app/Imports/GroupsImport.php` | Membaca Excel, buat kelompok, tambah anggota |
| **View** | `resources/views/imports/groups.blade.php` | Form upload dengan pilihan kelas |

---

### 🎬 Alur Kerja Import Kelompok

```
1. User pilih Kelas & upload file Excel
   ↓
2. ImportController::importGroups() 
   - Validasi class_room_id
   - Validasi file (bisa multiple files)
   ↓
3. GroupsImport::collection()
   - Loop setiap baris Excel
   - Cari ketua berdasarkan NIM/Email
   - Validasi ketua belum punya kelompok lain
   - Buat kelompok baru (tabel groups)
   - Tambah ketua sebagai leader (tabel group_members)
   - Cari & tambah anggota 1-4 (max 5 orang per kelompok)
   - Validasi anggota belum punya kelompok lain
   ↓
4. Response
   - Berhasil: redirect ke halaman kelompok
   - Gagal: redirect back dengan error
```

---

### 📝 Format Excel untuk Import Kelompok

**Kolom yang diperlukan:**

| Kolom Excel | Tipe Data | Required | Keterangan |
|-------------|-----------|----------|------------|
| `nama_kelompok` | String | Required | Nama kelompok (unik per kelas) |
| `ketua_nim_atau_email` | String | Required | NIM atau Email ketua |
| `anggota_1_nim_atau_email` | String | Optional | NIM/Email anggota 1 |
| `anggota_2_nim_atau_email` | String | Optional | NIM/Email anggota 2 |
| `anggota_3_nim_atau_email` | String | Optional | NIM/Email anggota 3 |
| `anggota_4_nim_atau_email` | String | Optional | NIM/Email anggota 4 |

**Contoh data:**
```
nama_kelompok | ketua_nim_atau_email          | anggota_1_nim_atau_email      | anggota_2_nim_atau_email
Kelompok 1    | 2301010001@mhs.politala.ac.id | 2301010002@mhs.politala.ac.id | 2301010003@mhs.politala.ac.id
Kelompok 2    | 2301010005                    | 2301010006                    | 2301010007
```

---

### 🔍 Validasi & Error Handling

**Validasi yang dilakukan:**
1. ✅ Nama kelompok wajib diisi
2. ✅ Nama kelompok tidak boleh duplikat dalam 1 kelas
3. ✅ Ketua harus ada dan dari kelas yang sama
4. ✅ Ketua belum punya kelompok lain di kelas ini
5. ✅ Anggota harus dari kelas yang sama
6. ✅ Anggota belum punya kelompok lain di kelas ini
7. ✅ Maksimal 5 anggota per kelompok (1 ketua + 4 anggota)

**Error yang mungkin terjadi:**
- Baris X: Kelompok 'Kelompok 1' sudah ada di kelas ini
- Baris X: Ketua '2301010001' tidak ditemukan
- Baris X: Ketua Ahmad Fauzi sudah tergabung dalam kelompok lain

---

### 💻 Kode Penting

#### Controller Method (ImportController.php)

```php
public function importGroups(Request $request)
{
    $request->validate([
        'class_room_id' => 'required|exists:class_rooms,id',
        'files' => 'required|array|min:1|max:10',
        'files.*' => 'required|mimes:xlsx,xls,csv|max:5120',
    ]);

    $classRoomId = $request->class_room_id;
    
    foreach ($files as $file) {
        $import = new GroupsImport($classRoomId);
        Excel::import($import, $file);
        
        $totalImported += $import->getImportedCount();
    }
    
    return redirect()->route('groups.index')
        ->with('success', "Import berhasil: {$totalImported} kelompok");
}
```

#### Import Class (GroupsImport.php)

```php
public function collection(Collection $rows)
{
    foreach ($rows as $index => $row) {
        DB::beginTransaction();
        
        // Cari ketua
        $leader = $this->findStudent($row['ketua_nim_atau_email'], $classRoom);
        
        // Cek ketua sudah punya kelompok?
        if ($this->hasGroupInClass($leader->id, $this->classRoomId)) {
            $this->skippedCount++;
            DB::rollback();
            continue;
        }

        // Buat kelompok
        $group = Group::create([
            'name' => $row['nama_kelompok'],
            'class_room_id' => $this->classRoomId,
            'leader_id' => $leader->id,
        ]);

        // Tambah ketua sebagai member
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => $leader->id,
            'is_leader' => true,
        ]);

        // Tambah anggota lain (1-4 orang)
        for ($i = 1; $i <= 4; $i++) {
            $memberIdentifier = $row["anggota_{$i}_nim_atau_email"] ?? '';
            
            if (empty($memberIdentifier)) continue;
            
            $member = $this->findStudent($memberIdentifier, $classRoom);
            
            if ($member && !$this->hasGroupInClass($member->id, $this->classRoomId)) {
                GroupMember::create([
                    'group_id' => $group->id,
                    'user_id' => $member->id,
                    'is_leader' => false,
                ]);
            }
        }

        DB::commit();
        $this->importedCount++;
    }
}

private function findStudent($identifier, $classRoom)
{
    return User::where('role', 'mahasiswa')
        ->where('class_room_id', $classRoom->id)
        ->where(function($query) use ($identifier) {
            $query->where('nim', $identifier)
                  ->orWhere('email', $identifier);
        })->first();
}
```

---

## 📊 Statistik & Logging

Kedua fitur import mencatat statistik:
- **Imported Count**: Jumlah berhasil diimport
- **Skipped Count**: Jumlah baris dilewati
- **Errors**: Array pesan error per baris

```php
public function getImportedCount() { return $this->importedCount; }
public function getSkippedCount() { return $this->skippedCount; }
public function getErrors() { return $this->errors; }
```

Logging ke Laravel Log:
```php
Log::info('Excel Import - Processing started', [
    'total_rows_to_process' => $rows->count()
]);
```

---

## 🎯 Fitur Tambahan

### 1. **Multiple Files Upload**
- Support upload hingga 10 file sekaligus
- Setiap file diproses secara terpisah
- Statistik ditampilkan per file

### 2. **Download Template**
```php
public function downloadTemplate()
{
    $filePath = storage_path('app/templates/template-import-mahasiswa.xlsx');
    return response()->download($filePath);
}
```

### 3. **Auto Generate Politala ID**
```php
private function generatePolitalaId($name)
{
    $firstName = strtoupper(explode(' ', $name)[0]);
    do {
        $random = rand(1000, 9999);
        $politalaId = 'MHS_' . $firstName . '_' . $random;
    } while (User::where('politala_id', $politalaId)->exists());
    
    return $politalaId;
}
```

---

## 🔧 Troubleshooting

### Problem: "Template file tidak ditemukan"
**Solusi**: Pastikan file template ada di `storage/app/templates/`

### Problem: "Kelas tidak ditemukan"
**Solusi**: Pastikan kode kelas di Excel sesuai dengan field `code` di tabel `class_rooms`

### Problem: "Email sudah terdaftar"
**Solusi**: Hapus user lama atau gunakan email berbeda

### Problem: "Kelompok sudah ada"
**Solusi**: Gunakan nama kelompok yang berbeda atau hapus kelompok lama

---

## 📚 Database Tables

### users
- `nim`: String (nullable)
- `name`: String
- `email`: String (unique)
- `politala_id`: String (unique)
- `program_studi`: String
- `class_room_id`: Foreign key
- `role`: Enum ('mahasiswa', 'dosen', 'admin')

### groups
- `name`: String
- `class_room_id`: Foreign key
- `leader_id`: Foreign key (users)
- `max_members`: Integer (default: 5)

### group_members
- `group_id`: Foreign key
- `user_id`: Foreign key
- `is_leader`: Boolean
- `status`: Enum ('active', 'inactive')

---

## ✅ Testing Checklist

### Import User
- [ ] Upload file valid (.xlsx)
- [ ] Upload multiple files (2-10 files)
- [ ] File dengan baris kosong
- [ ] Email duplikat
- [ ] NIM duplikat
- [ ] Email domain salah
- [ ] Kelas tidak ditemukan
- [ ] Download template

### Import Kelompok
- [ ] Upload file valid
- [ ] Upload multiple files
- [ ] Ketua tidak ditemukan
- [ ] Ketua sudah punya kelompok
- [ ] Anggota sudah punya kelompok
- [ ] Nama kelompok duplikat
- [ ] Kelompok dengan anggota 1-5 orang

---

## 👨‍💻 Developer Notes

**Dependencies:**
```json
{
    "require": {
        "maatwebsite/excel": "^3.1"
    }
}
```

**Config:**
```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

**Routes:**
```php
Route::get('/import/users', [UserImportController::class, 'showImportForm']);
Route::post('/import/users', [UserImportController::class, 'import']);
Route::get('/import/groups', [ImportController::class, 'showGroupsImport']);
Route::post('/import/groups', [ImportController::class, 'importGroups']);
```

---

**Dibuat untuk keperluan presentasi kepada dosen pembimbing** ✨
