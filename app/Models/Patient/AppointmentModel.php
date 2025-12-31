<?php

namespace App\Models\Patient;

use App\Core\Model;
use PDO;

class AppointmentModel extends Model
{
    public function create(int $patientId, int $slotId): void
    {
        $sql = "
            INSERT INTO appointments (patient_id, availability_id, created_at)
            VALUES (:patient_id, :availability_id, NOW())
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':patient_id'      => $patientId,
            ':availability_id' => $slotId
        ]);
    }
}
