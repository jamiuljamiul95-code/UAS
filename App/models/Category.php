<?php
namespace App\models;

class Category extends BaseModel {
    protected $table = 'categories';

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua kategori + jumlah produk published di dalamnya.
     * Dipakai di homepage untuk tampilkan "1.250+ Produk" per kategori.
     */
    public function allWithCount(): array {
        $stmt = $this->db->query("
            SELECT c.*,
                   COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN products p ON p.category_id = c.id AND p.status = 'published'
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array {
        return $this->findBy('slug', $slug);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}