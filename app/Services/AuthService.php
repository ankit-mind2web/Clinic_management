<?php

namespace App\Services;

use App\Core\Database;
use App\Models\User;
use PDO;

class AuthService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /* ================= REGISTER ================= */
    public function register(array $data): bool
    {
        $userModel = new User();

        // check duplicate email or mobile
        if ($userModel->exists($data['email'], $data['mobile'])) {
            return false;
        }

        // hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // create user
        $userModel->create($data);

        return true;
    }

    /* ================= EMAIL EXISTS (AJAX CHECK) ================= */
    public function emailExists(string $email): bool
    {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    /* ================= LOGIN ================= */
    public function login(string $login, string $password): ?array
    {
        $userModel = new User();
        $user = $userModel->findByEmailOrMobile($login);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
