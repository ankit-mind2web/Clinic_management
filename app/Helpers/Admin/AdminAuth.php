<?php

namespace App\Helpers\Admin;

class AdminAuth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header("Location: /auth/login");
            exit;
        }

        if ($_SESSION['user']['role'] !== 'admin') {
            die('Access denied');
        }
    }
}
