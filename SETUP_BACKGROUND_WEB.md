# 🎨 Setup Background Web

**Tanggal:** 7 Oktober 2025  
**Status:** ✅ Implemented

---

## 📋 Ringkasan

Background web sudah diatur menggunakan gambar `main-bg.jpg` yang tersimpan di `public/images/backgrounds/`.

---

## 📁 Struktur File

```
public/
└── images/
    └── backgrounds/
        └── main-bg.jpg  ← Background image Anda
```

---

## 🛠️ Implementasi

### 1. **Layout HTML** (`resources/views/layouts/app.blade.php`)

```html
<body class="font-sans antialiased">
    <!-- Background Image Container -->
    <div class="app-background with-image"></div>
    
    <div class="min-h-screen">
        <!-- Content di sini -->
    </div>
</body>
```

**Line 19**: Div dengan class `app-background with-image` akan menampilkan background image.

---

### 2. **CSS Styling** (`resources/css/app.css`)

#### Background Container:
```css
.app-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
}
```

#### Background Image:
```css
.app-background.with-image {
    background-image: url('/images/backgrounds/main-bg.jpg');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}
```

#### Overlay (untuk keterbacaan):
```css
.app-background::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /* Overlay minimal 10% - gambar SANGAT JELAS terlihat */
    background: rgba(255, 255, 255, 0.10);
    backdrop-filter: blur(0px); /* TIDAK ADA BLUR */
}
```

---

## 🎨 Opacity Settings

### **Current Settings** (Gambar SANGAT Jelas):
- **Light mode**: 10% overlay (gambar 90% terlihat)
- **Dark mode**: 15% overlay (gambar 85% terlihat)

### **Opsi Opacity**:

#### Sangat Jelas (Current):
```css
background: rgba(255, 255, 255, 0.10); /* 10% overlay */
```

#### Jelas:
```css
background: rgba(255, 255, 255, 0.20); /* 20% overlay */
```

#### Sedang:
```css
background: rgba(255, 255, 255, 0.30); /* 30% overlay */
```

#### Soft:
```css
background: rgba(255, 255, 255, 0.40); /* 40% overlay */
```

---

## 📱 Responsive Behavior

### **Desktop**:
```css
background-attachment: fixed; /* Parallax effect */
```

### **Mobile**:
```css
@media (max-width: 768px) {
    background-attachment: scroll; /* Better performance */
}
```

---

## 🔧 Cara Ganti Background

### **Opsi 1: Ganti File**
1. Simpan gambar baru dengan nama `main-bg.jpg`
2. Upload ke `public/images/backgrounds/`
3. Refresh browser (Ctrl+F5)

### **Opsi 2: Ganti Nama File**
1. Upload gambar baru (misal: `new-bg.jpg`)
2. Edit `resources/css/app.css` line 26:
   ```css
   background-image: url('/images/backgrounds/new-bg.jpg');
   ```
3. Run `npm run build`

---

## 🎯 Background Properties

| Property | Value | Keterangan |
|----------|-------|------------|
| **Size** | `cover` | Gambar memenuhi layar |
| **Position** | `center center` | Gambar di tengah |
| **Repeat** | `no-repeat` | Tidak diulang |
| **Attachment** | `fixed` (desktop) | Parallax effect |
| **Overlay** | `10-15%` | Transparan minimal |
| **Blur** | `0px` | Tidak ada blur |

---

## 🎨 Features

### ✅ **Full Screen Coverage**
- Background memenuhi seluruh layar
- Auto-adjust ke ukuran layar apapun

### ✅ **No Blur**
- Gambar tetap tajam
- Tidak ada blur filter

### ✅ **Minimal Overlay**
- Hanya 10-15% overlay putih
- Gambar tetap jelas terlihat
- Konten tetap mudah dibaca

### ✅ **Parallax Effect** (Desktop)
- Background fixed saat scroll
- Memberikan depth effect
- Disabled di mobile untuk performa

### ✅ **Responsive**
- Desktop: Fixed background
- Mobile: Scroll background (performa lebih baik)

---

## 🔍 Troubleshooting

