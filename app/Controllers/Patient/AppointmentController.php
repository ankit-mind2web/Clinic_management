<?php

namespace App\Controllers\Patient;

use App\Core\Controller;
use App\Models\Doctor\DoctorAvailability;
use App\Models\Patient\AppointmentModel;

class AppointmentController extends Controller
{
    //LIST DOCTORS
    public function index()
    {
        $availabilityModel = new DoctorAvailability();
        $doctors = $availabilityModel->getDoctorsWithAvailability();

        $this->view('patient/appointments/index', [
            'doctors' => $doctors
        ]);
    }

    //   FETCH AVAILABLE SLOTS (AJAX) 
    public function availability()
    {
        header('Content-Type: application/json');

        $doctorId = $_POST['doctor_id'] ?? null;
        if (!$doctorId) {
            echo json_encode([]);
            return;
        }

        $availabilityModel = new DoctorAvailability();
        echo json_encode(
            $availabilityModel->getAvailableSlotsForPatient((int)$doctorId)
        );
    }

    //   BOOK APPOINTMENT (AJAX)       
    public function book()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        // must be logged in
        if (empty($_SESSION['user'])) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'User not logged in'
            ]);
            return;
        }

        // 🔒 EMAIL VERIFICATION CHECK (IMPORTANT)
        if ((int)($_SESSION['user']['email_verified'] ?? 0) !== 1) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Please verify your email before booking an appointment'
            ]);
            return;
        }

        $patientId = $_SESSION['user']['id'];
        $slotId    = $_POST['slot_id'] ?? null;

        if (!$slotId) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Invalid slot'
            ]);
            return;
        }

        $appointmentModel = new AppointmentModel();

        try {
            $success = $appointmentModel->bookBySlotId(
                (int)$patientId,
                (int)$slotId
            );

            echo json_encode([
                'status'  => $success ? 'success' : 'error',
                'message' => $success
                    ? 'Appointment booked successfully'
                    : 'Slot already booked'
            ]);
        } catch (\Throwable $e) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Booking failed'
            ]);
        }
    }
}
