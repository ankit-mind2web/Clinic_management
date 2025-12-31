<?php

namespace App\Models\Admin;

use App\Core\Model;
use PDO;

class SpecializationModel extends Model
{
    // Build WHERE clause for search
    private function buildWhere(string $search = ''): array
    {
        $where  = "WHERE 1=1";
        $params = [];

        // Search by name or description
        if ($search !== '') {
            $where .= " AND (name LIKE :search OR description LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        return [$where, $params];
    }

    // Get all specializations (backward compatible)
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM specializations ORDER BY id DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get specialization by ID
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM specializations WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Create specialization
    public function create(string $name, string $description = ''): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO specializations (name, description) VALUES (?, ?)"
        );
        $stmt->execute([$name, $description]);
    }

    // Update specialization
    public function update(int $id, string $name, string $description = ''): void
    {
        $stmt = $this->db->prepare(
            "UPDATE specializations SET name = ?, description = ? WHERE id = ?"
        );
        $stmt->execute([$name, $description, $id]);
    }

    // Delete specialization
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare(
            "DELETE FROM specializations WHERE id = ?"
        );
        $stmt->execute([$id]);
    }

    // Get paginated specializations with search
    public function getPaginated(
        int $limit,
        int $offset,
        string $search = ''
    ): array {
        [$where, $params] = $this->buildWhere($search);

        $sql = "
            SELECT *
            FROM specializations
            $where
            ORDER BY id DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count specializations with search
    public function countFiltered(string $search = ''): int
    {
        [$where, $params] = $this->buildWhere($search);

        $sql = "SELECT COUNT(*) FROM specializations $where";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    // Count all specializations
    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) FROM specializations";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
