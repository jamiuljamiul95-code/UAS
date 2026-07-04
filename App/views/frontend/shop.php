<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <div class="row">
    <div class="col-lg-3 mb-4">
  <h6 class="fw-semibold mb-3">Kategori</h6>
  <ul class="list-unstyled small">
    <li class="mb-2">
      <a href="<?= BASE_URL ?>/shop"
         class="text-decoration-none <?= $activeCategory === '' ? 'fw-semibold text-primary' : 'text-dark' ?>">
        Semua Produk
      </a>
    </li>

    <?php foreach ($categories as $parent): ?>
      <li class="mb-1">
        <!-- Parent kategori — bisa diklik untuk expand/collapse -->
        <div class="d-flex align-items-center justify-content-between cat-parent-toggle"
             data-target="cat-<?= $parent['id'] ?>"
             style="cursor:pointer; padding:6px 0">
          <span class="fw-semibold" style="color:#111827; font-size:13px">
            <?= htmlspecialchars($parent['name']) ?>
          </span>
          <?php if (!empty($parent['children'])): ?>
            <i class="ti ti-chevron-down" style="font-size:13px; color:#9ca3af; transition:transform 0.2s"
               id="arrow-<?= $parent['id'] ?>"></i>
          <?php endif; ?>
        </div>

        <?php if (!empty($parent['children'])): ?>
          <ul class="list-unstyled ps-3 cat-children" id="cat-<?= $parent['id'] ?>"
              style="display:none">
            <?php foreach ($parent['children'] as $child): ?>
              <li class="mb-1">
                <a href="<?= BASE_URL ?>/shop?category=<?= $child['slug'] ?>"
                   class="text-decoration-none d-flex align-items-center gap-1
                          <?= $activeCategory === $child['slug'] ? 'fw-semibold text-primary' : 'text-secondary' ?>"
                   style="font-size:13px; padding:4px 0">
                  <span style="color:#d1d5db">├</span>
                  <?= htmlspecialchars($child['name']) ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
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
            <a href="<?= BASE_URL ?>/product/<?= $p['slug'] ?>" class="text-decoration-none text-dark">
              <div class="product-card position-relative">
                <?php if ($p['discount'] > 0): ?>
                  <span class="badge-discount">-<?= (int)$p['discount'] ?>%</span>
                <?php endif; ?>
                <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($p['thumbnail'] ?: 'placeholder.jpg') ?>" alt="<?= htmlspecialchars($p['title']) ?>">
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



<script>
// Accordion sidebar kategori
document.querySelectorAll('.cat-parent-toggle').forEach(function(toggle) {
  toggle.addEventListener('click', function() {
    var target = this.dataset.target;
    var children = document.getElementById(target);
    var arrow = document.getElementById('arrow-' + target.replace('cat-', ''));

    if (children) {
      var isOpen = children.style.display !== 'none';
      children.style.display = isOpen ? 'none' : 'block';
      if (arrow) arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }
  });
});

// Auto-expand kategori yang sedang aktif
<?php foreach ($categories as $parent): ?>
  <?php foreach ($parent['children'] as $child): ?>
    <?php if ($activeCategory === $child['slug']): ?>
      document.getElementById('cat-<?= $parent['id'] ?>').style.display = 'block';
      var arrow = document.getElementById('arrow-<?= $parent['id'] ?>');
      if (arrow) arrow.style.transform = 'rotate(180deg)';
    <?php endif; ?>
  <?php endforeach; ?>
<?php endforeach; ?>
</script>


<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>