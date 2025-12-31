<?php

namespace App\Models\Patient;

use App\Core\Model;
use PDO;
use Exception;

class AppointmentModel extends Model
{
    /**
     * BOOK APPOINTMENT (CORRECT & FINAL)
     * - Book strictly by slot_id
     * - Prevents double booking
     * - Blocks slot atomically
     */
    public function bookBySlotId(int $patientId, int $slotId): bool
    {
        try {
            $this->db->beginTransaction();

            // 1️⃣ Lock and fetch free slot by ID
            $slotStmt = $this->db->prepare("
                SELECT id, doctor_id, start_utc, end_utc
                FROM slots
                WHERE id = :id
                  AND is_blocked = 0
                FOR UPDATE
            ");
            $slotStmt->execute([':id' => $slotId]);

            $slot = $slotStmt->fetch(PDO::FETCH_ASSOC);

            if (!$slot) {
                $this->db->rollBack();
                return false; // already booked or invalid slot
            }

            // 2️⃣ Insert appointment
            $insertStmt = $this->db->prepare("
                INSERT INTO appointments
                    (slot_id, patient_id, doctor_id, start_utc, end_utc, status, created_at)
                VALUES
                    (:slot_id, :patient_id, :doctor_id, :start_utc, :end_utc, 'booked', NOW())
            ");

            $insertStmt->execute([
                ':slot_id'    => $slot['id'],
                ':doctor_id'  => $slot['doctor_id'],
                ':patient_id' => $patientId,
                ':start_utc'  => $slot['start_utc'],
                ':end_utc'    => $slot['end_utc']
            ]);

            // 3️⃣ Block slot
            $blockStmt = $this->db->prepare("
                UPDATE slots
                SET is_blocked = 1
                WHERE id = :id
            ");
            $blockStmt->execute([':id' => $slot['id']]);

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
