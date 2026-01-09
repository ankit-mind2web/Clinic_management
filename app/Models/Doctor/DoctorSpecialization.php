<?php

namespace App\Models\Doctor;

use App\Core\Model;

class DoctorSpecialization extends Model
{
    public function getAllSpecializations()
    {
        return $this->fetchAll(
            "SELECT id, name, description FROM specializations ORDER BY name"
        );
    }

    /* GET ALL SPECIALIZATIONS OF DOCTOR */
    public function getByDoctorAll($doctorId)
    {
        return $this->fetchAll(
            "SELECT ds.experience, s.name AS specialization_name
         FROM doctor_specialization ds
         JOIN specializations s ON s.id = ds.specialization_id
         WHERE ds.doctor_id = ?",
            [$doctorId]
        );
    }

    /* CHECK DUPLICATE */
    public function exists($doctorId, $specializationId)
    {
        return (bool) $this->fetch(
            "SELECT id FROM doctor_specialization
             WHERE id = ? AND specialization_id = ?",
            [$doctorId, $specializationId]
        );
    }

    /* ADD SPECIALIZATION */
    public function add($doctorId, $specializationId, $experience)
    {
        return $this->execute(
            "INSERT INTO doctor_specialization (doctor_id, specialization_id, experience)
         VALUES (?, ?, ?)",
            [$doctorId, $specializationId, $experience]
        );
    }


    /* EMAIL TOKEN */
    public function saveToken($doctorId, $token, $expiry)
    {
        return $this->execute(
            "UPDATE users
             SET email_verify_token = ?, email_token_expires = ?
             WHERE id = ?",
            [$token, $expiry, $doctorId]
        );
    }
    /* GET BY NAME */
    public function getByName($name)
    {
        return $this->fetch(
            "SELECT * FROM specializations WHERE name = ?",
            [$name]
        );
    }
}
