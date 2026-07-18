<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/blog.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/blog-detail.css">

<div class="container py-5">

  <a href="<?= BASE_URL ?>/blog" class="blog-back-link">
    <i class="ti ti-arrow-left"></i> Kembali ke Blog
  </a>

  <div class="row justify-content-center">
    <div class="col-lg-9">

      <div class="blog-detail-meta">
        <i class="ti ti-calendar"></i>
        <?= date('d M Y', strtotime($post['created_at'])) ?>
      </div>

      <h1 class="blog-detail-title"><?= htmlspecialchars($post['title']) ?></h1>

      <div class="blog-detail-thumb">
        <i class="ti ti-notes"></i>
        <img src="<?= BASE_URL ?>/assets/images/blog/<?= htmlspecialchars($post['thumbnail']) ?>"
          alt="<?= htmlspecialchars($post['title']) ?>" onerror="this.style.display='none'">
      </div>

      <div class="blog-detail-content">
        <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
      </div>

    </div>
  </div>

  <?php if (!empty($related)): ?>
    <div class="mt-5 pt-4">
      <h4 class="blog-related-title">Artikel Lainnya</h4>
      <div class="row g-4">
        <?php foreach ($related as $r): ?>
          <div class="col-sm-6 col-lg-4" data-aos="fade-up">
            <a href="<?= BASE_URL ?>/blog/detail?slug=<?= urlencode($r['slug']) ?>" class="blog-card">
              <div class="blog-card-thumb">
                <i class="ti ti-notes"></i>
                <img src="<?= BASE_URL ?>/assets/images/blog/<?= htmlspecialchars($r['thumbnail']) ?>"
                  alt="<?= htmlspecialchars($r['title']) ?>" onerror="this.style.display='none'">
              </div>
              <div class="blog-card-body">
                <div class="blog-card-date">
                  <i class="ti ti-calendar"></i>
                  <?= date('d M Y', strtotime($r['created_at'])) ?>
                </div>
                <h5 class="blog-card-title"><?= htmlspecialchars($r['title']) ?></h5>
                <span class="blog-card-link">Baca Selengkapnya →</span>
              </div>
            </a>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>