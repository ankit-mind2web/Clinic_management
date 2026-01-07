<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\Admin\AppointmentModel;
use App\Models\Admin\SpecializationModel;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        AdminAuth::check();

        $userModel            = new User();
        $appointmentModel     = new AppointmentModel();
        $specializationModel  = new SpecializationModel();

        $doctors = $userModel->getAllDoctors(7);

        $data = [
            'totalPatients'       => $userModel->countByRole('patient'),
            'totalDoctors'        => $userModel->countByRole('doctor'),
            'pendingDoctors'      => $userModel->countPendingDoctors(),
            'totalAppointments'   => $appointmentModel->countAll(),
            'totalSpecializations'=> $specializationModel->countAll(),
            'recentAppointments'  => $appointmentModel->getRecent(7),
            'doctors'             => $doctors
        ];

        $this->view('admin/dashboard', $data);
    }
}
