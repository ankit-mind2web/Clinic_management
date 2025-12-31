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
    //find all doctors
    public function getAllDoctors(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, full_name, status
         FROM users
         WHERE role = 'doctor'
         ORDER BY created_at DESC
         LIMIT ?"
        );

        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Get all patients with profile */
    public function getPatients(): array
    {
        $sql = "
        SELECT 
            u.id,
            u.full_name,
            u.email,
            u.mobile,
            u.status,
            u.created_at,
            p.gender,
            p.dob,
            p.address
        FROM users u
        LEFT JOIN profile p ON p.user_id = u.id
        WHERE u.role = 'patient'
        ORDER BY u.created_at DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    /* Count users by role (active only) */
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

    // Doctor management (Admin)
    public function getDoctors(): array
    {
        $sql = "
        SELECT 
            u.id,
            u.full_name,
            u.email,
            u.mobile,
            u.status,
            u.created_at,
            p.dob
        FROM users u
        LEFT JOIN profile p ON p.id = u.id
        WHERE u.role = 'doctor'
        ORDER BY u.created_at DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /* Get pending doctors */
    public function getPendingDoctors(): array
    {
        $stmt = $this->db->prepare(
            "SELECT id, full_name, email, mobile, created_at
             FROM users
             WHERE role = 'doctor'
               AND status = 'pending'
             ORDER BY id DESC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Update user status (approve / block) */
    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $id]);
    }
}
