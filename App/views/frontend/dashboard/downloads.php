<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/product-detail.css">

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

                                                <!-- ===== Ulasan produk: hanya bisa sekali per produk ===== -->
                                                <?php if ($d['already_reviewed']): ?>
                                                    <span class="badge bg-success-subtle text-success mt-2 d-inline-block">
                                                        <i class="ti ti-check"></i> Sudah diulas
                                                    </span>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-sm btn-outline-warning rounded-pill mt-2"
                                                        data-bs-toggle="modal" data-bs-target="#reviewModal-<?= $d['product_id'] ?>">
                                                        <i class="ti ti-star"></i> Beri Ulasan
                                                    </button>

                                                    <!-- Popup ulasan -->
                                                    <div class="modal fade" id="reviewModal-<?= $d['product_id'] ?>" tabindex="-1"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <form action="<?= BASE_URL ?>/dashboard/downloads/review" method="POST">
                                                                    <input type="hidden" name="product_id" value="<?= $d['product_id'] ?>">
                                                                    <div class="modal-header">
                                                                        <h6 class="modal-title">
                                                                            Beri Ulasan — <?= htmlspecialchars($d['product_title']) ?>
                                                                        </h6>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <label class="fw-semibold small mb-2 d-block">
                                                                            Bagaimana produk ini menurutmu?
                                                                        </label>
                                                                        <div class="star-rating-input mb-3">
                                                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                                            <input type="radio" name="rating"
                                                                                id="star-<?= $d['product_id'] ?>-<?= $i ?>"
                                                                                value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                                                                            <label for="star-<?= $d['product_id'] ?>-<?= $i ?>">
                                                                                <i class="ti ti-star-filled"></i>
                                                                            </label>
                                                                            <?php endfor; ?>
                                                                        </div>
                                                                        <textarea name="comment" class="form-control" rows="3"
                                                                            placeholder="Ceritakan pengalamanmu dengan produk ini (opsional)"></textarea>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary rounded-pill"
                                                                            data-bs-dismiss="modal">Nanti Saja</button>
                                                                        <button type="submit" class="btn btn-primary rounded-pill">
                                                                            Kirim Ulasan
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
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