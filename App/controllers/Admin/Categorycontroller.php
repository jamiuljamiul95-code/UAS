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
        $name = trim($_POST['name'] ?? '');
        if (!$name) { $this->redirect('/admin/categories'); return; }

        $slug = StringHelper::uniqueSlug($name, fn($s) => (bool)$this->category->findBySlug($s));

        $this->category->create([
            'name' => $name,
            'slug' => $slug,
            'icon' => trim($_POST['icon'] ?? 'ti-folder'),
        ]);

        $this->redirect('/admin/categories');
    }

    public function update(): void {
        $id = (int)$_POST['id'];
        $this->category->update($id, [
            'name' => trim($_POST['name']),
            'icon' => trim($_POST['icon'] ?? 'ti-folder'),
        ]);
        $this->redirect('/admin/categories');
    }

    public function destroy(): void {
        $id = (int)$_POST['id'];
        $this->category->delete($id);
        $this->redirect('/admin/categories');
    }
}