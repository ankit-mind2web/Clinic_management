<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    /* Check if user exists (email or mobile) */
    public function exists(string $email, string $mobile): bool
    {
        $sql = "SELECT id FROM users WHERE email = ? OR mobile = ? LIMIT 1";
        return $this->fetch($sql, [$email, $mobile]) !== null;
    }

    /* Create new user */
    public function create(array $data): void
    {
        $sql = "
            INSERT INTO users (full_name, email, mobile, password_hash, role, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ";

        $this->db->prepare($sql)->execute([
            $data['full_name'],
            $data['email'],
            $data['mobile'],
            $data['password'],
            $data['role']
        ]);
    }

    /* Find by email or mobile */
    public function findByEmailOrMobile(string $login): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :login OR mobile = :login LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':login', $login);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /* Count users by role */
    public function countByRole(string $role): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE role = ? AND status = 'active'"
        );
        $stmt->execute([$role]);

        return (int) $stmt->fetchColumn();
    }

    /* Count pending doctors */
    public function countPendingDoctors(): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE role = 'doctor' AND status = 'pending'"
        );
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    /* Get all doctors */
    public function getDoctors(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM users WHERE role = 'doctor' ORDER BY id DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Update user status */
    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $id]);
    }
}
