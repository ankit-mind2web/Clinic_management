<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Helpers\Doctor\DoctorAuth;
use App\Models\Doctor\AppointmentModel;

class AppointmentController extends Controller
{
    public function index()
    {
        DoctorAuth::check();

        $doctorId = $_SESSION['user']['id'];

        $this->view('doctor/appointments/index', [
            'appointments' => (new AppointmentModel())->getAll($doctorId)
        ]);
    }

    public function show()
    {
        DoctorAuth::check();

        $doctorId = $_SESSION['user']['id'];
        $id = (int)($_GET['id'] ?? 0);

        $appointment = (new AppointmentModel())->getById($id, $doctorId);

        if (!$appointment) {
            die('Appointment not found');
        }

        $this->view('doctor/appointments/view', [
            'appointment' => $appointment
        ]);
    }
}
