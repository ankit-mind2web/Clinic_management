<?php

namespace App\Services;

use App\Core\Database;
use App\Models\User;
use PDO;

class AuthService
{
    private PDO $db;
    private User $userModel;

    public function __construct()
    {
        // get database connection
        $this->db = Database::getConnection();

        // initialize user model
        $this->userModel = new User();
    }

    //  REGISTER 
    public function register(array $data): bool
    {
        // check duplicate email or mobile
        if ($this->userModel->exists($data['email'], $data['mobile'])) {
            return false;
        }

        // hash password before saving
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // create user record
        $this->userModel->create($data);

        return true;
    }

    //  EMAIL EXISTS (AJAX CHECK) 
    public function emailExists(string $email): bool
    {
        $sql = "SELECT id FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch() !== false;
    }

    // verify email token
    public function verifyEmailToken(string $token): bool
    {
        $sql = "SELECT id, email_token_expires 
            FROM users 
            WHERE email_verify_token = :token 
            AND email_verified = 0 
            LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (strtotime($user['email_token_expires']) < time()) {
            return false;
        }

        $update = $this->db->prepare("
        UPDATE users
        SET email_verified = 1,
            email_verify_token = NULL,
            email_token_expires = NULL
        WHERE id = :id
    ");
        $update->execute(['id' => $user['id']]);

        return true;
    }


    //  LOGIN 
    public function login(string $login, string $password): ?array
    {
        // fetch user by email or mobile
        $user = $this->userModel->findByEmailOrMobile($login);

        // user not found
        if (!$user) {
            return null;
        }

        // verify password
        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        // check account status
        // if ($user['status'] !== 'active') {
        //     return null;
        // }

        // authentication successful
        return $user;
    }
}
