<?php

namespace App\Models\Doctor;

use App\Core\Model;
use PDO;

class DoctorProfile extends Model
{
    public function get(int $userId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM doctor_profiles WHERE user_id = ?"
        );
        $stmt->execute([$userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function save(int $userId, string $bio, int $experience): void
    {
        $stmt = $this->db->prepare(
            "INSERT INTO doctor_profiles (user_id, bio, experience)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE
             bio = VALUES(bio),
             experience = VALUES(experience)"
        );
        $stmt->execute([$userId, $bio, $experience]);
    }

    public function saveToken(int $userId, string $token, string $expiry): void
    {
        $stmt = $this->db->prepare(
            "UPDATE doctor_profiles
             SET verify_token = ?, token_expiry = ?
             WHERE user_id = ?"
        );
        $stmt->execute([$token, $expiry, $userId]);
    }

    public function verifyByToken(string $token): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE doctor_profiles
             SET email_verified = 1, verify_token = NULL, token_expiry = NULL
             WHERE verify_token = ? AND token_expiry > NOW()"
        );
        $stmt->execute([$token]);

        return $stmt->rowCount() > 0;
    }
}
