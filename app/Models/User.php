<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    /*  Check if user already exists  */
    public function exists(string $email, string $mobile): bool
    {
        $sql = "SELECT id FROM users WHERE email = ? OR mobile = ? LIMIT 1";
        return $this->fetch($sql, [$email, $mobile]) !== null;
    }

    /*  Create new user  */
    public function create(array $data): void
    {
        $sql = "
            INSERT INTO users (full_name, email, mobile, password_hash, role)
            VALUES (?, ?, ?, ?, ?)";

        $this->db->prepare($sql)->execute([
            $data['full_name'],
            $data['email'],
            $data['mobile'],
            $data['password'],
            $data['role']
        ]);
    }

    /*  Login helper  */
    public function findByEmailOrMobile(string $login): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ? OR mobile = ? LIMIT 1";
        return $this->fetch($sql, [$login, $login]);
    }
}
