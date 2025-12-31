<?php
namespace App\Models\Admin;

use App\Core\Database;
use PDO;

class DoctorModel
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function getPendingDoctors(): array
    {
        $stmt = $this->conn->prepare(
            "SELECT id, full_name, email
            FROM users
            WHERE role = 'doctor' and status = 'pending'"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);    
    }

    public function approveDoctor(int $id) : void
    {
        $stmt = $this->conn->prepare(
            "UPDATE users SET status ='active' WHERE id=:id and role='doctor' "
        );
        $stmt->execute(['id' => $id]);
    }

    public function rejectDoctor(int $id) : void
    {
        $stmt = $this->conn->prepare(
            "UPDATE users SET status ='blocked' WHERE id=:id AND role='doctor' "
        );
        $stmt->execute(['id' => $id]);
    }
}