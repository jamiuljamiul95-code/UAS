<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/footer.css">

<footer class="footer-mizu mt-5 pt-5 pb-4">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h5 class="fw-bold"><span style="color:#2563EB">Mizu</span><span style="color:#7C3AED">Design</span></h5>
        <p class="text-secondary small">Marketplace produk digital — template, preset, font, dan asset kreatif lainnya.</p>
      </div>
      <div class="col-md-2">
        <h6 class="fw-semibold">Produk</h6>
        <ul class="list-unstyled small">
          <li><a href="<?= BASE_URL ?>/shop" class="footer-link">Semua Produk</a></li>
          <li><a href="<?= BASE_URL ?>/shop?sort=terlaris" class="footer-link">Terlaris</a></li>
        </ul>
      </div>
      <div class="col-md-2">
        <h6 class="fw-semibold">Akun</h6>
        <ul class="list-unstyled small">
          <li><a href="<?= BASE_URL ?>/login" class="footer-link">Login</a></li>
          <li><a href="<?= BASE_URL ?>/register" class="footer-link">Daftar</a></li>
        </ul>
      </div>
      <div class="col-md-4">
        <h6 class="fw-semibold">Pembayaran</h6>
        <p class="footer-payment-text mb-0">Transaksi aman lewat berbagai metode pembayaran favoritmu.</p>
        <div class="footer-payment-badges">
          <span class="payment-badge">QRIS</span>
          <span class="payment-badge">Dana</span>
          <span class="payment-badge">OVO</span>
          <span class="payment-badge">ShopeePay</span>
          <span class="payment-badge">GoPay</span>
          <span class="payment-badge">Transfer Bank</span>
          <span class="payment-badge">Virtual Account</span>
        </div>
      </div>
    </div>
    <hr class="my-4">
    <p class="text-center footer-bottom mb-0">&copy; <?= date('Y') ?> Mizu Design. All rights reserved.</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>AOS.init({ duration: 700, once: true });</script>
</body>
</html>