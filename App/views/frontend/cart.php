<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <h2 class="section-title">Keranjang Belanja</h2>

  <?php if (empty($items)): ?>
    <div class="text-center py-5">
      <i class="ti ti-shopping-cart-off" style="font-size:48px;color:#9ca3af"></i>
      <p class="text-secondary mt-3">Keranjangmu masih kosong.</p>
      <a href="/shop" class="btn btn-primary rounded-pill px-4">Mulai Belanja</a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-8">
        <?php foreach ($items as $item):
          $price = $item['discount'] > 0
            ? $item['price'] - ($item['price'] * $item['discount'] / 100)
            : $item['price'];
        ?>
          <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-4 border">
            <img src="/assets/images/products/<?= htmlspecialchars($item['thumbnail'] ?: 'placeholder.jpg') ?>"
                 style="width:80px;height:80px;object-fit:cover;border-radius:12px">
            <div class="flex-fill">
              <div class="cat-tag"><?= htmlspecialchars($item['category_name']) ?></div>
              <div class="fw-medium"><?= htmlspecialchars($item['title']) ?></div>
              <div class="text-primary fw-bold">Rp <?= number_format($price,0,',','.') ?></div>
            </div>
            <form action="/cart/remove" method="POST">
              <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
              <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle">
                <i class="ti ti-trash"></i>
              </button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="col-lg-4">
        <div class="p-4 rounded-4 border" style="background:#F9FAFB">
          <h6 class="fw-semibold mb-3">Ringkasan Pesanan</h6>

          <form action="/cart/coupon" method="POST" class="mb-3">
            <div class="input-group">
              <input type="text" name="code" class="form-control" placeholder="Kode voucher" value="<?= htmlspecialchars($coupon['code'] ?? '') ?>">
              <button class="btn btn-outline-primary" type="submit">Pakai</button>
            </div>
            <?php if (!empty($_SESSION['coupon_error'])): ?>
              <small class="text-danger"><?= $_SESSION['coupon_error']; unset($_SESSION['coupon_error']); ?></small>
            <?php endif; ?>
          </form>

          <div class="d-flex justify-content-between small mb-2">
            <span class="text-secondary">Subtotal</span>
            <span>Rp <?= number_format($subtotal,0,',','.') ?></span>
          </div>
          <?php if ($coupon): ?>
            <div class="d-flex justify-content-between small mb-2 text-success">
              <span>Voucher (<?= htmlspecialchars($coupon['code']) ?>)</span>
              <span>- Rp <?= number_format($couponDiscount,0,',','.') ?></span>
            </div>
          <?php endif; ?>
          <hr>
          <div class="d-flex justify-content-between fw-bold mb-4">
            <span>Total</span>
            <span class="text-primary">Rp <?= number_format($total,0,',','.') ?></span>
          </div>

          <a href="/checkout" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">Lanjut ke Checkout</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>