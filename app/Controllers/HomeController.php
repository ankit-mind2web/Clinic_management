<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $userModel = new \App\Models\User();
        $specModel = new \App\Models\Doctor\DoctorSpecialization();

        $doctors = $userModel->getPublicDoctors(6);
        $specializations = $specModel->getAllSpecializations();

        $this->view('home/index', [
            'doctors' => $doctors,
            'specializations' => $specializations
        ]);
    }
}
