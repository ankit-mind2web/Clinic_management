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
        
        // AUTO-COMPLETE CHECK
        $model->autoCompletePastAppointments($doctorId);

        // Pagination
        $page  = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $totalItems = $model->appointmentCount($doctorId); // Reusing existing count method
        
        $pagination = new \App\Helpers\Pagination($totalItems, $limit, $page, '/doctor/appointments');

        $appointments = $model->getAppointmentsByDoctor($doctorId, $limit, $pagination->getOffset());

        $this->view('doctor/appointments/index', [
            'appointments' => $appointments,
            'pagination'   => $pagination->getLinks()
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

    public function confirm()
    {
        DoctorAuth::check();
        $doctorId = $_SESSION['user']['id'];
        $id = (int)($_POST['id'] ?? 0);

        if ($id && (new AppointmentModel())->updateStatus($id, $doctorId, 'confirmed')) {
            // Success
        }

        header('Location: /doctor/appointments');
        exit;
    }

    public function cancel()
    {
        DoctorAuth::check();
        $doctorId = $_SESSION['user']['id'];
        $id = (int)($_POST['id'] ?? 0);

        if ($id && (new AppointmentModel())->updateStatus($id, $doctorId, 'cancelled')) {
            // Success
        }

        header('Location: /doctor/appointments');
        exit;
    }
}
