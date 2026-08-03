<?php
namespace App\controllers;

use App\models\Product;
use App\models\Category;

class ProductController extends BaseController
{
    private Product $product;
    private Category $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    // GET /shop
    public function shop(): void
    {
        $keyword = trim($_GET['q'] ?? '');
        $categorySlug = trim($_GET['category'] ?? '');
        $categories = $this->category->allGrouped();

        if ($keyword !== '') {
            $products = $this->product->search($keyword);
        } elseif ($categorySlug !== '') {
            $cat = $this->category->findBySlug($categorySlug);

            if ($cat) {
                if ($cat['parent_id'] === null) {
                    $products = $this->product->byParentCategory($cat['id']);
                } else {
                    $products = $this->product->byCategory($cat['id']);
                }
            } else {
                $products = [];
            }
        } else {
            $products = $this->product->published(40, 0);
        }

        $this->view('frontend/shop', [
            'title' => 'Shop — Mizu Design',
            'products' => $products,
            'categories' => $categories,
            'keyword' => $keyword,
            'activeCategory' => $categorySlug,
        ]);
    }

    // GET /product/{slug}
    public function detail(string $slug): void
    {
        $product = $this->product->findBySlug($slug);
        if (!$product) {
            http_response_code(404);
            die('Produk tidak ditemukan.');
        }

        $media = $this->product->getMedia($product['id']);
        $ratingSummary = $this->product->getRatingSummary($product['id']);
        $reviews = $this->product->getReviews($product['id']);

        $this->view('frontend/product-detail', [
            'title' => $product['title'] . ' — Mizu Design',
            'product' => $product,
            'media' => $media,
            'ratingSummary' => $ratingSummary,
            'reviews' => $reviews,
        ]);
    }

    public function promo()
    {
        $productModel = new \App\models\Product();

        // Ambil semua produk yang sedang promo
        $promoProducts = $productModel->getPromoProducts();

        // Kirim data ke view
        return $this->view('frontend/promo', [
            'title' => 'Promo Spesial - Mizu Design',
            'products' => $promoProducts
        ]);
    }
}