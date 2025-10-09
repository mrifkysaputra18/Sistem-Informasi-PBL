# Perubahan Warna Website - Biru Laut & Merah Maroon

## 📋 Ringkasan Perubahan
Sistem PBL telah diperbarui dengan skema warna baru:
- **Warna Primary (Utama)**: Biru Laut (Navy Blue)
- **Warna Secondary (Pendukung)**: Merah Maroon

Tanggal Implementasi: {{ date }}

---

## 🎨 Skema Warna Baru

### Warna Primary - Biru Laut
```css
primary-50:  #e6f0ff  (Sangat Terang)
primary-100: #b3d1ff  (Terang)
primary-200: #80b3ff  
primary-300: #4d94ff  
primary-400: #1a75ff  
primary-500: #0056b3  (Base - Biru Laut Utama)
primary-600: #004080  
primary-700: #003366  (Gelap)
primary-800: #002952  
primary-900: #001f3f  (Sangat Gelap)
```

### Warna Secondary - Merah Maroon
```css
secondary-50:  #ffe6e6  (Sangat Terang)
secondary-100: #ffb3b3  (Terang)
secondary-200: #ff8080  
secondary-300: #ff4d4d  
secondary-400: #cc0000  
secondary-500: #990000  (Base - Merah Maroon Utama)
secondary-600: #800000  (Maroon)
secondary-700: #660000  (Gelap)
secondary-800: #4d0000  
secondary-900: #330000  (Sangat Gelap)
```

---

## 📁 File yang Diubah

### 1. Konfigurasi Tailwind CSS
**File**: `tailwind.config.js`
- Menambahkan custom color palette untuk `primary` dan `secondary`
- Mendefinisikan 10 tingkat warna untuk setiap palette

### 2. CSS Custom
**File**: `resources/css/app.css`
- **Navbar**: Gradient biru laut dengan aksen maroon
  ```css
  gradient: #001f3f → #003366 → #0056b3 → #004080 → #800000
  ```
- **Page Header**: Gradient biru laut ke maroon
  ```css
  gradient: rgba(0,51,102,0.95) → rgba(0,86,179,0.95) → rgba(128,0,0,0.95)
  ```
