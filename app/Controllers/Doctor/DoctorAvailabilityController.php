<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\DoctorAvailability;

class DoctorAvailabilityController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
            header('Location: /auth/login');
            exit;
        }

        $doctorId = $_SESSION['user']['id'];

        $model = new DoctorAvailability();
        $slots = $model->getDoctorAvailability($doctorId);

        $this->view('doctor/availability', compact('slots'));
    }

    public function store()
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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
        $status   = $_POST['type'] ?? ''; // available / unavailable

        if (!$startUtc || !$endUtc || !$status) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing fields'
            ]);
            return;
        }

        try {
            $model = new DoctorAvailability();
            $model->createSlot($doctorId, $startUtc, $endUtc, $status);

            echo json_encode([
                'status'  => 'success',
                'message' => 'Availability saved and slots generated'
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
