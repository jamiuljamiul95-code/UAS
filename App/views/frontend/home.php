<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">

<section class="hero-section">
  <div class="container text-center" data-aos="fade-up">
    <h1>Temukan Aset Digital Terbaik untuk Karyamu</h1>
    <p>Template Canva, Preset Lightroom, Mockup, Font Premium, dan ribuan asset kreatif lainnya.</p>
    <form action="<?= BASE_URL ?>/shop" method="GET" class="hero-search-form d-flex justify-content-center mt-4">
      <input type="text" name="q" class="form-control rounded-pill me-2" style="max-width:340px" placeholder="Cari template, preset, font...">
      <button class="btn btn-light rounded-pill px-4 fw-semibold">Cari</button>
    </form>
  </div>
</section>

<div class="container py-5">

  <h2 class="section-title" data-aos="fade-up">Kategori</h2>
  <div class="row g-3 mb-5">
    <?php if (!empty($categories)): ?>
      <?php foreach ($categories as $cat): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/shop?category=<?= $cat['slug'] ?>" class="category-pill">
            <i class="ti <?= htmlspecialchars($cat['icon'] ?: 'ti-folder') ?>"></i>
            <span class="small"><?= htmlspecialchars($cat['name']) ?></span>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Belum ada kategori yang tersedia.</div>
    <?php endif; ?>
  </div>

  <h2 class="section-title" data-aos="fade-up">Produk Terbaru</h2>
  <div class="row g-4 mb-5">
    <?php if (!empty($latest)): ?>
      <?php foreach ($latest as $p): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
            <div class="product-card position-relative">
              <?php if ($p['discount'] > 0): ?>
                <span class="badge-discount">-<?= (int)$p['discount'] ?>%</span>
              <?php endif; ?>
              <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['title']) ?>">
              <div class="body">
                <div class="cat-tag"><?= htmlspecialchars($p['category_name']) ?></div>
                <div class="title"><?= htmlspecialchars($p['title']) ?></div>
                <div>
                  <?php if ($p['discount'] > 0):
                    $finalPrice = $p['price'] - ($p['price'] * $p['discount'] / 100); ?>
                    <span class="price-old">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                    <span class="price">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                  <?php else: ?>
                    <span class="price">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Belum ada produk yang dipublikasikan.</div>
    <?php endif; ?>
  </div>

  <h2 class="section-title" data-aos="fade-up">Produk Terlaris</h2>
  <div class="row g-4">
    <?php if (!empty($bestSellers)): ?>
      <?php foreach ($bestSellers as $p): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
            <div class="product-card">
              <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['title']) ?>">
              <div class="body">
                <div class="cat-tag"><?= htmlspecialchars($p['category_name']) ?></div>
                <div class="title"><?= htmlspecialchars($p['title']) ?></div>
                <span class="price">Rp <?= number_format($p['price'], 0, ',', '.') ?></span>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Belum ada produk terlaris untuk ditampilkan.</div>
    <?php endif; ?>
  </div>

</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>