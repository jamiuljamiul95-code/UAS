<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row g-4">

            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
            </div>

            <!-- Konten -->
            <div class="col-lg-9">
                <div class="dashboard-content">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div class="dashboard-title mb-0">
                            <i class="ti ti-download"></i> Download Saya
                        </div>

                        <?php
                        $expiredCount = 0;
                        if (!empty($downloads)) {
                            foreach ($downloads as $d) {
                                if (strtotime($d['expired_at']) < time()) {
                                    $expiredCount++;
                                }
                            }
                        }
                        ?>

                        <?php if ($expiredCount > 0): ?>
                            <form action="<?= BASE_URL ?>/dashboard/downloads/hide-expired" method="POST"
                                onsubmit="return confirm('Hapus semua (<?= $expiredCount ?>) download yang sudah kedaluwarsa?')">
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                    <i class="ti ti-trash"></i> Hapus Semua Kedaluwarsa (<?= $expiredCount ?>)
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <?php if (empty($downloads)): ?>
                        <div class="alert alert-light border">Belum ada file yang bisa diunduh. File akan muncul di sini
                            setelah pesanan kamu dibayar.</div>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($downloads as $d): ?>
                                <?php $expired = strtotime($d['expired_at']) < time(); ?>
                                <div class="col-md-6">
                                    <div class="card shadow-sm h-100 <?= $expired ? 'border-secondary-subtle' : '' ?>">
                                        <div class="card-body d-flex gap-3">

                                            <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($d['thumbnail']) ?>"
                                                alt="" class="rounded" width="64" height="64"
                                                style="object-fit:cover<?= $expired ? ';filter:grayscale(1);opacity:.6' : '' ?>">
                                            <div class="flex-grow-1">
                                                <h6 class="fw-semibold mb-1"><?= htmlspecialchars($d['product_title']) ?></h6>
                                                <p class="small text-secondary mb-2">Invoice:
                                                    <?= htmlspecialchars($d['invoice']) ?>
                                                </p>

                                                <?php if ($expired): ?>
                                                    <span class="badge bg-secondary mb-1">Link kedaluwarsa</span>

                                                    <form action="<?= BASE_URL ?>/dashboard/downloads/hide" method="POST"
                                                        style="display:inline"
                                                        onsubmit="return confirm('Hapus file ini dari daftar download kamu?')">
                                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                            <i class="ti ti-trash"></i> Hapus
                                                        </button>
                                                    </form>

                                                    <span class="small text-secondary d-block mt-1">
                                                        Kedaluwarsa sejak <?= date('d M Y H:i', strtotime($d['expired_at'])) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <a href="<?= BASE_URL ?>/download/<?= $d['token'] ?>"
                                                        class="btn btn-primary btn-sm rounded-pill">
                                                        Download (<?= $d['download_count'] ?>x)
                                                    </a>
                                                    <form action="<?= BASE_URL ?>/dashboard/downloads/hide" method="POST"
                                                        style="display:inline"
                                                        onsubmit="return confirm('Hapus file ini dari daftar download kamu?')">
                                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>

                                                    <span class="small text-secondary d-block mt-1">
                                                        Berlaku sampai <?= date('d M Y H:i', strtotime($d['expired_at'])) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>