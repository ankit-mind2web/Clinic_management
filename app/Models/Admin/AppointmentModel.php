<?php

namespace App\Models\Admin;

use App\Core\Model;
use PDO;

class AppointmentModel extends Model
{
    /* Count all appointments (admin dashboard) */
    public function countAll(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM appointments"
        );

        return (int) $stmt->fetchColumn();
    }

    /* Get recent appointments for dashboard */
    public function getRecent(int $limit = 5): array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.status,
                p.full_name AS patient_name,
                d.full_name AS doctor_name
             FROM appointments a
             JOIN users p ON p.id = a.patient_id
             JOIN users d ON d.id = a.doctor_id
             ORDER BY a.start_utc DESC
             LIMIT ?"
        );

        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Get all appointments (admin view) */
    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.status,
                p.full_name AS patient_name,
                d.full_name AS doctor_name
             FROM appointments a
             JOIN users p ON p.id = a.patient_id
             JOIN users d ON d.id = a.doctor_id
             ORDER BY a.start_utc DESC"
        );

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* Get appointment by ID */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.*,
                p.full_name AS patient_name,
                p.email AS patient_email,
                d.full_name AS doctor_name,
                d.email AS doctor_email
             FROM appointments a
             JOIN users p ON p.id = a.patient_id
             JOIN users d ON d.id = a.doctor_id
             WHERE a.id = ?
             LIMIT 1"
        );

        $stmt->execute([$id]);
        $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

        return $appointment ?: null;
    }
}
