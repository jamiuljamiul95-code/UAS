<?php
namespace App\models;

class Product extends BaseModel
{
    protected $table = 'products';

    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function published(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.status = 'published'
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function search(string $keyword): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.status = 'published' AND p.title LIKE ?
            ORDER BY p.created_at DESC
        ");
        $stmt->execute(["%$keyword%"]);
        return $stmt->fetchAll();
    }

    public function byCategory(int $categoryId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.category_id = ? AND p.status = 'published'
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function findMany(array $ids): array
    {
        if (empty($ids))
            return [];
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.id IN ($placeholders)
        ");
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua produk dari kategori utama + semua sub-kategorinya
     */
    public function byParentCategory(int $parentId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.status = 'published'
            AND (
                p.category_id = ?
                OR p.category_id IN (
                    SELECT id FROM categories WHERE parent_id = ?
                )
            )
            ORDER BY p.created_at DESC
        ");
        $stmt->execute([$parentId, $parentId]);
        return $stmt->fetchAll();
    }

    public function getMedia(int $productId): array
    {
        $stmt = $this->db->prepare("
        SELECT * FROM product_media 
        WHERE product_id = ? 
        ORDER BY sort_order ASC, id ASC
    ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function addMedia(int $productId, string $type, string $filePath, int $order = 0): void
    {
        $stmt = $this->db->prepare("
            INSERT INTO product_media (product_id, type, file_path, sort_order) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$productId, $type, $filePath, $order]);
    }

    public function deleteMedia(int $mediaId): void
    {
        $stmt = $this->db->prepare("DELETE FROM product_media WHERE id = ?");
        $stmt->execute([$mediaId]);
    }
    // Tambahkan fungsi ini di dalam class Product
    public function getPromoProducts(): array
    {
        // Mengambil produk yang memiliki diskon > 0
        $stmt = $this->db->query("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.status = 'published'
            AND p.discount IS NOT NULL 
            AND p.discount > 0 
            ORDER BY p.created_at DESC
        ");

        return $stmt->fetchAll();
    }

    public function getRatingSummary(int $productId): array
    {
        $stmt = $this->db->prepare("
        SELECT COUNT(*) AS total_reviews, COALESCE(AVG(rating), 0) AS avg_rating
        FROM reviews WHERE product_id = ?
    ");
        $stmt->execute([$productId]);
        return $stmt->fetch();
    }

    public function getReviews(int $productId): array
    {
        $stmt = $this->db->prepare("
        SELECT r.*, u.name AS user_name, u.photo AS user_photo
        FROM reviews r
        JOIN users u ON u.id = r.user_id
        WHERE r.product_id = ?
        ORDER BY r.created_at DESC
    ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function hasUserReviewed(int $productId, int $userId): bool
    {
        $stmt = $this->db->prepare("SELECT id FROM reviews WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$productId, $userId]);
        return (bool) $stmt->fetch();
    }

    public function userHasPurchased(int $productId, int $userId): bool
    {
        $stmt = $this->db->prepare("
        SELECT oi.id FROM order_items oi
        JOIN orders o ON o.id = oi.order_id
        WHERE o.user_id = ? AND oi.product_id = ? AND o.status = 'paid'
        LIMIT 1
    ");
        $stmt->execute([$userId, $productId]);
        return (bool) $stmt->fetch();
    }

    public function addReview(int $productId, int $userId, int $rating, ?string $comment): void
    {
        $stmt = $this->db->prepare("
        INSERT INTO reviews (product_id, user_id, rating, comment)
        VALUES (?, ?, ?, ?)
    ");
        $stmt->execute([$productId, $userId, $rating, $comment]);
    }
}