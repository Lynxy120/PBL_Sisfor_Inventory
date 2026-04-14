# API Documentation - Inventory/POS UMKM System

Base URL: `http://localhost/backend/public`

## Authentication

Most endpoints require authentication headers:

- `X-User-Id`: User ID from login
- `X-User-Role`: User role (`super_admin`, `admin_gudang`, `sales`)

## Response Format

All responses follow this format:

```json
{
  "status": "success|error",
  "message": "Optional message",
  "data": {}
}
```

---

## Authentication Endpoints

### POST /login

Login to the system

**Request Body:**

```json
{
  "username": "admin",
  "password": "password"
}
```

**Response (200):**

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "nama": "Super Admin",
    "role": "super_admin"
  }
}
```

---

## User Management

**Required Role:** `super_admin`

### GET /users

Get all users

**Response (200):**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "nama": "Super Admin",
      "username": "admin",
      "role": "super_admin",
      "created_at": "2026-01-01 00:00:00"
    }
  ]
}
```

### GET /users/{id}

Get specific user

### POST /users

Create new user

**Request Body:**

```json
{
  "nama": "John Doe",
  "username": "johndoe",
  "password": "password123",
  "role": "admin_gudang"
}
```

### PUT /users/{id}

Update user (excludes password)

**Request Body:**

```json
{
  "nama": "John Doe Updated",
  "username": "johndoe",
  "role": "sales"
}
```

### DELETE /users/{id}

Delete user

---

## Categories

### GET /categories

Get all categories

### GET /categories/{id}

Get specific category

### POST /categories

Create category

**Request Body:**

```json
{
  "nama_kategori": "Elektronik"
}
```

### PUT /categories/{id}

Update category

### DELETE /categories/{id}

Delete category

---

## Suppliers

### GET /suppliers

Get all suppliers

### GET /suppliers/{id}

Get specific supplier

### POST /suppliers

Create supplier

**Request Body:**

```json
{
  "nama_suppliers": "PT Jaya Abadi",
  "kontak": "081234567890"
}
```

### PUT /suppliers/{id}

Update supplier

### DELETE /suppliers/{id}

Delete supplier

---

## Items

**Required Role:** `super_admin`, `admin_gudang` (for create, update, delete)

### GET /items

Get all items with category info

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "kategori_id": 1,
      "nama_barang": "Lampu LED",
      "harga_beli": 15000,
      "harga_jual": 22000,
      "stok": 50,
      "foto": "item_123.jpg",
      "nama_kategori": "Elektronik"
    }
  ]
}
```

### GET /items/{id}

Get specific item

### POST /items

Create item (supports multipart/form-data for photo upload)

**Request (multipart/form-data):**

```
kategori_id: 1
nama_barang: Lampu LED
harga_beli: 15000
harga_jual: 22000
stok: 50
foto: [file]
```

### PUT /items/{id}

Update item (supports multipart/form-data)

### DELETE /items/{id}

Delete item

### POST /items/{id}/photo

Upload/update item photo

**Request (multipart/form-data):**

```
foto: [file]
```

---

## Stock Management

### POST /stock-in

Add stock to item

**Request Body:**

```json
{
  "item_id": 1,
  "jumlah": 20,
  "supplier_id": 1,
  "keterangan": "Pembelian bulanan"
}
```

### POST /stock-out

Remove stock from item

**Request Body:**

```json
{
  "item_id": 1,
  "jumlah": 5,
  "keterangan": "Barang rusak"
}
```

### POST /stock-adjust

Adjust stock to specific amount

**Request Body:**

```json
{
  "item_id": 1,
  "stok_baru": 100,
  "keterangan": "Stock opname"
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Stok berhasil disesuaikan",
  "stok_lama": 50,
  "stok_baru": 100
}
```

---

## Stock History

### GET /stock-histories

Get all stock history with item and supplier info

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "item_id": 1,
      "supplier_id": 1,
      "tipe": "in",
      "jumlah": 50,
      "sumber": "supplier",
      "ref_id": null,
      "keterangan": "Stok awal",
      "tanggal": "2026-01-01 08:00:00",
      "nama_barang": "Lampu LED",
      "nama_suppliers": "PT Jaya Abadi"
    }
  ]
}
```

