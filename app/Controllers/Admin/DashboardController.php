<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\Admin\AppointmentModel;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        // ensure only admin can access
        AdminAuth::check();

        $userModel        = new User();
        $appointmentModel = new AppointmentModel();

        $data = [
            'totalPatients'      => $userModel->countByRole('patient'),
            'totalDoctors'       => $userModel->countByRole('doctor'),
            'pendingDoctors'     => $userModel->countPendingDoctors(),
            'totalAppointments'  => $appointmentModel->countAll(),
            'recentAppointments' => $appointmentModel->getRecent(5)
        ];

        $this->view('admin/dashboard', $data);
    }
}
