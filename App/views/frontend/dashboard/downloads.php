<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <div class="row g-4">
    <div class="col-md-3">
      <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
    </div>

    <div class="col-md-9">
      <h4 class="fw-bold mb-4">Download Saya</h4>

      <?php if (empty($downloads)): ?>
        <div class="alert alert-light border">Belum ada file yang bisa diunduh. File akan muncul di sini setelah pesanan kamu dibayar.</div>
      <?php else: ?>
        <div class="row g-3">
          <?php foreach ($downloads as $d): ?>
            <?php $expired = strtotime($d['expired_at']) < time(); ?>
            <div class="col-md-6">
              <div class="card shadow-sm h-100">
                <div class="card-body d-flex gap-3">
                  <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($d['thumbnail']) ?>"
                       alt="" class="rounded" width="64" height="64" style="object-fit:cover">
                  <div class="flex-grow-1">
                    <h6 class="fw-semibold mb-1"><?= htmlspecialchars($d['product_title']) ?></h6>
                    <p class="small text-secondary mb-2">Invoice: <?= htmlspecialchars($d['invoice']) ?></p>

                    <?php if ($expired): ?>
                      <span class="badge bg-secondary">Link kedaluwarsa</span>
                    <?php else: ?>
                      <a href="<?= BASE_URL ?>/download/<?= htmlspecialchars($d['token']) ?>" class="btn btn-sm btn-primary">
                        Download (<?= (int)$d['download_count'] ?>x)
                      </a>
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

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>