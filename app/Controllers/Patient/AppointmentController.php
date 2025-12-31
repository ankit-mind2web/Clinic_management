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
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $patientId = $_SESSION['user']['id'] ?? null;
        $slotId    = $_POST['slot_id'] ?? null;

        if (!$patientId || !$slotId) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            return;
        }

        $appointmentModel = new AppointmentModel();

        // ✅ book strictly by slot_id
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
    }
}
