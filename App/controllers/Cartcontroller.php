<?php
namespace App\controllers;

use App\models\Product;
use App\models\Coupon;
use App\helpers\CartHelper;

class CartController extends BaseController {
    private Product $product;
    private Coupon $coupon;

    public function __construct() {
        $this->product = new Product();
        $this->coupon  = new Coupon();
    }

    // GET /cart
    public function index(): void {
        $ids = CartHelper::all();
        $items = $this->product->findMany($ids);

        $subtotal = 0;
        foreach ($items as $item) {
            $price = $item['discount'] > 0
                ? $item['price'] - ($item['price'] * $item['discount'] / 100)
                : $item['price'];
            $subtotal += $price;
        }

        $couponData = $_SESSION['coupon'] ?? null;
        $couponDiscount = 0;
        if ($couponData) {
            $couponDiscount = $subtotal * ($couponData['discount'] / 100);
        }

        $this->view('frontend/cart', [
            'title'          => 'Keranjang — Mizu Design',
            'items'          => $items,
            'subtotal'       => $subtotal,
            'coupon'         => $couponData,
            'couponDiscount' => $couponDiscount,
            'total'          => $subtotal - $couponDiscount,
        ]);
    }

    // POST /cart/add
    public function add(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $product = $this->product->find($productId);

        if (!$product) {
            $this->json(['success' => false, 'message' => 'Produk tidak ditemukan.']);
        }

        CartHelper::add($productId);
        $this->json(['success' => true, 'message' => 'Berhasil ditambahkan ke keranjang.', 'count' => CartHelper::count()]);
    }

    // POST /cart/remove
    public function remove(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        CartHelper::remove($productId);
        $this->redirect('/cart');
    }

    // POST /cart/coupon
    public function applyCoupon(): void {
        $code = trim($_POST['code'] ?? '');
        $coupon = $this->coupon->findValidByCode($code);

        if (!$coupon) {
            $_SESSION['coupon_error'] = 'Kode voucher tidak valid atau sudah expired.';
            $this->redirect('/cart');
        }

        $_SESSION['coupon'] = $coupon;
        $this->redirect('/cart');
    }
}