# 🚀 QUICK GUIDE - MANAJEMEN MAHASISWA

**Update:** 5 Oktober 2025  
**Status:** ✅ READY TO USE

---

## 🎯 APA YANG BARU?

Admin sekarang bisa:
1. ✅ **Kelola semua user** (mahasiswa, dosen, admin, koordinator)
2. ✅ **Filter mahasiswa per kelas** dengan mudah
3. ✅ **Lihat mahasiswa yang belum punya kelompok** dengan statistik lengkap
4. ✅ **CRUD lengkap** (Create, Read, Update, Delete)

---

## 📍 CARA AKSES

### **Menu Admin:**
```
Dashboard → Kelola User
```

### **Atau langsung:**
```
http://localhost:8000/admin/users
```

---

## 🔥 FITUR CEPAT

### 1️⃣ **Lihat Semua User**
```
Menu: Kelola User
Filter: Search, Role, Kelas, Status
```

### 2️⃣ **Mahasiswa Tanpa Kelompok**
```
Menu: Kelola User → [Mahasiswa Tanpa Kelompok]
Lihat: Statistik per kelas + daftar lengkap
```

### 3️⃣ **Tambah User Baru**
```
Menu: Kelola User → [+ Tambah User]
Isi: Nama, Email, Password, Role, Kelas (jika mahasiswa)
```

### 4️⃣ **Edit User**
```
Klik: [Edit] pada user
Ubah: Nama, kelas, role, status, dll
Password: Opsional (kosongkan jika tidak ubah)
```

### 5️⃣ **Toggle Status**
```
Klik: Badge "Aktif" atau "Tidak Aktif" di tabel
Otomatis: Status berubah
```

---

## 💡 USE CASE POPULER

### **Cek Mahasiswa TI-3A Tanpa Kelompok:**
```
1. Klik "Mahasiswa Tanpa Kelompok"
2. Lihat card TI-3A (contoh: 3/28)
3. Filter kelas "TI-3A"
4. Lihat 3 mahasiswa yang belum masuk kelompok
5. Action: Edit atau langsung "Buat Kelompok"
```

### **Tambah Mahasiswa Baru:**
```
1. Klik "Tambah User"
2. Isi data mahasiswa
3. Pilih Role: "Mahasiswa"
4. Pilih Kelas: "TI-3A" (wajib untuk mahasiswa)
5. Simpan
✅ Mahasiswa langsung terdaftar di TI-3A
```

### **Pindah Mahasiswa ke Kelas Lain:**
```
1. Cari mahasiswa (search/filter)
2. Klik "Edit"
3. Ubah kelas: TI-3A → TI-3B
4. Update
✅ Mahasiswa berhasil dipindah kelas
```

---

## 🎨 NAVIGASI

### **Menu Bar (Admin):**
```
┌──────────────────────────────────────────┐
│ Dashboard | Periode | [Kelola User] |... │
└──────────────────────────────────────────┘
```

### **Sub-menu:**
```
┌─────────────────────────────────┐
│  Kelola User                    │
│  ├─ Daftar User                 │
│  ├─ [+] Tambah User             │
│  └─ [!] Mahasiswa Tanpa Kelompok│
└─────────────────────────────────┘
```

---

## 📊 STATISTIK REALTIME

**Di halaman "Mahasiswa Tanpa Kelompok":**
```
┌──────┬──────┬──────┬──────┬──────┐
│ TI-3A│ TI-3B│ TI-3C│ TI-3D│ TI-3E│
│ 3/28 │ 2/28 │ 3/27 │ 2/29 │ 5/28 │
└──────┴──────┴──────┴──────┴──────┘
```
- **Angka pertama:** Mahasiswa tanpa kelompok
- **Angka kedua:** Total mahasiswa di kelas

---

## ⚡ KEYBOARD SHORTCUTS (Future)

| Key | Action |
|-----|--------|
| `Ctrl + K` | Focus search |
| `Ctrl + N` | New user |
| `Esc` | Close modal |

---

## 🔒 PERMISSION

**Hanya Admin** yang bisa akses fitur ini!

```
Role yang TIDAK bisa akses:
❌ Mahasiswa
❌ Dosen
❌ Koordinator

Role yang BISA akses:
✅ Admin
```

---

## 📱 MOBILE FRIENDLY

✅ Responsive design  
✅ Touch-friendly buttons  
✅ Scrollable tables  
✅ Hamburger menu

---

## 🆘 TROUBLESHOOTING

### **Mahasiswa tidak muncul saat buat kelompok?**
→ Pastikan mahasiswa sudah punya kelas (edit user → pilih kelas)

### **Statistik tidak update?**
→ Refresh halaman (Ctrl+F5)

### **Error "permission denied"?**
→ Login sebagai admin (bukan dosen/koordinator)

---

## 📞 SUPPORT

**Dokumentasi Lengkap:**  
→ Baca file `FITUR_MANAJEMEN_MAHASISWA.md`

**Need Help?**  
→ Hubungi koordinator sistem

---

## ✅ QUICK CHECKLIST

**Sebelum periode baru dimulai:**
- [ ] Cek mahasiswa tanpa kelompok
- [ ] Pastikan setiap mahasiswa punya kelas
- [ ] Verifikasi data mahasiswa baru
- [ ] Update data mahasiswa tidak aktif

---

**🎉 Happy Managing! Sistem siap digunakan!**
