<?php
namespace App\models;

class Order extends BaseModel
{
    protected $table = 'orders';

    public function createOrder(string $userId, string $invoice, float $total): int
    {
        return $this->create([
            'user_id' => $userId,
            'invoice' => $invoice,
            'total' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);
    }

    public function addItem(int $orderId, int $productId, float $price): void
    {
        $stmt = $this->db->prepare("INSERT INTO order_items (order_id, product_id, price) VALUES (?, ?, ?)");
        $stmt->execute([$orderId, $productId, $price]);
    }

    public function findByInvoice(string $invoice): ?array
    {
        return $this->findBy('invoice', $invoice);
    }

    public function generateInvoice(): string
    {
        return 'INV-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
    }

    public function updateStatus(int $id, string $status, string $paymentStatus): void
    {
        $this->update($id, ['status' => $status, 'payment_status' => $paymentStatus]);
    }

    public function getItems(int $orderId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua order untuk ADMIN, lengkap dengan nama & email pembeli.
     * Bisa difilter by status (pending/paid/failed/refund).
     */
    public function all(?string $status = null): array
    {
        if ($status) {
            $stmt = $this->db->prepare("
                SELECT o.*, u.name AS customer_name, u.email AS customer_email
                FROM orders o
                JOIN users u ON u.id = o.user_id
                WHERE o.status = ?
                ORDER BY o.created_at DESC
            ");
            $stmt->execute([$status]);
        } else {
            $stmt = $this->db->query("
                SELECT o.*, u.name AS customer_name, u.email AS customer_email
                FROM orders o
                JOIN users u ON u.id = o.user_id
                ORDER BY o.created_at DESC
            ");
        }
        return $stmt->fetchAll();
    }

    /**
     * Ambil semua order milik 1 user (untuk halaman riwayat pesanan CUSTOMER).
     */
    public function byUser(string $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM orders WHERE user_id = ? AND is_hidden = 0 ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findWithItems(int $id): ?array
    {
        $order = $this->find($id);
        if (!$order)
            return null;

        $stmt = $this->db->prepare("
            SELECT oi.*, p.title AS product_title
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            WHERE oi.order_id = ?
        ");
        $stmt->execute([$id]);
        $order['items'] = $stmt->fetchAll();

        return $order;
    }

    public function hideFromUser(int $id, int $userId): bool
    {
        $stmt = $this->db->prepare("
        UPDATE orders SET is_hidden = 1 
        WHERE id = ? AND user_id = ?
    ");
        return $stmt->execute([$id, $userId]);
    }
    /**
     * Total pendapatan dari semua order yang sudah paid.
     */
    public function totalRevenue(): float
    {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total), 0) AS revenue FROM orders WHERE status = 'paid'");
        return (float) $stmt->fetch()['revenue'];
    }

    /**
     * Data penjualan harian untuk grafik (N hari terakhir).
     */
    public function salesByDay(int $days = 30): array
    {
        $stmt = $this->db->prepare("
        SELECT DATE(created_at) AS date,
               COALESCE(SUM(total), 0) AS revenue,
               COUNT(*) AS order_count
        FROM orders
        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
        GROUP BY DATE(created_at)
        ORDER BY date ASC
    ");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    /**
     * Produk paling laris berdasarkan jumlah terjual (dari order yang paid).
     */
    public function topProducts(int $limit = 5): array
    {
        $stmt = $this->db->prepare("
        SELECT p.title, p.thumbnail, COUNT(*) AS sold_count, SUM(oi.price) AS revenue
        FROM order_items oi
        JOIN products p ON p.id = oi.product_id
        JOIN orders o ON o.id = oi.order_id
        WHERE o.status = 'paid'
        GROUP BY oi.product_id
        ORDER BY sold_count DESC
        LIMIT ?
    ");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }



    /**
     * Ambil semua order paid beserta bukti pembayaran, dalam rentang tanggal tertentu.
     * Dipakai untuk laporan PDF/Excel.
     */
    public function paidOrdersBetween(string $from, string $to): array
    {
        $stmt = $this->db->prepare("
        SELECT o.*, u.name AS customer_name, u.email AS customer_email
        FROM orders o
        JOIN users u ON u.id = o.user_id
        WHERE o.status = 'paid' AND DATE(o.created_at) BETWEEN ? AND ?
        ORDER BY o.created_at DESC
    ");
        $stmt->execute([$from, $to]);
        return $stmt->fetchAll();
    }


    /**
     * Data penjualan bulanan untuk grafik (N bulan terakhir).
     */
    public function salesByMonth(int $months = 12): array
    {
        $stmt = $this->db->prepare("
        SELECT DATE_FORMAT(created_at, '%Y-%m') AS period,
               COALESCE(SUM(total), 0) AS revenue,
               COUNT(*) AS order_count
        FROM orders
        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY period ASC
    ");
        $stmt->execute([$months]);
        return $stmt->fetchAll();
    }

    /**
     * Data penjualan tahunan untuk grafik (N tahun terakhir).
     */
    public function salesByYear(int $years = 5): array
    {
        $stmt = $this->db->prepare("
        SELECT YEAR(created_at) AS period,
               COALESCE(SUM(total), 0) AS revenue,
               COUNT(*) AS order_count
        FROM orders
        WHERE status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL ? YEAR)
        GROUP BY YEAR(created_at)
        ORDER BY period ASC
    ");
        $stmt->execute([$years]);
        return $stmt->fetchAll();
    }
}