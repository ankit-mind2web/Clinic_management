<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\DoctorProfile;
use App\Models\Doctor\DoctorSpecialization;

class ProfileController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /auth/login');
            exit;
        }

        $user     = $_SESSION['user'];
        $userId   = $user['id'];
        $isApproved = ($user['status'] ?? 'pending') === 'active';

        $profileModel = new DoctorProfile();
        $specModel    = new DoctorSpecialization();

        $message = $_SESSION['flash_message'] ?? '';
        unset($_SESSION['flash_message']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!$isApproved) {
                die('Access denied');
            }

            $bio        = trim($_POST['bio'] ?? '');
            $experience = (int)($_POST['experience'] ?? 0);

            if ($bio === '' || $experience <= 0) {
                $_SESSION['flash_message'] = 'All fields are required';
                header('Location: /doctor/profile?edit=1');
                exit;
            }

            $profileModel->save($userId, $bio, $experience);

            $_SESSION['flash_message'] = 'Profile updated successfully';
            header('Location: /doctor/profile');
            exit;
        }

        $this->view('doctor/profile/index', [
            'profile'    => $profileModel->get($userId),
            'doctorSpec' => $specModel->getByDoctorAll($userId),
            'message'    => $message,
            'isApproved' => $isApproved
        ]);
    }

    public function sendVerification()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /auth/login');
            exit;
        }

        if (($_SESSION['user']['status'] ?? 'pending') !== 'active') {
            $_SESSION['flash_message'] = 'Wait for admin approval to verify email';
            header('Location: /doctor/profile');
            exit;
        }

        $doctorId = $_SESSION['user']['id'];

        $specModel = new DoctorSpecialization();
        if (!$specModel->getByDoctorAll($doctorId)) {
            $_SESSION['flash_message'] = 'Add specialization before email verification';
            header('Location: /doctor/profile');
            exit;
        }

        $profileModel = new DoctorProfile();

        $token  = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $profileModel->saveToken($doctorId, $token, $expiry);

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $link   = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/auth/verify-email?token=' . $token;

        file_put_contents(
            __DIR__ . '/../../../storage/email_log.txt',
            $link . PHP_EOL,
            FILE_APPEND
        );

        $_SESSION['flash_message'] = 'Verification email sent';
        header('Location: /doctor/profile');
        exit;
    }
}
