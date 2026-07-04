<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Category;
use App\helpers\StringHelper;

class CategoryController extends BaseController {
    private Category $category;

    public function __construct() {
        $this->category = new Category();
    }

    public function index(): void {
        $categories = $this->category->all();
        $this->view('admin/categories/index', ['categories' => $categories]);
    }
    public function store(): void {
    $name     = trim($_POST['name'] ?? '');
    $parentId = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;

    if (!$name) { $this->redirect('/admin/categories'); return; }

    $slug = StringHelper::uniqueSlug($name, fn($s) => (bool)$this->category->findBySlug($s));

    $data = [
        'name' => $name,
        'slug' => $slug,
        'icon' => trim($_POST['icon'] ?? 'ti-folder'),
    ];
    if ($parentId !== null) $data['parent_id'] = $parentId;

    $this->category->create($data);
    $this->redirect('/admin/categories');
}

public function update(): void {
    $id       = (int)$_POST['id'];
    $parentId = $_POST['parent_id'] !== '' ? (int)$_POST['parent_id'] : null;

    $data = [
        'name'      => trim($_POST['name']),
        'icon'      => trim($_POST['icon'] ?? 'ti-folder'),
        'parent_id' => $parentId,
    ];

    $this->category->update($id, $data);
    $this->redirect('/admin/categories');
}

public function destroy(): void {
        // Mengambil ID dari database, sesuaikan dengan cara form/URL kamu mengirimkan datanya
        // Jika dikirim via POST form (seperti update)
        $id = (int)($_POST['id'] ?? 0); 

        if ($id > 0) {
            $this->category->delete($id); 
        }

        $this->redirect('/admin/categories');
    }
}