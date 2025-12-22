<?php
namespace App\Core;

use App\Core\Database as CoreDatabase;
use PDO;
use Database;
use PDOStatement;
class Model
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = CoreDatabase::getConnection();
    }

    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    protected function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    protected function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    protected function execute(string $sql, array $params = []): bool
    {
        return $this->query($sql, $params)->rowCount() > 0;
    }
}
