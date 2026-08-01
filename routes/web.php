<?php
session_start(); //[cite: 1]

// Hilangkan BASE_URL dari URI supaya routing tetap baca 'login', bukan 'mizu-design/public/login'
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); //[cite: 1]
$basePath = trim(BASE_URL, '/'); //[cite: 1]
if ($basePath !== '' && str_starts_with($uri, $basePath)) { //[cite: 1]
    $uri = trim(substr($uri, strlen($basePath)), '/'); //[cite: 1]
} //[cite: 1]

$method = $_SERVER['REQUEST_METHOD']; //[cite: 1]

use App\controllers\AuthController; //[cite: 1]
use App\controllers\HomeController; //[cite: 1]
use App\controllers\ProductController; //[cite: 1]
use App\controllers\CartController; //[cite: 1]
use App\controllers\CheckoutController; //[cite: 1]
use App\controllers\WebhookController; //[cite: 1]
use App\controllers\DashboardController; //[cite: 1]
use App\controllers\WishlistController; //[cite: 1]
use App\middleware\AuthMiddleware; //[cite: 1]
use App\controllers\DownloadController; //[cite: 1]
use App\controllers\PageController; //[cite: 1]
use App\controllers\Admin\AdminController; //[cite: 1]
use App\controllers\Admin\ProductController as AdminProductController; //[cite: 1]
use App\controllers\Admin\CategoryController as AdminCategoryController; //[cite: 1]
use App\controllers\Admin\OrderController as AdminOrderController; //[cite: 1]
use App\controllers\Admin\UserController as AdminUserController; //[cite: 1]

// Import Controller Blog Baru
use App\controllers\Admin\AdminBlogController;
use App\controllers\BlogController;


// ====== AUTH ======
if (in_array($uri, ['login', 'register', 'logout'])) { //[cite: 1]
    $auth = new AuthController(); //[cite: 1]
    match (true) { //[cite: 1]
        $uri === 'login' && $method === 'GET' => $auth->loginForm(), //[cite: 1]
        $uri === 'login' && $method === 'POST' => $auth->login(), //[cite: 1]
        $uri === 'register' && $method === 'GET' => $auth->registerForm(), //[cite: 1]
        $uri === 'register' && $method === 'POST' => $auth->register(), //[cite: 1]
        $uri === 'logout' => $auth->logout(), //[cite: 1]
    }; //[cite: 1]
    exit; //[cite: 1]
} //[cite: 1]

