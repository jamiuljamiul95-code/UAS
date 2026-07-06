<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin' ?> — Mizu Design</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <style>
    .notif-bell {
        position: relative;
        cursor: pointer;
    }

    .notif-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        background: #dc2626;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .notif-dropdown {
        position: absolute;
        right: 0;
        top: 40px;
        width: 320px;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 14px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        z-index: 999;
        display: none;
        overflow: hidden;
    }

    .notif-header {
        padding: 14px 16px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .notif-header span {
        font-weight: 600;
        font-size: 14px;
    }

    .notif-header a {
        font-size: 12px;
        color: #2563EB;
        text-decoration: none;
    }

    .notif-item {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        display: flex;
        gap: 12px;
        align-items: flex-start;
        text-decoration: none;
        color: #111827;
        transition: background 0.15s;
    }

    .notif-item:hover {
        background: #f9fafb;
    }

    .notif-item.unread {
        background: #eff6ff;
    }

    .notif-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    .notif-icon.order {
        background: rgba(37, 99, 235, 0.1);
        color: #2563EB;
    }

    .notif-icon.payment {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .notif-icon.register {
        background: rgba(124, 58, 237, 0.1);
        color: #7C3AED;
    }

    .notif-title {
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .notif-msg {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.4;
    }

    .notif-time {
        font-size: 11px;
        color: #9ca3af;
        margin-top: 3px;
    }

    .notif-empty {
        padding: 24px;
        text-align: center;
        color: #9ca3af;
        font-size: 13px;
    }
    </style>
</head>

<body>

    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="brand">
                <span style="color:#fff">Mizu</span><span style="color:#7C3AED">Admin</span>
            </div>
            <nav>
                <a href="<?= BASE_URL ?>/admin/dashboard"
                    class="<?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                <a href="<?= BASE_URL ?>/admin/products"
                    class="<?= ($activeMenu ?? '') === 'products' ? 'active' : '' ?>">Produk</a>
                <a href="<?= BASE_URL ?>/admin/categories"
                    class="<?= ($activeMenu ?? '') === 'categories' ? 'active' : '' ?>">Kategori</a>
                <a href="<?= BASE_URL ?>/admin/orders"
                    class="<?= ($activeMenu ?? '') === 'orders' ? 'active' : '' ?>">Order</a>
                <a href="<?= BASE_URL ?>/admin/users"
                    class="<?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?>">User</a>
                <a href="<?= BASE_URL ?>/logout"
                    style="margin-top:20px;border-top:1px solid rgba(255,255,255,0.08);padding-top:18px">Logout</a>
            </nav>
        </aside>

        <main class="admin-content">



            <?php
      $notifModel   = new \App\models\Notification();
      $adminNotifs  = $notifModel->forAdmin(8);
      $unreadCount  = $notifModel->countUnread(null);
      ?>
            <div style="display:flex;justify-content:flex-end;margin-bottom:20px">
                <div class="notif-bell" onclick="toggleNotif()" style="position:relative">
                    <i class="ti ti-bell" style="font-size:22px;color:#6b7280"></i>
                    <?php if ($unreadCount > 0): ?>
                    <span class="notif-badge"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                    <?php endif; ?>

                    <div class="notif-dropdown" id="notifDropdown">
                        <div class="notif-header">
                            <span>Notifikasi <?php if ($unreadCount > 0): ?><span
                                    style="color:#dc2626">(<?= $unreadCount ?>)</span><?php endif; ?></span>
                            <a href="<?= BASE_URL ?>/admin/notifications/read-all">Tandai semua dibaca</a>
                        </div>

                        <?php if (empty($adminNotifs)): ?>
                        <div class="notif-empty">Tidak ada notifikasi</div>
                        <?php else: ?>
                        <?php foreach ($adminNotifs as $n): ?>
                        <a href="<?= BASE_URL . $n['url'] ?>" class="notif-item <?= $n['is_read'] ? '' : 'unread' ?>">
                            <div class="notif-icon <?= $n['type'] ?>">
                                <?php
                      echo match($n['type']) {
                        'order'    => '<i class="ti ti-shopping-bag"></i>',
                        'payment'  => '<i class="ti ti-credit-card"></i>',
                        'register' => '<i class="ti ti-user-plus"></i>',
                        default    => '<i class="ti ti-bell"></i>',
                      };
                    ?>
                            </div>
                            <div>
                                <div class="notif-title"><?= htmlspecialchars($n['title']) ?></div>
                                <div class="notif-msg"><?= htmlspecialchars($n['message']) ?></div>
                                <div class="notif-time"><?= date('d M Y, H:i', strtotime($n['created_at'])) ?></div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <script>
            function toggleNotif() {
                var d = document.getElementById('notifDropdown');
                d.style.display = d.style.display === 'block' ? 'none' : 'block';
            }
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.notif-bell')) {
                    document.getElementById('notifDropdown').style.display = 'none';
                }
            });
            </script>