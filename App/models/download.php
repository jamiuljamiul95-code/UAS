<?php
namespace App\models;

class Download extends BaseModel {
    protected $table = 'downloads';

    public function generateForOrder(int $userId, int $orderId): void {
        $items = (new Order())->getItems($orderId);

        foreach ($items as $item) {
            $token = bin2hex(random_bytes(24));
            $this->create([
                'user_id'    => $userId,
                'product_id' => $item['product_id'],
                'order_id'   => $orderId,
                'token'      => $token,
                'expired_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            ]);
        }
    }

    public function findByToken(string $token): ?array {
        return $this->findBy('token', $token);
    }

    public function incrementCount(int $id): void {
        $stmt = $this->db->prepare("UPDATE downloads SET download_count = download_count + 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    /**
     * Ambil semua link download milik 1 user, lengkap dengan nama produk & invoice.
     * Dipakai di halaman Dashboard > Download.
     */
    public function byUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT d.*, p.title AS product_title, p.thumbnail, o.invoice
            FROM downloads d
            JOIN products p ON p.id = d.product_id
            JOIN orders o ON o.id = d.order_id
            WHERE d.user_id = ?
            ORDER BY d.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}