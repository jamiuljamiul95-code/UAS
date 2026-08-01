<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/hero.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/home.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/kategori.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/hero-interactive.css">

<!-- ===== HERO SECTION ===== -->
<section class="hero-section-v2">
  <div class="hero-blob hero-blob-1"></div>
  <div class="hero-blob hero-blob-2"></div>
  <div class="hero-blob hero-blob-3"></div>

  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-7 hero-left">
        <span class="badge-promo">✨ Marketplace Aset Digital #1 Indonesia</span>

        <h1 class="hero-title">
          Temukan <span class="hero-rotate-wrap"><span id="heroRotateText" class="hero-rotate-text">Template
              Canva</span></span><br>
          Terbaik untuk Karyamu
        </h1>

        <p>Template Canva, Preset Lightroom, Mockup, Font Premium, dan ribuan aset kreatif lainnya siap pakai.
        </p>

        <form action="<?= BASE_URL ?>/shop" method="GET" class="hero-search-wrap-v2">
          <i class="ti ti-search"></i>
          <input type="text" name="q" placeholder="Cari template, preset, font...">
          <button type="submit">Cari</button>
        </form>

        <div class="mt-4 d-flex flex-wrap gap-2">
          <span style="color:rgba(255,255,255,0.65);font-size:13px">Populer:</span>
          <?php foreach (['Template Canva', 'Preset Lightroom', 'Mockup', 'Font Premium'] as $tag): ?>
            <a href="<?= BASE_URL ?>/shop?q=<?= urlencode($tag) ?>" class="hero-tag-v2"><?= $tag ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-lg-5 hero-right">
        <div class="hero-floating-stack" id="heroFloatingStack">
          <?php $heroProducts = array_slice($latest, 0, 2); ?>
          <?php foreach ($heroProducts as $i => $hp): ?>
            <?php
            $hpPrice = $hp['discount'] > 0
              ? $hp['price'] - ($hp['price'] * $hp['discount'] / 100)
              : $hp['price'];
            ?>
            <a href="<?= BASE_URL ?>/product/<?= $hp['slug'] ?>" class="hero-mockup-card hero-mockup-card-<?= $i + 1 ?>">
              <div class="hero-mockup-thumb"
                style="background-image:url('<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($hp['thumbnail'] ?: 'placeholder.jpg') ?>')">
              </div>
              <div class="hero-mockup-title-real"><?= htmlspecialchars($hp['title']) ?></div>
              <div class="hero-mockup-price">Rp <?= number_format($hpPrice, 0, ',', '.') ?></div>
            </a>
          <?php endforeach; ?>

          <div class="promo-card-v2">
            <div class="label">Promo Spesial</div>
            <div class="pct"><span>Diskon</span> 50%</div>
            <div class="desc fw-semibold">Semua Aset Digital</div>
            <div class="timer">⏰ Hanya hari ini!</div>
            <a href="<?= BASE_URL ?>/shop" class="btn-promo">Lihat Sekarang →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>

  <div class="container">
    <div class="stats-bar-v2">
      <div class="stat-item-v2">
        <div class="number" data-count="<?= (int) $totalProducts ?>">0</div>
        <div class="label">Produk</div>
      </div>
      <div class="stat-item-v2">
        <div class="number" data-count="<?= count($categories) ?>">0</div>
        <div class="label">Kategori</div>
      </div>
      <div class="stat-item-v2">
        <div class="number" data-count="<?= (int) $totalUsers ?>">0</div>
        <div class="label">Customer</div>
      </div>
      <div class="stat-item-v2">
        <div class="number" data-count="4.9" data-decimal="1">0</div>
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
        <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="<?= $i * 80 ?>">
          <a href="<?= BASE_URL ?>/shop?category=<?= $cat['slug'] ?>" class="cat-card-v2 cat-color-<?= $i % 8 ?>">
            <?php if (!empty($cat['sample_thumbnail'])): ?>
              <div class="cat-card-v2-bg"
                style="background-image:url('<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($cat['sample_thumbnail']) ?>')">
              </div>
              <div class="cat-card-v2-overlay"></div>
            <?php endif; ?>

            <div class="cat-card-v2-content">
              <div class="cat-icon-wrap-v2">
                <i class="ti <?= htmlspecialchars($cat['icon'] ?: 'ti-folder') ?>"></i>
              </div>
              <div class="cat-name-v2"><?= htmlspecialchars($cat['name']) ?></div>
              <div class="cat-count-v2"><?= (int) $cat['product_count'] ?> Produk</div>
              <div class="cat-arrow-v2"><i class="ti ti-arrow-right"></i></div>
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
                <span class="badge-discount">-<?= (int) $p['discount'] ?>%</span>
              <?php endif; ?>
              <img
                src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>"
                alt="<?= htmlspecialchars($p['title']) ?>">
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

  <!-- ===== PRODUK TERLARIS ===== -->
  <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-up">
    <h2 class="section-title mb-0">Produk Terlaris</h2>
    <a href="<?= BASE_URL ?>/shop?sort=terlaris" class="text-primary small fw-semibold text-decoration-none">Lihat
      Semua →</a>
  </div>

  <div class="row g-4 mb-5">
    <?php if (!empty($bestSellers)): ?>
      <?php foreach ($bestSellers as $p): ?>
        <div class="col-6 col-md-3" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
            <div class="product-card position-relative">
              <?php if ($p['discount'] > 0): ?>
                <span class="badge-discount">-<?= (int) $p['discount'] ?>%</span>
              <?php endif; ?>
              <img
                src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>"
                alt="<?= htmlspecialchars($p['title']) ?>">
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
      <div class="empty-state">Belum ada produk terlaris untuk ditampilkan.</div>
    <?php endif; ?>
  </div>

  <!-- ===== NEWSLETTER ===== -->
  <div class="newsletter-section" data-aos="fade-up">
    <div class="row align-items-center">
      <div class="col-md-6 mb-3 mb-md-0">
        <h5 class="fw-bold mb-1">Dapatkan info promo & produk terbaru</h5>
        <p class="text-secondary small mb-0">Langganan newsletter dan dapatkan produk terbaru dari MizuDesign.
        </p>
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

