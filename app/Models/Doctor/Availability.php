<?php

namespace App\Models\Doctor;

use App\Core\Model;
use PDO;

class Availability extends Model
{
    public function add($doctorId, $date, $from, $to)
    {
        $stmt = $this->db->prepare("
            INSERT INTO doctor_availability (doctor_id, date, time_from, time_to)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$doctorId, $date, $from, $to]);
    }

    public function getAll($doctorId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM doctor_availability WHERE doctor_id = ?
        ");
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
