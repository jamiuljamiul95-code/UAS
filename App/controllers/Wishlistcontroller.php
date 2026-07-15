<?php
namespace App\controllers;

use App\models\Wishlist;
use App\middleware\AuthMiddleware;

class WishlistController extends BaseController
{
    private Wishlist $wishlist;

    public function __construct()
    {
        $this->wishlist = new Wishlist();
    }

    // GET /wishlist
    public function index(): void
    {
        AuthMiddleware::check();
        $items = $this->wishlist->byUser((int) ($_SESSION['user_id'] ?? 0));

        $this->view('frontend/wishlist', [
            'title' => 'Wishlist Saya — Mizu Design',
            'items' => $items,
        ]);
    }

    // POST /wishlist/add (AJAX)
    public function add(): void
    {
        if (!isset($_SESSION['user_id'])) {
            $this->json(['success' => false, 'message' => 'Silakan login dulu.']);
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        if (!$productId) {
            $this->json(['success' => false, 'message' => 'Produk tidak valid.']);
        }

        $result = $this->wishlist->toggle((int) ($_SESSION['user_id'] ?? 0), $productId);
        $this->json($result);
    }
}