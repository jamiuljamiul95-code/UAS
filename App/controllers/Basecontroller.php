<?php

namespace App\controllers;

use Database;
use App\models\Notification;

class BaseController
{
    protected function view(string $path, array $data = []): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $data = array_merge($this->headerData(), $data);

        extract($data);

        $file = ROOT . "/app/views/{$path}.php";

        if (!file_exists($file)) {
            die("View not found: {$path}");
        }

        require $file;
    }

    /**
     * Data global untuk Header
     */
    protected function headerData(): array
    {
        $header = [
            'cartCount'      => count($_SESSION['cart'] ?? []),
            'wishlistCount'  => 0,
            'notifications'  => [],
            'unreadCount'    => 0,
        ];

        if (!isset($_SESSION['user_id'])) {
            return $header;
        }

        $db = Database::getInstance()->getConnection();

        // Wishlist
        $stmt = $db->prepare("
            SELECT COUNT(*)
            FROM wishlists
            WHERE user_id = ?
        ");

        $stmt->execute([
            $_SESSION['user_id']
        ]);

        $header['wishlistCount'] = (int)$stmt->fetchColumn();

        // Notification
        $notif = new Notification();

        $header['notifications'] = $notif->forUser(
            $_SESSION['user_id'],
            5
        );

        $header['unreadCount'] = $notif->countUnread(
            $_SESSION['user_id']
        );

        return $header;
    }

    protected function redirect(string $path): void
    {
        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://')
        ) {
            header("Location: {$path}");
            exit;
        }

        $path = '/' . ltrim($path, '/');

        header("Location: " . BASE_URL . $path);
        exit;
    }

    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);

        header('Content-Type: application/json');

        echo json_encode($data);

        exit;
    }
}