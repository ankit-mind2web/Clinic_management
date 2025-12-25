<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\User;

class DoctorController extends Controller
{
    /* 
       LIST ALL DOCTORS
     */
    public function index()
    {
        AdminAuth::check();

        $userModel = new User();

        $data = [
            'doctors' => $userModel->getDoctors()
        ];

        $this->view('admin/doctors/index', $data);
    }

    /* 
       PENDING DOCTORS
     */
    public function pending()
    {
        AdminAuth::check();

        $userModel = new User();

        $data = [
            'doctors' => $userModel->getPendingDoctors()
        ];

        $this->view('admin/doctors/pending', $data);
    }

    /* 
       VIEW SINGLE DOCTOR
     */
    public function show()
    {
        AdminAuth::check();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid doctor');
        }

        $userModel = new User();

        // fetch all doctors and find the one we want (simple + safe)
        $doctors = $userModel->getDoctors();
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

    /* 
       APPROVE DOCTOR
     */
    public function approve()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                (new User())->updateStatus($id, 'active');
            }
        }

        header('Location: /admin/doctors/pending');
        exit;
    }

    /* 
       BLOCK DOCTOR
     */
    public function block()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id > 0) {
                (new User())->updateStatus($id, 'blocked');
            }
        }

        header('Location: /admin/doctors');
        exit;
    }
}
