<?php

require_once __DIR__ . '/../core/Model.php';

class SaleItem extends Model
{
    protected string $table = 'sales_items';

    public function store($saleId, $itemId, $qty, $harga): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO sales_items (sale_id, item_id, jumlah, harga)
             VALUES (:sale, :item, :jumlah, :harga)"
        );

        return $stmt->execute([
            'sale'   => $saleId,
            'item'   => $itemId,
            'jumlah' => $qty,
            'harga'  => $harga
        ]);
    }

    public function getBySale($saleId)
    {
        $stmt = $this->db->prepare(
            "SELECT si.*, i.nama_barang
             FROM sales_items si
             JOIN items i ON i.id = si.item_id
             WHERE si.sale_id = :sale"
        );
        $stmt->execute(['sale' => $saleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
