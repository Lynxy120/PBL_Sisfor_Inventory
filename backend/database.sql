-- ============================================
-- Database Schema for Inventory/POS UMKM System
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS dbumkm;
USE dbumkm;

-- ============================================
-- Table: users
-- ============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin_gudang', 'sales') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: categories
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    INDEX idx_nama (nama_kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: suppliers
-- ============================================
CREATE TABLE IF NOT EXISTS suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_suppliers VARCHAR(150) NOT NULL,
    kontak VARCHAR(50),
    INDEX idx_nama (nama_suppliers)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: items
-- ============================================
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT NOT NULL,
    nama_barang VARCHAR(150) NOT NULL,
    harga_beli DECIMAL(15, 2) NOT NULL DEFAULT 0,
    harga_jual DECIMAL(15, 2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 0,
    foto VARCHAR(255),
    FOREIGN KEY (kategori_id) REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_nama_barang (nama_barang),
    INDEX idx_kategori (kategori_id),
    INDEX idx_stok (stok)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: sales
-- ============================================
CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kasir_id INT NOT NULL,
    total DECIMAL(15, 2) NOT NULL DEFAULT 0,
    tanggal DATETIME NOT NULL,
    FOREIGN KEY (kasir_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_kasir (kasir_id),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: sales_items
-- ============================================
CREATE TABLE IF NOT EXISTS sales_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    item_id INT NOT NULL,
    jumlah INT NOT NULL,
    harga DECIMAL(15, 2) NOT NULL,
    FOREIGN KEY (sale_id) REFERENCES sales(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_sale (sale_id),
    INDEX idx_item (item_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table: stock_histories
-- ============================================
CREATE TABLE IF NOT EXISTS stock_histories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    supplier_id INT NULL,
    tipe ENUM('in', 'out', 'adjustment') NOT NULL,
    jumlah INT NOT NULL,
    sumber ENUM('supplier', 'sales') NOT NULL,
    ref_id INT NULL,
    keterangan TEXT,
    tanggal DATETIME NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL ON UPDATE CASCADE,
    INDEX idx_item (item_id),
    INDEX idx_supplier (supplier_id),
    INDEX idx_tipe (tipe),
    INDEX idx_tanggal (tanggal)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Sample Data for Testing
-- ============================================

-- Insert sample users
INSERT INTO users (nama, username, password, role) VALUES
('Super Admin', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin'), -- password: password
('Admin Gudang', 'gudang', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin_gudang'), -- password: password
('Kasir 1', 'kasir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sales'); -- password: password

-- Insert sample categories
INSERT INTO categories (nama_kategori) VALUES
('Elektronik'),
('Makanan'),
('Minuman'),
('Pakaian'),
('Alat Tulis');

-- Insert sample suppliers
INSERT INTO suppliers (nama_suppliers, kontak) VALUES
('PT Elektronik Jaya', '081234567890'),
('CV Makanan Sehat', '081234567891'),
('Toko Minuman Fresh', '081234567892'),
('Konveksi ABC', '081234567893');

-- Insert sample items
INSERT INTO items (kategori_id, nama_barang, harga_beli, harga_jual, stok, foto) VALUES
(1, 'Lampu LED 10W', 15000, 22000, 50, NULL),
(1, 'Kabel USB Type-C', 25000, 35000, 100, NULL),
(2, 'Mie Instan Goreng', 2500, 3500, 200, NULL),
(2, 'Biskuit Cokelat', 5000, 7000, 150, NULL),
(3, 'Air Mineral 600ml', 2000, 3000, 300, NULL),
(3, 'Teh Botol', 3500, 5000, 250, NULL),
(4, 'Kaos Polos Putih', 30000, 50000, 75, NULL),
(5, 'Pulpen Hitam', 1500, 2500, 500, NULL);

-- Sample sales
INSERT INTO sales (kasir_id, total, tanggal) VALUES
(3, 10500, '2026-01-09 10:30:00'),
(3, 45000, '2026-01-09 14:15:00');

-- Sample sales_items
INSERT INTO sales_items (sale_id, item_id, jumlah, harga) VALUES
(1, 3, 3, 3500),
(2, 1, 2, 22000),
(2, 8, 4, 2500);

-- Sample stock_histories
INSERT INTO stock_histories (item_id, supplier_id, tipe, jumlah, sumber, ref_id, keterangan, tanggal) VALUES
(1, 1, 'in', 50, 'supplier', NULL, 'Stok awal', '2026-01-01 08:00:00'),
(2, 1, 'in', 100, 'supplier', NULL, 'Stok awal', '2026-01-01 08:00:00'),
(3, 2, 'in', 200, 'supplier', NULL, 'Stok awal', '2026-01-01 08:00:00'),
(1, NULL, 'out', 2, 'sales', 2, 'Penjualan', '2026-01-09 14:15:00'),
(3, NULL, 'out', 3, 'sales', 1, 'Penjualan', '2026-01-09 10:30:00');

-- ============================================
-- Default password for all sample users: password
-- Use this for testing login
-- ============================================
