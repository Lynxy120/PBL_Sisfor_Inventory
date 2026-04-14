<?php
require_once __DIR__ . '/../core/Model.php';

class Supplier extends Model
{
    protected string $table = 'suppliers';

    public function getAll()
    {
        return $this->db
            ->query("SELECT * FROM suppliers ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO suppliers (nama_suppliers, kontak)
             VALUES (:nama, :kontak)"
        );
        $stmt->execute([
            ':nama' => $data['nama_suppliers'],
            ':kontak' => $data['kontak']
        ]);
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE suppliers SET nama_suppliers=:nama, kontak=:kontak WHERE id=:id"
        );
        $stmt->execute([
            ':id' => $id,
            ':nama' => $data['nama_suppliers'],
            ':kontak' => $data['kontak']
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM suppliers WHERE id=:id");
        $stmt->execute([':id' => $id]);
    }
}
