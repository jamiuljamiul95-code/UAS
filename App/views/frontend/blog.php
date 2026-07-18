<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/blog.css">

<!-- ===== HERO ===== -->
<section class="blog-hero">
  <div class="container text-center">
    <span class="badge-promo-light">📝 Blog Mizu Design</span>
    <h1>Tips, Inspirasi &amp; Insight<br>Seputar Aset Digital</h1>
    <p>Kumpulan artikel seputar desain, template, dan tips kreatif untuk mendukung karyamu.</p>
  </div>
</section>

<div class="container py-5">

  <?php if (!empty($posts)): ?>
    <div class="row g-4">
      <?php foreach ($posts as $post): ?>
        <div class="col-sm-6 col-lg-4" data-aos="fade-up">
          <a href="<?= BASE_URL ?>/blog/detail?slug=<?= urlencode($post['slug']) ?>" class="blog-card">
            <div class="blog-card-thumb">
              <i class="ti ti-notes"></i>
              <img src="<?= BASE_URL ?>/assets/images/blog/<?= htmlspecialchars($post['thumbnail']) ?>"
                alt="<?= htmlspecialchars($post['title']) ?>" onerror="this.style.display='none'">
            </div>
            <div class="blog-card-body">
              <div class="blog-card-date">
                <i class="ti ti-calendar"></i>
                <?= date('d M Y', strtotime($post['created_at'])) ?>
              </div>
              <h5 class="blog-card-title"><?= htmlspecialchars($post['title']) ?></h5>
              <p class="blog-card-excerpt">
                <?= htmlspecialchars(mb_strimwidth(trim(strip_tags($post['content'])), 0, 130, '...')) ?>
              </p>
              <span class="blog-card-link">Baca Selengkapnya →</span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <div class="blog-empty-state" data-aos="fade-up">
      <i class="ti ti-notes"></i>
      <h5>Belum ada artikel</h5>
      <p>Nantikan artikel menarik seputar desain dan aset digital dari kami.</p>
    </div>
  <?php endif; ?>

</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>