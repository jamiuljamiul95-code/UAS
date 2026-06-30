<?php
namespace App\models;

class User extends BaseModel {
    protected $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findBy('email', $email);
    }

    public function register(string $name, string $email, string $password): int {
        return $this->create([
            'name'     => $name,
            'email'    => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'role'     => 'customer',
            'status'   => 'active'
        ]);
    }

    public function verifyPassword(string $input, string $hash): bool {
        return password_verify($input, $hash);
        }
        
    public function all(): array {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}