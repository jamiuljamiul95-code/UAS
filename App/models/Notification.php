<?php
namespace App\models;

class Notification extends BaseModel
{
    protected $table = 'notifications';

    // Ambil notif untuk admin (user_id = NULL)
    public function forAdmin(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE user_id IS NULL
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Ambil notif untuk customer tertentu (Diubah ke string agar mendukung UUID)
    public function forUser(string $userId, int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $userId, \PDO::PARAM_STR); // Menggunakan PARAM_STR untuk string/UUID
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Hitung notif yang belum dibaca (Diubah ke ?string agar mendukung UUID)
    public function countUnread(?string $userId = null): int
    {
        if ($userId === null) {
            $stmt = $this->db->query("SELECT COUNT(*) FROM notifications WHERE user_id IS NULL AND is_read = 0");
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$userId]);
        }
        return (int) $stmt->fetchColumn();
    }

    // Tandai semua sudah dibaca (Diubah ke ?string agar mendukung UUID)
    public function markAllRead(?string $userId = null): void
    {
        if ($userId === null) {
            $this->db->exec("UPDATE notifications SET is_read = 1 WHERE user_id IS NULL");
        } else {
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
    }

    // Buat notif baru (Diubah ke ?string agar mendukung UUID)
    public function push(?string $userId, string $type, string $title, string $message, string $url = ''): void
    {
        $this->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'is_read' => 0,
            'url' => $url,
        ]);
    }


    /**
     * Hapus 1 notifikasi milik user tertentu (Diubah ke string agar mendukung UUID)
     */
    public function deleteOne(int $id, string $userId): void
    {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
    }

    /**
     * Hapus semua notifikasi milik user (Diubah ke string agar mendukung UUID)
     */
    public function deleteAllForUser(string $userId): void
    {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM notifications WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
    // Tandai 1 notifikasi tertentu sudah dibaca
    public function markOneRead(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Hapus 1 notifikasi admin
    public function deleteAdminOne(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM notifications WHERE id = ? AND user_id IS NULL");
        $stmt->execute([$id]);
    }

    // Hapus semua notifikasi admin
    public function deleteAllAdmin(): void
    {
        $this->db->exec("DELETE FROM notifications WHERE user_id IS NULL");
    }
}