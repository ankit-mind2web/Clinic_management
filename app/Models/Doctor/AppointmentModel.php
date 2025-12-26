<?php

namespace App\Models\Doctor;

use App\Core\Model;
use PDO;

class AppointmentModel extends Model
{
    public function appointmentCount(int $doctorId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM appointments WHERE doctor_id = ?"
        );
        $stmt->execute([$doctorId]);
        return (int)$stmt->fetchColumn();
    }

    public function countTodayAppointment(int $doctorId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
         FROM appointments
         WHERE doctor_id = ?
           AND DATE(start_utc) = CURDATE()"
        );
        $stmt->execute([$doctorId]);
        return (int)$stmt->fetchColumn();
    }


    public function getRecent(int $doctorId, int $limit): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.full_name AS patient_name
             FROM appointments a
             JOIN users u ON u.id = a.patient_id
             WHERE a.doctor_id = ?
             ORDER BY a.start_utc DESC
             LIMIT ?"
        );
        $stmt->bindValue(1, $doctorId, PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(int $doctorId): array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.full_name AS patient_name
             FROM appointments a
             JOIN users u ON u.id = a.patient_id
             WHERE a.doctor_id = ?
             ORDER BY a.start_utc DESC"
        );
        $stmt->execute([$doctorId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, int $doctorId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.full_name AS patient_name
             FROM appointments a
             JOIN users u ON u.id = a.patient_id
             WHERE a.id = ? AND a.doctor_id = ?"
        );
        $stmt->execute([$id, $doctorId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
