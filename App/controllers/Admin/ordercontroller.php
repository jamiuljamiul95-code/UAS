<?php
namespace App\controllers\Admin;

use App\controllers\BaseController;
use App\models\Order;

class OrderController extends BaseController {
    private Order $order;

    public function __construct() {
        $this->order = new Order();
    }

    // GET /admin/orders
    public function index(): void {
        $status = $_GET['status'] ?? null;
        $orders = $this->order->all($status ?: null);

        $this->view('admin/orders/index', [
            'title'        => 'Kelola Order',
            'orders'       => $orders,
            'activeStatus' => $status ?? '',
        ]);
    }

    // GET /admin/orders/detail?id=1
    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        $order = $this->order->findWithItems($id);

        if (!$order) {
            http_response_code(404);
            die('Order tidak ditemukan.');
        }

        $this->view('admin/orders/detail', [
            'title' => 'Detail Order — ' . $order['invoice'],
            'order' => $order,
        ]);
    }

    // POST /admin/orders/update-status
    public function updateStatus(): void {
        $id = (int)$_POST['id'];
        $status = $_POST['status'];

        $paymentStatus = in_array($status, ['paid']) ? 'paid' : 'unpaid';
        $this->order->updateStatus($id, $status, $paymentStatus);

        $this->redirect('/admin/orders/detail?id=' . $id);
    }
}