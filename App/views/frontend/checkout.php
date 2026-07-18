<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>

<div class="container py-5">
  <h2 class="section-title">Checkout</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="p-4 rounded-4 border mb-4">
        <h6 class="fw-semibold mb-3">Data Pembeli</h6>
        <div class="mb-2"><span class="text-secondary small">Nama:</span>
          <?= htmlspecialchars($_SESSION['user_name']) ?></div>
        <div class="small text-secondary">Pembayaran akan dikirim ke email yang terdaftar pada akunmu.</div>
      </div>

      <div class="p-4 rounded-4 border mb-4">
        <h6 class="fw-semibold mb-3">Produk Dibeli</h6>
        <?php foreach ($items as $item): ?>
          <div class="d-flex justify-content-between small mb-2">
            <span><?= htmlspecialchars($item['title']) ?></span>
            <span>Rp <?= number_format(
              $item['discount'] > 0 ? $item['price'] - ($item['price'] * $item['discount'] / 100) : $item['price'],
              0,
              ',',
              '.'
            ) ?></span>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="p-4 rounded-4 border" style="background:#F9FAFB">
        <h6 class="fw-semibold mb-3"><i class="ti ti-building-bank"></i> Rekening Tujuan Transfer</h6>
        <p class="small mb-1"><strong>Bank BCA</strong> — 1234567890 a.n. Mizu Design</p>
        <p class="small text-secondary mb-0">Transfer sesuai jumlah total, lalu upload bukti transfer di form
          sebelah kanan.</p>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="p-4 rounded-4 border" style="background:#F9FAFB">
        <h6 class="fw-semibold mb-3">Ringkasan</h6>
        <div class="d-flex justify-content-between small mb-2">
          <span class="text-secondary">Subtotal</span>
          <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
        </div>
        <?php if ($couponDiscount > 0): ?>
          <div class="d-flex justify-content-between small mb-2 text-success">
            <span>Voucher</span>
            <span>- Rp <?= number_format($couponDiscount, 0, ',', '.') ?></span>
          </div>
        <?php endif; ?>
        <hr>
        <div class="d-flex justify-content-between fw-bold mb-4">
          <span>Total</span>
          <span class="text-primary">Rp <?= number_format($total, 0, ',', '.') ?></span>
        </div>

        <form action="<?= BASE_URL ?>/checkout" method="POST" enctype="multipart/form-data">

          <div class="mb-3">
            <label class="form-label small fw-semibold">
              Upload Bukti Pembayaran <span class="text-danger">*</span>
            </label>
            <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" class="form-control" required>
            <small class="text-secondary d-block mt-1">JPG/PNG/PDF, maks 5MB. Wajib diisi sebelum order
              diproses.</small>
          </div>

          <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
            <i class="ti ti-lock"></i> Kirim Order & Bukti Pembayaran
          </button>
        </form>
        <p class="small text-secondary mt-3 mb-0 text-center">Order akan diverifikasi admin setelah bukti
          pembayaran diterima.</p>
      </div>
    </div>
  </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>