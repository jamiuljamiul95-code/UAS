<?php
namespace App\controllers;

use App\models\User;
use App\models\Order;
use App\models\Download;

class DashboardController extends BaseController
{
    private User $user;
    private Order $order;
    private Download $download;

    public function __construct()
    {
        $this->user = new User();
        $this->order = new Order();
        $this->download = new Download();
    }

    // GET /dashboard -> halaman ringkasan
    public function index(): void
    {
        // Mendukung UUID (string), jangan di-cast ke (int)
        $userId = $_SESSION['user_id'] ?? '';
        $user = $this->user->find($userId);

        $orders = $this->order->byUser($userId);
        $downloads = $this->download->byUser($userId);

        // Hitung wishlist secara defensif
        $wishlistCount = 0;
        if (class_exists('\App\models\Wishlist')) {
            $wishlistModel = new \App\models\Wishlist();
            if (method_exists($wishlistModel, 'byUser')) {
                $wishlistCount = count($wishlistModel->byUser($userId));
            } elseif (method_exists($wishlistModel, 'countByUser')) {
                $wishlistCount = $wishlistModel->countByUser($userId);
            } elseif (method_exists($wishlistModel, 'all')) {
                $wishlistCount = count($wishlistModel->all($userId));
            }
        }

        $recentOrders = array_slice($orders, 0, 3);
        $recentDownloads = array_slice($downloads, 0, 3);

        $this->view('frontend/dashboard/index', [
            'title' => 'Dashboard',
            'user' => $user,
            'totalOrders' => count($orders),
            'totalDownloads' => count($downloads),
            'wishlistCount' => $wishlistCount,
            'recentOrders' => $recentOrders,
            'recentDownloads' => $recentDownloads,
        ]);
    }

    // GET /dashboard/profile
    public function profile(): void
    {
        $userId = $_SESSION['user_id'] ?? '';
        $user = $this->user->find($userId);

        $this->view('frontend/dashboard/profile', [
            'title' => 'Profil Saya',
            'user' => $user,
        ]);
    }

    // POST /dashboard/profile/update
    public function updateProfile(): void
    {
        $userId = $_SESSION['user_id'] ?? '';
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (!$name || !$email) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user' => $this->user->find($userId),
                'error' => 'Nama dan email wajib diisi.',
            ]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user' => $this->user->find($userId),
                'error' => 'Format email tidak valid.',
            ]);
            return;
        }

        // Kalau email diganti, pastikan belum dipakai user lain (Perbandingan string UUID)
        $existing = $this->user->findByEmail($email);
        if ($existing && $existing['id'] !== $userId) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user' => $this->user->find($userId),
                'error' => 'Email sudah dipakai akun lain.',
            ]);
            return;
        }

        $photo = $this->user->find($userId)['photo'] ?? null;

        // Upload foto profil baru kalau ada
        if (!empty($_FILES['photo']['name'])) {
            try {
                $uploaded = \App\helpers\UploadHelper::uploadImage($_FILES['photo'], 'avatars');
                if ($uploaded) {
                    $photo = $uploaded;
                }
            } catch (\Exception $e) {
                $this->view('frontend/dashboard/profile', [
                    'title' => 'Profil Saya',
                    'user' => $this->user->find($userId),
                    'error' => $e->getMessage(),
                ]);
                return;
            }
        }

        // Eksekusi update ke database
        $this->user->updateProfile($userId, [
            'name' => $name,
            'email' => $email,
            'photo' => $photo,
        ]);

        // 🔥 PENTING: Update data Session agar perubahan nama & foto langsung sinkron di Sidebar & Navbar!
        $_SESSION['user_name'] = $name;
        $_SESSION['user_photo'] = $photo;

        // Ambil ulang data terbaru dari database untuk di-render ke view
        $user = $this->user->find($userId);

        $this->view('frontend/dashboard/profile', [
            'title' => 'Profil Saya',
            'user' => $user,
            'success' => 'Profil berhasil diperbarui.',
        ]);
    }

    // POST /dashboard/password/update
    public function updatePassword(): void
    {
        $userId = $_SESSION['user_id'] ?? '';
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $user = $this->user->find($userId);

        if (!$this->user->verifyPassword($oldPassword, $user['password'])) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user' => $user,
                'error' => 'Password lama salah.',
            ]);
            return;
        }

        if (strlen($newPassword) < 8) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user' => $user,
                'error' => 'Password baru minimal 8 karakter.',
            ]);
            return;
        }

        if ($newPassword !== $confirm) {
            $this->view('frontend/dashboard/profile', [
                'title' => 'Profil Saya',
                'user' => $user,
                'error' => 'Konfirmasi password baru tidak cocok.',
            ]);
            return;
        }

        $this->user->updatePassword($userId, $newPassword);

        $this->view('frontend/dashboard/profile', [
            'title' => 'Profil Saya',
            'user' => $user,
            'success' => 'Password berhasil diganti.',
        ]);
    }

    // GET /dashboard/orders
    public function orders(): void
    {
        $orders = $this->order->byUser($_SESSION['user_id'] ?? '');

        $this->view('frontend/dashboard/orders', [
            'title' => 'Riwayat Pesanan',
            'orders' => $orders,
        ]);
    }

    // GET /dashboard/orders/detail?invoice=...
    public function orderDetail(): void
    {
        $invoice = $_GET['invoice'] ?? '';
        $order = $this->order->findByInvoice($invoice);

        // Pastikan order ini benar milik user yang sedang login (Perbandingan string UUID)
        if (!$order || $order['user_id'] !== ($_SESSION['user_id'] ?? '')) {
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
    public function downloads(): void
    {
        $downloads = $this->download->byUser($_SESSION['user_id'] ?? '');

        $this->view('frontend/dashboard/downloads', [
            'title' => 'Download Saya',
            'downloads' => $downloads,
        ]);
    }

    // POST /dashboard/orders/hide
    public function hideOrder(): void
    {
        $id = $_POST['id'] ?? '';
        $this->order->hideFromUser($id, $_SESSION['user_id'] ?? '');
        $this->redirect('/dashboard/orders');
    }

    // POST /dashboard/downloads/hide
    public function hideDownload(): void
    {
        $id = $_POST['id'] ?? '';
        $this->download->hideFromUser($id, $_SESSION['user_id'] ?? '');
        $this->redirect('/dashboard/downloads');
    }

    // POST /dashboard/downloads/hide-expired
    public function hideAllExpiredDownloads(): void
    {
        $this->download->hideAllExpiredFromUser($_SESSION['user_id'] ?? '');
        $this->redirect('/dashboard/downloads');
    }
}