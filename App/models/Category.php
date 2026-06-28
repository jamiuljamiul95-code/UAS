<?php
namespace App\models;

class Category extends BaseModel {
    protected $table = 'categories';

    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
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