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

    // Ambil notif untuk customer tertentu
    public function forUser(int $userId, int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Hitung notif yang belum dibaca
    public function countUnread(?int $userId = null): int
    {
        if ($userId === null) {
            $stmt = $this->db->query("SELECT COUNT(*) FROM notifications WHERE user_id IS NULL AND is_read = 0");
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$userId]);
        }
        return (int) $stmt->fetchColumn();
    }

    // Tandai semua sudah dibaca
    public function markAllRead(?int $userId = null): void
    {
        if ($userId === null) {
            $this->db->exec("UPDATE notifications SET is_read = 1 WHERE user_id IS NULL");
        } else {
            $stmt = $this->db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
    }

    // Buat notif baru
    public function push(?int $userId, string $type, string $title, string $message, string $url = ''): void
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
     * Hapus 1 notifikasi milik user tertentu (pastikan user hanya bisa hapus miliknya sendiri)
     */
    public function deleteOne(int $id, int $userId): void
    {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM notifications WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
    }

    /**
     * Hapus semua notifikasi milik user
     */
    public function deleteAllForUser(int $userId): void
    {
        $db = \Database::getInstance()->getConnection();
        $stmt = $db->prepare("DELETE FROM notifications WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
}