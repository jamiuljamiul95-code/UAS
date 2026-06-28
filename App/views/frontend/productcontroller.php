<?php
namespace App\controllers;

use App\models\Product;
use App\models\Category;

class ProductController extends BaseController {
    private Product $product;
    private Category $category;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
    }

    // GET /shop
    public function shop(): void {
        $keyword     = trim($_GET['q'] ?? '');
        $categorySlug = trim($_GET['category'] ?? '');
        $categories  = $this->category->all();

        if ($keyword !== '') {
            $products = $this->product->search($keyword);
        } elseif ($categorySlug !== '') {
            $cat = $this->category->findBySlug($categorySlug);
            $products = $cat ? $this->product->byCategory($cat['id']) : [];
        } else {
            $products = $this->product->published(40, 0);
        }

        $this->view('frontend/shop', [
            'title'      => 'Shop — Mizu Design',
            'products'   => $products,
            'categories' => $categories,
            'keyword'    => $keyword,
            'activeCategory' => $categorySlug,
        ]);
    }

    // GET /product/{slug}
    public function detail(string $slug): void {
        $product = $this->product->findBySlug($slug);
        if (!$product) { http_response_code(404); die('Produk tidak ditemukan.'); }

        $this->view('frontend/product-detail', [
            'title'   => $product['title'] . ' — Mizu Design',
            'product' => $product,
        ]);
    }
}