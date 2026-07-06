<?php
namespace App\models;

class Product extends BaseModel {
    protected $table = 'products';

    public function all(): array {
        $stmt = $this->db->query("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON c.id = p.category_id
            ORDER BY p.created_at DESC
        ");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, c.name AS category_name, c.slug AS category_slug
            FROM products p
            JOIN categories c ON c.id = p.category_id
            WHERE p.slug = ?
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function published(int $limit = 20, int $offset = 0): array {
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

    public function search(string $keyword): array {
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

    public function byCategory(int $categoryId): array {
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

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function findMany(array $ids): array {
        if (empty($ids)) return [];
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
    public function byParentCategory(int $parentId): array {
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

    public function getMedia(int $productId): array {
    $stmt = $this->db->prepare("
        SELECT * FROM product_media 
        WHERE product_id = ? 
        ORDER BY sort_order ASC, id ASC
    ");
    $stmt->execute([$productId]);
    return $stmt->fetchAll();
    }
    
    public function addMedia(int $productId, string $type, string $filePath, int $order = 0): void {
        $stmt = $this->db->prepare("
            INSERT INTO product_media (product_id, type, file_path, sort_order) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$productId, $type, $filePath, $order]);
    }
    
    public function deleteMedia(int $mediaId): void {
        $stmt = $this->db->prepare("DELETE FROM product_media WHERE id = ?");
        $stmt->execute([$mediaId]);
    }
}