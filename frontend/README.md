# Frontend - Sistem Inventory UMKM "Grosiran Ayah"

**Version:** 1.0.0  
**Last Updated:** 21 Januari 2026

## 🎯 Overview

Frontend untuk Sistem Inventory UMKM "Grosiran Ayah" - aplikasi web berbasis PHP dan JavaScript untuk mengelola inventori barang, supplier, kategori, stok, dan laporan penjualan secara real-time.

---

## 📋 Daftar Fitur Utama

- ✅ **Dashboard** - Overview data dan statistik real-time
- ✅ **Autentikasi** - Login/Register dengan role-based access
- ✅ **Data Master:**
  - Manajemen Barang (CRUD + photo upload)
  - Manajemen Kategori (CRUD)
  - Manajemen Supplier (CRUD)
  - Manajemen User (CRUD, role management)
- ✅ **Stok Management:**
  - Stock In (pembelian)
  - Stock Out (pengeluaran)
  - Stock Adjust (penyesuaian)
  - Riwayat Stok dengan filter tanggal
- ✅ **Laporan & Analisis:**
  - Sales Report (penjualan per periode)
  - Stock Report (status stok semua barang)
  - Low Stock Alert (peringatan stok rendah)
  - Top Selling Products (produk terlaris)
- ✅ **User Management:**
  - Profile settings
  - Password change
  - Preferences
- ✅ **Responsive Design** - Mobile-friendly UI dengan Bootstrap 5

---

## 🛠️ Tech Stack

| Teknologi    | Versi  | Kegunaan                        |
| ------------ | ------ | ------------------------------- |
| PHP          | 7.4+   | Server-side rendering & session |
| HTML5        | Latest | Markup structure                |
| CSS3         | Latest | Styling & responsive design     |
| Bootstrap    | 5.2.3  | UI Framework                    |
| JavaScript   | ES6+   | Frontend logic & interactions   |
| Chart.js     | 2.8.0  | Data visualization              |
| DataTables   | 7.1.2  | Advanced table features         |
| FontAwesome  | 6.3.0  | Icons                           |
| Fetch API    | Native | API communication               |
| LocalStorage | Native | Session management              |

---

## 📁 Struktur Folder

```
frontend/
├── index.php                      # Dashboard (home)
├── login.php                      # Login page
├── register.php                   # Register page
├── table_stock.php                # Data barang (Items)
├── kategori.php                   # Kategori management
├── supplier.php                   # Supplier management
├── kelolastock.php                # Stock management (in/out/adjust)
├── riwayatstock.php               # Stock history
├── laporan.php                    # Reports & analytics
├── manajemenuser.php              # User management
├── settings.php                   # User settings/profile
├── password.php                   # Password change
├── charts.php                     # Chart demo
├── 404.php                        # 404 error page
├── 401.php                        # 401 unauthorized page
├── 500.php                        # 500 server error page
├── includes/                      # Shared components
│   ├── config.php                 # Configuration & API base URL
│   ├── head.php                   # HTML head & meta tags
│   ├── header.php                 # Top navigation bar
│   ├── sidebar.php                # Left sidebar menu
│   ├── footer.php                 # Footer
│   └── scripts.php                # Script references
├── js/                            # JavaScript files
│   ├── api.js                     # API helper functions
│   ├── validation.js              # Form validation utilities
│   ├── scripts.js                 # Page-specific logic
│   └── datatables-simple-demo.js  # DataTables initialization
├── css/                           # Stylesheets
│   └── styles.css                 # Main stylesheet (Bootstrap + custom)
└── assets/                        # Static files
    ├── demo/                      # Demo charts
    └── img/                       # Images
```

---

## 🚀 Installation & Setup

### Prerequisites

