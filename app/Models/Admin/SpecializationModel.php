<?php

namespace App\Models\Admin;

use App\Core\Model;
use PDO;

class SpecializationModel extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM specializations ORDER BY id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM specializations WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $name): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO specializations (name) VALUES (?)"
        );
        $stmt->execute([$name]);
    }

    public function update(int $id, string $name): void
    {
        $stmt = $this->db->prepare(
            "UPDATE specializations SET name = ? WHERE id = ?"
        );
        $stmt->execute([$name, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM specializations WHERE id = ?"
        );
        $stmt->execute([$id]);
    }
}
