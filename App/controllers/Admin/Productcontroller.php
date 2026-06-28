<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Product;
use App\models\Category;
use App\helpers\StringHelper;
use App\helpers\UploadHelper;

class ProductController extends BaseController {
    private Product $product;
    private Category $category;

    public function __construct() {
        $this->product  = new Product();
        $this->category = new Category();
    }

    // GET /admin/products
    public function index(): void {
        $products = $this->product->all();
        $this->view('admin/products/index', ['products' => $products]);
    }

    // GET /admin/products/create
    public function createForm(): void {
        $categories = $this->category->all();
        $this->view('admin/products/create', ['categories' => $categories]);
    }

    // POST /admin/products
    public function store(): void {
        try {
            $title = trim($_POST['title'] ?? '');
            if (!$title) throw new \Exception('Judul produk wajib diisi.');

            $slug = StringHelper::uniqueSlug($title, fn($s) => (bool)$this->product->findBySlug($s));

            $thumbnail = UploadHelper::uploadImage($_FILES['thumbnail'] ?? [], 'products');
            $preview   = UploadHelper::uploadImage($_FILES['preview_image'] ?? [], 'products');
            $filePath  = UploadHelper::uploadDigitalFile($_FILES['file_path'] ?? []);

            $this->product->create([
                'category_id'   => (int)$_POST['category_id'],
                'title'         => $title,
                'slug'          => $slug,
                'description'   => trim($_POST['description'] ?? ''),
                'thumbnail'     => $thumbnail,
                'preview_image' => $preview,
                'file_path'     => $filePath,
                'price'         => (float)$_POST['price'],
                'discount'      => (float)($_POST['discount'] ?? 0),
                'status'        => $_POST['status'] ?? 'draft',
            ]);

            $this->redirect('/admin/products');
        } catch (\Exception $e) {
            $categories = $this->category->all();
            $this->view('admin/products/create', [
                'categories' => $categories,
                'error' => $e->getMessage()
            ]);
        }
    }

    // GET /admin/products/edit?id=1
    public function editForm(): void {
        $id = (int)($_GET['id'] ?? 0);
        $product = $this->product->find($id);
        if (!$product) { http_response_code(404); die('Produk tidak ditemukan.'); }

        $categories = $this->category->all();
        $this->view('admin/products/edit', ['product' => $product, 'categories' => $categories]);
    }

    // POST /admin/products/update
    public function update(): void {
        $id = (int)$_POST['id'];
        $product = $this->product->find($id);
        if (!$product) { http_response_code(404); die('Produk tidak ditemukan.'); }

        try {
            $data = [
                'category_id' => (int)$_POST['category_id'],
                'title'       => trim($_POST['title']),
                'description' => trim($_POST['description'] ?? ''),
                'price'       => (float)$_POST['price'],
                'discount'    => (float)($_POST['discount'] ?? 0),
                'status'      => $_POST['status'] ?? 'draft',
            ];

            if (!empty($_FILES['thumbnail']['name'])) {
                $data['thumbnail'] = UploadHelper::uploadImage($_FILES['thumbnail'], 'products');
            }
            if (!empty($_FILES['preview_image']['name'])) {
                $data['preview_image'] = UploadHelper::uploadImage($_FILES['preview_image'], 'products');
            }
            if (!empty($_FILES['file_path']['name'])) {
                $data['file_path'] = UploadHelper::uploadDigitalFile($_FILES['file_path']);
            }

            $this->product->update($id, $data);
            $this->redirect('/admin/products');
        } catch (\Exception $e) {
            $categories = $this->category->all();
            $this->view('admin/products/edit', [
                'product' => $product,
                'categories' => $categories,
                'error' => $e->getMessage()
            ]);
        }
    }

    // POST /admin/products/delete
    public function destroy(): void {
        $id = (int)$_POST['id'];
        $this->product->delete($id);
        $this->redirect('/admin/products');
    }
}