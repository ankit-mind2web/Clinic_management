<?php

namespace App\Controllers\Patient;

use App\Core\Controller;
use App\Models\Doctor\DoctorAvailability;
use App\Models\Patient\AppointmentModel;

class AppointmentController extends Controller
{

    //   LIST DOCTORS

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

        $availabilityModel = new \App\Models\Doctor\DoctorAvailability();
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
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request'
            ]);
            return;
        }

        $availabilityModel = new DoctorAvailability();
        $locked = $availabilityModel->bookSlot((int)$slotId);

        if (!$locked) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Slot already booked'
            ]);
            return;
        }

        $appointmentModel = new AppointmentModel();
        $appointmentModel->create($patientId, $slotId);

        echo json_encode([
            'status' => 'success',
            'message' => 'Appointment booked successfully'
        ]);
    }
}
