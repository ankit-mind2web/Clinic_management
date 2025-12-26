<?php

namespace App\Controllers\Doctor;

use App\Core\Controller;
use App\Models\Doctor\Availability;

class AvailabilityController extends Controller
{
    public function index()
    {
        doctorOnly();

        $doctorId = $_SESSION['user']['id'];
        $model = new Availability();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'];
            $from = $_POST['from'];
            $to   = $_POST['to'];

            $model->add($doctorId, $date, $from, $to);
        }

        $slots = $model->getAll($doctorId);

        $this->view('doctor/availability/index', [
            'slots' => $slots
        ]);
    }
}
