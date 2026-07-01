<?php
namespace App\controllers;

use App\models\User;
use App\models\Order;
use App\models\Download;

class DashboardController extends BaseController {
    private User $user;
    private Order $order;
    private Download $download;

    public function __construct() {
        $this->user     = new User();
        $this->order    = new Order();
        $this->download = new Download();
    }

    // GET /dashboard -> alias ke profil
    public function index(): void {
        $this->redirect('/dashboard/profile');
    }

    // GET /dashboard/profile
    public function profile(): void {
        $user = $this->user->find($_SESSION['user_id']);

        $this->view('frontend/dashboard/profile', [
            'title' => 'Profil Saya',
            'user'  => $user,
        ]);
    }

    // POST /dashboard/profile/update
    public function updateProfile(): void {
        $userId = $_SESSION['user_id'];
        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');

        if (!$name || !$email) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user'  => $this->user->find($userId),
                'error' => 'Nama dan email wajib diisi.',
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user'  => $this->user->find($userId),
                'error' => 'Format email tidak valid.',
            ]);
            return;
        }

        // Kalau email diganti, pastikan belum dipakai user lain
        $existing = $this->user->findByEmail($email);
        if ($existing && (int)$existing['id'] !== (int)$userId) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user'  => $this->user->find($userId),
                'error' => 'Email sudah dipakai akun lain.',
            ]);
            return;
        }

        $photo = $this->user->find($userId)['photo'] ?? null;

        // Upload foto profil baru kalau ada
        if (!empty($_FILES['photo']['name'])) {
            try {
                $uploaded = \App\helpers\UploadHelper::uploadImage($_FILES['photo'], 'avatars');
                if ($uploaded) $photo = $uploaded;
            } catch (\Exception $e) {
                $this->view('frontend/dashboard/profile', [
                    'title' => 'Profil Saya',
                    'user'  => $this->user->find($userId),
                    'error' => $e->getMessage(),
                ]);
                return;
            }
        }

        $this->user->updateProfile($userId, [
            'name'  => $name,
            'email' => $email,
            'photo' => $photo,
        ]);

        $_SESSION['user_name'] = $name;

        $this->view('frontend/dashboard/profile', [
            'title'   => 'Profil Saya',
            'user'    => $this->user->find($userId),
            'success' => 'Profil berhasil diperbarui.',
        ]);
    }

    // POST /dashboard/password/update
    public function updatePassword(): void {
        $userId      = $_SESSION['user_id'];
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirm     = $_POST['confirm_password'] ?? '';

        $user = $this->user->find($userId);

        if (!$this->user->verifyPassword($oldPassword, $user['password'])) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user'  => $user,
                'error' => 'Password lama salah.',
            ]);
            return;
        }

        if (strlen($newPassword) < 8) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user'  => $user,
                'error' => 'Password baru minimal 8 karakter.',
            ]);
            return;
        }

        if ($newPassword !== $confirm) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user'  => $user,
                'error' => 'Konfirmasi password baru tidak cocok.',
            ]);
            return;
        }

        $this->user->updatePassword($userId, $newPassword);

        $this->view('frontend/dashboard/profile', [
            'title'   => 'Profil Saya',
            'user'    => $user,
            'success' => 'Password berhasil diganti.',
        ]);
    }

    // GET /dashboard/orders
    public function orders(): void {
        $orders = $this->order->byUser($_SESSION['user_id']);

        $this->view('frontend/dashboard/orders', [
            'title'  => 'Riwayat Pesanan',
            'orders' => $orders,
        ]);
    }

    // GET /dashboard/orders/detail?invoice=...
    public function orderDetail(): void {
        $invoice = $_GET['invoice'] ?? '';
        $order   = $this->order->findByInvoice($invoice);

        // Pastikan order ini benar milik user yang sedang login
        if (!$order || (int)$order['user_id'] !== (int)$_SESSION['user_id']) {
            http_response_code(404);
            die('404 — Pesanan tidak ditemukan.');
        }

        $orderWithItems = $this->order->findWithItems($order['id']);

        $this->view('frontend/dashboard/order-detail', [
            'title' => 'Detail Pesanan',
            'order' => $orderWithItems,
        ]);
    }

    // GET /dashboard/downloads
    public function downloads(): void {
        $downloads = $this->download->byUser($_SESSION['user_id']);

        $this->view('frontend/dashboard/downloads', [
            'title'     => 'Download Saya',
            'downloads' => $downloads,
        ]);
    }
}