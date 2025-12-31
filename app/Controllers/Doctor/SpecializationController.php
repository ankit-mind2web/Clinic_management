<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\DoctorSpecialization;

class SpecializationController extends Controller
{
    public function index()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /auth/login');
            exit;
        }

        if (($_SESSION['user']['email_verified'] ?? 0) != 1) {
            $_SESSION['flash_message'] = 'Verify email first';
            header('Location: /doctor/dashboard');
            exit;
        }

        $doctorId = $_SESSION['user']['id'];
        $model = new DoctorSpecialization();

        /* FLASH MESSAGE */
        $message = $_SESSION['flash_message'] ?? '';
        unset($_SESSION['flash_message']);

        /* HANDLE POST */
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $specializationId = (int)($_POST['specialization_id'] ?? 0);
            $experience       = (int)($_POST['experience'] ?? 0);

            if ($specializationId <= 0 || $experience <= 0) {
                $_SESSION['flash_message'] = 'All fields are required';
                header('Location: /doctor/specialization');
                exit;
            }

            /* prevent duplicate */
            if ($model->exists($doctorId, $specializationId)) {
                $_SESSION['flash_message'] = 'Specialization already added';
                header('Location: /doctor/specialization');
                exit;
            }

            $model->add($doctorId, $specializationId, $experience);

            $_SESSION['flash_message'] = 'Specialization added successfully';
            header('Location: /doctor/specialization');
            exit;
        }

        $this->view('doctor/specialization', [
            'specializations' => $model->getAllSpecializations(),
            'doctorSpecs'     => $model->getByDoctorAll($doctorId),
            'message'         => $message
        ]);
    }
}
