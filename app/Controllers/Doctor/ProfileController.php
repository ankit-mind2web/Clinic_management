<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\DoctorProfile;

class ProfileController extends Controller
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $model  = new DoctorProfile();
        $message = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bio = trim($_POST['bio'] ?? '');
            $experience = (int)($_POST['experience'] ?? 0);

            if ($bio === '' || $experience <= 0) {
                $message = 'All fields required';
            } else {
                $model->save($userId, $bio, $experience);
                $message = 'Profile saved successfully';
            }
        }

        $profile = $model->get($userId);

        $this->view('doctor/profile', [
            'profile' => $profile,
            'message' => $message
        ]);
    }

    public function sendVerification()
    {
        session_start();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /auth/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $model  = new DoctorProfile();

        $profile = $model->get($userId);
        if (!$profile || empty($profile['bio']) || empty($profile['experience'])) {
            header('Location: /doctor/profile');
            exit;
        }

        $token  = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));

        $model->saveToken($userId, $token, $expiry);

        $link = "http://{$_SERVER['HTTP_HOST']}/auth/verify-email?token={$token}";

        file_put_contents(
            __DIR__ . '/../../../storage/email_log.txt',
            $link . PHP_EOL,
            FILE_APPEND
        );

        header('Location: /doctor/profile');
        exit;
    }
}
