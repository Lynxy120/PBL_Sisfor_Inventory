<?php

require_once __DIR__ . '/../core/Model.php';

class Sale extends Model
{
    protected string $table = 'sales';

    public function getAll()
    {
        $sql = "SELECT s.*, u.nama 
            FROM sales s
            JOIN users u ON s.kasir_id = u.id
            ORDER BY s.tanggal DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    // insert ke tabel sales
    public function create(array $data)
    {
        $sql = "INSERT INTO sales (kasir_id, total, tanggal)
                VALUES (:kasir_id, :total, :tanggal)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kasir_id' => $data['kasir_id'],
            ':total'    => $data['total'],
            ':tanggal'  => $data['tanggal']
        ]);

        return $this->db->lastInsertId();
    }

    // insert ke tabel sales_items
    public function addItem(array $data)
    {
        $sql = "INSERT INTO sales_items (sale_id, item_id, jumlah, harga)
                VALUES (:sale_id, :item_id, :jumlah, :harga)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':sale_id' => $data['sale_id'],
            ':item_id' => $data['item_id'],
            ':jumlah'  => $data['jumlah'],
            ':harga'   => $data['harga']
        ]);
    }

    public function findWithItems($id)
    {
        $sqlSale = "SELECT * FROM sales WHERE id = :id";
        $stmt = $this->db->prepare($sqlSale);
        $stmt->execute([':id' => $id]);
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sale) return null;

        $sqlItems = "SELECT si.*, i.nama_barang
                 FROM sales_items si
                 JOIN items i ON si.item_id = i.id
                 WHERE si.sale_id = :id";

        $stmt = $this->db->prepare($sqlItems);
        $stmt->execute([':id' => $id]);
        $sale['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $sale;
    }
}
