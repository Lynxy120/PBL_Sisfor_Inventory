<?php

require_once __DIR__ . '/../core/Model.php';

class Item extends Model
{
    protected string $table = 'items';

    public function getAll()
    {
        $sql = "SELECT i.*, c.nama_kategori
                FROM items i
                JOIN categories c ON i.kategori_id = c.id
                ORDER BY i.id DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO items
                (kategori_id, nama_barang, harga_beli, harga_jual, stok, foto)
                VALUES
                (:kategori_id, :nama, :hb, :hj, :stok, :foto)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':kategori_id' => $data['kategori_id'],
            ':nama' => $data['nama_barang'],
            ':hb' => $data['harga_beli'],
            ':hj' => $data['harga_jual'],
            ':stok' => $data['stok'],
            ':foto' => $data['foto']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE items SET
            kategori_id = :kategori_id,
            nama_barang = :nama,
            harga_beli = :hb,
            harga_jual = :hj,
            stok = :stok,
            foto = :foto
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':kategori_id' => $data['kategori_id'],
            ':nama' => $data['nama_barang'],
            ':hb' => $data['harga_beli'],
            ':hj' => $data['harga_jual'],
            ':stok' => $data['stok'],
            ':foto' => $data['foto'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM items WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function categoryExists($kategori_id): bool
    {
        $stmt = $this->db->prepare(
            "SELECT id FROM categories WHERE id = :id"
        );
        $stmt->execute(['id' => $kategori_id]);
        return (bool) $stmt->fetch();
    }

    public function find($id)
    {
        $sql = "SELECT * FROM items WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateStock($id, $stok)
    {
        $stmt = $this->db->prepare(
            "UPDATE items SET stok = :stok WHERE id = :id"
        );
        return $stmt->execute([
            'stok' => $stok,
            'id'   => $id
        ]);
    }


    public function updatePhoto($id, $filename)
    {
        $stmt = $this->db->prepare(
            "UPDATE items SET foto = :foto WHERE id = :id"
        );

        return $stmt->execute([
            'foto' => $filename,
            'id'   => $id
        ]);
    }

    public function decreaseStock($item_id, $jumlah)
    {
        $sql = "UPDATE items
            SET stok = stok - :jumlah
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':jumlah' => $jumlah,
            ':id'     => $item_id
        ]);
    }
}
