<?php
$current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
// Hapus BASE_URL dari current path supaya perbandingan bersih
$basePath = trim(BASE_URL, '/');
if ($basePath && str_starts_with($current, $basePath)) {
    $current = trim(substr($current, strlen($basePath)), '/');
}

function isDashboardSidebarActive(string $path, string $current): bool
{
    if ($current === $path) {
        return true;
    }

    if ($path === 'dashboard') {
        return false;
    }

    return str_starts_with($current, $path . '/');
}

// Kalau halaman ini tidak mengirim $user (misal halaman Orders/Downloads/Wishlist),
// ambil sendiri datanya dari session supaya foto & email tetap konsisten di semua halaman dashboard.
if (!isset($user) && isset($_SESSION['user_id'])) {
    $sidebarUserModel = new \App\models\User();
    $user = $sidebarUserModel->find($_SESSION['user_id']);
}

// Ambil inisial nama untuk avatar secara dinamis dari session terkini
$initials = strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2));
?>

<div class="dashboard-sidebar">
    <div class="sidebar-user">
        <!-- Menggunakan $_SESSION['user_photo'] agar gambar langsung berubah instan tanpa delay -->
        <?php if (!empty($_SESSION['user_photo'])): ?>
            <img src="<?= BASE_URL ?>/assets/images/avatars/<?= htmlspecialchars($_SESSION['user_photo']) ?>"
                class="sidebar-avatar" alt="Foto Profil" style="object-fit: cover;">
        <?php else: ?>
            <div class="sidebar-avatar"><?= $initials ?></div>
        <?php endif; ?>
        <div class="user-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></div>
        <div class="user-email"><?= htmlspecialchars($_SESSION['user_email'] ?? ($user['email'] ?? '')) ?></div>
    </div>

    <nav class="sidebar-nav">
        <a href="<?= BASE_URL ?>/dashboard"
            class="<?= isDashboardSidebarActive('dashboard', $current) ? 'active' : '' ?>">
            <i class="ti ti-layout-dashboard"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>/dashboard/profile"
            class="<?= isDashboardSidebarActive('dashboard/profile', $current) ? 'active' : '' ?>">
            <i class="ti ti-user"></i> Profil Saya
        </a>
        <a href="<?= BASE_URL ?>/dashboard/orders"
            class="<?= isDashboardSidebarActive('dashboard/orders', $current) ? 'active' : '' ?>">
            <i class="ti ti-shopping-bag"></i> Riwayat Pesanan
        </a>
        <a href="<?= BASE_URL ?>/dashboard/downloads"
            class="<?= isDashboardSidebarActive('dashboard/downloads', $current) ? 'active' : '' ?>">
            <i class="ti ti-download"></i> Download Saya
        </a>
        <a href="<?= BASE_URL ?>/logout" class="logout-link" onclick="return confirm('Yakin mau logout?')">
            <i class="ti ti-logout"></i> Logout
        </a>
    </nav>
    <nav class="sidebar-nav sidebar-nav-secondary">
        <a href="<?= BASE_URL ?>/faq" class="<?= isDashboardSidebarActive('faq', $current) ? 'active' : '' ?>">
            <i class="ti ti-help-circle"></i> Bantuan & FAQ
        </a>
    </nav>
</div>