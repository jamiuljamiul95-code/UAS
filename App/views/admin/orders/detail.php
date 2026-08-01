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
      <p class="small mb-1"><span class="text-secondary">Invoice:</span>
        <?= htmlspecialchars($order['invoice']) ?></p>
      <p class="small mb-1"><span class="text-secondary">Total:</span> <strong>Rp
          <?= number_format($order['total'], 0, ',', '.') ?></strong></p>
      <p class="small mb-1"><span class="text-secondary">Tanggal:</span>
        <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
      <p class="small mb-0"><span class="text-secondary">Status saat ini:</span>
        <strong><?= htmlspecialchars($order['status']) ?></strong>
      </p>
    </div>

    <div class="admin-card">
      <h6 class="fw-semibold mb-3"><i class="ti ti-receipt-2"></i> Bukti Pembayaran</h6>

      <?php if (!empty($order['midtrans_transaction_id'])): ?>
        <!-- Pembayaran otomatis via Midtrans -->
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="badge bg-success">Terverifikasi Otomatis via Midtrans</span>
        </div>
        <table class="table table-sm">
          <tr>
            <td class="text-secondary" style="width:180px">Transaction ID</td>
            <td><code><?= htmlspecialchars($order['midtrans_transaction_id']) ?></code></td>
          </tr>
          <tr>
            <td class="text-secondary">Metode Pembayaran</td>
            <td><?= strtoupper(htmlspecialchars($order['midtrans_payment_type'] ?? '-')) ?></td>
          </tr>
          <tr>
            <td class="text-secondary">Waktu Dibayar</td>
            <td><?= $order['midtrans_paid_at'] ? date('d M Y, H:i', strtotime($order['midtrans_paid_at'])) : '-' ?>
              WIB</td>
          </tr>
        </table>
        <p class="small text-secondary mb-0">
          Untuk detail transaksi lengkap (mutasi, status settlement), cek di
          <a href="https://dashboard.sandbox.midtrans.com/transactions" target="_blank">Dashboard Midtrans →
            Transaksi</a>,
          cari pakai Transaction ID di atas.
        </p>

      <?php elseif (!empty($order['payment_proof'])): ?>
        <!-- Upload manual (kode yang sudah ada, tidak berubah) -->
        <?php $proofExt = strtolower(pathinfo($order['payment_proof'], PATHINFO_EXTENSION)); ?>
        <?php if ($proofExt === 'pdf'): ?>
          <a href="<?= BASE_URL ?>/uploads/payment_proof/<?= htmlspecialchars($order['payment_proof']) ?>" target="_blank"
            class="btn btn-outline-primary">
            <i class="ti ti-file-type-pdf"></i> Lihat Bukti Pembayaran (PDF)
          </a>
        <?php else: ?>
          <a href="<?= BASE_URL ?>/uploads/payment_proof/<?= htmlspecialchars($order['payment_proof']) ?>" target="_blank">
            <img src="<?= BASE_URL ?>/uploads/payment_proof/<?= htmlspecialchars($order['payment_proof']) ?>"
              style="max-width:100%;max-height:400px;border-radius:10px;border:1px solid #e5e7eb">
          </a>
          <p class="small text-secondary mt-2 mb-0">Klik gambar untuk lihat ukuran penuh.</p>
        <?php endif; ?>

      <?php else: ?>
        <p class="small text-secondary mb-0">Belum ada bukti pembayaran untuk order ini.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>