<?php
namespace App\controllers;

class BaseController {
    protected function view(string $path, array $data = []): void {
        extract($data);
        $file = ROOT . "/app/views/{$path}.php";
        if (!file_exists($file)) die("View not found: $path");
        require $file;
    }

    /**
     * Redirect ke path relatif (misal: '/login', '/admin/dashboard').
     * BASE_URL otomatis ditambahkan di depan, supaya semua controller
     * tidak perlu menulis BASE_URL berulang-ulang secara manual.
     */
    protected function redirect(string $path): void {
        // Kalau $path sudah berupa URL lengkap (http://...), jangan ditambah BASE_URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            header("Location: $path");
            exit;
        }

        $path = '/' . ltrim($path, '/');
        header("Location: " . BASE_URL . $path);
        exit;
    }

    protected function json(array $data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}