<?php
namespace App\models;

class Wishlist extends BaseModel {
    protected $table = 'wishlists';

    public function byUser(int $userId): array {
        $stmt = $this->db->prepare("
            SELECT w.*, p.title, p.slug, p.thumbnail, p.price, p.discount, c.name AS category_name
            FROM wishlists w
            JOIN products p ON p.id = w.product_id
            JOIN categories c ON c.id = p.category_id
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function toggle(int $userId, int $productId): array {
        $existing = $this->db->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $existing->execute([$userId, $productId]);
        $row = $existing->fetch();

        if ($row) {
            $this->db->prepare("DELETE FROM wishlists WHERE id = ?")->execute([$row['id']]);
            return ['success' => true, 'message' => 'Dihapus dari wishlist.', 'wishlisted' => false];
        } else {
            $this->create(['user_id' => $userId, 'product_id' => $productId]);
            return ['success' => true, 'message' => 'Ditambahkan ke wishlist!', 'wishlisted' => true];
        }
    }

    public function isWishlisted(int $userId, int $productId): bool {
        $stmt = $this->db->prepare("SELECT id FROM wishlists WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        return (bool)$stmt->fetch();
    }
}