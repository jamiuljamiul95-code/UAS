<?php

namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Product;
use App\models\Category;
use App\helpers\StringHelper;
use App\helpers\UploadHelper;

class ProductController extends BaseController
{
    private Product $product;
    private Category $category;

    public function __construct()
    {
        $this->product = new Product();
        $this->category = new Category();
    }

    /**
     * GET /admin/products
     */
    public function index(): void
    {
        $products = $this->product->all();

        $this->view('admin/products/index', [
            'products' => $products
        ]);
    }

    /**
     * GET /admin/products/create
     */
    public function createForm(): void
    {
        $categoriesGrouped = $this->category->allGrouped();

        $this->view('admin/products/create', [
            'categoriesGrouped' => $categoriesGrouped
        ]);
    }

    /**
     * POST /admin/products
     */
    public function store(): void
    {
        try {

            $title = trim($_POST['title'] ?? '');

            if (empty($title)) {
                throw new \Exception('Judul produk wajib diisi.');
            }

            $slug = StringHelper::uniqueSlug(
                $title,
                fn($slug) => (bool) $this->product->findBySlug($slug)
            );

            // Upload thumbnail
            $thumbnail = null;
            if (!empty($_FILES['thumbnail']['name'])) {
                $thumbnail = UploadHelper::uploadImage(
                    $_FILES['thumbnail'],
                    'products'
                );
            }

            // Upload preview
            $previewImage = null;
            if (!empty($_FILES['preview_image']['name'])) {
                $previewImage = UploadHelper::uploadImage(
                    $_FILES['preview_image'],
                    'products'
                );
            }

            // Upload file digital
            $filePath = null;
            if (!empty($_FILES['file_path']['name'])) {
                $filePath = UploadHelper::uploadDigitalFile(
                    $_FILES['file_path']
                );
            }

            $productId = $this->product->create([
                'category_id' => (int) ($_POST['category_id'] ?? 0),
                'title' => $title,
                'slug' => $slug,
                'description' => trim($_POST['description'] ?? ''),
                'thumbnail' => $thumbnail,
                'preview_image' => $previewImage,
                'file_path' => $filePath,
                'price' => (float) ($_POST['price'] ?? 0),
                'discount' => (float) ($_POST['discount'] ?? 0),
                'status' => $_POST['status'] ?? 'draft',
            ]);

            // Upload media tambahan
            $this->uploadAdditionalMedia($productId);

            $this->redirect('/admin/products');

        } catch (\Exception $e) {

            $categoriesGrouped = $this->category->allGrouped();

            $this->view('admin/products/create', [
                'categoriesGrouped' => $categoriesGrouped,
                'error' => $e->getMessage()
            ]);

            return;
        }
    }

    /**
     * GET /admin/products/edit?id=1
     */
    public function editForm(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        $product = $this->product->find($id);

        if (!$product) {
            http_response_code(404);
            die('Produk tidak ditemukan.');
        }

        $categoriesGrouped = $this->category->allGrouped();

        // Ambil semua media (foto & video) milik produk ini
        $media = $this->product->getMedia($id);

        $this->view('admin/products/edit', [
            'product' => $product,
            'categoriesGrouped' => $categoriesGrouped,
            'media' => $media
        ]);
    }

    /**
     * POST /admin/products/update
     */
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        $product = $this->product->find($id);

        if (!$product) {
            http_response_code(404);
            die('Produk tidak ditemukan.');
        }

        try {

            $data = [
                'category_id' => (int) $_POST['category_id'],
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float) $_POST['price'],
                'discount' => (float) ($_POST['discount'] ?? 0),
                'status' => $_POST['status'] ?? 'draft',
            ];

            // Thumbnail
            if (!empty($_FILES['thumbnail']['name'])) {
                $data['thumbnail'] = UploadHelper::uploadImage(
                    $_FILES['thumbnail'],
                    'products'
                );
            }

            // Preview Image
            if (!empty($_FILES['preview_image']['name'])) {
                $data['preview_image'] = UploadHelper::uploadImage(
                    $_FILES['preview_image'],
                    'products'
                );
            }

            // File Digital
            if (!empty($_FILES['file_path']['name'])) {
                $data['file_path'] = UploadHelper::uploadDigitalFile(
                    $_FILES['file_path']
                );
            }

            // Update Produk
            $this->product->update($id, $data);

            // Upload media tambahan
            $this->uploadAdditionalMedia($id);

