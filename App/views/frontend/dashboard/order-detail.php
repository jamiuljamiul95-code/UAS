<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-3">
      <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
    </div>

    <div class="col-md-9">
      <a href="<?= BASE_URL ?>/dashboard/orders" class="text-decoration-none small">&larr; Kembali ke Riwayat Pesanan</a>
      <h4 class="fw-bold mt-2 mb-4">Detail Pesanan — <?= htmlspecialchars($order['invoice']) ?></h4>

      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <div class="row">
            <div class="col-md-4"><strong>Tanggal:</strong> <?= date('d M Y H:i', strtotime($order['created_at'])) ?></div>
            <div class="col-md-4"><strong>Status:</strong> <?= ucfirst($order['status']) ?></div>
            <div class="col-md-4"><strong>Pembayaran:</strong> <?= ucfirst($order['payment_status']) ?></div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm">
        <div class="card-body">
          <h6 class="fw-semibold mb-3">Produk</h6>
          <table class="table align-middle mb-3">
            <thead><tr><th>Produk</th><th class="text-end">Harga</th></tr></thead>
            <tbody>
              <?php foreach ($order['items'] as $item): ?>
                <tr>
                  <td><?= htmlspecialchars($item['product_title']) ?></td>
                  <td class="text-end">Rp<?= number_format($item['price'], 0, ',', '.') ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot>
              <tr>
                <th>Total</th>
                <th class="text-end">Rp<?= number_format($order['total'], 0, ',', '.') ?></th>
              </tr>
            </tfoot>
          </table>

          <?php if ($order['payment_status'] === 'paid'): ?>
            <a href="<?= BASE_URL ?>/dashboard/downloads" class="btn btn-primary">Lihat Download</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>