<?php
namespace App\models;

class User extends BaseModel
{
    protected $table = 'users';

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    // Mengembalikan mix/string karena menggunakan UUID
    public function register(string $name, string $email, string $password)
    {
        return $this->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'role' => 'customer',
            'status' => 'active'
        ]);
    }

    public function verifyPassword(string $input, string $hash): bool
    {
        return password_verify($input, $hash);
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Parameter ID diubah menjadi string untuk mendukung UUID
    public function delete(string $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Memperbarui data profil (nama, email, foto) milik user.
     * Dipanggil oleh DashboardController pada saat simpan perubahan profil.
     */
    public function updateProfile(string $id, array $data): bool
    {
        return $this->update($id, [
            'name' => $data['name'],
            'email' => $data['email'],
            'photo' => $data['photo'] ?? null
        ]);
    }

    /**
     * Ganti password user. Parameter ID diubah menjadi string untuk mendukung UUID.
     */
    public function updatePassword(string $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
        ]);
    }
}