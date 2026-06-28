<?php
session_start();

use App\controllers\AuthController;
use App\controllers\HomeController;
use App\controllers\ProductController;
use App\controllers\CartController;
use App\controllers\CheckoutController;
use App\middleware\AuthMiddleware;
use App\controllers\Admin\ProductController as AdminProductController;
use App\controllers\Admin\CategoryController as AdminCategoryController;

$method = $_SERVER['REQUEST_METHOD'];

// Ambil path folder tempat index.php berada (misal: /mizu-design/public)
// supaya routing tetap benar walau project ada di subfolder, bukan di root domain.
$basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));

// Ambil URI dari request, lalu buang query string (?xxx)
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Hapus prefix base path, baru bersihkan slash di awal/akhir
$uri = $requestPath;
if ($basePath !== '/' && str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}
$uri = trim($uri, '/');

// ===== Cek dulu khusus rute admin, karena butuh middleware =====
if (str_starts_with($uri, 'admin')) {
    AuthMiddleware::adminOnly();

    $productCtrl  = new AdminProductController();
    $categoryCtrl = new AdminCategoryController();

    match (true) {
        $uri === 'admin/products'        && $method === 'GET'  => $productCtrl->index(),
        $uri === 'admin/products/create' && $method === 'GET'  => $productCtrl->createForm(),
        $uri === 'admin/products'        && $method === 'POST' => $productCtrl->store(),
        $uri === 'admin/products/edit'   && $method === 'GET'  => $productCtrl->editForm(),
        $uri === 'admin/products/update' && $method === 'POST' => $productCtrl->update(),
        $uri === 'admin/products/delete' && $method === 'POST' => $productCtrl->destroy(),

        $uri === 'admin/categories'        && $method === 'GET'  => $categoryCtrl->index(),
        $uri === 'admin/categories'        && $method === 'POST' => $categoryCtrl->store(),
        $uri === 'admin/categories/update' && $method === 'POST' => $categoryCtrl->update(),
        $uri === 'admin/categories/delete' && $method === 'POST' => $categoryCtrl->destroy(),

        default => (function () {
            http_response_code(404);
            die('404 — Halaman admin tidak ditemukan.');
        })()
    };
    exit;
}

// ===== Semua rute non-admin digabung dalam SATU match(true) =====
$auth         = new AuthController();
$home         = new HomeController();
$productCtrl  = new ProductController();
$cartCtrl     = new CartController();
$checkoutCtrl = new CheckoutController();

match (true) {
    // --- Auth ---
    $uri === 'login'    && $method === 'GET'  => $auth->loginForm(),
    $uri === 'login'    && $method === 'POST' => $auth->login(),
    $uri === 'register' && $method === 'GET'  => $auth->registerForm(),
    $uri === 'register' && $method === 'POST' => $auth->register(),
    $uri === 'logout'                         => $auth->logout(),

    // --- Home & Produk ---
    $uri === ''                       => $home->index(),
    $uri === 'shop'                   => $productCtrl->shop(),
    str_starts_with($uri, 'product/') => $productCtrl->detail(substr($uri, 8)),

    // --- Cart ---
    $uri === 'cart'        && $method === 'GET'  => $cartCtrl->index(),
    $uri === 'cart/add'    && $method === 'POST' => $cartCtrl->add(),
    $uri === 'cart/remove' && $method === 'POST' => $cartCtrl->remove(),
    $uri === 'cart/coupon' && $method === 'POST' => $cartCtrl->applyCoupon(),

    // --- Checkout ---
    $uri === 'checkout'         && $method === 'GET'  => $checkoutCtrl->index(),
    $uri === 'checkout'         && $method === 'POST' => $checkoutCtrl->process(),
    $uri === 'checkout/pending' && $method === 'GET'  => $checkoutCtrl->pending(),

    // --- 404 ---
    default => (function () {
        http_response_code(404);
        die('404 — Halaman tidak ditemukan.');
    })()
};