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

        $page   = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $limit  = 10;
        
        // Build base URL for pagination
        $baseUrl = '/admin/doctors';
        if ($search !== '') {
            $baseUrl .= '?search=' . urlencode($search);
        }
        
        $totalItems = $this->userModel->countDoctors($search);
        $pagination = new \App\Helpers\Pagination($totalItems, $limit, $page, $baseUrl);
        
        $doctors = $this->userModel->getDoctorsPaginated($limit, $pagination->getOffset(), $search);

        $this->view('admin/doctors/index', [
            'doctors'    => $doctors,
            'pagination' => $pagination->getLinks(),
            'search'     => $search
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

        $doctor = $this->userModel->findDoctorById($id);

        if (!$doctor) {
            die('Doctor not found');
        }

        $this->view('admin/doctors/view', [
            'doctor' => $doctor
        ]);
    }

    /* APPROVE DOCTOR (PENDING → ACTIVE) */
    public function approve()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid doctor');
        }

        $this->userModel->updateStatus($id, 'active');

        $_SESSION['flash_message'] = 'Doctor approved successfully';
        header('Location: /admin/doctors/pending');
        exit;
    }

    /* REJECT DOCTOR (PENDING → BLOCKED) */
    public function reject()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid doctor');
        }

        $this->userModel->updateStatus($id, 'blocked');

        $_SESSION['flash_message'] = 'Doctor rejected successfully';
        header('Location: /admin/doctors/pending');
        exit;
    }

    /* BLOCK DOCTOR (ACTIVE → BLOCKED) */
    public function block()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid doctor');
        }

        $this->userModel->updateStatus($id, 'blocked');

        $_SESSION['flash_message'] = 'Doctor blocked';
        header('Location: /admin/doctors');
        exit;
    }

    /* UNBLOCK DOCTOR (BLOCKED → ACTIVE) */
    public function unblock()
    {
        AdminAuth::check();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request');
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            die('Invalid doctor');
        }

        $this->userModel->updateStatus($id, 'active');

        $_SESSION['flash_message'] = 'Doctor unblocked';
        header('Location: /admin/doctors');
        exit;
    }
}
