<?php

require_once __DIR__ . '/../core/Model.php';

class StockHistory extends Model
{
    protected string $table = 'stock_histories';

    public function create(array $data)
    {
        $sql = "INSERT INTO stock_histories 
                (item_id, supplier_id, tipe, jumlah, sumber, ref_id, keterangan, tanggal)
                VALUES 
                (:item_id, :supplier_id, :tipe, :jumlah, :sumber, :ref_id, :keterangan, :tanggal)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':item_id'     => $data['item_id'],
            ':supplier_id' => $data['supplier_id'],
            ':tipe'        => $data['tipe'],
            ':jumlah'      => $data['jumlah'],
            ':sumber'      => $data['sumber'],
            ':ref_id'      => $data['ref_id'],
            ':keterangan'  => $data['keterangan'],
            ':tanggal'     => $data['tanggal']
        ]);
    }

    public function getAll()
    {
        $sql = "SELECT sh.*, i.nama_barang, s.nama_suppliers
                FROM stock_histories sh
                JOIN items i ON sh.item_id = i.id
                LEFT JOIN suppliers s ON sh.supplier_id = s.id
                ORDER BY sh.tanggal DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByItem($item_id)
    {
        $sql = "SELECT sh.*, s.nama_suppliers
                FROM stock_histories sh
                LEFT JOIN suppliers s ON sh.supplier_id = s.id
                WHERE sh.item_id = :item_id
                ORDER BY sh.tanggal DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':item_id' => $item_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDateRange($start, $end)
    {
        $sql = "SELECT sh.*, i.nama_barang, s.nama_suppliers
                FROM stock_histories sh
                JOIN items i ON sh.item_id = i.id
                LEFT JOIN suppliers s ON sh.supplier_id = s.id
                WHERE sh.tanggal BETWEEN :start AND :end
                ORDER BY sh.tanggal DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':start' => $start,
            ':end' => $end
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
