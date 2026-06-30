<?php
session_start();

// Hilangkan BASE_URL dari URI supaya routing tetap baca 'login', bukan 'mizu-design/public/login'
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$basePath = trim(BASE_URL, '/');
if ($basePath !== '' && str_starts_with($uri, $basePath)) {
    $uri = trim(substr($uri, strlen($basePath)), '/');
}

$method = $_SERVER['REQUEST_METHOD'];

use App\controllers\AuthController;
use App\controllers\HomeController;
use App\controllers\ProductController;
use App\controllers\CartController;
use App\controllers\CheckoutController;
use App\controllers\WebhookController;
use App\middleware\AuthMiddleware;
use App\controllers\Admin\AdminController;
use App\controllers\Admin\ProductController as AdminProductController;
use App\controllers\Admin\CategoryController as AdminCategoryController;
use App\controllers\Admin\OrderController as AdminOrderController;
use App\controllers\Admin\UserController as AdminUserController;

// ====== WEBHOOK (harus di luar middleware, dipanggil server Midtrans bukan browser) ======
if ($uri === 'webhook/midtrans' && $method === 'POST') {
    (new WebhookController())->midtrans();
    exit;
}

// ====== AUTH ======
if (in_array($uri, ['login', 'register', 'logout'])) {
    $auth = new AuthController();
    match (true) {
        $uri === 'login'    && $method === 'GET'  => $auth->loginForm(),
        $uri === 'login'    && $method === 'POST' => $auth->login(),
        $uri === 'register' && $method === 'GET'  => $auth->registerForm(),
        $uri === 'register' && $method === 'POST' => $auth->register(),
        $uri === 'logout'                         => $auth->logout(),
    };
    exit;
}

// ====== ADMIN (semua butuh login + role admin) ======
if (str_starts_with($uri, 'admin')) {
    AuthMiddleware::adminOnly();

    $adminCtrl    = new AdminController();
    $productCtrl  = new AdminProductController();
    $categoryCtrl = new AdminCategoryController();
    $orderCtrl    = new AdminOrderController();
    $userCtrl     = new AdminUserController();

    match (true) {
        $uri === 'admin/dashboard' && $method === 'GET' => $adminCtrl->dashboard(),

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

        $uri === 'admin/orders'               && $method === 'GET'  => $orderCtrl->index(),
        $uri === 'admin/orders/detail'         && $method === 'GET'  => $orderCtrl->detail(),
        $uri === 'admin/orders/update-status'  && $method === 'POST' => $orderCtrl->updateStatus(),

        $uri === 'admin/users'                && $method === 'GET'  => $userCtrl->index(),
        $uri === 'admin/users/toggle-status'  && $method === 'POST' => $userCtrl->toggleStatus(),
        $uri === 'admin/users/delete'          && $method === 'POST' => $userCtrl->destroy(),

        default => (function () {
            http_response_code(404);
            die('404 — Halaman admin tidak ditemukan.');
        })()
    };
    exit;
}

// ====== FRONTEND ======
$home        = new HomeController();
$productCtrl = new ProductController();
$cartCtrl    = new CartController();
$checkoutCtrl = new CheckoutController();

match (true) {
    $uri === ''                       => $home->index(),
    $uri === 'shop'                   => $productCtrl->shop(),
    str_starts_with($uri, 'product/') => $productCtrl->detail(substr($uri, 8)),

    $uri === 'cart'        && $method === 'GET'  => $cartCtrl->index(),
    $uri === 'cart/add'    && $method === 'POST' => $cartCtrl->add(),
    $uri === 'cart/remove' && $method === 'POST' => $cartCtrl->remove(),
    $uri === 'cart/coupon' && $method === 'POST' => $cartCtrl->applyCoupon(),

    $uri === 'checkout'         && $method === 'GET'  => $checkoutCtrl->index(),
    $uri === 'checkout'         && $method === 'POST' => $checkoutCtrl->process(),
    $uri === 'checkout/pending' && $method === 'GET'  => $checkoutCtrl->pending(),

    default => (function () {
        http_response_code(404);
        die('404 — Halaman tidak ditemukan.');
    })()
};