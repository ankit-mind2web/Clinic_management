<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\AppointmentModel;

class DashboardController extends Controller
{
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

        // doctor-only access
        if (strtolower($_SESSION['user']['role']) !== 'doctor') {
            header('Location: /');
            exit;
        }

        // email must be verified
        // if (empty($_SESSION['user']['email_verified'])) {
        //     $this->view('doctor/verify_email');
        //     return;
        // }


        $doctorId = $_SESSION['user']['id'];
        $appointmentModel = new AppointmentModel();

        $this->view('doctor/dashboard', [
            'doctorName'        => $_SESSION['user']['full_name'],
            'totalAppointments' => $appointmentModel->appointmentCount($doctorId),
            'todayAppointments' => $appointmentModel->countTodayAppointment($doctorId),
        ]);
    }
}
