<?php
$activeMenu = 'dashboard';
require ROOT . '/app/views/admin/partials/admin-header.php';
?>

<div class="admin-topbar">
  <h1>Dashboard</h1>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Total Produk</div>
      <div class="fs-3 fw-bold"><?= $totalProducts ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Total User</div>
      <div class="fs-3 fw-bold"><?= $totalUsers ?></div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="admin-card">
      <div class="text-secondary small mb-1">Selamat datang</div>
      <div class="fw-semibold"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
    </div>
  </div>
</div>

<div class="admin-card">
  <p class="text-secondary small mb-0">Statistik penjualan, grafik, dan laporan akan ditambahkan di Minggu 9 sesuai roadmap.</p>
</div>

<?php require ROOT . '/app/views/admin/partials/admin-footer.php'; ?>