// ====== ADMIN (semua butuh login + role admin) ======
if (str_starts_with($uri, 'admin')) { //[cite: 1]
    AuthMiddleware::adminOnly(); //[cite: 1]

    // Tandai semua notif admin sudah dibaca
    if ($uri === 'admin/notifications/read-all') { //[cite: 1]
        $n = new \App\models\Notification(); //[cite: 1]
        $n->markAllRead(null); //[cite: 1]
        header('Location: ' . BASE_URL . '/admin/dashboard'); //[cite: 1]
        exit; //[cite: 1]
    } //[cite: 1]
    if ($uri === 'admin/notifications/read' && $method === 'GET') {
        $id = (int) ($_GET['id'] ?? 0);
        $redirect = $_GET['redirect'] ?? '/admin/dashboard';

        if ($id > 0) {
            $n = new \App\models\Notification();
            $n->markOneRead($id);
        }

        header('Location: ' . BASE_URL . $redirect);
        exit;
    }

    if ($uri === 'admin/notifications/delete' && $method === 'POST') {
        $id = (int) ($_POST['id'] ?? 0);
        $n = new \App\models\Notification();
        $n->deleteAdminOne($id);
        exit; // fetch() tidak butuh redirect
    }

    if ($uri === 'admin/notifications/delete-all' && $method === 'GET') {
        $n = new \App\models\Notification();
        $n->deleteAllAdmin();
        header('Location: ' . BASE_URL . '/admin/dashboard');
        exit;
    }


    $adminCtrl = new AdminController(); //[cite: 1]
    $productCtrl = new AdminProductController(); //[cite: 1]
    $categoryCtrl = new AdminCategoryController(); //[cite: 1]
    $orderCtrl = new AdminOrderController(); //[cite: 1]
    $userCtrl = new AdminUserController(); //[cite: 1]
    $adminBlogCtrl = new AdminBlogController(); // Inisialisasi controller blog admin

    match (true) { //[cite: 1]
        $uri === 'admin/dashboard' && $method === 'GET' => $adminCtrl->dashboard(), //[cite: 1]

        // --- BLOG ADMIN CRUD ---
        $uri === 'admin/blogs' && $method === 'GET' => $adminBlogCtrl->index(),
        $uri === 'admin/blogs/create' && $method === 'GET' => $adminBlogCtrl->create(),
        $uri === 'admin/blogs/store' && $method === 'POST' => $adminBlogCtrl->store(),
        $uri === 'admin/blogs/edit' && $method === 'GET' => $adminBlogCtrl->edit(),
        $uri === 'admin/blogs/update' && $method === 'POST' => $adminBlogCtrl->update(),
        $uri === 'admin/blogs/delete' && $method === 'GET' => $adminBlogCtrl->delete(),

        // --- PRODUCT ADMIN ---
        $uri === 'admin/products' && $method === 'GET' => $productCtrl->index(), //[cite: 1]
        $uri === 'admin/products/create' && $method === 'GET' => $productCtrl->createForm(), //[cite: 1]
        $uri === 'admin/products' && $method === 'POST' => $productCtrl->store(), //[cite: 1]
        $uri === 'admin/products/edit' && $method === 'GET' => $productCtrl->editForm(), //[cite: 1]
        $uri === 'admin/products/update' && $method === 'POST' => $productCtrl->update(), //[cite: 1]
        $uri === 'admin/products/delete' && $method === 'POST' => $productCtrl->destroy(), //[cite: 1]

        // --- CATEGORY ADMIN ---
        $uri === 'admin/categories' && $method === 'GET' => $categoryCtrl->index(), //[cite: 1]
        $uri === 'admin/categories' && $method === 'POST' => $categoryCtrl->store(), //[cite: 1]
        $uri === 'admin/categories/update' && $method === 'POST' => $categoryCtrl->update(), //[cite: 1]
        $uri === 'admin/categories/delete' && $method === 'POST' => $categoryCtrl->destroy(), //[cite: 1]

        // --- ORDER ADMIN ---
        $uri === 'admin/orders' && $method === 'GET' => $orderCtrl->index(), //[cite: 1]
        $uri === 'admin/orders/detail' && $method === 'GET' => $orderCtrl->detail(), //[cite: 1]
        $uri === 'admin/orders/update-status' && $method === 'POST' => $orderCtrl->updateStatus(), //[cite: 1]

        // --- USER ADMIN ---
        $uri === 'admin/users' && $method === 'GET' => $userCtrl->index(), //[cite: 1]
        $uri === 'admin/users/toggle-status' && $method === 'POST' => $userCtrl->toggleStatus(), //[cite: 1]
        $uri === 'admin/users/delete' && $method === 'POST' => $userCtrl->destroy(), //[cite: 1]

        $uri === 'admin/products/media/delete' && $method === 'POST' => $productCtrl->deleteMedia(), //[cite: 1]

        $uri === 'admin/reports/download' && $method === 'GET' => $adminCtrl->downloadReport(),
        $uri === 'admin/reports/download-excel' && $method === 'GET' => $adminCtrl->downloadReportExcel(),

        default => (function () { //[cite: 1]
                http_response_code(404); //[cite: 1]
                require ROOT . '/app/views/frontend/404.php'; //[cite: 1]
                exit; //[cite: 1]
            })() //[cite: 1]
    }; //[cite: 1]
    exit; //[cite: 1]
} //[cite: 1]

// ====== DASHBOARD CUSTOMER (butuh login, tidak harus admin) ======
if (str_starts_with($uri, 'dashboard')) { //[cite: 1]
    AuthMiddleware::check(); //[cite: 1]

    $dashboardCtrl = new DashboardController(); //[cite: 1]

    match (true) { //[cite: 1]
        $uri === 'dashboard' && $method === 'GET' => $dashboardCtrl->index(), //[cite: 1]
        $uri === 'dashboard/profile' && $method === 'GET' => $dashboardCtrl->profile(), //[cite: 1]
        $uri === 'dashboard/profile/update' && $method === 'POST' => $dashboardCtrl->updateProfile(), //[cite: 1]
        $uri === 'dashboard/password/update' && $method === 'POST' => $dashboardCtrl->updatePassword(), //[cite: 1]
        $uri === 'dashboard/orders' && $method === 'GET' => $dashboardCtrl->orders(), //[cite: 1]
        $uri === 'dashboard/orders/detail' && $method === 'GET' => $dashboardCtrl->orderDetail(), //[cite: 1]
        $uri === 'dashboard/downloads' && $method === 'GET' => $dashboardCtrl->downloads(), //[cite: 1]
        $uri === 'dashboard/orders/hide' && $method === 'POST' => $dashboardCtrl->hideOrder(), //[cite: 1]
        $uri === 'dashboard/downloads/hide' && $method === 'POST' => $dashboardCtrl->hideDownload(), //[cite: 1]
        $uri === 'dashboard/downloads/hide-expired' && $method === 'POST' => $dashboardCtrl->hideAllExpiredDownloads(), //[cite: 1]

        default => (function () { //[cite: 1]
                http_response_code(404); //[cite: 1]
                require ROOT . '/app/views/frontend/404.php'; //[cite: 1]
                exit; //[cite: 1]
            })() //[cite: 1]
    }; //[cite: 1]
    exit; //[cite: 1]
} //[cite: 1]


