<?php
namespace App\controllers;

use App\models\Product;
use App\models\Category;

class HomeController extends BaseController {
    private Product $product;
    private Category $category;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
    }

    public function index(): void {
        $categories = $this->category->all();
        $latest     = $this->product->published(8, 0);

        // Produk terlaris — query sederhana sort by sales
        $stmt = $this->product->all();
        $bestSellers = array_slice(
            array_filter($stmt, fn($p) => $p['status'] === 'published'),
            0, 8
        );

        $this->view('frontend/home', [
            'title'       => 'Mizu Design — Marketplace Produk Digital',
            'categories'  => $categories,
            'latest'      => $latest,
            'bestSellers' => $bestSellers,
        ]);
    }
}