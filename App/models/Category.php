<?php
namespace App\models;

class Category extends BaseModel
{
    protected $table = 'categories';

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua kategori + jumlah produk published di dalamnya.
     * Dipakai di homepage untuk tampilkan "1.250+ Produk" per kategori.
     */
    public function allWithCount(): array
    {
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

    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Ambil semua kategori dikelompokkan: parent → children
     */
    public function allGrouped(): array
    {
        $stmt = $this->db->query("
            SELECT * FROM categories ORDER BY parent_id ASC, name ASC
        ");
        $all = $stmt->fetchAll();

        $parents = [];
        $children = [];

        foreach ($all as $cat) {
            if ($cat['parent_id'] === null) {
                $parents[$cat['id']] = $cat;
                $parents[$cat['id']]['children'] = [];
            } else {
                $children[] = $cat;
            }
        }

        foreach ($children as $child) {
            if (isset($parents[$child['parent_id']])) {
                $parents[$child['parent_id']]['children'][] = $child;
            }
        }

        return array_values($parents);
    }


    /**
     * Ambil hanya kategori UTAMA (parent_id = NULL) dengan jumlah produk di dalamnya
     * (termasuk produk dari sub-kategorinya)
     */
    public function parentsWithCount(): array
    {
        $stmt = $this->db->query("
            SELECT c.*,
                   COUNT(p.id) AS product_count
            FROM categories c
            LEFT JOIN categories sub ON sub.parent_id = c.id
            LEFT JOIN products p ON (
                p.category_id = c.id OR p.category_id = sub.id
            ) AND p.status = 'published'
            WHERE c.parent_id IS NULL
            GROUP BY c.id
            ORDER BY c.name ASC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Sama seperti parentsWithCount(), tapi sekalian ambil 1 foto produk
     * representatif (terbaru) per kategori untuk ditampilkan sebagai thumbnail.
     */
    public function parentsWithCountAndThumbnail(): array
    {
        $stmt = $this->db->query("
        SELECT c.*,
               COUNT(p.id) AS product_count,
               (
                   SELECT p2.thumbnail
                   FROM products p2
                   WHERE (
                       p2.category_id = c.id
                       OR p2.category_id IN (SELECT id FROM categories WHERE parent_id = c.id)
                   )
                   AND p2.status = 'published'
                   ORDER BY p2.created_at DESC
                   LIMIT 1
               ) AS sample_thumbnail
        FROM categories c
        LEFT JOIN categories sub ON sub.parent_id = c.id
        LEFT JOIN products p ON (
            p.category_id = c.id OR p.category_id = sub.id
        ) AND p.status = 'published'
        WHERE c.parent_id IS NULL
        GROUP BY c.id
        ORDER BY c.name ASC
    ");
        return $stmt->fetchAll();
    }

}