<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <h2 class="section-title">Checkout</h2>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="p-4 rounded-4 border mb-4">
        <h6 class="fw-semibold mb-3">Data Pembeli</h6>
        <div class="mb-2"><span class="text-secondary small">Nama:</span> <?= htmlspecialchars($_SESSION['user_name']) ?></div>
        <div class="small text-secondary">Pembayaran akan dikirim ke email yang terdaftar pada akunmu.</div>
      </div>

      <div class="p-4 rounded-4 border">
        <h6 class="fw-semibold mb-3">Produk Dibeli</h6>
        <?php foreach ($items as $item): ?>
          <div class="d-flex justify-content-between small mb-2">
            <span><?= htmlspecialchars($item['title']) ?></span>
            <span>Rp <?= number_format(
              $item['discount'] > 0 ? $item['price'] - ($item['price'] * $item['discount']/100) : $item['price'],
              0, ',', '.') ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="p-4 rounded-4 border" style="background:#F9FAFB">
        <h6 class="fw-semibold mb-3">Ringkasan</h6>
        <div class="d-flex justify-content-between small mb-2">
          <span class="text-secondary">Subtotal</span>
          <span>Rp <?= number_format($subtotal,0,',','.') ?></span>
        </div>
        <?php if ($couponDiscount > 0): ?>
          <div class="d-flex justify-content-between small mb-2 text-success">
            <span>Voucher</span>
            <span>- Rp <?= number_format($couponDiscount,0,',','.') ?></span>
          </div>
        <?php endif; ?>
        <hr>
        <div class="d-flex justify-content-between fw-bold mb-4">
          <span>Total</span>
          <span class="text-primary">Rp <?= number_format($total,0,',','.') ?></span>
        </div>

        <form action="/checkout" method="POST">
          <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
            <i class="ti ti-lock"></i> Bayar Sekarang
          </button>
        </form>
        <p class="small text-secondary mt-3 mb-0 text-center">Pembayaran aman via Midtrans — QRIS, e-wallet, VA, transfer bank.</p>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>