            // ======================================
            // NOTIFIKASI WISHLIST SAAT DISKON
            // ======================================

            if (
                $data['status'] === 'published' &&
                $data['discount'] > 0 &&
                $product['discount'] != $data['discount']
            ) {

                $db = \Database::getInstance()->getConnection();

                $stmt = $db->prepare("
                    SELECT DISTINCT user_id
                    FROM wishlists
                    WHERE product_id = ?
                ");

                $stmt->execute([$id]);

                $wishers = $stmt->fetchAll();

                if (!empty($wishers)) {

                    $produk = $this->product->find($id);

                    $notification = new \App\models\Notification();

                    foreach ($wishers as $user) {

                        $notification->push(

                            $user['user_id'],

                            'wishlist',

                            '🔥 Produk Wishlist Sedang Diskon',

                            "{$produk['title']} sekarang diskon {$data['discount']}%! Jangan sampai kehabisan.",

                            '/product/' . $produk['slug']

                        );

                    }

                }

            }

            $this->redirect('/admin/products');

        } catch (\Exception $e) {

            $categoriesGrouped = $this->category->allGrouped();

            // Ambil media lagi supaya galeri tetap tampil saat form gagal disubmit
            $media = $this->product->getMedia($id);

            $this->view('admin/products/edit', [

                'product' => $product,

                'categoriesGrouped' => $categoriesGrouped,

                'media' => $media,

                'error' => $e->getMessage()

            ]);

            return;
        }
    }

    /**
     * POST /admin/products/delete
     */
    public function destroy(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id > 0) {
            $this->product->delete($id);
        }

        $this->redirect('/admin/products');
    }

    /**
     * Hapus media produk
     */
    public function deleteMedia(): void
    {
        $mediaId = (int) ($_POST['media_id'] ?? 0);
        $productId = (int) ($_POST['product_id'] ?? 0);

        if ($mediaId > 0) {
            $this->product->deleteMedia($mediaId);
        }

        $this->redirect('/admin/products/edit?id=' . $productId);
    }

    /**
     * Upload Multiple Image & Video
     */
    private function uploadAdditionalMedia(int $productId): void
    {
        // =========================
        // Upload Multiple Images
        // =========================

        if (!empty($_FILES['media_images']['name'][0])) {

            foreach ($_FILES['media_images']['tmp_name'] as $key => $tmp) {

                if ($_FILES['media_images']['error'][$key] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $file = [
                    'name' => $_FILES['media_images']['name'][$key],
                    'tmp_name' => $tmp,
                    'error' => $_FILES['media_images']['error'][$key],
                    'size' => $_FILES['media_images']['size'][$key],
                ];

                $filename = UploadHelper::uploadImage($file, 'products');

                if ($filename) {
                    $this->product->addMedia(
                        $productId,
                        'image',
                        $filename
                    );
                }
            }
        }

        // =========================
        // Upload Multiple Videos
        // =========================

        if (!empty($_FILES['media_videos']['name'][0])) {

            foreach ($_FILES['media_videos']['tmp_name'] as $key => $tmp) {

                if ($_FILES['media_videos']['error'][$key] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $file = [
                    'name' => $_FILES['media_videos']['name'][$key],
                    'tmp_name' => $tmp,
                    'error' => $_FILES['media_videos']['error'][$key],
                    'size' => $_FILES['media_videos']['size'][$key],
                ];

                $filename = UploadHelper::uploadVideo($file);

                if ($filename) {
                    $this->product->addMedia(
                        $productId,
                        'video',
                        $filename
                    );
                }
            }
        }
    }

    /**
     * Kirim Notifikasi Diskon Wishlist
     */
    private function sendWishlistDiscountNotification(
        int $productId,
        array $product,
        float $discount
    ): void {

        if ($discount <= 0) {
            return;
        }

        $db = \Database::getInstance()->getConnection();

        $stmt = $db->prepare("
            SELECT DISTINCT user_id
            FROM wishlists
            WHERE product_id = ?
        ");

        $stmt->execute([$productId]);

        $users = $stmt->fetchAll();

        if (empty($users)) {
            return;
        }

        $notification = new \App\models\Notification();

        foreach ($users as $user) {

            $notification->push(

                $user['user_id'],

                'wishlist',

                '🔥 Produk Wishlist Sedang Diskon',

                $product['title'] .
                ' sekarang diskon ' .
                (int) $discount .
                '%! Jangan sampai kehabisan.',

                '/product/' . $product['slug']

            );

        }
    }
}