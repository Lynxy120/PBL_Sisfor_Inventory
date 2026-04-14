<?php

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    protected string $table = 'users';

    public function all()
    {
        return $this->db->query("SELECT id, nama, username, role, created_at FROM users")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare(
            "SELECT id, nama, username, role, created_at FROM users WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function store($data): bool
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (nama, username, password, role)
             VALUES (:nama, :username, :password, :role)"
        );

        return $stmt->execute([
            'nama' => $data['nama'],
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'role' => $data['role']
        ]);
    }

    public function update($id, $data): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET nama = :nama, username = :username, role = :role WHERE id = :id"
        );

        return $stmt->execute([
            'nama' => $data['nama'],
            'username' => $data['username'],
            'role' => $data['role'],
            'id' => $id
        ]);
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE username = :username LIMIT 1"
        );
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
