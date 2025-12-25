<?php

namespace App\Controllers\Doctor;

use App\Controllers\Admin\AppointmentController;
use App\Core\Controller;
use App\Helpers\Doctor\DoctorAuth;
use App\Models\Admin\AppointmentModel;

class DashboardController extends Controller
{
    public function index()
    {
        DoctorAuth::check();

        $doctorId = $_SESSION['user']['id'];

        $appointmentModel = new AppointmentModel();

        $this->view('doctor/dashboard', [
            'totalAppointments' => $appointmentModel->countByDoctor($doctorId),
            'todayAppointments' => $appointmentModel->countToday($doctorId),
            'recentAppointments'=> $appointmentModel->getRecent($doctorId, 5)
        ]);
    }
}
