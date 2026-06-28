<?php
namespace App\controllers;

class BaseController {
    protected function view(string $path, array $data = []): void {
        extract($data);
        $file = ROOT . "/app/views/{$path}.php";
        if (!file_exists($file)) die("View not found: $path");
        require $file;
    }

    protected function redirect(string $url): void {
        header("Location: $url");
        exit;
    }

    protected function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}