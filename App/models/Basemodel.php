<?php
namespace App\models;

use Database;

class BaseModel {
    protected $db;
    protected $table;

    public function __construct() {
        require_once ROOT . '/config/database.php';
        $this->db = Database::getInstance()->getConnection();
    }

    public function find(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findBy(string $col, $value): ?array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$col} = ?");
        $stmt->execute([$value]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int {
        $cols = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
        $stmt = $this->db->prepare("UPDATE {$this->table} SET $set WHERE id=?");
        return $stmt->execute([...array_values($data), $id]);
    }
}