- **Shadow**: Update shadow dengan warna biru laut dan maroon
- **User Avatar**: Text color menggunakan navy blue (#003366)
- **Hover Effects**: Background gradient biru laut ke merah muda maroon

### 3. Komponen Blade
**Files Updated**: 60+ file blade.php

#### Komponen Navigasi:
- `resources/views/components/nav-link.blade.php`
- `resources/views/components/dropdown-link.blade.php`
- `resources/views/components/responsive-nav-link.blade.php`

#### Layout:
- `resources/views/layouts/app.blade.php`
  - Flash messages menggunakan warna primary
  - Alert info menggunakan primary-50, primary-500, primary-800

#### Semua View Pages:
Penggantian otomatis di semua file `.blade.php`:
- `bg-blue-*` → `bg-primary-*`
- `text-blue-*` → `text-primary-*`
- `border-blue-*` → `border-primary-*`
- `bg-indigo-*` → `bg-primary-*`
- `text-indigo-*` → `text-primary-*`
- `border-indigo-*` → `border-primary-*`
- `ring-indigo-*` → `ring-primary-*`
- `from-indigo-*` → `from-primary-*`
- `to-indigo-*` → `to-primary-*`
- `bg-purple-*` → `bg-secondary-*`
- `text-purple-*` → `text-secondary-*`
- `border-purple-*` → `border-secondary-*`
- `bg-violet-*` → `bg-secondary-*`
- `text-violet-*` → `text-secondary-*`
- `bg-pink-*` → `bg-secondary-*`
- `text-pink-*` → `text-secondary-*`

---

## 🔧 Build & Deploy

### Build Command
```bash
npm run build
```

### Output
```
✓ 54 modules transformed
public/build/manifest.json           0.27 kB
public/build/assets/app-fABwUccO.css 76.44 kB │ gzip: 12.80 kB
public/build/assets/app-CWmVnECS.js  80.66 kB │ gzip: 30.21 kB
```

---

## 🎯 Penggunaan Warna di Aplikasi

### Primary (Biru Laut)
Digunakan untuk:
- ✅ Navigation bar dan header
- ✅ Tombol utama (Primary buttons)
- ✅ Link dan hyperlink
- ✅ Icon utama
- ✅ Status bar dan progress
- ✅ Informational alerts
- ✅ Active states
- ✅ Focus states

### Secondary (Merah Maroon)
Digunakan untuk:
- ✅ Aksen pada navbar (gradient end)
- ✅ Tombol sekunder (Secondary buttons)
- ✅ Highlight khusus
- ✅ Badge atau label penting
- ✅ Hover states (kombinasi dengan primary)

### Warna Lain (Tetap)
- **Green**: Success messages
- **Red**: Error messages
- **Yellow**: Warning messages
- **Gray**: Text, borders, backgrounds

---

## 📱 Tampilan Responsif

Perubahan warna diterapkan untuk semua ukuran layar:
- ✅ Desktop (>1024px)
- ✅ Tablet (768px - 1024px)
- ✅ Mobile (<768px)

---

## 🔍 Verifikasi

### Komponen yang Menggunakan Warna Baru:
1. ✅ Navbar (gradient biru laut → maroon)
2. ✅ Page headers (gradient biru laut → maroon)
3. ✅ Dropdown menus (hover primary-secondary)
4. ✅ Navigation links (border primary)
5. ✅ Flash messages (background primary-50, border primary-500)
6. ✅ User profile button (background primary dengan opacity)
7. ✅ Active link indicators (primary colors)
8. ✅ Buttons (bg-primary, text-primary)
9. ✅ Forms (focus:ring-primary)
10. ✅ Cards dan panels (border-primary)

---

## 🚀 Cara Menggunakan di Kode Baru

### Tailwind Classes
```html
<!-- Background -->
<div class="bg-primary-500">Biru Laut</div>
<div class="bg-secondary-500">Merah Maroon</div>

<!-- Text -->
<p class="text-primary-700">Teks Biru Laut</p>
<p class="text-secondary-600">Teks Maroon</p>

<!-- Border -->
<div class="border-2 border-primary-400">Border Biru</div>

<!-- Hover -->
<button class="bg-primary-500 hover:bg-primary-600">Tombol</button>

<!-- Gradient -->
<div class="bg-gradient-to-r from-primary-500 to-secondary-500">
  Gradient Biru → Maroon
</div>
```

### Custom CSS
```css
/* Navbar custom */
.modern-navbar {
    background: linear-gradient(135deg, 
        #001f3f 0%,   /* Navy blue darkest */
        #003366 25%,  /* Navy blue */
        #0056b3 50%,  /* Blue */
        #004080 75%,  /* Navy blue medium */
        #800000 100%  /* Maroon accent */
    );
}

/* Page header */
.page-header {
    background: linear-gradient(135deg, 
        rgba(0, 51, 102, 0.95) 0%,
        rgba(0, 86, 179, 0.95) 50%,
        rgba(128, 0, 0, 0.95) 100%
    );
}
```

---

## 📊 Statistik Perubahan

- **Total Files Changed**: 63 files
- **Blade Views Updated**: 60+ files
- **CSS Files Modified**: 1 file (app.css)
- **Config Files Modified**: 1 file (tailwind.config.js)
- **Color Replacements**: 500+ instances
- **Build Size**: 76.44 kB (CSS) + 80.66 kB (JS)

---

## ✅ Checklist Completed

- [x] Update Tailwind config dengan color palette baru
- [x] Update custom CSS (app.css)
- [x] Update komponen navigasi
- [x] Update layout utama (app.blade.php)
- [x] Update semua file blade dengan warna baru
- [x] Compile CSS dengan npm run build
- [x] Verifikasi tampilan di berbagai komponen
- [x] Dokumentasi perubahan

---

## 🎨 Preview Warna

### Navbar
- Background: Gradient Navy Blue (#001f3f) → Maroon (#800000)
- Text: White (#ffffff)
- Active Link: White dengan background primary-transparent
- Hover: White dengan background primary-transparent yang lebih terang

### Page Header
- Background: Gradient Navy Blue → Ocean Blue → Maroon
- Text: White dengan text-shadow
- Border: White 30% opacity

### Buttons
- Primary Button: bg-primary-500 hover:bg-primary-600
- Secondary Button: bg-secondary-500 hover:bg-secondary-600
- Text color: White

### Links
- Normal: text-primary-600
- Hover: text-primary-700
- Active: border-primary-400

---

## 📞 Support

Jika ada masalah atau pertanyaan terkait perubahan warna:
1. Pastikan sudah menjalankan `npm run build`
2. Clear cache browser (Ctrl+F5)
3. Periksa konsol browser untuk error
4. Verifikasi file `tailwind.config.js` dan `app.css`

---

## 🔄 Rollback (jika diperlukan)

Jika ingin mengembalikan ke warna sebelumnya:
1. Git revert ke commit sebelum perubahan warna
2. Atau ganti semua `primary-*` kembali ke `blue-*` atau `indigo-*`
3. Ganti semua `secondary-*` kembali ke `purple-*` atau `violet-*`
4. Jalankan `npm run build` ulang

---

**Dokumentasi dibuat**: {{ date }}
**Status**: ✅ SELESAI & TERIMPLEMENTASI

