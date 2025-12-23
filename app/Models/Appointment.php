<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Appointment extends Model
{
    /* Get recent appointments for admin dashboard */
    public function getRecent(int $limit = 5): array
    {
        $sql = "
            SELECT
                a.id,
                a.start_utc,
                a.status,
                p.full_name AS patient_name,
                d.full_name AS doctor_name
            FROM appointments a
            JOIN users p ON p.id = a.patient_id
            JOIN users d ON d.id = a.doctor_id
            ORDER BY a.start_utc DESC
            LIMIT ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Count total appointments */
    public function countAll(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM appointments");
        return (int) $stmt->fetchColumn();
    }

    /* Count today's appointments */
    public function countToday(): int
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM appointments
            WHERE DATE(start_utc) = CURDATE()
        ");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
