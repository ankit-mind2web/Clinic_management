<?php

namespace App\Models\Doctor;

use App\Core\Database;
use App\Core\Model;
use DateTime;
use PDO;

class DoctorAvailability extends Model
{
    /* =======================
       CREATE AVAILABILITY (DOCTOR)
       ======================= */
    public function createSlot(
        int $doctorId,
        string $startUtc,
        string $endUtc,
        string $status
    ): void {
        // prevent overlapping availability
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
            die('Overlapping availability not allowed');
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

        // 🔑 generate slots immediately
        $this->generateSlots($doctorId, $startUtc, $endUtc);
    }

    /* =======================
       DOCTOR VIEW (AVAILABILITY)
       ======================= */
    public function getDoctorAvailability(int $doctorId): array
    {
        $sql = "
            SELECT id, start_utc, end_utc, status
            FROM doctor_availability
            WHERE doctor_id = :doctor_id
            ORDER BY start_utc
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
            JOIN doctor_availability da ON da.doctor_id = u.id
            WHERE u.role = 'doctor'
              AND da.status = 'available'
              AND da.start_utc > NOW()
            ORDER BY u.full_name
        ";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       SLOT GENERATION (SYSTEM)
       ======================= */
    public function generateSlots(
        int $doctorId,
        string $startUtc,
        string $endUtc
    ): void {
        $db = Database::getConnection();

        $start = new DateTime($startUtc);
        $end   = new DateTime($endUtc);

        while ($start < $end) {
            $slotStart = $start->format('Y-m-d H:i:s');
            $start->modify('+30 minutes');
            $slotEnd = $start->format('Y-m-d H:i:s');

            $stmt = $db->prepare("
                INSERT IGNORE INTO slots (doctor_id, start_utc, end_utc)
                VALUES (:doctor_id, :start_utc, :end_utc)
            ");

            $stmt->execute([
                ':doctor_id' => $doctorId,
                ':start_utc' => $slotStart,
                ':end_utc'   => $slotEnd
            ]);
        }
    }

    /* =======================
       PATIENT VIEW (SLOTS)
       ======================= */
    public function getAvailableSlotsForPatient(int $doctorId): array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT id, start_utc, end_utc
            FROM slots
            WHERE doctor_id = :doctor_id
              AND is_blocked = 0
              AND start_utc > NOW()
            ORDER BY start_utc
        ");
        $stmt->execute([':doctor_id' => $doctorId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* =======================
       SLOT BY ID (FOR BOOKING)
       ======================= */
    public function getSlotById(int $slotId): ?array
    {
        $db = Database::getConnection();

        $stmt = $db->prepare("
            SELECT id, doctor_id, start_utc, end_utc
            FROM slots
            WHERE id = :id
        ");
        $stmt->execute([':id' => $slotId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
