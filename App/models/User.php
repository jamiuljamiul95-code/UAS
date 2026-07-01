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

    /**
     * Update data profil (nama, email, foto) milik 1 user.
     * Dipakai di Dashboard Customer > Profil.
     */
    public function updateProfile(int $id, array $data): bool {
        // Whitelist kolom yang boleh diupdate lewat form profil
        $allowed = array_intersect_key($data, array_flip(['name', 'email', 'photo']));

        if (empty($allowed)) return false;

        return $this->update($id, $allowed);
    }

    /**
     * Ganti password user. Wajib cek password lama dulu sebelum dipanggil
     * (pengecekan dilakukan di controller, bukan di model).
     */
    public function updatePassword(int $id, string $newPassword): bool {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);
    }
}