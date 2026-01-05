<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class Profile
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // get profile by user_id
    public function getByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM profile WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // insert or update profile (CORRECT)
    public function saveOrUpdate(int $userId, string $gender, string $dob, string $address, int $emailVerified): void
{
    $status = $emailVerified === 1 ? 'Verified' : 'Pending';

    $sql = "
        INSERT INTO profile (user_id, gender, dob, address, status)
        VALUES (:user_id, :gender, :dob, :address, :status)
        ON DUPLICATE KEY UPDATE
            gender  = VALUES(gender),
            dob     = VALUES(dob),
            address = VALUES(address),
            status  = VALUES(status)
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'gender'  => $gender,
        'dob'     => $dob,
        'address' => $address,
        'status'  => $status
    ]);
}


    // save email verification token
    public function saveEmailToken(int $userId, string $token, string $expiry): void
    {
        $sql = "
            UPDATE users
            SET email_verify_token = :token,
                email_token_expires = :expiry
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'token'  => $token,
            'expiry' => $expiry,
            'id'     => $userId
        ]);
    }

    // verify email
    public function verifyEmailByToken(string $token): ?array
    {
        $sql = "
            SELECT id
            FROM users
            WHERE email_verify_token = :token
              AND email_token_expires > NOW()
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        $update = "
            UPDATE users
            SET email_verified = 1,
                email_verify_token = NULL,
                email_token_expires = NULL
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($update);
        $stmt->execute(['id' => $user['id']]);

        return $user;
    }
}
