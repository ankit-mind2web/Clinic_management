<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\Admin\AppointmentModel;

class AppointmentController extends Controller
{
    /* list all appointments */
    public function index()
    {
        AdminAuth::check();

        $appointmentModel = new AppointmentModel();

        $data = [
            'appointments' => $appointmentModel->getAll()
        ];

        $this->view('admin/appointments/index', $data);
    }

    /* show single appointment */
    public function show()
    {
        AdminAuth::check();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid appointment');
        }

        $appointmentModel = new AppointmentModel();
        $appointment = $appointmentModel->getById($id);

        if (!$appointment) {
            die('Appointment not found');
        }

        $this->view('admin/appointments/view', [
            'appointment' => $appointment
        ]);
    }
}
