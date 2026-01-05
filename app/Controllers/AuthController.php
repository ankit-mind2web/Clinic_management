<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Services\AuthService;
use App\Helpers\Mailer;
use PDO;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->authService = new AuthService();
    }

    // ================= REGISTER =================
    public function register()
    {
        // GET → show register page
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/register');
            return;
        }

        // POST → AJAX
        header('Content-Type: application/json');
        ob_clean();

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $mobile   = trim($_POST['mobile'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role     = trim($_POST['role'] ?? '');

        if ($fullName === '' || $email === '' || $mobile === '' || $password === '' || $role === '') {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
            exit;
        }

        if (!in_array($role, ['patient', 'doctor'], true)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid role']);
            exit;
        }

        if (!preg_match('/^[A-Za-z ]{3,50}$/', $fullName)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid full name']);
            exit;
        }

        if (!preg_match('/^[\w.-]{1,25}@([\w-]+\.)+[\w-]{2,4}$/', $email)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
            exit;
        }

        if (!preg_match('/^\d{10}$/', $mobile)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid mobile number']);
            exit;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/', $password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Password must contain uppercase, lowercase, number and special character'
            ]);
            exit;
        }

        // Register user
        $userId = $this->authService->register([
            'full_name' => $fullName,
            'email'     => $email,
            'mobile'    => $mobile,
            'password'  => $password,
            'role'      => $role
        ]);

        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'Email or mobile already exists']);
            exit;
        }

        // ================= EMAIL VERIFICATION =================
        $token  = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE users
            SET email_verify_token = ?,
                email_token_expires = ?
            WHERE id = ?
        ");
        $stmt->execute([$token, $expiry, $userId]);

        $verifyLink = BASE_URL . '/auth/verify-email?token=' . $token;

        $emailBody = "
            <h3>Email Verification</h3>
            <p>Hello {$fullName},</p>
            <p>Please verify your email by clicking the button below:</p>
            <p>
                <a href='{$verifyLink}'
                   style='display:inline-block;padding:10px 15px;
                          background:#4b6cb7;color:#fff;
                          text-decoration:none;border-radius:5px;'>
                    Verify Email
                </a>
            </p>
            <p>This link will expire in 24 hours.</p>
        ";

        Mailer::send($email, 'Verify Your Email', $emailBody);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Registration successful. Please verify your email.',
            'redirect' => '/auth/login'
        ]);
        exit;
    }

    // ================= LOGIN =================
    public function login()
    {
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
            'status'         => $user['status'],
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

    // ================= VERIFY EMAIL =================
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

        // find user by token
        $stmt = $db->prepare("
        SELECT id, role, email_token_expires
        FROM users
        WHERE email_verify_token = ?
    ");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            die('Invalid token');
        }

        if (strtotime($user['email_token_expires']) < time()) {
            die('Verification link expired');
        }

        //  mark email verified
        $stmt = $db->prepare("
        UPDATE users
        SET email_verified = 1,
            email_verify_token = NULL,
            email_token_expires = NULL
        WHERE id = ?
    ");
        $stmt->execute([$user['id']]);

        // update profile status also
        $stmt = $db->prepare("
        UPDATE profile
        SET status = 'Verified'
        WHERE user_id = ?
    ");
        $stmt->execute([$user['id']]);

        // update session if logged in
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] == $user['id']) {
            $_SESSION['user']['email_verified'] = 1;
        }

        // redirect by role
        header('Location: ' . ($user['role'] === 'doctor'
            ? '/doctor/specialization'
            : '/patient/profile'
        ));
        exit;
    }


    // ================= LOGOUT =================
    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: /auth/login');
        exit;
    }
}