<script>
  // ===== Rotating headline text =====
  (function () {
    const words = ['Template Canva', 'Preset Lightroom', 'Font Premium', 'Mockup Produk'];
    const el = document.getElementById('heroRotateText');
    let index = 0;

    setInterval(() => {
      el.style.opacity = 0;
      setTimeout(() => {
        index = (index + 1) % words.length;
        el.textContent = words[index];
        el.style.opacity = 1;
      }, 300);
    }, 2200);
  })();

  // ===== Animated counter (jalan sekali saat elemen terlihat) =====
  (function () {
    const counters = document.querySelectorAll('.stat-item-v2 .number');
    let done = false;

    function runCounters() {
      if (done) return;
      done = true;
      counters.forEach(counter => {
        const target = parseFloat(counter.dataset.count);
        const decimal = parseInt(counter.dataset.decimal || 0);
        const duration = 1200;
        const startTime = performance.now();

        function tick(now) {
          const progress = Math.min((now - startTime) / duration, 1);
          const value = target * progress;
          counter.textContent = decimal > 0 ? value.toFixed(decimal) : Math.floor(value);
          if (progress < 1) requestAnimationFrame(tick);
          else counter.textContent = decimal > 0 ? target.toFixed(decimal) : target;
        }
        requestAnimationFrame(tick);
      });
    }

    const statsBar = document.querySelector('.stats-bar-v2');
    if (statsBar) {
      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) runCounters();
      }, {
        threshold: 0.4
      });
      observer.observe(statsBar);
    }
  })();

  // ===== Parallax mockup card mengikuti gerakan mouse =====
  (function () {
    const stack = document.getElementById('heroFloatingStack');
    if (!stack || window.innerWidth < 992) return;

    stack.addEventListener('mousemove', (e) => {
      const rect = stack.getBoundingClientRect();
      const x = (e.clientX - rect.left - rect.width / 2) / rect.width;
      const y = (e.clientY - rect.top - rect.height / 2) / rect.height;

      stack.querySelectorAll('.hero-mockup-card').forEach((card, i) => {
        const strength = (i + 1) * 10;
        card.style.transform = `translate(${x * strength}px, ${y * strength}px)`;
      });
    });

    stack.addEventListener('mouseleave', () => {
      stack.querySelectorAll('.hero-mockup-card').forEach(card => {
        card.style.transform = 'translate(0, 0)';
      });
    });
  })();
</script>
<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>