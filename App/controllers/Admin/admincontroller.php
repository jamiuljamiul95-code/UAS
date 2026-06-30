<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Product;
use App\models\User;
use App\models\Order;

class AdminController extends BaseController {
    public function dashboard(): void {
        $product = new Product();
        $user    = new User();
        $order   = new Order();

        $totalProducts = count($product->all());
        $totalUsers    = count($user->all());

        $this->view('admin/dashboard', [
            'title'         => 'Dashboard Admin',
            'totalProducts' => $totalProducts,
            'totalUsers'    => $totalUsers,
        ]);
    }
}