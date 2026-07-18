<?php
namespace App\controllers;

use App\models\Product;
use App\models\Order;
use App\helpers\CartHelper;

class CheckoutController extends BaseController
{
    private Product $product;
    private Order $order;

    public function __construct()
    {
        $this->product = new Product();
        $this->order = new Order();
    }

    // GET /checkout
    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $ids = CartHelper::all();
        if (empty($ids)) {
            $this->redirect('/cart');
            return;
        }

        $items = $this->product->findMany($ids);
        [$subtotal, $couponDiscount, $total] = $this->calculateTotal($items);

        $this->view('frontend/checkout', [
            'title' => 'Checkout — Mizu Design',
            'items' => $items,
            'subtotal' => $subtotal,
            'couponDiscount' => $couponDiscount,
            'total' => $total,
        ]);
    }

    // POST /checkout
    public function process(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
            return;
        }

        $ids = CartHelper::all();
        if (empty($ids)) {
            $this->redirect('/cart');
            return;
        }

        $items = $this->product->findMany($ids);
        [$subtotal, $couponDiscount, $total] = $this->calculateTotal($items);

        // ===== Validasi bukti pembayaran (WAJIB) =====
        if (empty($_FILES['payment_proof']['name'])) {
            $this->view('frontend/checkout', [
                'title' => 'Checkout — Mizu Design',
                'items' => $items,
                'subtotal' => $subtotal,
                'couponDiscount' => $couponDiscount,
                'total' => $total,
                'error' => 'Bukti pembayaran wajib diupload sebelum order diproses.',
            ]);
            return;
        }

        $paymentProofFile = $this->uploadPaymentProof($_FILES['payment_proof']);

        if (!$paymentProofFile) {
            $this->view('frontend/checkout', [
                'title' => 'Checkout — Mizu Design',
                'items' => $items,
                'subtotal' => $subtotal,
                'couponDiscount' => $couponDiscount,
                'total' => $total,
                'error' => 'File bukti pembayaran tidak valid. Gunakan JPG/PNG/PDF maks 5MB.',
            ]);
            return;
        }

        $invoice = $this->order->generateInvoice();

        $orderId = $this->order->createOrder($_SESSION['user_id'], $invoice, $total);

        // Simpan nama file bukti pembayaran ke order yang baru dibuat
        $this->order->update($orderId, ['payment_proof' => $paymentProofFile]);

        foreach ($items as $item) {
            $price = $item['discount'] > 0
                ? $item['price'] - ($item['price'] * $item['discount'] / 100)
                : $item['price'];
            $this->order->addItem($orderId, $item['id'], $price);
        }

        $notif = new \App\models\Notification();
        $notif->push(
            null,
            'order',
            'Order Baru Masuk',
            'Invoice ' . $invoice . ' — Rp ' . number_format($total, 0, ',', '.') . ' (bukti pembayaran sudah diupload)',
            '/admin/orders/detail?id=' . $orderId
        );

        $notif->push(
            $_SESSION['user_id'],
            'order',
            'Pesanan Berhasil Dibuat!',
            'Invoice ' . $invoice . ' senilai Rp ' . number_format($total, 0, ',', '.') . ' sedang menunggu verifikasi pembayaran.',
            '/dashboard/orders'
        );

        // Cart & kupon dibersihkan setelah order dibuat
        CartHelper::clear();
        unset($_SESSION['coupon']);

        $this->redirect('/checkout/pending?invoice=' . $invoice);
    }

    // GET /checkout/pending
    public function pending(): void
    {
        $invoice = $_GET['invoice'] ?? '';
        $order = $this->order->findByInvoice($invoice);
        if (!$order) {
            http_response_code(404);
            die('Order tidak ditemukan.');
        }

        $this->view('frontend/checkout-pending', [
            'title' => 'Menunggu Pembayaran — Mizu Design',
            'order' => $order,
        ]);
    }

    /**
     * Upload file bukti pembayaran ke public/uploads/payment_proof/.
     * Return nama file (string) kalau berhasil, atau null kalau gagal/invalid.
     */
    private function uploadPaymentProof(array $file): ?string
    {
        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > $maxSize) {
            return null;
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt)) {
            return null;
        }

        $uploadDir = ROOT . '/public/uploads/payment_proof/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = 'proof_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return $filename;
    }

    private function calculateTotal(array $items): array
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $price = $item['discount'] > 0
                ? $item['price'] - ($item['price'] * $item['discount'] / 100)
                : $item['price'];
            $subtotal += $price;
        }

        $couponDiscount = 0;
        if (!empty($_SESSION['coupon'])) {
            $couponDiscount = $subtotal * ($_SESSION['coupon']['discount'] / 100);
        }

        return [$subtotal, $couponDiscount, $subtotal - $couponDiscount];
    }

}