<?php
namespace App\middleware;

class AuthMiddleware {
    public static function check(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function adminOnly(): void {
        self::check();
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            die('403 — Akses ditolak.');
        }
    }

    public static function guest(): void {
        if (isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    }
}