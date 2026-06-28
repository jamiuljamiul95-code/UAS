<?php
namespace App\controllers;

use App\models\User;

class AuthController extends BaseController {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    // GET /login
    public function loginForm(): void {
        if (isset($_SESSION['user_id'])) $this->redirect('/');
        $this->view('frontend/auth/login');
    }

    // POST /login
    public function login(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $this->view('frontend/auth/login', ['error' => 'Email dan password wajib diisi.']);
            return;
        }

        $user = $this->user->findByEmail($email);

        if (!$user || !$this->user->verifyPassword($password, $user['password'])) {
            $this->view('frontend/auth/login', ['error' => 'Email atau password salah.']);
            return;
        }

        if ($user['status'] === 'inactive') {
            $this->view('frontend/auth/login', ['error' => 'Akun kamu dinonaktifkan.']);
            return;
        }

        // Set session
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        $this->redirect($user['role'] === 'admin' ? '/admin/dashboard' : '/');
    }

    // GET /register
    public function registerForm(): void {
        if (isset($_SESSION['user_id'])) $this->redirect('/');
        $this->view('frontend/auth/register');
    }

    // POST /register
    public function register(): void {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';

        if (!$name || !$email || !$password) {
            $this->view('frontend/auth/register', ['error' => 'Semua field wajib diisi.']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('frontend/auth/register', ['error' => 'Format email tidak valid.']);
            return;
        }

        if (strlen($password) < 8) {
            $this->view('frontend/auth/register', ['error' => 'Password minimal 8 karakter.']);
            return;
        }

        if ($password !== $confirm) {
            $this->view('frontend/auth/register', ['error' => 'Konfirmasi password tidak cocok.']);
            return;
        }

        if ($this->user->findByEmail($email)) {
            $this->view('frontend/auth/register', ['error' => 'Email sudah terdaftar.']);
            return;
        }

        $id = $this->user->register($name, $email, $password);

        $_SESSION['user_id']   = $id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = 'customer';

        $this->redirect('/');
    }

    // GET /logout
    public function logout(): void {
        session_destroy();
        $this->redirect('/login');
    }
}