<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Sale.php';
require_once __DIR__ . '/../models/Item.php';

class ReportController extends Controller
{
    public function salesReport()
    {
        $this->auth(['super_admin']);
        $input = $this->getJsonInput();

        $start = $input['start'] ?? date('Y-m-d', strtotime('-30 days'));
        $end = $input['end'] ?? date('Y-m-d');

        $db = Database::connect();

        $sql = "SELECT 
                    DATE(s.tanggal) as tanggal,
                    COUNT(s.id) as total_transaksi,
                    SUM(s.total) as total_penjualan
                FROM sales s
                WHERE DATE(s.tanggal) BETWEEN :start AND :end
                GROUP BY DATE(s.tanggal)
                ORDER BY tanggal DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':start' => $start,
            ':end' => $end
        ]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        Response::json([
            'status' => 'success',
            'data' => $data,
            'period' => [
                'start' => $start,
                'end' => $end
            ]
        ]);
    }

    public function stockReport()
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $db = Database::connect();

        $sql = "SELECT i.*, c.nama_kategori
                FROM items i
                JOIN categories c ON i.kategori_id = c.id
                ORDER BY i.stok ASC";

        $data = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $this->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function lowStockReport()
    {
        $this->auth(['super_admin', 'admin_gudang']);
        $input = $this->getJsonInput();
        $threshold = $input['threshold'] ?? 10;

        $db = Database::connect();

        $sql = "SELECT i.*, c.nama_kategori
                FROM items i
                JOIN categories c ON i.kategori_id = c.id
                WHERE i.stok <= :threshold
                ORDER BY i.stok ASC";

        $stmt = $db->prepare($sql);
        $stmt->execute([':threshold' => $threshold]);
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->json([
            'status' => 'success',
            'data' => $data,
            'threshold' => $threshold
        ]);
    }

    public function topSellingItems()
    {
        $this->auth(['super_admin']);
        $input = $this->getJsonInput();

        $start = $input['start'] ?? date('Y-m-d', strtotime('-30 days'));
        $end = $input['end'] ?? date('Y-m-d');
        $limit = $input['limit'] ?? 10;

        $db = Database::connect();

        $sql = "SELECT 
                    i.id,
                    i.nama_barang,
                    c.nama_kategori,
                    SUM(si.jumlah) as total_terjual,
                    SUM(si.jumlah * si.harga) as total_pendapatan
                FROM sales_items si
                JOIN items i ON si.item_id = i.id
                JOIN categories c ON i.kategori_id = c.id
                JOIN sales s ON si.sale_id = s.id
                WHERE DATE(s.tanggal) BETWEEN :start AND :end
                GROUP BY i.id, i.nama_barang, c.nama_kategori
                ORDER BY total_terjual DESC
                LIMIT :limit";

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':start', $start, PDO::PARAM_STR);
        $stmt->bindValue(':end', $end, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->json([
            'status' => 'success',
            'data' => $data,
            'period' => [
                'start' => $start,
                'end' => $end
            ]
        ]);
    }
}
