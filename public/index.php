<?php

use App\Controllers\Admin\AppointmentController;
use App\Controllers\AuthController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\DoctorController;
use App\Controllers\Admin\SpecializationController;
use App\Controllers\HomeController;
use App\Controllers\Patient\PatientController;
use App\Controllers\Patient\ProfileController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../config/bootstrap.php';
/* CORE FILES */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Model.php';

/* ROUTES */
$routes = [

    'GET' => [
        '/'              => [HomeController::class, 'index'],
        '/auth/login'    => [AuthController::class, 'login'],
        '/auth/register' => [AuthController::class, 'register'],
        '/auth/logout'   => [AuthController::class, 'logout'],

        //admin dashboard
        '/admin/dashboard' => [DashboardController::class, 'index'],
        '/admin/doctors'    => [DoctorController::class, 'index'],
        '/admin/doctors/pending'  => [DoctorController::class, 'pending'],
        '/admin/doctors/view' => [DoctorController::class, 'show'],


        //appointment
        '/admin/appointments'      => [AppointmentController::class, 'index'],
        '/admin/appointments/view' => [AppointmentController::class, 'show'],

        //specialization
        '/admin/specializations'        => [SpecializationController::class, 'index'],
        '/admin/specializations/create' => [SpecializationController::class, 'create'],
        '/admin/specializations/edit'   => [SpecializationController::class, 'edit'],
        '/admin/specializations/delete' => [SpecializationController::class, 'delete'],

        //doctor
        '/doctor/dashboard'        => [DashboardController::class, 'index'],
        '/doctor/appointments'     => [AppointmentController::class, 'index'],
        '/doctor/appointments/view' => [AppointmentController::class, 'show'],



        //patient dashboard
        '/patient/dashboard'         => [HomeController::class, 'index'],
        '/patient/appointment'       => [PatientController::class, 'appointments'],
        '/patient/profile'           => [ProfileController::class, 'index'],
        '/auth/verify-email'         => [AuthController::class, 'verifyEmail'],


    ],
    'POST' => [
        '/auth/login'    => [AuthController::class, 'login'],
        '/auth/register' => [AuthController::class, 'register'],
        '/auth/check-email' => [AuthController::class, 'checkEmail'],

        //doctor-admin
        '/admin/doctors/approve'  => [DoctorController::class, 'approve'],
        '/admin/doctors/block'    => [DoctorController::class, 'block'],

        '/patient/profile' => [ProfileController::class, 'index'],
        '/profile/send-verification' => [ProfileController::class, 'sendVerification'],
    ]

];

/* DISPATCH */
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '/clinic_management';
if (strpos($uri, $base) === 0) {
    $uri = substr($uri, strlen($base));
}

$uri = rtrim($uri, '/') ?: '/';

if (!isset($routes[$method][$uri])) {
    http_response_code(404);
    echo "404 Page Not Found";
    exit;
}

[$controllerClass, $action] = $routes[$method][$uri];

if (!class_exists($controllerClass)) {
    die("Controller class not found: {$controllerClass}");
}

$instance = new $controllerClass();

if (!method_exists($instance, $action)) {
    die("Method not found: {$action}");
}

$instance->$action();