### **Background tidak muncul?**

#### 1. Check file exists:
```bash
# Pastikan file ada
ls public/images/backgrounds/main-bg.jpg
```

#### 2. Check permissions:
```bash
# Pastikan file readable
chmod 644 public/images/backgrounds/main-bg.jpg
```

#### 3. Clear cache:
```bash
# Clear browser cache
Ctrl + Shift + R (Chrome)
Ctrl + F5 (Firefox)
```

#### 4. Rebuild CSS:
```bash
npm run build
```

### **Background terlalu gelap/terang?**

Edit overlay di `resources/css/app.css`:

```css
/* Lebih terang = overlay lebih kecil */
background: rgba(255, 255, 255, 0.05); /* 5% overlay */

/* Lebih gelap = overlay lebih besar */
background: rgba(255, 255, 255, 0.40); /* 40% overlay */
```

### **Background terlalu besar/kecil?**

Ubah `background-size`:

```css
/* Full coverage (default) */
background-size: cover;

/* Contain (fit to screen) */
background-size: contain;

/* Custom size */
background-size: 100% auto;
```

---

## 🎨 Alternative Layouts

### **Opsi 1: Gradient Overlay**

```css
.app-background.with-gradient::before {
    background: linear-gradient(135deg, 
        rgba(105, 130, 169, 0.2) 0%, 
        rgba(147, 51, 234, 0.2) 100%
    );
}
```

**Cara pakai**: Ganti class `with-image` menjadi `with-image with-gradient`

### **Opsi 2: Pattern Overlay**

```css
.app-background.with-pattern {
    background-image: 
        url('/images/backgrounds/pattern.png'),
        url('/images/backgrounds/main-bg.jpg');
}
```

**Cara pakai**: Ganti class `with-image` menjadi `with-pattern`

### **Opsi 3: No Overlay**

Hapus atau comment overlay:

```css
.app-background::before {
    display: none; /* Disable overlay */
}
```

---

## 📊 Performance

### **Image Optimization Tips**:

1. **Ukuran File**: 
   - Ideal: < 500KB
   - Max: 1-2MB

2. **Dimensi**:
   - Desktop: 1920x1080px (Full HD)
   - Compress untuk web

3. **Format**:
   - JPG: Untuk foto
   - WebP: Lebih kecil, kualitas sama
   - PNG: Untuk transparency

4. **Optimization Tools**:
   - TinyPNG/TinyJPG
   - ImageOptim
   - Squoosh

---

## 🚀 Build & Deploy

### **Development**:
```bash
npm run dev
```

### **Production**:
```bash
npm run build
```

### **Watch Mode**:
```bash
npm run watch
```

---

## 📝 File Locations

| File | Path | Purpose |
|------|------|---------|
| Background Image | `public/images/backgrounds/main-bg.jpg` | Source image |
| CSS Styles | `resources/css/app.css` | Styling rules |
| Layout File | `resources/views/layouts/app.blade.php` | HTML structure |
| Compiled CSS | `public/build/assets/app-*.css` | Production CSS |

---

## ✅ Checklist

- [x] Background image uploaded to `public/images/backgrounds/`
- [x] CSS configured in `resources/css/app.css`
- [x] Layout updated in `app.blade.php`
- [x] Overlay opacity set to 10-15%
- [x] No blur filter
- [x] Responsive settings
- [x] CSS compiled (`npm run build`)
- [x] Tested on browser

---

## 🎯 Result

```
┌─────────────────────────────────────────────────────────────────┐
│                                                                 │
│                    [Background Image]                           │
│                  (main-bg.jpg - 90% visible)                    │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │                      Navbar                              │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │                   Page Header                            │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐  │
│  │                                                           │  │
│  │                   Content Cards                           │  │
│  │              (with semi-transparent bg)                   │  │
│  │                                                           │  │
│  └─────────────────────────────────────────────────────────┘  │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

**Status:** ✅ **ACTIVE**  
**Last Updated:** 7 Oktober 2025  

Background web sudah aktif dengan gambar `main-bg.jpg` yang jelas terlihat (overlay minimal 10%)! 🎉


