<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Blog;
use App\helpers\UploadHelper;

class AdminBlogController extends BaseController
{
    private Blog $blog;

    public function __construct()
    {
        // Tidak perlu cek login di sini -- AuthMiddleware::adminOnly()
        // sudah menjaga semua route /admin/* di routes/web.php sebelum
        // controller ini diinisialisasi.
        $this->blog = new Blog();
    }

    // Menampilkan daftar blog di dashboard admin
    public function index(): void
    {
        $posts = $this->blog->getAll();
        $this->view('admin/blog/index', [
            'title' => 'Kelola Blog — Admin',
            'posts' => $posts
        ]);
    }

    // Menampilkan form tambah blog baru
    public function create(): void
    {
        $this->view('admin/blog/create', [
            'title' => 'Tambah Artikel Baru — Admin'
        ]);
    }

    // Memproses penyimpanan blog baru ke database
    public function store(): void
    {
        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';

        // Generate slug otomatis dari judul (Contoh: "Tips Desain" menjadi "tips-desain")
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));

        // Proses upload gambar thumbnail (pakai UploadHelper, sama seperti produk/video,
        // supaya file tersimpan di public/assets/images/blog dan konsisten dengan BASE_URL)
        $thumbnail = 'default-blog.jpg';
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploaded = UploadHelper::uploadImage($_FILES['thumbnail'], 'blog');
            if ($uploaded) {
                $thumbnail = $uploaded;
            }
        }

        $this->blog->create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'thumbnail' => $thumbnail
        ]);

        header('Location: ' . BASE_URL . '/admin/blogs');
        exit;
    }

    // Menampilkan form edit blog
    public function edit(): void
    {
        $id = $_GET['id'] ?? 0;
        $post = $this->blog->find((int) $id);

        if (!$post) {
            die('Artikel tidak ditemukan.');
        }

        $this->view('admin/blog/edit', [
            'title' => 'Edit Artikel — Admin',
            'post' => $post
        ]);
    }

    // Memproses pembaruan blog ke database
    public function update(): void
    {
        $id = $_POST['id'] ?? 0;
        $post = $this->blog->find((int) $id);

        if (!$post) {
            die('Artikel tidak ditemukan.');
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
        $thumbnail = $post['thumbnail']; // default pakai gambar lama

        // Jika upload gambar baru
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $uploaded = UploadHelper::uploadImage($_FILES['thumbnail'], 'blog');
            if ($uploaded) {
                // Hapus file gambar lama jika bukan default
                if ($post['thumbnail'] !== 'default-blog.jpg') {
                    $old_file = ROOT . '/public/assets/images/blog/' . $post['thumbnail'];
                    if (file_exists($old_file)) {
                        unlink($old_file);
                    }
                }
                $thumbnail = $uploaded;
            }
        }

        $this->blog->update((int) $id, [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'thumbnail' => $thumbnail
        ]);

        header('Location: ' . BASE_URL . '/admin/blogs');
        exit;
    }

    // Memproses penghapusan blog
    public function delete(): void
    {
        $id = $_POST['id'] ?? $_GET['id'] ?? 0;
        $post = $this->blog->find((int) $id);

        if ($post) {
            // Hapus file gambar dari server
            if ($post['thumbnail'] !== 'default-blog.jpg') {
                $file = ROOT . '/public/assets/images/blog/' . $post['thumbnail'];
                if (file_exists($file)) {
                    unlink($file);
                }
            }
            $this->blog->delete((int) $id);
        }

        header('Location: ' . BASE_URL . '/admin/blogs');
        exit;
    }
}