<?php
namespace App\Core;
class App
{
    public function dispatch(string $controller, string $method, array $params = []): void
    {
        $controllerFile = __DIR__ . "/../app/controllers/{$controller}.php";

        if (!file_exists($controllerFile)) {
            http_response_code(404);
            die("Controller {$controller} not found");
        }

        require_once $controllerFile;

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            http_response_code(404);
            die("Method {$method} not found in {$controller}");
        }

        call_user_func_array([$instance, $method], $params);
    }
}
