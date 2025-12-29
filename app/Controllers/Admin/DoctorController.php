<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\User;

class DoctorController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /* LIST ALL DOCTORS */
    public function index()
    {
        AdminAuth::check();

        $this->view('admin/doctors/index', [
            'doctors' => $this->userModel->getDoctors()
        ]);
    }

    /* PENDING DOCTORS */
    public function pending()
    {
        AdminAuth::check();

        $this->view('admin/doctors/pending', [
            'doctors' => $this->userModel->getPendingDoctors()
        ]);
    }

    /* VIEW SINGLE DOCTOR */
    public function show()
    {
        AdminAuth::check();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid doctor');
        }

        $doctors = $this->userModel->getDoctors();
        $doctor  = null;

        foreach ($doctors as $d) {
            if ((int)$d['id'] === $id) {
                $doctor = $d;
                break;
            }
        }

        if (!$doctor) {
            die('Doctor not found');
        }

        $this->view('admin/doctors/view', [
            'doctor' => $doctor
        ]);
    }

    /* APPROVE DOCTOR */
    public function approve()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->userModel->updateStatus($id, 'active');
            }
        }

        header('Location: /admin/doctors/pending');
        exit;
    }

    /* BLOCK DOCTOR */
    public function block()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->userModel->updateStatus($id, 'blocked');
            }
        }

        header('Location: /admin/doctors');
        exit;
    }

    /* UNBLOCK DOCTOR */
    public function unblock()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                $this->userModel->updateStatus($id, 'active');
            }
        }

        header('Location: /admin/doctors');
        exit;
    }
}
