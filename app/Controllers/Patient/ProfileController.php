<?php

namespace App\Controllers\Patient;

use App\Core\Controller;
use App\Models\Profile;
use App\Helpers\Mailer;

class ProfileController extends Controller
{
    // show and update profile
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // must be logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }

        // only patient allowed
        if ($_SESSION['user']['role'] !== 'patient') {
            header('Location: /');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $profileModel = new Profile();
        $message = '';

        // handle profile update
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $gender  = trim($_POST['gender'] ?? '');
            $dob     = trim($_POST['dob'] ?? '');
            $address = trim($_POST['address'] ?? '');

            if ($gender === '' || $dob === '' || $address === '') {
                $message = 'All fields are required';
            } else {
                $profileModel->saveOrUpdate($userId, $gender, $dob, $address, $emailVerified = (int)($_SESSION['user']['email_verified'] ?? 0));
                $message = 'Profile saved successfully';
            }
        }

        $profile = $profileModel->getByUserId($userId);

        $this->view('patient/profile/index', [
            'profile' => $profile,
            'message' => $message
        ]);
    }

    // send email verification link (patient only)
    public function sendVerification()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // must be logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }

        // only patient allowed
        if ($_SESSION['user']['role'] !== 'patient') {
            header('Location: /');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $profileModel = new Profile();

        // ensure profile is complete
        $profile = $profileModel->getByUserId($userId);
        if (!$profile || empty($profile['dob']) || empty($profile['address'])) {
            header('Location: /patient/profile');
            exit;
        }

        // cooldown (60 seconds)
        $now = time();
        if (isset($_SESSION['verify_cooldown_until']) && $_SESSION['verify_cooldown_until'] > $now) {
            header('Location: /patient/profile');
            exit;
        }

        // generate token
        $token  = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        // save token
        $profileModel->saveEmailToken($userId, $token, $expiry);

        // set cooldown
        $_SESSION['verify_cooldown_until'] = $now + 60;

        // build verification link
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'];
        $link   = $scheme . '://' . $host . '/auth/verify-email?token=' . $token;

        // mock email (log)
        if (isset($_SESSION['user']['email'])) {
            $email     = $_SESSION['user']['email'];
            $fullName  = $_SESSION['user']['full_name'] ?? 'Patient';
            
            $emailBody = "
                <h3>Email Verification</h3>
                <p>Hello {$fullName},</p>
                <p>Please verify your email by clicking the button below:</p>
                <p>
                    <a href='{$link}'
                       style='display:inline-block;padding:10px 15px;
                              background:#4b6cb7;color:#fff;
                              text-decoration:none;border-radius:5px;'>
                        Verify Email
                    </a>
                </p>
                <p>This link will expire in 24 hours.</p>
            ";

            Mailer::send($email, 'Verify Your Email', $emailBody);
        }

        header('Location: /patient/profile');
        exit;
    }
}
