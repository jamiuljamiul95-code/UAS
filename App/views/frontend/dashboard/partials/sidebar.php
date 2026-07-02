<?php
$current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
// Hapus BASE_URL dari current path supaya perbandingan bersih
$basePath = trim(BASE_URL, '/');
if ($basePath && str_starts_with($current, $basePath)) {
    $current = trim(substr($current, strlen($basePath)), '/');
}

// Ambil inisial nama untuk avatar
$initials = strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2));
?>

<div class="dashboard-sidebar">
  <div class="sidebar-user">
    <?php if (!empty($user['photo'])): ?>
      <img src="<?= BASE_URL ?>/assets/images/avatars/<?= htmlspecialchars($user['photo']) ?>"
           class="sidebar-avatar" alt="Foto Profil">
    <?php else: ?>
      <div class="sidebar-avatar"><?= $initials ?></div>
    <?php endif; ?>
    <div class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
    <div class="user-email"><?= htmlspecialchars($user['email'] ?? '') ?></div>
  </div>

  <nav class="sidebar-nav">
    <a href="<?= BASE_URL ?>/dashboard/profile"
       class="<?= str_contains($current, 'dashboard/profile') || $current === 'dashboard' ? 'active' : '' ?>">
      <i class="ti ti-user"></i> Profil Saya
    </a>
    <a href="<?= BASE_URL ?>/dashboard/orders"
       class="<?= str_contains($current, 'dashboard/orders') ? 'active' : '' ?>">
      <i class="ti ti-shopping-bag"></i> Riwayat Pesanan
    </a>
    <a href="<?= BASE_URL ?>/dashboard/downloads"
       class="<?= str_contains($current, 'dashboard/downloads') ? 'active' : '' ?>">
      <i class="ti ti-download"></i> Download Saya
    </a>
    <a href="<?= BASE_URL ?>/wishlist"
       class="<?= str_contains($current, 'wishlist') ? 'active' : '' ?>">
      <i class="ti ti-heart"></i> Wishlist
    </a>
    <a href="<?= BASE_URL ?>/logout" class="logout-link"
       onclick="return confirm('Yakin mau logout?')">
      <i class="ti ti-logout"></i> Logout
    </a>
  </nav>
</div>