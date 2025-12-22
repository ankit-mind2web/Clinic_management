<?php
namespace App\Core;
class Controller
{
    protected function model(string $model)
    {
        $file = __DIR__ . "/../models/{$model}.php";

        if (!file_exists($file)) {
            die("Model {$model} not found");
        }

        require_once $file;
        return new $model();
    }

    protected function view(string $view, array $data = []): void
    {
        $file = __DIR__ . "/../Views/{$view}.php";

        if (!file_exists($file)) {
            die("View {$view} not found");
        }

        extract($data);
        require_once $file;
    }

    protected function redirect(string $path): void
    {
        header("Location: {$path}");
        exit;
    }
}