### GET /stock-histories/{id}

Get specific stock history entry

### GET /items/{id}/stock-history

Get stock history for specific item

### POST /stock-histories/date-range

Get stock history by date range

**Request Body:**

```json
{
  "start": "2026-01-01",
  "end": "2026-01-31"
}
```

---

## Sales

**Required Role:** `sales` (for creating sales)

### GET /sales

Get all sales with cashier info

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "kasir_id": 3,
      "total": 10500,
      "tanggal": "2026-01-09 10:30:00",
      "nama": "Kasir 1"
    }
  ]
}
```

### GET /sales/{id}

Get sale details with items

**Response:**

```json
{
  "status": "success",
  "data": {
    "id": 1,
    "kasir_id": 3,
    "total": 10500,
    "tanggal": "2026-01-09 10:30:00",
    "items": [
      {
        "id": 1,
        "sale_id": 1,
        "item_id": 3,
        "jumlah": 3,
        "harga": 3500,
        "nama_barang": "Mie Instan"
      }
    ]
  }
}
```

### POST /sales

Create new sale

**Request Body:**

```json
{
  "kasir_id": 3,
  "items": [
    {
      "item_id": 1,
      "jumlah": 2
    },
    {
      "item_id": 3,
      "jumlah": 5
    }
  ]
}
```

**Response:**

```json
{
  "status": "success",
  "message": "Transaksi berhasil",
  "sale_id": 1,
  "total": 61500
}
```

**Notes:**

- Stock is automatically decreased
- Stock history is automatically created
- Price is taken from item's current `harga_jual`

---

## Reports

### POST /reports/sales

Get sales report by date range

**Request Body:**

```json
{
  "start": "2026-01-01",
  "end": "2026-01-31"
}
```

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "tanggal": "2026-01-09",
      "total_transaksi": 2,
      "total_penjualan": 55500
    }
  ],
  "period": {
    "start": "2026-01-01",
    "end": "2026-01-31"
  }
}
```

### GET /reports/stock

Get current stock levels for all items

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "kategori_id": 1,
      "nama_barang": "Lampu LED",
      "harga_beli": 15000,
      "harga_jual": 22000,
      "stok": 48,
      "foto": null,
      "nama_kategori": "Elektronik"
    }
  ]
}
```

### POST /reports/low-stock

Get items with stock below threshold

**Request Body:**

```json
{
  "threshold": 10
}
```

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 5,
      "nama_barang": "Produk A",
      "stok": 5,
      "nama_kategori": "Kategori X"
    }
  ],
  "threshold": 10
}
```

### POST /reports/top-selling

Get top selling items

**Request Body:**

```json
{
  "start": "2026-01-01",
  "end": "2026-01-31",
  "limit": 10
}
```

**Response:**

```json
{
  "status": "success",
  "data": [
    {
      "id": 3,
      "nama_barang": "Mie Instan",
      "nama_kategori": "Makanan",
      "total_terjual": 150,
      "total_pendapatan": 525000
    }
  ],
  "period": {
    "start": "2026-01-01",
    "end": "2026-01-31"
  }
}
```

---

## Error Responses

**400 Bad Request:**

```json
{
  "status": "error",
  "message": "Data tidak lengkap"
}
```

**401 Unauthorized:**

```json
{
  "status": "error",
  "message": "Unauthorized"
}
```

**403 Forbidden:**

```json
{
  "status": "error",
  "message": "Forbidden"
}
```

**404 Not Found:**

```json
{
  "status": "error",
  "message": "Item tidak ditemukan"
}
```

**422 Unprocessable Entity:**

```json
{
  "status": "error",
  "message": "item_id tidak valid"
}
```

**500 Internal Server Error:**

```json
{
  "status": "error",
  "message": "Gagal menyimpan data"
}
```

---

## Testing with cURL

### Login

```bash
curl -X POST http://localhost/backend/public/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

### Create Sale

```bash
curl -X POST http://localhost/backend/public/sales \
  -H "Content-Type: application/json" \
  -H "X-User-Id: 3" \
  -H "X-User-Role: sales" \
  -d '{"kasir_id":3,"items":[{"item_id":1,"jumlah":2}]}'
```

### Get Stock Report

```bash
curl http://localhost/backend/public/reports/stock
```
