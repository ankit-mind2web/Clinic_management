<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Appointment;

class AdminController extends Controller
{
    public function dashboard()
    {
        // security: only admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header("Location: /auth/login");
            exit;
        }

        $userModel = new User();
        $appointmentModel = new Appointment();

        $data = [
            'totalPatients'       => $userModel->countByRole('patient'),
            'totalDoctors'        => $userModel->countByRole('doctor'),
            'pendingDoctors'      => $userModel->countPendingDoctors(),
            'totalAppointments'   => $appointmentModel->countAll(),
            'recentAppointments'  => $appointmentModel->getRecent(5)
        ];

        $this->view('admin/dashboard', $data);
    }

    /* approve doctor */
    public function approveDoctor()
    {
        $id = (int)($_POST['id'] ?? 0);
        (new User())->updateStatus($id, 'active');
        header("Location: /admin/dashboard");
    }

    /* reject doctor */
    public function rejectDoctor()
    {
        $id = (int)($_POST['id'] ?? 0);
        (new User())->updateStatus($id, 'blocked');
        header("Location: /admin/dashboard");
    }
}
