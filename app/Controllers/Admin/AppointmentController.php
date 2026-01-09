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

        // Pagination
        $page   = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? ''); // Capture search
        $limit  = 10;
        
        // Build base URL for persistence
        $baseUrl = '/admin/appointments';
        if ($search !== '') {
            $baseUrl .= '?search=' . urlencode($search);
        }
        
        $totalItems = $appointmentModel->countAll();
        $pagination = new \App\Helpers\Pagination($totalItems, $limit, $page, $baseUrl);

        $appointments = $appointmentModel->getFiltered(
            '', '', '', '', '', // search, sort, status, from, to
            $page, $limit
        );

        $this->view('admin/appointments/index', [
            'appointments' => $appointments,
            'pagination'   => $pagination->getLinks(),
            'search'       => $search
        ]);
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
