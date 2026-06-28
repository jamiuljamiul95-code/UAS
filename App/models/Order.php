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
}