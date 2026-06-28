<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <div class="row">
    <div class="col-lg-3 mb-4">
      <h6 class="fw-semibold mb-3">Kategori</h6>
      <ul class="list-unstyled small">
        <li class="mb-2">
          <a href="/shop" class="text-decoration-none <?= $activeCategory === '' ? 'fw-semibold text-primary' : 'text-dark' ?>">Semua Produk</a>
        </li>
        <?php foreach ($categories as $cat): ?>
          <li class="mb-2">
            <a href="/shop?category=<?= $cat['slug'] ?>" class="text-decoration-none <?= $activeCategory === $cat['slug'] ? 'fw-semibold text-primary' : 'text-dark' ?>">
              <?= htmlspecialchars($cat['name']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
          <?= $keyword ? 'Hasil pencarian: "' . htmlspecialchars($keyword) . '"' : 'Semua Produk' ?>
        </h2>
        <span class="text-secondary small"><?= count($products) ?> produk</span>
      </div>

      <div class="row g-4">
        <?php foreach ($products as $p): ?>
          <div class="col-6 col-md-4" data-aos="fade-up">
            <a href="/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
              <div class="product-card position-relative">
                <?php if ($p['discount'] > 0): ?>
                  <span class="badge-discount">-<?= (int)$p['discount'] ?>%</span>
                <?php endif; ?>
                <img src="/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['title']) ?>">
                <div class="body">
                  <div class="cat-tag"><?= htmlspecialchars($p['category_name']) ?></div>
                  <div class="title"><?= htmlspecialchars($p['title']) ?></div>
                  <span class="price">Rp <?= number_format($p['price'],0,',','.') ?></span>
                </div>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
        <?php if (empty($products)): ?>
          <p class="text-secondary">Tidak ada produk ditemukan.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>