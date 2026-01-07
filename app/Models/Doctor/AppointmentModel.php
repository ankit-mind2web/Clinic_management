<?php

namespace App\Models\Doctor;

use App\Core\Model;
use PDO;

class AppointmentModel extends Model
{
    /* =======================
       TOTAL APPOINTMENTS
       ======================= */
    public function appointmentCount(int $doctorId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
             FROM appointments
             WHERE doctor_id = ?"
        );
        $stmt->execute([$doctorId]);
        return (int) $stmt->fetchColumn();
    }

    /* =======================
       TODAY'S APPOINTMENTS
       ======================= */
    public function countTodayAppointment(int $doctorId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
             FROM appointments
             WHERE doctor_id = ?
               AND DATE(start_utc) = CURDATE()"
        );
        $stmt->execute([$doctorId]);
        return (int) $stmt->fetchColumn();
    }
     public function getAppointmentsByDoctor(int $doctorId, int $limit = 100, int $offset = 0): array
    {
        $sql = "
            SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.status,
                u.full_name AS patient_name,
                u.email AS patient_email
            FROM appointments a
            JOIN users u ON u.id = a.patient_id
            WHERE a.doctor_id = :doctor_id
            ORDER BY a.start_utc ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':doctor_id', $doctorId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /* =======================
       RECENT APPOINTMENTS
       ======================= */
    public function getRecent(int $doctorId, int $limit): array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.created_at,
                u.full_name AS patient_name
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

    /* =======================
       ALL APPOINTMENTS
       ======================= */
    public function getAll(int $doctorId): array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.created_at,
                u.full_name AS patient_name
             FROM appointments a
             JOIN users u ON u.id = a.patient_id
             WHERE a.doctor_id = ?
             ORDER BY a.start_utc DESC"
        );
        $stmt->execute([$doctorId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       SINGLE APPOINTMENT
       ======================= */
    public function getById(int $id, int $doctorId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                a.id,
                a.start_utc,
                a.end_utc,
                a.created_at,
                u.full_name AS patient_name
             FROM appointments a
             JOIN users u ON u.id = a.patient_id
             WHERE a.id = ?
               AND a.doctor_id = ?"
        );
        $stmt->execute([$id, $doctorId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    /* =======================
       UPDATE STATUS
       ======================= */
    public function updateStatus(int $appointmentId, int $doctorId, string $status): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE appointments 
             SET status = :status 
             WHERE id = :id AND doctor_id = :doctor_id"
        );
        
        return $stmt->execute([
            'status' => $status,
            'id' => $appointmentId,
            'doctor_id' => $doctorId
        ]);
    }

    /* =======================
       AUTO-COMPLETE PAST
       ======================= */
    public function autoCompletePastAppointments(int $doctorId): void
    {
        // Mark as 'completed' if status is 'confirmed' AND end time is in the past
        // We use UTC comparison since start_utc/end_utc are stored in UTC
        $stmt = $this->db->prepare(
            "UPDATE appointments
             SET status = 'completed'
             WHERE doctor_id = :doctor_id
               AND status = 'confirmed'
               AND end_utc < UTC_TIMESTAMP()"
        );
        $stmt->execute(['doctor_id' => $doctorId]);
    }
}
