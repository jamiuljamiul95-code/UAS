<?php require ROOT . '/app/views/frontend/partials/header.php';
$finalPrice = $product['discount'] > 0
  ? $product['price'] - ($product['price'] * $product['discount'] / 100)
  : $product['price'];
?>

<div class="container py-5">
  <div class="row g-5">
    <div class="col-lg-6" data-aos="fade-right">
      <img src="/assets/images/products/<?= htmlspecialchars($product['preview_image'] ?: $product['thumbnail'] ?: 'placeholder.jpg') ?>"
           class="img-fluid rounded-4 w-100" style="object-fit:cover; max-height:480px" alt="<?= htmlspecialchars($product['title']) ?>">
    </div>

    <div class="col-lg-6" data-aos="fade-left">
      <span class="cat-tag"><?= htmlspecialchars($product['category_name']) ?></span>
      <h1 class="fw-semibold mt-2" style="font-size:1.8rem"><?= htmlspecialchars($product['title']) ?></h1>

      <div class="d-flex align-items-center gap-3 my-3">
        <?php if ($product['discount'] > 0): ?>
          <span class="text-secondary text-decoration-line-through">Rp <?= number_format($product['price'],0,',','.') ?></span>
          <span class="fw-bold fs-3 text-primary">Rp <?= number_format($finalPrice,0,',','.') ?></span>
          <span class="badge bg-danger">-<?= (int)$product['discount'] ?>%</span>
        <?php else: ?>
          <span class="fw-bold fs-3 text-primary">Rp <?= number_format($finalPrice,0,',','.') ?></span>
        <?php endif; ?>
      </div>

      <p class="text-secondary small mb-1"><i class="ti ti-shopping-cart"></i> <?= (int)$product['sales'] ?> terjual</p>

      <div class="d-flex gap-2 mt-4">
        <form action="/cart/add" method="POST" class="flex-fill">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
            <i class="ti ti-shopping-cart-plus"></i> Tambah ke Keranjang
          </button>
        </form>
        <button type="button" class="btn btn-outline-secondary rounded-pill px-3" onclick="addWishlist(<?= $product['id'] ?>)">
          <i class="ti ti-heart"></i>
        </button>
      </div>

      <hr class="my-4">
      <h6 class="fw-semibold mb-2">Deskripsi Produk</h6>
      <p class="text-secondary" style="white-space:pre-line"><?= htmlspecialchars($product['description']) ?></p>
    </div>
  </div>
</div>

<script>
function addWishlist(id) {
  fetch('/wishlist/add', { method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'}, body: 'product_id=' + id })
    .then(r => r.json())
    .then(data => {
      Swal.fire({ icon: data.success ? 'success' : 'error', title: data.message, timer: 1800, showConfirmButton: false });
    });
}
</script>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>