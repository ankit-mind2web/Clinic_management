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

    // get profile by user id (profile.id = users.id)
    public function getByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM profile WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId]);

        $profile = $stmt->fetch(PDO::FETCH_ASSOC);
        return $profile ?: null;
    }

    // insert or update profile
    public function saveOrUpdate(int $userId, string $gender, string $dob, string $address): void
    {
        // check if profile exists
        $exists = $this->getByUserId($userId);

        if ($exists) {
            // update
            $sql = "UPDATE profile
                    SET gender = :gender,
                        dob = :dob,
                        address = :address
                    WHERE id = :id";
        } else {
            // insert
            $sql = "INSERT INTO profile (id, gender, dob, address, status)
                    VALUES (:id, :gender, :dob, :address, 'Pending')";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id'      => $userId,
            'gender'  => $gender,
            'dob'     => $dob,
            'address' => $address
        ]);
    }

    // save email verification token in users table
    public function saveEmailToken(int $userId, string $token, string $expiry): void
    {
        $sql = "UPDATE users
                SET email_verify_token = :token,
                    email_token_expires = :expiry
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'token'  => $token,
            'expiry' => $expiry,
            'id'     => $userId
        ]);
    }

    // verify email using token
    public function verifyEmailByToken(string $token): ?array
    {
        // find user with valid token
        $sql = "SELECT id
                FROM users
                WHERE email_verify_token = :token
                  AND email_token_expires > NOW()
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        // mark email as verified and clear token
        $update = "UPDATE users
                   SET email_verified = 1,
                       email_verify_token = NULL,
                       email_token_expires = NULL
                   WHERE id = :id";

        $stmt = $this->db->prepare($update);
        $stmt->execute(['id' => $user['id']]);

        return $user;
    }
}
