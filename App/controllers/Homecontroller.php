<?php
namespace App\controllers;

use App\models\Product;
use App\models\Category;
use App\models\User;

class HomeController extends BaseController {
    private Product $product;
    private Category $category;
    private User $user;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
        $this->user     = new User();
    }

    public function index(): void {
        $categories   = $this->category->allWithCount(); // dengan jumlah produk
        $latest       = $this->product->published(8, 0);
        $allPublished = $this->product->published(100, 0);
        $allUsers     = $this->user->all();

        // Produk terlaris (sort by sales desc)
        usort($allPublished, fn($a, $b) => $b['sales'] - $a['sales']);
        $bestSellers = array_slice($allPublished, 0, 8);

        $this->view('frontend/home', [
            'title'         => 'Mizu Design — Marketplace Produk Digital',
            'categories'    => $categories,
            'latest'        => $latest,
            'bestSellers'   => $bestSellers,
            'totalProducts' => count($allPublished),
            'totalUsers'    => count($allUsers),
        ]);
    }
}