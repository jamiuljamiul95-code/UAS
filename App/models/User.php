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
}