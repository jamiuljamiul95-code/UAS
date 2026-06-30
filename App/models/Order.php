<?php
namespace App\models;

class Order extends BaseModel {
    protected $table = 'orders';

    public function createOrder(int $userId, string $invoice, float $total): int {
        return $this->create([
            'user_id'        => $userId,
            'invoice'        => $invoice,
            'total'          => $total,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    public function addItem(int $orderId, int $productId, float $price): void {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, price) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $productId, $price]);
    }

    public function findByInvoice(string $invoice): ?array {
        return $this->findBy('invoice', $invoice);
    }

    public function generateInvoice(): string {
        return 'INV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }

    public function updateStatus(int $id, string $status, string $paymentStatus): void {
        $this->update($id, ['status' => $status, 'payment_status' => $paymentStatus]);
    }

    public function getItems(int $orderId): array {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua order untuk admin, lengkap dengan nama & email pembeli.
     * Bisa difilter by status (pending/paid/failed/refund).
     */
    public function all(?string $status = null): array {
        if ($status) {
            $stmt = $this->db->prepare("
                SELECT o.*, u.name AS customer_name, u.email AS customer_email
                FROM orders o
                JOIN users u ON u.id = o.user_id
                WHERE o.status = ?
                ORDER BY o.created_at DESC
            ");
            $stmt->execute([$status]);
        } else {
            $stmt = $this->db->query("
                SELECT o.*, u.name AS customer_name, u.email AS customer_email
                FROM orders o
                JOIN users u ON u.id = o.user_id
                ORDER BY o.created_at DESC
            ");
        }
        return $stmt->fetchAll();
    }

    public function findWithItems(int $id): ?array {
        $order = $this->find($id);
        if (!$order) return null;

        $stmt = $this->db->prepare("
            SELECT oi.*, p.title AS product_title
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$id]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    }
}