<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard-home.css">

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row g-4">

            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
            </div>

            <!-- Konten -->
            <div class="col-lg-9">

                <!-- Greeting Banner -->
                <div class="greeting-banner">
                    <div class="greeting-text">
                        <h2>Halo, <?= htmlspecialchars($_SESSION['user_name']) ?>! 👋</h2>
                        <p>Selamat datang kembali di MizuDesign. Terima kasih telah menjadi bagian dari kami.</p>
                    </div>
                </div>

                <!-- Stats -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>/dashboard/orders" class="stat-card-link">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-blue"><i class="ti ti-shopping-bag"></i></div>
                                <div class="stat-num"><?= $totalOrders ?></div>
                                <div class="stat-lbl">Total Pesanan</div>
                                <div class="stat-more">Lihat detail <i class="ti ti-arrow-right"></i></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>/dashboard/downloads" class="stat-card-link">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-green"><i class="ti ti-download"></i></div>
                                <div class="stat-num"><?= $totalDownloads ?></div>
                                <div class="stat-lbl">Total Download</div>
                                <div class="stat-more">Lihat detail <i class="ti ti-arrow-right"></i></div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 col-md-4">
                        <a href="<?= BASE_URL ?>/wishlist" class="stat-card-link">
                            <div class="stat-card">
                                <div class="stat-icon stat-icon-pink"><i class="ti ti-heart"></i></div>
                                <div class="stat-num"><?= $wishlistCount ?></div>
                                <div class="stat-lbl">Produk di Wishlist</div>
                                <div class="stat-more">Lihat detail <i class="ti ti-arrow-right"></i></div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Lists -->
                <div class="row g-3">

                    <!-- Pesanan Terbaru -->
                    <div class="col-lg-6">
                        <div class="recent-card">
                            <div class="recent-card-header">
                                <span><i class="ti ti-shopping-bag"></i> Pesanan Terbaru</span>
                                <a href="<?= BASE_URL ?>/dashboard/orders">Lihat Semua</a>
                            </div>

                            <?php if (empty($recentOrders)): ?>
                                <div class="recent-empty">Belum ada pesanan.</div>
                            <?php else: ?>
                                <?php foreach ($recentOrders as $order): ?>
                                    <a href="<?= BASE_URL ?>/dashboard/orders/detail?invoice=<?= urlencode($order['invoice']) ?>"
                                        class="recent-item">
                                        <div class="recent-item-icon"><i class="ti ti-receipt"></i></div>
                                        <div class="recent-item-body">
                                            <div class="recent-item-title"><?= htmlspecialchars($order['invoice']) ?></div>
                                            <div class="recent-item-sub">
                                                Rp<?= number_format($order['total'], 0, ',', '.') ?> ·
                                                <?= date('d M Y', strtotime($order['created_at'])) ?>
                                            </div>
                                        </div>
                                        <span
                                            class="badge bg-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Download Terbaru -->
                    <div class="col-lg-6">
                        <div class="recent-card">
                            <div class="recent-card-header">
                                <span><i class="ti ti-download"></i> Download Terbaru</span>
                                <a href="<?= BASE_URL ?>/dashboard/downloads">Lihat Semua</a>
                            </div>

                            <?php if (empty($recentDownloads)): ?>
                                <div class="recent-empty">Belum ada download.</div>
                            <?php else: ?>
                                <?php foreach ($recentDownloads as $d): ?>
                                    <?php $expired = strtotime($d['expired_at']) < time(); ?>
                                    <div class="recent-item">
                                        <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($d['thumbnail']) ?>"
                                            class="recent-item-thumb" alt="">
                                        <div class="recent-item-body">
                                            <div class="recent-item-title"><?= htmlspecialchars($d['product_title']) ?></div>
                                            <div class="recent-item-sub">Invoice: <?= htmlspecialchars($d['invoice']) ?></div>
                                        </div>
                                        <?php if ($expired): ?>
                                            <span class="badge bg-secondary">Expired</span>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>/download/<?= $d['token'] ?>"
                                                class="btn btn-sm btn-primary rounded-pill">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>