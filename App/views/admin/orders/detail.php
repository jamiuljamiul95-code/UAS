<?php
$activeMenu = 'orders';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Detail Order — <?= htmlspecialchars($order['invoice']) ?></h1>
  <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-outline-secondary">← Kembali</a>
</div>

<div class="row g-3">
  <div class="col-md-8">
    <div class="admin-card mb-3">
      <h6 class="fw-semibold mb-3">Produk Dibeli</h6>
      <table class="admin-table">
        <thead>
          <tr>
            <th>Produk</th>
            <th>Harga</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($order['items'] as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['product_title']) ?></td>
              <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-4">
    <div class="admin-card mb-3">
      <h6 class="fw-semibold mb-3">Informasi Order</h6>
      <p class="small mb-1"><span class="text-secondary">Invoice:</span> <?= htmlspecialchars($order['invoice']) ?></p>
      <p class="small mb-1"><span class="text-secondary">Total:</span> <strong>Rp <?= number_format($order['total'], 0, ',', '.') ?></strong></p>
      <p class="small mb-1"><span class="text-secondary">Tanggal:</span> <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
      <p class="small mb-0"><span class="text-secondary">Status saat ini:</span> <strong><?= htmlspecialchars($order['status']) ?></strong></p>
    </div>

    <div class="admin-card">
      <h6 class="fw-semibold mb-3">Ubah Status Manual</h6>
      <p class="small text-secondary mb-3">Biasanya status berubah otomatis lewat webhook Midtrans. Gunakan ini hanya untuk koreksi manual.</p>
      <form action="<?= BASE_URL ?>/admin/orders/update-status" method="POST" class="admin-form">
        <input type="hidden" name="id" value="<?= $order['id'] ?>">
        <select name="status" class="form-select mb-3">
          <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Menunggu</option>
          <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Dibayar</option>
          <option value="failed" <?= $order['status'] === 'failed' ? 'selected' : '' ?>>Gagal</option>
          <option value="refund" <?= $order['status'] === 'refund' ? 'selected' : '' ?>>Refund</option>
        </select>
        <button type="submit" class="btn-admin-primary w-100">Update Status</button>
      </form>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>