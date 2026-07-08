<?php
namespace App\controllers;

use App\models\Download;
use App\middleware\AuthMiddleware;

class DownloadController extends BaseController
{
    private Download $download;

    public function __construct()
    {
        $this->download = new Download();
    }

    public function serve(string $token): void
    {
        AuthMiddleware::check();

        $dl = $this->download->findByToken($token);

        if (!$dl) {
            http_response_code(404);
            die('Link download tidak valid.');
        }

        if ((int) $dl['user_id'] !== (int) $_SESSION['user_id']) {
            http_response_code(403);
            die('Akses ditolak.');
        }

        if (strtotime($dl['expired_at']) < time()) {
            http_response_code(410);
            die('Link download sudah expired.');
        }

        // Ambil file_path dari tabel products (bukan dari downloads)
        require_once ROOT . '/config/database.php';
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT p.title, p.file_path FROM products p WHERE p.id = ?");
        $stmt->execute([$dl['product_id']]);
        $product = $stmt->fetch();

        if (!$product || !$product['file_path']) {
            http_response_code(404);
            die('File produk tidak ditemukan.');
        }

        $filePath = ROOT . '/' . $product['file_path'];

        if (!file_exists($filePath)) {
            http_response_code(404);
            die('File tidak ada di server. Path: ' . $filePath);
        }

        // Buat nama file download yang rapi pakai judul produk + ekstensi asli
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        $cleanTitle = preg_replace('/[^a-zA-Z0-9-_]/', '-', $product['title']);
        $downloadName = $cleanTitle . ($ext ? '.' . $ext : '');

        // Tambah counter
        $this->download->incrementCount($dl['id']);

        // Kirim file
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $downloadName . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        ob_clean();
        flush();
        readfile($filePath);
        exit;
    }
}