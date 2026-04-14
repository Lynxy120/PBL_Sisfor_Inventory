# Backend Inventory/POS UMKM System

Backend API untuk sistem inventory dan point of sale (POS) UMKM menggunakan PHP native dengan arsitektur MVC sederhana.

## Fitur

- ✅ Manajemen User dengan role-based access control (super_admin, admin_gudang, sales)
- ✅ Manajemen Kategori Produk
- ✅ Manajemen Supplier
- ✅ Manajemen Item/Barang dengan upload foto
- ✅ Manajemen Stok (stock in, stock out, adjustment)
- ✅ Riwayat Pergerakan Stok
- ✅ Transaksi Penjualan
- ✅ Laporan (penjualan, stok, item terlaris, stok rendah)
- ✅ RESTful API dengan CORS support
- ✅ Authentication dengan custom headers

## Tech Stack

- PHP 7.4+
- MySQL 5.7+
- Architecture: MVC Pattern
- No framework dependencies (PHP native)

## Setup

### 1. Clone Repository

```bash
git clone <repository-url>
cd backend
```

### 2. Database Setup

```bash
# Login to MySQL
mysql -u root -p

# Import database schema
CREATE DATABASE dbumkm;
USE dbumkm;
SOURCE database.sql;
```

### 3. Configure Database

Copy `.env.example` untuk referensi konfigurasi database. Edit `config/database.php`:

```php
return [
    'host' => 'localhost',
    'dbname' => 'dbumkm',
    'username' => 'root',
    'password' => '',
];
```

### 4. Web Server Setup

#### Option A: PHP Built-in Server (Development)

```bash
cd public
php -S localhost:8000
```

API akan tersedia di `http://localhost:8000`

#### Option B: Apache/Nginx

Arahkan document root ke folder `public/`

Contoh virtual host Apache:

```apache
<VirtualHost *:80>
    ServerName inventory-api.local
    DocumentRoot "/path/to/backend/public"

    <Directory "/path/to/backend/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 5. File Upload Directory

Pastikan folder `storage/uploads` memiliki permission write:

```bash
chmod -R 775 storage/uploads
```

## Default Users

Database schema sudah include sample users:

| Username | Password | Role         |
| -------- | -------- | ------------ |
| admin    | password | super_admin  |
| gudang   | password | admin_gudang |
| kasir    | password | sales        |

## API Documentation

Dokumentasi lengkap API tersedia di [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### Quick Start - Testing API

```bash
# Health check
curl http://localhost:8000/

# Login
curl -X POST http://localhost:8000/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'

# Get all items
curl http://localhost:8000/items

# Create sale (requires authentication)
curl -X POST http://localhost:8000/sales \
  -H "Content-Type: application/json" \
  -H "X-User-Id: 3" \
  -H "X-User-Role: sales" \
  -d '{"kasir_id":3,"items":[{"item_id":1,"jumlah":2}]}'
```

## Project Structure

```
backend/
├── app/
│   ├── controllers/        # Controllers for handling requests
│   │   ├── AuthController.php
│   │   ├── CategoryController.php
│   │   ├── ItemController.php
│   │   ├── SaleController.php
│   │   ├── StockController.php
│   │   ├── StockHistoryController.php
│   │   ├── SupplierController.php
│   │   ├── UserController.php
│   │   └── ReportController.php
│   ├── models/            # Database models
│   │   ├── Category.php
│   │   ├── Item.php
│   │   ├── Sale.php
│   │   ├── SaleItem.php
│   │   ├── StockHistory.php
│   │   ├── Supplier.php
│   │   └── User.php
│   ├── core/              # Core framework files
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Model.php
│   │   ├── Response.php
│   │   └── Router.php
│   └── helpers/           # Helper classes
│       ├── Auth.php
│       └── Upload.php
├── config/
│   └── database.php       # Database configuration
├── public/
│   └── index.php          # Entry point
├── routes/
│   └── api.php            # API route definitions
├── storage/
│   └── uploads/           # Uploaded files storage
├── database.sql           # Database schema
├── .env.example           # Environment config example
├── API_DOCUMENTATION.md   # Complete API documentation
└── README.md              # This file
```

## Database Schema

### Tables

- `users` - User accounts with roles
- `categories` - Product categories
- `suppliers` - Supplier information
- `items` - Products/items with pricing and stock
- `sales` - Sales transactions
- `sales_items` - Sales line items
- `stock_histories` - Stock movement history

### Entity Relationship

```
users (1) ----< (N) sales
categories (1) ----< (N) items
suppliers (1) ----< (N) stock_histories
items (1) ----< (N) sales_items
items (1) ----< (N) stock_histories
sales (1) ----< (N) sales_items
```

## Authentication

API menggunakan header-based authentication:

```
X-User-Id: <user_id dari login>
X-User-Role: <role dari user>
```

Endpoint `/login` akan mengembalikan user data yang perlu disimpan di client untuk digunakan sebagai headers.

## Role-Based Access Control

| Endpoint              | super_admin | admin_gudang   | sales          |
| --------------------- | ----------- | -------------- | -------------- |
| User Management       | ✅          | ❌             | ❌             |
| Categories            | ✅          | ✅             | ✅ (read only) |
| Suppliers             | ✅          | ✅             | ✅ (read only) |
| Items (Create/Update) | ✅          | ✅             | ❌             |
| Stock Management      | ✅          | ✅             | ❌             |
| Sales                 | ✅          | ✅ (read only) | ✅             |
| Reports               | ✅          | ✅             | ✅             |

## Development Notes

### Adding New Endpoint

1. Create/update controller method di `app/controllers/`
2. Add route di `routes/api.php`
3. Update API documentation

### Error Handling

Semua error response menggunakan format:

```json
{
  "status": "error",
  "message": "Error description"
}
```

HTTP status codes yang digunakan:

- 200: Success
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Unprocessable Entity
- 500: Internal Server Error

## License

MIT License

## Support

Untuk pertanyaan atau issue, silakan buat issue di repository ini.
