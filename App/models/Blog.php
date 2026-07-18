<?php
namespace App\models;

class Blog extends BaseModel
{
    protected $table = 'blogs';

    /**
     * Ambil semua artikel blog, terbaru duluan.
     */
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    /**
     * Cari 1 artikel berdasarkan slug (dipakai di halaman detail blog frontend).
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->findBy('slug', $slug);
    }

    /**
     * Hapus 1 artikel berdasarkan ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}