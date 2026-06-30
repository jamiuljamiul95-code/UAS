<?php
$activeMenu = 'orders';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Kelola Order</h1>
</div>

<div class="admin-card mb-3" style="padding:14px 20px">
  <div class="d-flex gap-2 flex-wrap">
    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm <?= $activeStatus === '' ? 'btn-admin-primary' : 'btn-outline-secondary' ?> rounded-pill">Semua</a>
    <a href="<?= BASE_URL ?>/admin/orders?status=pending" class="btn btn-sm <?= $activeStatus === 'pending' ? 'btn-admin-primary' : 'btn-outline-secondary' ?> rounded-pill">Menunggu</a>
    <a href="<?= BASE_URL ?>/admin/orders?status=paid" class="btn btn-sm <?= $activeStatus === 'paid' ? 'btn-admin-primary' : 'btn-outline-secondary' ?> rounded-pill">Dibayar</a>
    <a href="<?= BASE_URL ?>/admin/orders?status=failed" class="btn btn-sm <?= $activeStatus === 'failed' ? 'btn-admin-primary' : 'btn-outline-secondary' ?> rounded-pill">Gagal</a>
    <a href="<?= BASE_URL ?>/admin/orders?status=refund" class="btn btn-sm <?= $activeStatus === 'refund' ? 'btn-admin-primary' : 'btn-outline-secondary' ?> rounded-pill">Refund</a>
  </div>
</div>

<div class="admin-card">
  <table class="admin-table">
    <thead>
      <tr>
        <th>Invoice</th>
        <th>Pembeli</th>
        <th>Total</th>
        <th>Status</th>
        <th>Tanggal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($orders)): ?>
        <?php foreach ($orders as $o): ?>
          <tr>
            <td class="fw-medium"><?= htmlspecialchars($o['invoice']) ?></td>
            <td>
              <div><?= htmlspecialchars($o['customer_name']) ?></div>
              <div class="text-secondary" style="font-size:11.5px"><?= htmlspecialchars($o['customer_email']) ?></div>
            </td>
            <td>Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
            <td>
              <?php
                $statusMap = [
                  'paid'    => ['label' => 'Dibayar',  'class' => 'status-published'],
                  'pending' => ['label' => 'Menunggu', 'class' => 'status-draft'],
                  'failed'  => ['label' => 'Gagal',    'class' => 'status-draft'],
                  'refund'  => ['label' => 'Refund',   'class' => 'status-draft'],
                ];
                $s = $statusMap[$o['status']] ?? ['label' => $o['status'], 'class' => 'status-draft'];
              ?>
              <span class="status-badge <?= $s['class'] ?>"><?= $s['label'] ?></span>
            </td>
            <td class="text-secondary"><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
            <td class="table-action">
              <a href="<?= BASE_URL ?>/admin/orders/detail?id=<?= $o['id'] ?>" class="btn-action-edit">Detail</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr class="empty-row"><td colspan="6">Belum ada order masuk.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>