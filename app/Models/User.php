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
        $status = ($data['role'] === 'patient') ? 'active' : 'pending';

        $sql = "
            INSERT INTO users (full_name, email, mobile, password_hash, role, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ";

        $this->db->prepare($sql)->execute([
            $data['full_name'],
            $data['email'],
            $data['mobile'],
            $data['password'],
            $data['role'],
            $status
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
    public function findDoctorById(int $id): ?array
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
            LEFT JOIN profile p ON p.user_id = u.id
            WHERE u.role = 'doctor'
              AND u.id = :id
            LIMIT 1
        ";

        return $this->fetch($sql, ['id' => $id]);
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

    /* PAGINATION SUPPORT */
    public function countDoctors(string $search = ''): int
    {
        $search = trim($search);
        if ($search === '') {
            $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'doctor'");
            return (int) $stmt->fetchColumn();
        }

        $sql = "
            SELECT COUNT(*) 
            FROM users 
            WHERE role = 'doctor' 
              AND (full_name LIKE :s OR email LIKE :s OR mobile LIKE :s)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':s', "%$search%");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getDoctorsPaginated(int $limit, int $offset, string $search = ''): array
    {
        $search = trim($search);
        $searchSql = "";
        $params = [];

        if ($search !== '') {
            $searchSql = "AND (u.full_name LIKE :s OR u.email LIKE :s OR u.mobile LIKE :s)";
        }

        $sql = "
            SELECT u.*, p.dob 
            FROM users u 
            LEFT JOIN profile p ON p.user_id = u.id 
            WHERE u.role = 'doctor' 
            $searchSql
            ORDER BY u.id DESC 
            LIMIT :limit OFFSET :offset
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        
        if ($search !== '') {
            $stmt->bindValue(':s', "%$search%");
        }

        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /* PATIENT PAGINATION + SEARCH */
    public function countPatients(string $search = ''): int
    {
        $search = trim($search);
        if ($search === '') {
            $stmt = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'patient'");
            return (int) $stmt->fetchColumn();
        }

        $sql = "
            SELECT COUNT(*) 
            FROM users 
            WHERE role = 'patient' 
              AND (full_name LIKE :s OR email LIKE :s OR mobile LIKE :s)
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':s', "%$search%");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function getPatientsPaginated(int $limit, int $offset, string $search = ''): array
    {
        $search = trim($search);
        $searchSql = "";
        $params = [];

        if ($search !== '') {
            $searchSql = "AND (u.full_name LIKE :s OR u.email LIKE :s OR u.mobile LIKE :s)";
            $params[':s'] = "%$search%";
        }

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
            $searchSql
            ORDER BY u.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        
        if ($search !== '') {
            $stmt->bindValue(':s', "%$search%");
        }
        
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
    }

    public function getPublicDoctors(int $limit = 6): array
    {
        $sql = "
            SELECT 
                u.id, 
                u.full_name, 
                '' as bio, 
                MAX(ds.experience) as experience, 
                p.gender,
                GROUP_CONCAT(s.name SEPARATOR ', ') as specializations,
                MAX(s.name) as primary_specialization
            FROM users u
            LEFT JOIN profile p ON p.user_id = u.id
            JOIN doctor_specialization ds ON ds.doctor_id = u.id
            JOIN specializations s ON s.id = ds.specialization_id
            WHERE u.role = 'doctor' 
              AND u.status = 'active'
            GROUP BY u.id
            ORDER BY u.created_at ASC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