// ====== CUSTOMER NOTIFICATION ======
if ($uri === 'notifications/read-all' && isset($_SESSION['user_id'])) { //[cite: 1]
    AuthMiddleware::check(); //[cite: 1]

    $n = new \App\models\Notification(); //[cite: 1]
    $n->markAllRead($_SESSION['user_id']); //[cite: 1]

    header('Location: ' . BASE_URL . '/'); //[cite: 1]
    exit; //[cite: 1]
} //[cite: 1]

if ($uri === 'notifications/read' && $method === 'GET' && isset($_SESSION['user_id'])) {
    AuthMiddleware::check();

    $id = (int) ($_GET['id'] ?? 0);
    $redirect = $_GET['redirect'] ?? '/';

    if ($id > 0) {
        $n = new \App\models\Notification();
        $n->markOneRead($id);
    }

    header('Location: ' . BASE_URL . $redirect);
    exit;
}

if ($uri === 'notifications/delete' && $method === 'POST' && isset($_SESSION['user_id'])) { //[cite: 1]
    AuthMiddleware::check(); //[cite: 1]

    $id = (int) ($_POST['id'] ?? 0); //[cite: 1]
    $n = new \App\models\Notification(); //[cite: 1]
    $n->deleteOne($id, $_SESSION['user_id']); //[cite: 1]

    exit; // fetch() tidak butuh redirect/isi respons //[cite: 1]
} //[cite: 1]

if ($uri === 'notifications/delete-all' && $method === 'POST' && isset($_SESSION['user_id'])) { //[cite: 1]
    AuthMiddleware::check(); //[cite: 1]

    $n = new \App\models\Notification(); //[cite: 1]
    $n->deleteAllForUser($_SESSION['user_id']); //[cite: 1]

    exit; //[cite: 1]
} //[cite: 1]


// ====== FRONTEND ======
$home = new HomeController(); //[cite: 1]
$productCtrl = new ProductController(); //[cite: 1]
$cartCtrl = new CartController(); //[cite: 1]
$checkoutCtrl = new CheckoutController(); //[cite: 1]
$wishlistCtrl = new WishlistController(); //[cite: 1]
$downloadCtrl = new DownloadController(); //[cite: 1]
$pageCtrl = new PageController(); //[cite: 1]
$blogCtrl = new BlogController(); // Inisialisasi controller blog frontend

match (true) { //[cite: 1]
    $uri === '' => $home->index(), //[cite: 1]
    $uri === 'about' => $pageCtrl->about(), //[cite: 1]
    $uri === 'faq' => $pageCtrl->faq(), //[cite: 1]
    $uri === 'shop' => $productCtrl->shop(), //[cite: 1]
    preg_match('#^product/(.+)/review$#', $uri, $reviewMatch) && $method === 'POST' => $productCtrl->submitReview($reviewMatch[1]),
    str_starts_with($uri, 'product/') && $method === 'GET' => $productCtrl->detail(substr($uri, 8)),//[cite: 1]

    // --- BLOG FRONTEND ---
    $uri === 'blog' && $method === 'GET' => $blogCtrl->index(),
    $uri === 'blog/detail' && $method === 'GET' => $blogCtrl->detail(),

    $uri === 'cart' && $method === 'GET' => $cartCtrl->index(), //[cite: 1]
    $uri === 'cart/add' && $method === 'POST' => $cartCtrl->add(), //[cite: 1]
    $uri === 'cart/remove' && $method === 'POST' => $cartCtrl->remove(), //[cite: 1]
    $uri === 'cart/coupon' && $method === 'POST' => $cartCtrl->applyCoupon(), //[cite: 1]

    $uri === 'checkout' && $method === 'GET' => $checkoutCtrl->index(), //[cite: 1]
    $uri === 'checkout' && $method === 'POST' => $checkoutCtrl->process(), //[cite: 1]
    $uri === 'checkout/pending' && $method === 'GET' => $checkoutCtrl->pending(), //[cite: 1]

    $uri === 'checkout/core-charge' && $method === 'POST' => $checkoutCtrl->coreCharge(),
    $uri === 'checkout/order-status' && $method === 'GET' => $checkoutCtrl->orderStatus(),
    $uri === 'checkout/midtrans-webhook' && $method === 'POST' => $checkoutCtrl->midtransWebhook(),

    $uri === 'checkout/midtrans-token' && $method === 'POST' => $checkoutCtrl->midtransToken(),

    $uri === 'wishlist' && $method === 'GET' => $wishlistCtrl->index(), //[cite: 1]
    $uri === 'wishlist/add' && $method === 'POST' => $wishlistCtrl->add(), //[cite: 1]
    $uri === 'promo' => $productCtrl->promo(), //[cite: 1]

    str_starts_with($uri, 'download/') => $downloadCtrl->serve(substr($uri, 9)), //[cite: 1]

    default => (function () { //[cite: 1]
            http_response_code(404); //[cite: 1]
            require ROOT . '/app/views/frontend/404.php'; //[cite: 1]
            exit; //[cite: 1]
        })() //[cite: 1]
}; //[cite: 1]