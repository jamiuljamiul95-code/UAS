<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\User;

class UserController extends BaseController {
    private User $user;

    public function __construct() {
        $this->user = new User();
    }

    // GET /admin/users
    public function index(): void {
        $users = $this->user->all();

        $this->view('admin/users/index', [
            'title' => 'Kelola User',
            'users' => $users,
        ]);
    }

    // POST /admin/users/toggle-status
    public function toggleStatus(): void {
        $id = (int)$_POST['id'];
        $user = $this->user->find($id);

        if ($user) {
            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            $this->user->update($id, ['status' => $newStatus]);
        }

        $this->redirect('/admin/users');
    }

    // POST /admin/users/delete
    public function destroy(): void {
        $id = (int)$_POST['id'];

        // Cegah admin menghapus akunnya sendiri yang sedang login
        if ($id === (int)$_SESSION['user_id']) {
            $this->redirect('/admin/users');
            return;
        }

        $this->user->delete($id);
        $this->redirect('/admin/users');
    }
}