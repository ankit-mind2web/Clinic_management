<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\DoctorAvailability as DoctorDoctorAvailability;

require_once __DIR__ . '/../../Models/Doctor/DoctorAvailability.php';

class DoctorAvailabilityController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }


        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /clinic_management/auth/login');
            exit;
        }

        $doctorId = $_SESSION['user']['id'];

        $model = new DoctorDoctorAvailability();
        $slots = $model->getDoctorAvailability($doctorId);

        $this->view('doctor/availability', compact('slots'));
    }



    public function store()
    {
        header('Content-Type: application/json');

        try {
            if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ]);
                return;
            }

            $doctorId = $_SESSION['user']['id'];

            $startUtc = $_POST['start_utc'] ?? '';
            $endUtc   = $_POST['end_utc'] ?? '';
            $type     = $_POST['type'] ?? '';

            if (!$startUtc || !$endUtc || !$type) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Missing fields'
                ]);
                return;
            }

            require_once __DIR__ . '/../../Models/Doctor/DoctorAvailability.php';

            $model = new \App\Models\Doctor\DoctorAvailability();
            $model->createSlot($doctorId, $startUtc, $endUtc, $type);

            echo json_encode([
                'status' => 'success',
                'message' => 'Availability saved'
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
