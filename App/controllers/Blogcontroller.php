<?php
namespace App\controllers;

use App\models\Blog;

class BlogController extends BaseController
{
    private Blog $blog;

    public function __construct()
    {
        $this->blog = new Blog();
    }

    // GET /blog
    public function index(): void
    {
        $posts = $this->blog->getAll();

        $this->view('frontend/blog', [
            'title' => 'Blog — Mizu Design',
            'posts' => $posts
        ]);
    }

    // GET /blog/detail
    public function detail(): void
    {
        $slug = $_GET['slug'] ?? '';
        $post = $this->blog->findBySlug($slug);

        if (!$post) {
            http_response_code(404);
            die('Artikel tidak ditemukan.');
        }

        // Ambil beberapa artikel lain (selain yang sedang dibuka) untuk ditampilkan di bagian "Artikel Lainnya"
        $related = array_slice(array_filter(
            $this->blog->getAll(),
            fn($p) => $p['slug'] !== $post['slug']
        ), 0, 3);

        $this->view('frontend/blog-detail', [
            'title' => $post['title'] . ' — Mizu Design',
            'post' => $post,
            'related' => $related
        ]);
    }
}