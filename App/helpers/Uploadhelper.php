<?php
namespace App\helpers;

class UploadHelper {
    public static function uploadImage(array $file, string $folder): ?string {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $maxSize = 3 * 1024 * 1024; // 3MB

        return self::handle($file, $allowed, $maxSize, ROOT . "/public/assets/images/{$folder}");
    }

    public static function uploadDigitalFile(array $file): ?string {
        $allowed = ['zip', 'rar', 'pdf', 'psd', 'ai'];
        $maxSize = 200 * 1024 * 1024; // 200MB

        return self::handle($file, $allowed, $maxSize, ROOT . "/storage/products", true);
    }

    private static function handle(array $file, array $allowed, int $maxSize, string $targetDir, bool $isPrivate = false): ?string {
        if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            throw new \Exception("Tipe file .$ext tidak diizinkan.");
        }

        if ($file['size'] > $maxSize) {
            throw new \Exception("Ukuran file melebihi batas maksimal.");
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $filename = bin2hex(random_bytes(16)) . '.' . $ext;
        $destination = "$targetDir/$filename";

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \Exception("Gagal menyimpan file.");
        }
        return $isPrivate ? "storage/products/$filename" : "$filename";
    }


    public static function uploadVideo(array $file): ?string {
    $allowed = ['mp4', 'webm', 'mov'];
    $maxSize = 100 * 1024 * 1024; // 100MB
    $targetDir = ROOT . '/public/assets/videos/products';

    if (empty($file['name']) || $file['error'] !== UPLOAD_ERR_OK) return null;

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) throw new \Exception("Format video .$ext tidak didukung.");
    if ($file['size'] > $maxSize) throw new \Exception("Video maksimal 100MB.");

    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);

    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    move_uploaded_file($file['tmp_name'], "$targetDir/$filename");
    return $filename;
    }
}