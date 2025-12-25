<?php

namespace App\Helpers\Doctor;

class DoctorAuth
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header("Location: /auth/login");
            exit;
        }
    }
}
