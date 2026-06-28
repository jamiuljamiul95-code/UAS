<?php
namespace App\controllers;

use App\models\Product;
use App\models\Order;
use App\helpers\CartHelper;

class CheckoutController extends BaseController {
    private Product $product;
    private Order $order;

    public function __construct() {
        $this->product = new Product();
        $this->order   = new Order();
    }

    // GET /checkout
    public function index(): void {
        if (!isset($_SESSION['user_id'])) { $this->redirect('/login'); return; }

        $ids = CartHelper::all();
        if (empty($ids)) { $this->redirect('/cart'); return; }

        $items = $this->product->findMany($ids);
        [$subtotal, $couponDiscount, $total] = $this->calculateTotal($items);

        $this->view('frontend/checkout', [
            'title'          => 'Checkout — Mizu Design',
            'items'          => $items,
            'subtotal'       => $subtotal,
            'couponDiscount' => $couponDiscount,
            'total'          => $total,
        ]);
    }

    // POST /checkout
    public function process(): void {
        if (!isset($_SESSION['user_id'])) { $this->redirect('/login'); return; }

        $ids = CartHelper::all();
        if (empty($ids)) { $this->redirect('/cart'); return; }

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

        // Cart & kupon dibersihkan setelah order dibuat
        CartHelper::clear();
        unset($_SESSION['coupon']);

        // Minggu 6: di sini nanti kita panggil Midtrans Snap API
        // dan redirect ke halaman pembayaran, bukan langsung ke 'sukses'.
        $this->redirect('/checkout/pending?invoice=' . $invoice);
    }

    // GET /checkout/pending — placeholder sebelum Midtrans terpasang
    public function pending(): void {
        $invoice = $_GET['invoice'] ?? '';
        $order = $this->order->findByInvoice($invoice);
        if (!$order) { http_response_code(404); die('Order tidak ditemukan.'); }

        $this->view('frontend/checkout-pending', [
            'title' => 'Menunggu Pembayaran — Mizu Design',
            'order' => $order,
        ]);
    }

    private function calculateTotal(array $items): array {
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