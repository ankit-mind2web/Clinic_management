<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Helpers\Doctor\DoctorAuth;
use App\Models\Doctor\AppointmentModel;

class AppointmentController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $doctorId = $_SESSION['user']['id'] ?? null;

        if (!$doctorId) {
            die('Unauthorized');
        }

        $model = new AppointmentModel();
        $appointments = $model->getAppointmentsByDoctor($doctorId);

        $this->view('doctor/appointments/index', [
            'appointments' => $appointments
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
