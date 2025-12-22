<?php
namespace App\Core;
class Router
{
    private array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = '/clinic_management';
        if (strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
        }

        $uri = rtrim($uri, '/') ?: '/';

        if (!isset($this->routes[$method][$uri])) {
            http_response_code(404);
            echo "404 Not Found";
            exit;
        }

        [$controller, $action] = $this->routes[$method][$uri];

        $controllerFile = __DIR__ . "/../app/controllers/{$controller}.php";

        if (!file_exists($controllerFile)) {
            die("Controller {$controller} not found at {$controllerFile}");
        }

        require_once __DIR__ . '/Controller.php';
        require_once $controllerFile;

        $instance = new $controller();

        if (!method_exists($instance, $action)) {
            die("Method {$action} not found");
        }

        $instance->$action();
    }
}
