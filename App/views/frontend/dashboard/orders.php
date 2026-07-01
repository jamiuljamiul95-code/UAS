<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-3">
      <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
    </div>

    <div class="col-md-9">
      <h4 class="fw-bold mb-4">Riwayat Pesanan</h4>

      <?php if (empty($orders)): ?>
        <div class="alert alert-light border">Kamu belum punya pesanan. Yuk <a href="<?= BASE_URL ?>/shop">belanja sekarang</a>.</div>
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
                    <span class="badge bg-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                      <?= ucfirst($order['status']) ?>
                    </span>
                  </td>
                  <td><?= ucfirst($order['payment_status']) ?></td>
                  <td>
                    <a href="<?= BASE_URL ?>/dashboard/orders/detail?invoice=<?= urlencode($order['invoice']) ?>" class="btn btn-sm btn-outline-primary">Detail</a>
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

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>