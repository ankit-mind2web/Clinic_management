<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* ================= REGISTER ================= */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name     = trim($_POST['full_name'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $mobile   = trim($_POST['mobile'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role     = trim($_POST['role'] ?? '');

            if ($name === '' || $email === '' || $mobile === '' || $password === '' || $role === '') {
                $this->view('auth/register', ['error' => 'All fields are required']);
                return;
            }

            if (!in_array($role, ['patient', 'doctor'], true)) {
                $this->view('auth/register', ['error' => 'Invalid role']);
                return;
            }

            $authService = new AuthService();

            $success = $authService->register([
                'full_name' => $name,
                'email'     => $email,
                'mobile'    => $mobile,
                'password'  => $password,
                'role'      => $role
            ]);

            if (!$success) {
                $this->view('auth/register', ['error' => 'Email or mobile already exists']);
                return;
            }

            header("Location: /auth/login");
            exit;
        }

        $this->view('auth/register');
    }

    /* ================= LOGIN ================= */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $login    = trim($_POST['login'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($login === '' || $password === '') {
                $this->view('auth/login', ['error' => 'All fields are required']);
                return;
            }

            $authService = new AuthService();
            $user = $authService->login($login, $password);

            if (!$user) {
                $this->view('auth/login', ['error' => 'Invalid credentials']);
                return;
            }

            if (($user['status'] ?? 'active') !== 'active') {
                $this->view('auth/login', ['error' => 'Account not active']);
                return;
            }

            // security best practice
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id'    => $user['id'],
                'name'  => $user['full_name'], // IMPORTANT
                'email' => $user['email'],
                'role'  => $user['role']
            ];

            if ($user['role'] === 'admin') {
                header("Location: /admin/dashboard");
            } elseif ($user['role'] === 'doctor') {
                header("Location: /doctor/dashboard");
            } else {
                header("Location: /patient/dashboard");
            }
            exit;
        }

        $this->view('auth/login');
    }

    /* ================= LOGOUT ================= */
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: /auth/login");
        exit;
    }
}
