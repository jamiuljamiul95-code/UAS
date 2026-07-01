<?php $current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'); ?>
<div class="list-group shadow-sm">
  <a href="<?= BASE_URL ?>/dashboard/profile"
     class="list-group-item list-group-item-action <?= str_contains($current, 'dashboard/profile') ? 'active' : '' ?>">
    👤 Profil
  </a>
  <a href="<?= BASE_URL ?>/dashboard/orders"
     class="list-group-item list-group-item-action <?= str_contains($current, 'dashboard/orders') ? 'active' : '' ?>">
    🧾 Riwayat Pesanan
  </a>
  <a href="<?= BASE_URL ?>/dashboard/downloads"
     class="list-group-item list-group-item-action <?= str_contains($current, 'dashboard/downloads') ? 'active' : '' ?>">
    ⬇️ Download Saya
  </a>
  <a href="<?= BASE_URL ?>/logout"
     class="list-group-item list-group-item-action text-danger mt-2"
     onclick="return confirm('Yakin mau logout?')">
    🚪 Logout
  </a>
</div>