<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row g-4">

            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
            </div>

            <!-- Konten -->
            <div class="col-lg-9">
                <div class="dashboard-content">

                    <div class="dashboard-title">
                        <i class="ti ti-shopping-bag"></i> Riwayat Pesanan
                    </div>

                    <?php if (empty($orders)): ?>
                        <div class="alert-light-custom">
                            Kamu belum punya pesanan. Yuk <a href="<?= BASE_URL ?>/shop">belanja sekarang</a>.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Pembayaran</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($order['invoice']) ?></td>
                                            <td><?= date('d M Y H:i', strtotime($order['created_at'])) ?></td>
                                            <td>Rp<?= number_format($order['total'], 0, ',', '.') ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                                    <?= ucfirst($order['status']) ?>
                                                </span>
                                            </td>
                                            <td><?= ucfirst($order['payment_status']) ?></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>/dashboard/orders/detail?invoice=<?= urlencode($order['invoice']) ?>"
                                                    class="btn btn-sm btn-outline-primary">Detail</a>
                                                <form action="<?= BASE_URL ?>/dashboard/orders/hide" method="POST"
                                                    style="display:inline"
                                                    onsubmit="return confirm('Hapus pesanan ini dari riwayat kamu?')">
                                                    <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i
                                                            class="ti ti-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>