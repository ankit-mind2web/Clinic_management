<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Helpers\Admin\AdminAuth;
use App\Models\User;

class PatientController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }
    public function index()
    {
        AdminAuth::check();

        $this->view('admin/patients/index',['patients' => $this->userModel->getPatients()]);
    }
}