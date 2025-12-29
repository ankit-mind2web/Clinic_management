<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Services\AuthService;
use PDO;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        // start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->authService = new AuthService();
    }

    //  REGISTER 
    public function register()
    {
        // GET request → show register page
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/register');
            return;
        }

        // POST request → AJAX only
        header('Content-Type: application/json');
        ob_clean();

        // collect inputs
        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $mobile   = trim($_POST['mobile'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role     = trim($_POST['role'] ?? '');

        // required field validation
        if ($fullName === '' || $email === '' || $mobile === '' || $password === '' || $role === '') {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            exit;
        }

        // role validation
        if (!in_array($role, ['patient', 'doctor'], true)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
            exit;
        }

        // full name validation (letters and spaces, 3–50 chars)
        if (!preg_match('/^[A-Za-z ]{3,50}$/', $fullName)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid full name']);
            exit;
        }

        // email format validation
        if (!preg_match('/^[\w.-]{1,25}@([\w-]+\.)+[\w-]{2,4}$/', $email)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            exit;
        }

        // mobile validation (exactly 10 digits)
        if (!preg_match('/^\d{10}$/', $mobile)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number']);
            exit;
        }

        // password strength validation
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Password must contain uppercase, lowercase, number and special character'
            ]);
            exit;
        }

        // call service to register user
        $success = $this->authService->register([
            'full_name' => $fullName,
            'email'     => $email,
            'mobile'    => $mobile,
            'password'  => $password,
            'role'      => $role
        ]);

        // duplicate email or mobile
        if (!$success) {
            echo json_encode(['status' => 'error', 'message' => 'Email or mobile already exists']);
            exit;
        }

        // success response
        echo json_encode([
            'status'   => 'success',
            'redirect' => '/auth/login'
        ]);
        exit;
    }

    //  LOGIN 
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/login');
            return;
        }

        header('Content-Type: application/json');

        $login    = trim($_POST['login'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($login === '' || $password === '') {
            echo json_encode(['status' => 'error', 'message' => 'All fields required']);
            exit;
        }

        $emailRegex  = '/^[a-z0-9._-]{1,25}@([a-z0-9-]+\.)+[a-z]{2,4}$/';
        $mobileRegex = '/^\d{10}$/';

        if (!preg_match($emailRegex, $login) && !preg_match($mobileRegex, $login)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email or mobile number']);
            exit;
        }

        $user = $this->authService->login($login, $password);

        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
            exit;
        }

        $_SESSION['user'] = [
            'id'             => $user['id'],
            'full_name'      => $user['full_name'],
            'email'          => $user['email'],
            'mobile'         => $user['mobile'],
            'role'           => strtolower($user['role']),
            'email_verified' => (int)$user['email_verified']
        ];

        $redirect = match ($user['role']) {
            'admin'   => '/admin/dashboard',
            'doctor'  => '/doctor/dashboard',
            'patient' => '/patient/dashboard',
            default   => '/'
        };

        echo json_encode(['status' => 'success', 'redirect' => $redirect]);
        exit;
    }


    // verify email link
    public function verifyEmail()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_GET['token'] ?? '';
        if ($token === '') {
            die('Invalid verification token');
        }

        $db = Database::getConnection();

        $stmt = $db->prepare(
            "SELECT id, role, email_token_expires 
            FROM users 
            WHERE email_verify_token = ?"
        );
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die('Invalid token');
        }

        if (strtotime($user['email_token_expires']) < time()) {
            die('Verification link expired');
        }

        $stmt = $db->prepare(
            "UPDATE users 
            SET email_verified = 1,
            email_verify_token = NULL,
            email_token_expires = NULL
            WHERE id = ?"
        );
        $stmt->execute([$user['id']]);

        // update session
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $user['id']) {
            $_SESSION['user']['email_verified'] = 1;
        }

        // redirect by role
        if ($user['role'] === 'doctor') {
            header('Location: /doctor/specialization');
        } else {
            header('Location: /patient/profile');
        }

        exit;
    }


    //  LOGOUT 
    public function logout()
    {
        // clear session
        session_unset();
        session_destroy();

        // redirect to login
        header('Location: /auth/login');
        exit;
    }
}
