<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mizu Design' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/navbar.css">
</head>

<body>

    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <!-- Banner kecil khusus kalau admin sedang berada di halaman customer (bukan area /admin) -->
        <div class="admin-notice-bar">
            <span>Kamu login sebagai <strong><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></strong> (Admin)</span>
            <a href="<?= BASE_URL ?>/admin/dashboard">Buka Admin Panel →</a>
        </div>
    <?php endif; ?>

    <nav class="navbar navbar-expand-lg navbar-glass sticky-top">
        <div class="container">
            <a class="navbar-brand brand-logo" href="<?= BASE_URL ?>/">
                <img src="<?= BASE_URL ?>/assets/images/logo/logo-mizu.svg" alt="" class="navbar-logo">
            </a>
            <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>/">
                <span style="color:#2563EB">Mizu</span><span style="color:#7C3AED">Design</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain">

                <!-- 1. KELOMPOK MENU UTAMA (Otomatis ke Tengah berkat mx-auto) -->
                <ul class="navbar-nav mx-auto align-items-lg-center gap-3">
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/shop">Shop</a></li>
                    <!-- Kamu tinggal tambah <li> baru di bawah sini jika ada menu mendatang, otomatis ikut di tengah -->
                </ul>

                <!-- 2. KELOMPOK FITUR & PROFIL (Tetap di Kanan berkat ms-auto) -->
                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    <li class="nav-item">
                        <form action="<?= BASE_URL ?>/shop" method="GET" class="navbar-search-form d-flex">
                            <input type="text" name="q" class="form-control form-control-sm rounded-pill"
                                placeholder="Cari produk..." style="width:200px">
                        </form>
                    </li>

                    <?php if (isset($_SESSION['user_id'], $_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item"><a class="btn btn-sm btn-nav-user rounded-pill"
                                href="<?= BASE_URL ?>/admin/dashboard">
                                <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?> (Admin)
                            </a></li>

                    <?php elseif (isset($_SESSION['user_id'])): ?>

                        <!-- Cart -->
                        <li class="nav-item">
                            <a class="nav-icon-link position-relative" href="<?= BASE_URL ?>/cart">
                                <i class="ti ti-shopping-cart"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="cart-badge"><?= $cartCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!-- Wishlist -->
                        <li class="nav-item">
                            <a class="nav-icon-link position-relative" href="<?= BASE_URL ?>/wishlist">
                                <i class="ti ti-heart"></i>
                                <?php if ($wishlistCount > 0): ?>
                                    <span class="cart-badge"><?= $wishlistCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!-- Notification -->
                        <li class="nav-item dropdown">
                            <a class="nav-icon-link position-relative" href="#" data-bs-toggle="dropdown">
                                <i class="ti ti-bell"></i>
                                <?php if ($unreadCount > 0): ?>
                                    <span class="cart-badge"><?= $unreadCount ?></span>
                                <?php endif; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow notif-dropdown">
                                <div class="notif-dropdown-header">
                                    <span class="notif-header-title">
                                        <i class="ti ti-bell-ringing"></i>
                                        Notifikasi
                                        <?php if ($unreadCount > 0): ?>
                                            <span class="notif-header-count"><?= $unreadCount ?></span>
                                        <?php endif; ?>
                                    </span>
                                    <div class="notif-header-actions">
                                        <?php if ($unreadCount > 0): ?>
                                            <a href="<?= BASE_URL ?>/notifications/read-all" class="notif-mark-read">
                                                <i class="ti ti-checks"></i> Tandai dibaca
                                            </a>
                                        <?php endif; ?>
                                        <?php if (!empty($notifications)): ?>
                                            <button type="button" class="notif-clear-all" onclick="clearAllNotifications()">
                                                <i class="ti ti-trash"></i> Hapus semua
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <?php if (empty($notifications)): ?>
                                    <div class="notif-empty" id="notifEmptyState">
                                        <div class="notif-empty-icon">
                                            <i class="ti ti-bell-off"></i>
                                        </div>
                                        <span class="fw-semibold">Belum ada notifikasi</span>
                                        <span class="notif-empty-sub">Kami akan kabari kalau ada yang baru</span>
                                    </div>
                                <?php else: ?>
                                    <div class="notif-list" id="notifList">
                                        <?php foreach ($notifications as $n): ?>
                                            <?php
                                            $isUnread = empty($n['is_read']);
                                            $timeLabel = '';
                                            if (!empty($n['created_at'])) {
                                                $diff = time() - strtotime($n['created_at']);
                                                if ($diff < 60) {
                                                    $timeLabel = 'Baru saja';
                                                } elseif ($diff < 3600) {
                                                    $timeLabel = floor($diff / 60) . ' menit lalu';
                                                } elseif ($diff < 86400) {
                                                    $timeLabel = floor($diff / 3600) . ' jam lalu';
                                                } elseif ($diff < 604800) {
                                                    $timeLabel = floor($diff / 86400) . ' hari lalu';
                                                } else {
                                                    $timeLabel = date('d M Y', strtotime($n['created_at']));
                                                }
                                            }

                                            $type = $n['type'] ?? 'default';
                                            $iconMap = [
                                                'order' => ['ti-shopping-bag', 'notif-icon-blue'],
                                                'wishlist' => ['ti-heart', 'notif-icon-pink'],
                                                'payment' => ['ti-credit-card', 'notif-icon-green'],
                                                'default' => ['ti-bell', 'notif-icon-gray'],
                                            ];
                                            [$iconClass, $colorClass] = $iconMap[$type] ?? $iconMap['default'];
                                            ?>
                                            <div class="notif-item <?= $isUnread ? 'notif-unread' : '' ?>"
                                                data-notif-id="<?= $n['id'] ?>">
                                                <a class="notif-item-link" href="<?= BASE_URL . $n['url'] ?>">
                                                    <span class="notif-icon <?= $colorClass ?>">
                                                        <i class="ti <?= $iconClass ?>"></i>
                                                    </span>
                                                    <div class="notif-item-body">
                                                        <div class="notif-item-title">
                                                            <?= htmlspecialchars($n['title']) ?>
                                                            <?php if ($isUnread): ?><span class="notif-dot"></span><?php endif; ?>
                                                        </div>
                                                        <div class="notif-item-msg"><?= htmlspecialchars($n['message']) ?></div>
                                                        <?php if ($timeLabel): ?>
                                                            <div class="notif-item-time"><i class="ti ti-clock"></i> <?= $timeLabel ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </a>
                                                <button type="button" class="notif-item-delete"
                                                    onclick="deleteNotification(event, <?= $n['id'] ?>, this)"
                                                    title="Hapus notifikasi">
                                                    <i class="ti ti-x"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>

                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="btn btn-nav-user d-flex align-items-center gap-2 rounded-pill dropdown-toggle"
                                href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if (!empty($_SESSION['user_photo'])): ?>
                                    <img src="<?= BASE_URL ?>/assets/images/avatars/<?= htmlspecialchars($_SESSION['user_photo']) ?>"
                                        class="user-avatar" alt="Avatar">
                                <?php else: ?>
                                    <div class="user-avatar-placeholder">
                                        <?= strtoupper(substr($_SESSION['user_name'] ?? '', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <span class="user-name">
                                    <?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/dashboard">
                                        <i class="ti ti-layout-dashboard me-2"></i>
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/profile">
                                        <i class="ti ti-user me-2"></i>
                                        Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/orders">
                                        <i class="ti ti-shopping-bag me-2"></i>
                                        Pesanan
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/dashboard/downloads">
                                        <i class="ti ti-download me-2"></i>
                                        Download
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_URL ?>/wishlist">
                                        <i class="ti ti-heart me-2"></i>
                                        Wishlist
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= BASE_URL ?>/logout">
                                        <i class="ti ti-logout me-2"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                    <?php else: ?>

                        <!-- Keranjang untuk guest -->
                        <li class="nav-item">
                            <a class="nav-icon-link position-relative" href="<?= BASE_URL ?>/cart">
                                <i class="ti ti-shopping-cart"></i>
                                <?php
                                $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                                if ($cartCount > 0):
                                    ?>
                                    <span class="cart-badge"><?= $cartCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item"><a class="btn btn-sm btn-nav-outline rounded-pill"
                                href="<?= BASE_URL ?>/login">Login</a></li>
                        <li class="nav-item"><a class="btn btn-sm btn-nav-primary rounded-pill"
                                href="<?= BASE_URL ?>/register">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <script>
        function deleteNotification(event, id, btnEl) {
            event.preventDefault();
            event.stopPropagation();

            fetch('<?= BASE_URL ?>/notifications/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + id
            }).then(res => {
                if (!res.ok) {
                    console.error('Gagal hapus notifikasi, status:', res.status);
                    alert('Gagal menghapus notifikasi (error ' + res.status + '). Cek console.');
                    return;
                }
                const item = btnEl.closest('.notif-item');
                item.remove();

                const list = document.getElementById('notifList');
                if (list && list.children.length === 0) {
                    list.outerHTML = `
                <div class="notif-empty">
                    <div class="notif-empty-icon"><i class="ti ti-bell-off"></i></div>
                    <span class="fw-semibold">Belum ada notifikasi</span>
                    <span class="notif-empty-sub">Kami akan kabari kalau ada yang baru</span>
                </div>`;
                }
            }).catch(err => {
                console.error('Fetch error:', err);
                alert('Terjadi kesalahan koneksi.');
            });
        }
    </script>