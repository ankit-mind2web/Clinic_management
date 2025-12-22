<?php
namespace App\Services;

use App\Models\User;

class AuthService
{
    
    // Register a new user
    public function register(array $data): bool
    {
        $userModel = new User();

        // check duplicate
        if ($userModel->exists($data['email'], $data['mobile'])) {
            return false;
        }

        // hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        // create user
        $userModel->create($data);

        return true;
    }

    /**
     * Login user
     */
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
