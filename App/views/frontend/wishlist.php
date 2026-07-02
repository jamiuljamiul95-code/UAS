<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <h2 class="section-title mb-4">Wishlist Saya</h2>

  <?php if (empty($items)): ?>
    <div class="text-center py-5">
      <i class="ti ti-heart-off" style="font-size:56px;color:#9ca3af"></i>
      <p class="text-secondary mt-3">Belum ada produk di wishlist kamu.</p>
      <a href="<?= BASE_URL ?>/shop" class="btn btn-primary rounded-pill px-4">Mulai Belanja</a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($items as $item):
        $finalPrice = $item['discount'] > 0
          ? $item['price'] - ($item['price'] * $item['discount'] / 100)
          : $item['price'];
      ?>
        <div class="col-6 col-md-3">
          <div class="product-card position-relative">
            <?php if ($item['discount'] > 0): ?>
              <span class="badge-discount">-<?= (int)$item['discount'] ?>%</span>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/product/<?= $item['slug'] ?>">
              <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($item['thumbnail'] ?: 'placeholder.jpg') ?>"
                   alt="<?= htmlspecialchars($item['title']) ?>">
            </a>
            <!-- Tombol hapus dari wishlist -->
            <button class="btn-remove-wish" onclick="removeWishlist(<?= $item['product_id'] ?>, this)" title="Hapus dari wishlist">
              <i class="ti ti-heart-filled" style="color:#7C3AED"></i>
            </button>
            <div class="body">
              <div class="cat-tag"><?= htmlspecialchars($item['category_name']) ?></div>
              <a href="<?= BASE_URL ?>/product/<?= $item['slug'] ?>" class="text-decoration-none text-dark">
                <div class="title"><?= htmlspecialchars($item['title']) ?></div>
              </a>
              <div class="d-flex align-items-center justify-content-between mt-2">
                <div>
                  <?php if ($item['discount'] > 0): ?>
                    <span class="price-old">Rp <?= number_format($item['price'],0,',','.') ?></span><br>
                  <?php endif; ?>
                  <span class="price">Rp <?= number_format($finalPrice,0,',','.') ?></span>
                </div>
                <button class="btn btn-sm btn-primary rounded-pill px-3"
                        onclick="addToCart(<?= $item['product_id'] ?>)">
                  + Keranjang
                </button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<style>
.btn-remove-wish {
  position: absolute;
  top: 10px;
  right: 10px;
  background: #fff;
  border: none;
  border-radius: 50%;
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  cursor: pointer;
  transition: transform 0.2s;
}
.btn-remove-wish:hover { transform: scale(1.1); }
</style>

<script>
function removeWishlist(id, btn) {
  fetch('<?= BASE_URL ?>/wishlist/add', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + id
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      // Hapus card dari tampilan langsung tanpa reload
      btn.closest('.col-6').remove();
      Swal.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false });
    }
  });
}

function addToCart(id) {
  fetch('<?= BASE_URL ?>/cart/add', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'product_id=' + id
  })
  .then(r => r.json())
  .then(data => {
    Swal.fire({
      icon: data.success ? 'success' : 'error',
      title: data.success ? 'Ditambahkan!' : 'Gagal',
      text: data.message,
      timer: 1800,
      showConfirmButton: false
    });
  });
}
</script>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>