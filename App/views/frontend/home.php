<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/hero.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/kategori.css">

<!-- ===== HERO SECTION ===== -->
<section class="hero-section">
  <div class="container">
    <div class="row align-items-end">
      <div class="col-lg-7 hero-left">
        <span class="badge-promo">✨ Marketplace Aset Digital #1 Indonesia</span>
        <h1>Temukan Aset Digital<br>Terbaik untuk Karyamu</h1>
        <p>Template Canva, Preset Lightroom, Mockup, Font Premium, dan ribuan aset kreatif lainnya siap pakai.</p>
        <form action="<?= BASE_URL ?>/shop" method="GET" class="hero-search-wrap">
          <input type="text" name="q" placeholder="Cari template, preset, font...">
          <button type="submit">Cari</button>
        </form>
        <div class="mt-4 d-flex flex-wrap gap-2">
          <span style="color:rgba(255,255,255,0.65);font-size:13px">Populer:</span>
          <?php foreach (['Template Canva','Preset Lightroom','Mockup','Font Premium'] as $tag): ?>
            <a href="<?= BASE_URL ?>/shop?q=<?= urlencode($tag) ?>"
               style="background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);
                      color:#fff;font-size:12px;padding:4px 12px;border-radius:20px;
                      text-decoration:none;transition:all 0.2s"
               onmouseover="this.style.background='rgba(255,255,255,0.22)'"
               onmouseout="this.style.background='rgba(255,255,255,0.12)'"><?= $tag ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="col-lg-5 hero-right">
        <div class="promo-card">
          <div class="label">Promo Spesial</div>
          <div class="pct"><span>Diskon</span> 50%</div>
          <div class="desc fw-semibold">Semua Aset Digital</div>
          <div class="timer">⏰ Hanya hari ini!</div>
          <a href="<?= BASE_URL ?>/shop" class="btn-promo">Lihat Sekarang →</a>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="stats-bar">
      <div class="stat-item">
        <div class="number"><?= $totalProducts ?>+</div>
        <div class="label">Produk</div>
      </div>
      <div class="stat-item">
        <div class="number"><?= count($categories) ?>+</div>
        <div class="label">Kategori</div>
      </div>
      <div class="stat-item">
        <div class="number"><?= $totalUsers ?>+</div>
        <div class="label">Customer</div>
      </div>
      <div class="stat-item">
        <div class="number">4.9</div>
        <div class="label">Rating</div>
      </div>
    </div>
  </div>
</section>

<div class="container py-5">

  <!-- ===== KATEGORI ===== -->
  <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
    <h2 class="section-title mb-0">Kategori Populer</h2>
    <a href="<?= BASE_URL ?>/shop" class="text-primary small fw-semibold text-decoration-none">Lihat Semua →</a>
  </div>

  <div class="row g-3 mb-5">
    <?php if (!empty($categories)): ?>
      <?php foreach ($categories as $i => $cat): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/shop?category=<?= $cat['slug'] ?>" class="cat-card">
            <div class="cat-icon-wrap cat-color-<?= $i % 8 ?>">
              <i class="ti <?= htmlspecialchars($cat['icon'] ?: 'ti-folder') ?>"></i>
            </div>
            <div>
              <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
              <div class="cat-count"><?= (int)$cat['product_count'] ?> Produk</div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Belum ada kategori yang tersedia.</div>
    <?php endif; ?>
  </div>

  <!-- ===== PRODUK TERBARU ===== -->
  <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
    <h2 class="section-title mb-0">Produk Terbaru</h2>
    <a href="<?= BASE_URL ?>/shop" class="text-primary small fw-semibold text-decoration-none">Lihat Semua →</a>
  </div>

  <div class="row g-4 mb-5">
    <?php if (!empty($latest)): ?>
      <?php foreach ($latest as $p): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
            <div class="product-card position-relative">
              <?php if ($p['discount'] > 0): ?>
                <span class="badge-discount">-<?= (int)$p['discount'] ?>%</span>
              <?php endif; ?>
              <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>"
                   alt="<?= htmlspecialchars($p['title']) ?>">
              <div class="body">
                <div class="cat-tag"><?= htmlspecialchars($p['category_name']) ?></div>
                <div class="title"><?= htmlspecialchars($p['title']) ?></div>
                <div>
                  <?php if ($p['discount'] > 0):
                    $finalPrice = $p['price'] - ($p['price'] * $p['discount'] / 100); ?>
                    <span class="price-old">Rp <?= number_format($p['price'],0,',','.') ?></span>
                    <span class="price">Rp <?= number_format($finalPrice,0,',','.') ?></span>
                  <?php else: ?>
                    <span class="price">Rp <?= number_format($p['price'],0,',','.') ?></span>
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

  <!-- ===== PRODUK TERLARIS ===== -->
  <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
    <h2 class="section-title mb-0">Produk Terlaris</h2>
    <a href="<?= BASE_URL ?>/shop?sort=terlaris" class="text-primary small fw-semibold text-decoration-none">Lihat Semua →</a>
  </div>

  <div class="row g-4 mb-5">
    <?php if (!empty($bestSellers)): ?>
      <?php foreach ($bestSellers as $p): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
            <div class="product-card">
              <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>"
                   alt="<?= htmlspecialchars($p['title']) ?>">
              <div class="body">
                <div class="cat-tag"><?= htmlspecialchars($p['category_name']) ?></div>
                <div class="title"><?= htmlspecialchars($p['title']) ?></div>
                <span class="price">Rp <?= number_format($p['price'],0,',','.') ?></span>
              </div>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="empty-state">Belum ada produk terlaris untuk ditampilkan.</div>
    <?php endif; ?>
  </div>

  <!-- ===== NEWSLETTER ===== -->
  <div class="newsletter-section" data-aos="fade-up">
    <div class="row align-items-center">
      <div class="col-md-6 mb-3 mb-md-0">
        <h5 class="fw-bold mb-1">Dapatkan info promo & produk terbaru</h5>
        <p class="text-secondary small mb-0">Langganan newsletter dan dapatkan produk terbaru dari MizuDesign.</p>
      </div>
      <div class="col-md-6">
        <form class="d-flex gap-2">
          <input type="email" class="form-control rounded-pill" placeholder="Masukkan email anda">
          <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold">Subscribe</button>
        </form>
      </div>
    </div>
  </div>

</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>