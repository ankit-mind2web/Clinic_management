<?php

namespace App\Models\Doctor;

use App\Core\Model;
use PDO;

class DoctorAvailability extends Model
{
    /* =======================
       CREATE SLOT (DOCTOR)
       ======================= */
    public function createSlot(
        int $doctorId,
        string $startUtc,
        string $endUtc,
        string $status
    ): void {
        // Prevent overlapping slots
        $checkSql = "
            SELECT id
            FROM doctor_availability
            WHERE doctor_id = :doctor_id
              AND start_utc < :end_utc
              AND end_utc > :start_utc
        ";

        $stmt = $this->db->prepare($checkSql);
        $stmt->execute([
            ':doctor_id' => $doctorId,
            ':start_utc' => $startUtc,
            ':end_utc'   => $endUtc
        ]);

        if ($stmt->rowCount() > 0) {
            die('Overlapping slot not allowed');
        }

        $insertSql = "
            INSERT INTO doctor_availability
                (doctor_id, start_utc, end_utc, status)
            VALUES
                (:doctor_id, :start_utc, :end_utc, :status)
        ";

        $stmt = $this->db->prepare($insertSql);
        $stmt->execute([
            ':doctor_id' => $doctorId,
            ':start_utc' => $startUtc,
            ':end_utc'   => $endUtc,
            ':status'    => $status
        ]);
    }

    /* =======================
       DOCTOR VIEW (ALL SLOTS)
       ======================= */
    public function getDoctorAvailability(int $doctorId): array
    {
        $sql = "
            SELECT id, start_utc, end_utc, status
            FROM doctor_availability
            WHERE doctor_id = :doctor_id
            ORDER BY start_utc ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doctor_id' => $doctorId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       DOCTORS WITH FUTURE AVAILABILITY
       ======================= */
    public function getDoctorsWithAvailability(): array
    {
        $sql = "
            SELECT DISTINCT u.id, u.full_name
            FROM users u
            JOIN doctor_availability da
                ON da.doctor_id = u.id
            WHERE u.role = 'doctor'
              AND da.status = 'available'
              AND da.start_utc > NOW()
            ORDER BY u.full_name
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       PATIENT VIEW (AVAILABLE SLOTS)
       ======================= */
    public function getAvailableSlotsForPatient(int $doctorId): array
    {
        $sql = "
            SELECT da.id, da.start_utc, da.end_utc
            FROM doctor_availability da
            WHERE da.doctor_id = :doctor_id
              AND da.status = 'available'
              AND da.start_utc > NOW()
            ORDER BY da.start_utc ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doctor_id' => $doctorId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
