<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5 text-center">
  <i class="ti ti-clock-hour-4" style="font-size:56px;color:#7C3AED"></i>
  <h2 class="mt-3">Order Diterima</h2>
  <p class="text-secondary">Invoice: <strong><?= htmlspecialchars($order['invoice']) ?></strong></p>
  <p class="text-secondary">Total: <strong>Rp <?= number_format($order['total'],0,',','.') ?></strong></p>
  <p class="text-secondary small">Integrasi pembayaran Midtrans akan aktif di tahap berikutnya.</p>
  <a href="/" class="btn btn-outline-primary rounded-pill px-4 mt-3">Kembali ke Home</a>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>