- PHP 7.4 atau lebih tinggi
- Web Server (Apache dengan mod_rewrite)
- Browser modern (Chrome, Firefox, Edge, Safari)
- Backend API server berjalan (http://localhost:8001)

### Step-by-Step Installation

#### 1. Clone/Download Project

```bash
# Jika menggunakan git
git clone <repository-url>
cd umkm/frontend

# Atau extract file ZIP ke folder web server
```

#### 2. Configure Backend URL

Edit file `includes/config.php`:

```php
<?php
// Development
define('API_BASE_URL', 'http://localhost:8001');

// Production (uncomment for production)
// define('API_BASE_URL', 'https://your-domain.com/backend/public');
```

#### 3. Setup Web Server

**Untuk Apache:**

```bash
# Pastikan mod_rewrite enabled
a2enmod rewrite
systemctl restart apache2

# Buat symlink atau copy ke htdocs
cp -r frontend /var/www/html/umkm_frontend
```

**Untuk Development dengan PHP Built-in Server:**

```bash
cd frontend
php -S localhost:8000
# Akses: http://localhost:8000
```

#### 4. Verify Installation

1. Buka http://localhost/umkm/frontend/login.php di browser
2. Coba login dengan credential dari backend
3. Check browser console (F12) untuk error messages

---

## 🔑 User Credentials (Default)

Gunakan username/password dari database backend:

| Role         | Username     | Password | Akses                 |
| ------------ | ------------ | -------- | --------------------- |
| Super Admin  | admin        | password | Semua fitur           |
| Admin Gudang | admin_gudang | password | Barang, Stok, Laporan |
| Sales/Kasir  | sales        | password | Laporan, Riwayat      |

---

## 📚 File Documentation

### Configuration Files

**`includes/config.php`**

- Sets API_BASE_URL
- Initializes session
- Defines constants
- Gets user info from $\_SESSION

**`includes/head.php`**

- Meta tags & charset
- CSS links (Bootstrap, FontAwesome, custom styles)
- Custom styling untuk sidebar & tables
- Responsive meta viewport

**`includes/header.php`**

- Top navigation bar
- User dropdown menu
- Search functionality
- Logout button

**`includes/sidebar.php`**

- Left navigation menu
- Menu items dengan active state
- User info display
- Responsive menu toggle

**`includes/scripts.php`**

- Script references di urutan yang benar
- Bootstrap bundle
- API helper (api.js)
- Validation utilities (validation.js)
- DataTables
- Custom scripts

### JavaScript Files

**`js/api.js`**

- Base API configuration
- Authentication headers setup
- Generic request functions (apiRequest, apiRequestFormData)
- Category/Supplier/Item/User API functions
- Stock management functions
- Report functions
- Utility functions (formatting, alerts)

**`js/validation.js`** (NEW)

- Form validation functions
- Field validation (required, number, email, phone)
- File upload validation
- Password strength validation
- Generic form validation
- Error display functions

**`js/scripts.js`**

- Page-specific JavaScript logic
- Event listeners
- Form handlers
- DOM manipulation

**`js/datatables-simple-demo.js`**

- DataTables initialization
- Table configuration
- Sorting & pagination setup

### CSS Files

**`css/styles.css`**

- Bootstrap 5 framework
- Custom sidebar styling
- Table enhancements
- Form styling
- Alert styling
- Responsive utilities
- Color scheme customization

---

## 🔗 API Integration

### Base URL

```
Development: http://localhost:8001
Production: https://your-domain.com/backend/public
```

### Authentication Headers

Setiap request ke API **harus** include headers berikut:

```javascript
headers: {
  "Content-Type": "application/json",
  "X-User-Id": localStorage.getItem("user_id"),
  "X-User-Role": localStorage.getItem("user_role")
}
```

### Response Format

**Success Response (200-299):**

```json
{
  "status": "success",
  "message": "Optional message",
  "data": {
    /* data */
  }
}
```

**Error Response (4xx-5xx):**

```json
{
  "status": "error",
  "message": "Error description"
}
```

### Main Endpoints Used

| Endpoint           | Method              | Deskripsi           |
| ------------------ | ------------------- | ------------------- |
| `/login`           | POST                | Login user          |
| `/users`           | GET/POST/PUT/DELETE | User management     |
| `/categories`      | GET/POST/PUT/DELETE | Category management |
| `/items`           | GET/POST/PUT/DELETE | Item management     |
| `/suppliers`       | GET/POST/PUT/DELETE | Supplier management |
| `/stock-in`        | POST                | Add stock           |
| `/stock-out`       | POST                | Remove stock        |
| `/stock-adjust`    | POST                | Adjust stock        |
| `/stock-histories` | GET/POST            | Stock history       |
| `/reports/*`       | GET/POST            | Various reports     |

---

## 💾 Session Management

### Session Storage (localStorage)

```javascript
// Saat login
localStorage.setItem("user_id", response.data.id);
localStorage.setItem("user_name", response.data.nama);
localStorage.setItem("user_role", response.data.role);
localStorage.setItem("auth_token", response.data.token); // jika ada

// Saat logout
localStorage.clear();
```

### Check Login Status

```javascript
if (!isLoggedIn()) {
  window.location.href = "login.php";
}
```

---

## 🎨 Customization

### Change Color Scheme

Edit di `includes/head.php` atau `css/styles.css`:

```css
/* Sidebar gradient */
.sb-sidenav-dark {
  background: linear-gradient(135deg, #1e1e2f 0%, #2d2d44 100%);
}

/* Active nav link */
.sb-sidenav-dark .nav-link.active {
  background: linear-gradient(90deg, #4e54c8 0%, #8f94fb 100%);
}
```

### Change Logo/Brand

Edit `includes/sidebar.php`:

```php
<div class="sidebar-brand">
  <h4><i class="fas fa-boxes me-2"></i>Your Brand Name</h4>
  <small>Your Tagline</small>
</div>
```

### Change Dashboard Cards Colors

Edit `index.php` - gunakan Bootstrap color classes:

- `bg-primary` (blue)
- `bg-success` (green)
- `bg-danger` (red)
- `bg-warning` (yellow)
- `bg-info` (cyan)
- `bg-dark` (dark gray)

---

## 🐛 Troubleshooting

### Issue: "Unauthorized" atau "403 Forbidden"

**Penyebab:** User belum login atau session expired

**Solusi:**

```javascript
// Check login status
console.log("Logged in:", isLoggedIn());
console.log("User role:", getCurrentUser().role);

// Re-login jika perlu
if (!isLoggedIn()) {
  window.location.href = "login.php";
}
```

### Issue: API Request Failed / "Network Error"

**Penyebab:** Backend tidak berjalan atau URL salah

**Solusi:**

1. Verify backend API berjalan: `http://localhost:8001`
2. Check `API_BASE_URL` di `includes/config.php`
3. Check browser console untuk error details (F12)
4. Verify CORS settings di backend

### Issue: Form tidak bisa submit

**Penyebab:** Validation error atau field kosong

**Solusi:**

```javascript
// Check form values
const form = document.getElementById("formId");
console.log(form.elements);

// Manual validation
if (validateRequired("fieldId", "Field Name")) {
  // Submit form
}
```

### Issue: Table tidak load data

**Penyebab:** API gagal atau format response salah

**Solusi:**

```javascript
// Test API response
getItems().then((res) => {
  console.log("API Response:", res);
  if (res.status === "success") {
    console.log("Data:", res.data);
  }
});
```

### Issue: CSS/Styling tidak apply

**Penyebab:** File CSS tidak ter-load atau cache

**Solusi:**

```bash
# Hard refresh browser
Ctrl+Shift+R (Windows/Linux)
Cmd+Shift+R (Mac)

# Check dalam DevTools (F12) > Network tab
# Verify css/styles.css loading dengan status 200
```

### Issue: Login tidak bekerja

**Penyebab:** Credential salah atau backend error

**Solusi:**

```javascript
// Test login
login("admin", "password")
  .then((res) => console.log("Login result:", res))
  .catch((err) => console.error("Login error:", err));
```

---

## 🔒 Security Best Practices

1. **HTTPS Only (Production)**
   - Always use HTTPS for production
   - Enable HSTS header

2. **Secure Session**
   - Don't store sensitive data in localStorage
   - Clear session on logout
   - Use secure session cookies

3. **Input Validation**
   - Validate all inputs client-side
   - Always validate server-side too
   - Escape output untuk prevent XSS

4. **CORS & CSRF**
   - Setup proper CORS di backend
   - Implement CSRF tokens untuk form submissions
   - Validate origin headers

5. **File Upload Security**
   - Validate file type & size
   - Store files outside web root
   - Rename files dengan random name

---

## 📱 Browser Support

| Browser | Min Version | Status           |
| ------- | ----------- | ---------------- |
| Chrome  | 90+         | ✅ Full Support  |
| Firefox | 88+         | ✅ Full Support  |
| Safari  | 14+         | ✅ Full Support  |
| Edge    | 90+         | ✅ Full Support  |
| IE 11   | Any         | ❌ Not Supported |

---

## 📞 Support & Contribution

### Reporting Issues

1. Check dokumentasi terlebih dahulu
2. Lihat browser console (F12) untuk error messages
3. Cek API Documentation di backend
4. Report dengan detail: browser, OS, steps to reproduce

### Contributing

1. Fork repository
2. Create feature branch
3. Make changes & test thoroughly
4. Submit pull request

---

## 📄 License

Sistem Inventory UMKM "Grosiran Ayah"  
Dikembangkan oleh: **Kelompok 6**  
Tahun: **2026**

---

## 📚 Additional Resources

- **API Documentation:** `/backend/API_DOCUMENTATION.md`
- **Feature Documentation:** `DOKUMENTASI_FITUR_FRONTEND.md`
- **Bootstrap 5:** https://getbootstrap.com/docs/5.2
- **DataTables:** https://www.datatables.net/
- **FontAwesome:** https://fontawesome.com/

---

**Last Updated:** 21 Januari 2026  
**Maintainer:** Kelompok 6
