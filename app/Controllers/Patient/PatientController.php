<?php

namespace App\Controllers\Patient;

use App\Core\Controller;

class PatientController extends Controller
{
    protected array $user;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // check login
        if (!isset($_SESSION['user'])) {
            header("Location: /auth/login");
            exit;
        }

        // check role
        if (($_SESSION['user']['role'] ?? '') !== 'patient') {
            header("Location: /auth/login");
            exit;
        }

        $this->user = $_SESSION['user'];
    }

    /*  PROFILE  */
    public function profile()
    {
        $this->view('patient/profile', [
            'user' => $this->user
        ]);
    }

    /*  APPOINTMENTS  */
    public function appointments()
    {
        $this->view('patient/appointments', [
            'user' => $this->user
        ]);
    }

    /*  LOGOUT  */
    public function logout()
    {
        session_unset();
        session_destroy();
        header("Location: /auth/login");
        exit;
    }
}
