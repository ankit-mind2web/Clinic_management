<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\PatientController;

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
        '/logout'        => [AuthController::class, 'logout'],

        //patient dashboard
        '/patient/dashboard'=> [PatientController::class, 'dashboard'],
        '/patient/profile'  => [PatientController::class, 'profile'],
        '/patient/appointment'=>[PatientController::class, 'appointments']
    ],
    'POST' => [
        '/auth/login'    => [AuthController::class, 'login'],
        '/auth/register' => [AuthController::class, 'register'],
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
