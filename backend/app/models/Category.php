<?php
require_once __DIR__ . '/../core/Model.php';

class Category extends Model
{
    protected string $table = 'categories';

    public function getAll()
    {
        return $this->db
            ->query("SELECT * FROM categories ORDER BY id DESC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($nama)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO categories (nama_kategori) VALUES (:nama)"
        );
        $stmt->execute([':nama' => $nama]);
    }

    public function update($id, $nama)
    {
        $stmt = $this->db->prepare(
            "UPDATE categories SET nama_kategori = :nama WHERE id = :id"
        );
        $stmt->execute([
            ':id' => $id,
            ':nama' => $nama
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare(
            "DELETE FROM categories WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
    }
}
