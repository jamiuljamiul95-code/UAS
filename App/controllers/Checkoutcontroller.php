<?php
namespace App\controllers;

use App\models\Product;
use App\models\Order;
use App\models\Download;
use App\helpers\CartHelper;

class CheckoutController extends BaseController
{
    private Product $product;
    private Order $order;

    public function __construct()
    {
        $this->product = new Product();
        $this->order = new Order();

        \Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? '';
        \Midtrans\Config::$isProduction = filter_var($_ENV['MIDTRANS_IS_PRODUCTION'] ?? false, FILTER_VALIDATE_BOOLEAN);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
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

    // POST /checkout  (jalur MANUAL: upload bukti transfer)
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
        $this->order->update($orderId, ['payment_proof' => $paymentProofFile]);

        foreach ($items as $item) {
            $price = $item['discount'] > 0
                ? $item['price'] - ($item['price'] * $item['discount'] / 100)
                : $item['price'];
            $this->order->addItem($orderId, $item['id'], $price);
        }

        $this->pushOrderNotifications($invoice, $total, 'Bukti pembayaran manual sudah diupload.');

        CartHelper::clear();
        unset($_SESSION['coupon']);

        $this->redirect('/checkout/pending?invoice=' . $invoice);
    }

    // POST /checkout/core-charge  (jalur OTOMATIS custom -- QRIS / VA, TANPA popup Snap)
    // Dipanggil via fetch() dari halaman checkout, return JSON.
    public function coreCharge(): void
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.']);
            exit;
        }

        $method = $_POST['method'] ?? ''; // 'qris' | 'bca' | 'bni' | 'bri'
        if (!in_array($method, ['qris', 'bca', 'bni', 'bri'])) {
            echo json_encode(['success' => false, 'message' => 'Metode pembayaran tidak valid.']);
            exit;
        }

        $ids = CartHelper::all();
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'Keranjang kosong.']);
            exit;
        }

        $items = $this->product->findMany($ids);
        [$subtotal, $couponDiscount, $total] = $this->calculateTotal($items);

        $invoice = $this->order->generateInvoice();
        $orderId = $this->order->createOrder($_SESSION['user_id'], $invoice, $total);

        foreach ($items as $item) {
            $price = $item['discount'] > 0
                ? $item['price'] - ($item['price'] * $item['discount'] / 100)
                : $item['price'];
            $this->order->addItem($orderId, $item['id'], $price);
        }

        $params = [
            'transaction_details' => [
                'order_id' => $invoice,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $_SESSION['user_name'],
                'email' => $_SESSION['user_email'] ?? '',
            ],
        ];

        if ($method === 'qris') {
            $params['payment_type'] = 'qris';
        } else {
            $params['payment_type'] = 'bank_transfer';
            $params['bank_transfer'] = ['bank' => $method];
        }

        try {
            $result = \Midtrans\CoreApi::charge($params);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()]);
            exit;
        }

        $result = (array) $result;

        $response = [
            'success' => true,
            'invoice' => $invoice,
            'method' => $method,
        ];

        if ($method === 'qris') {
            // Cari URL gambar QR dari actions
            $qrUrl = null;
            foreach ($result['actions'] ?? [] as $action) {
                $action = (array) $action;
                if ($action['name'] === 'generate-qr-code') {
                    $qrUrl = $action['url'];
                    break;
                }
            }
            $response['qr_url'] = $qrUrl;
        } else {
            // Cari nomor VA dari va_numbers
            $vaNumber = null;
            foreach ($result['va_numbers'] ?? [] as $va) {
                $va = (array) $va;
                $vaNumber = $va['va_number'];
                break;
            }
            $response['va_number'] = $vaNumber;
            $response['bank'] = strtoupper($method);
        }

        CartHelper::clear();
        unset($_SESSION['coupon']);

        $this->pushOrderNotifications($invoice, $total, 'Menunggu pembayaran via ' . strtoupper($method) . '.');

        echo json_encode($response);
        exit;
    }

    // GET /checkout/order-status?invoice=...  (dipanggil tombol "Cek Status" di halaman custom)
    public function orderStatus(): void
    {
        header('Content-Type: application/json');

        $invoice = $_GET['invoice'] ?? '';
        $order = $this->order->findByInvoice($invoice);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order tidak ditemukan.']);
            exit;
        }

        echo json_encode([
            'success' => true,
            'status' => $order['status'],
            'payment_status' => $order['payment_status'],
        ]);
        exit;
    }

    // POST /checkout/midtrans-webhook  (dipanggil server Midtrans, berlaku untuk Snap MAUPUN Core API)
    public function midtransWebhook(): void
    {
        header('Content-Type: application/json');

        $notif = json_decode(file_get_contents('php://input'), true);

        if (!$notif || empty($notif['order_id'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Payload tidak valid']);
            exit;
        }

        $invoice = $notif['order_id'];
        $statusCode = $notif['status_code'] ?? '';
        $grossAmount = $notif['gross_amount'] ?? '';
        $serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? '';

        $expectedSignature = hash('sha512', $invoice . $statusCode . $grossAmount . $serverKey);
        if (!hash_equals($expectedSignature, $notif['signature_key'] ?? '')) {
            http_response_code(403);
            echo json_encode(['message' => 'Signature tidak valid']);
            exit;
        }

        $order = $this->order->findByInvoice($invoice);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['message' => 'Order tidak ditemukan']);
            exit;
        }

        $transactionStatus = $notif['transaction_status'] ?? '';
        $fraudStatus = $notif['fraud_status'] ?? '';

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || $transactionStatus === 'settlement') {
                $this->order->updateStatus($order['id'], 'paid', 'paid');

                // Simpan detail transaksi Midtrans supaya admin bisa lihat "bukti" pembayaran otomatis
                $this->order->update($order['id'], [
                    'midtrans_transaction_id' => $notif['transaction_id'] ?? null,
                    'midtrans_payment_type' => $notif['payment_type'] ?? null,
                    'midtrans_paid_at' => date('Y-m-d H:i:s'),
                ]);

                $notif2 = new \App\models\Notification();
                $notif2->push(
                    null,
                    'order',
                    'Pembayaran Diterima',
                    'Invoice ' . $invoice . ' — Rp ' . number_format($order['total'], 0, ',', '.') . ' sudah DIBAYAR.',
                    '/admin/orders'
                );

                // Generate token download otomatis, sama seperti saat admin ubah status jadi Paid manual.
                // Tanpa ini, order yang dibayar via Midtrans tidak akan muncul di "Download Saya".
                $download = new Download();
                $download->generateForOrder($order['user_id'], $order['id']);
            }
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $this->order->updateStatus($order['id'], 'failed', 'failed');
        } elseif ($transactionStatus === 'pending') {
            $this->order->updateStatus($order['id'], 'pending', 'unpaid');
        }

        echo json_encode(['message' => 'OK']);
        exit;
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

    private function pushOrderNotifications(string $invoice, float $total, string $note): void
    {
        $notif = new \App\models\Notification();

        $notif->push(
            null,
            'order',
            'Order Baru Masuk',
            'Invoice ' . $invoice . ' — Rp ' . number_format($total, 0, ',', '.') . ' (' . $note . ')',
            '/admin/orders'
        );

        $notif->push(
            $_SESSION['user_id'] ?? null,
            'order',
            'Pesanan Berhasil Dibuat!',
            'Invoice ' . $invoice . ' senilai Rp ' . number_format($total, 0, ',', '.') . ' sedang menunggu pembayaran.',
            '/dashboard/orders'
        );
    }

    private function uploadPaymentProof(array $file): ?string
    {
        $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
        $maxSize = 5 * 1024 * 1024;

        if ($file['error'] !== UPLOAD_ERR_OK)
            return null;
        if ($file['size'] > $maxSize)
            return null;

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt))
            return null;

        $uploadDir = ROOT . '/public/uploads/payment_proof/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = 'proof_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination))
            return null;

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