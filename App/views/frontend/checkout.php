<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/checkout-custom.css">

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
    </div>

    <div class="col-lg-5">
      <div class="p-4 rounded-4 border mb-4" style="background:#F9FAFB">
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
        <div class="d-flex justify-content-between fw-bold">
          <span>Total</span>
          <span class="text-primary" id="displayTotal" data-total="<?= (int) $total ?>">Rp
            <?= number_format($total, 0, ',', '.') ?></span>
        </div>
      </div>

      <!-- ===== Pilih Metode Bayar (custom, TANPA Snap popup) ===== -->
      <div class="p-4 rounded-4 border mb-4" id="payMethodBox">
        <h6 class="fw-semibold mb-3"><i class="ti ti-bolt"></i> Bayar Otomatis</h6>

        <div class="pay-method-list">
          <label class="pay-method-item">
            <input type="radio" name="pay_method" value="qris" checked>
            <span class="pay-method-label">
              <i class="ti ti-qrcode"></i> QRIS
              <small>GoPay, OVO, Dana, ShopeePay, semua e-wallet</small>
            </span>
          </label>
          <label class="pay-method-item">
            <input type="radio" name="pay_method" value="bca">
            <span class="pay-method-label">
              <i class="ti ti-building-bank"></i> Virtual Account BCA
            </span>
          </label>
          <label class="pay-method-item">
            <input type="radio" name="pay_method" value="bni">
            <span class="pay-method-label">
              <i class="ti ti-building-bank"></i> Virtual Account BNI
            </span>
          </label>
          <label class="pay-method-item">
            <input type="radio" name="pay_method" value="bri">
            <span class="pay-method-label">
              <i class="ti ti-building-bank"></i> Virtual Account BRI
            </span>
          </label>
        </div>

        <button type="button" id="btnCharge" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold mt-3">
          Bayar Sekarang
        </button>
      </div>

      <!-- ===== Hasil pembayaran (QR / VA) muncul di sini, custom UI kita sendiri ===== -->
      <div class="p-4 rounded-4 border mb-4 d-none" id="payResultBox" style="background:#EFF6FF;border-color:#BFDBFE">
        <div id="payResultContent"></div>
        <button type="button" id="btnCheckStatus"
          class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold mt-3">
          <i class="ti ti-refresh"></i> Cek Status Pembayaran
        </button>
        <p class="small text-secondary text-center mt-2 mb-0">
          Setelah berhasil bayar, klik tombol di atas atau tunggu beberapa saat -- status akan terupdate
          otomatis.
        </p>
      </div>

      <!-- ===== Transfer Manual + Upload Bukti (cadangan) ===== -->
      <div class="p-4 rounded-4 border" style="background:#F9FAFB">
        <h6 class="fw-semibold mb-2"><i class="ti ti-building-bank"></i> Transfer Manual</h6>
        <p class="small mb-1"><strong>Bank BCA</strong> — 1234567890 a.n. Mizu Design</p>
        <p class="small text-secondary mb-3">Kalau bayar otomatis bermasalah, transfer manual lalu upload bukti
          di sini.</p>

        <form action="<?= BASE_URL ?>/checkout" method="POST" enctype="multipart/form-data">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Upload Bukti Pembayaran</label>
            <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" class="form-control" required>
            <small class="text-secondary d-block mt-1">JPG/PNG/PDF, maks 5MB.</small>
          </div>
          <button type="submit" class="btn btn-outline-primary w-100 rounded-pill py-2 fw-semibold">
            Kirim Order & Bukti Pembayaran
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  let currentInvoice = null;
  let statusPollInterval = null;

  document.getElementById('btnCharge').addEventListener('click', function () {
    const btn = this;
    const method = document.querySelector('input[name="pay_method"]:checked').value;

    btn.disabled = true;
    btn.innerHTML = 'Memproses...';

    fetch('<?= BASE_URL ?>/checkout/core-charge', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'method=' + method
    })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerHTML = 'Bayar Sekarang';

        if (!data.success) {
          alert(data.message || 'Gagal memproses pembayaran.');
          return;
        }

        currentInvoice = data.invoice;
        document.getElementById('payMethodBox').classList.add('d-none');

        const resultBox = document.getElementById('payResultBox');
        const content = document.getElementById('payResultContent');

        if (data.method === 'qris') {
          content.innerHTML = `
                <h6 class="fw-semibold mb-2 text-center"><i class="ti ti-qrcode"></i> Scan QRIS untuk Bayar</h6>
                <div class="text-center">
                    <img src="${data.qr_url}" alt="QRIS" style="width:220px;height:220px;background:#fff;padding:10px;border-radius:12px">
                </div>
                <p class="small text-secondary text-center mt-2 mb-0">Scan pakai aplikasi GoPay/OVO/Dana/ShopeePay/m-banking mana pun yang support QRIS.</p>
            `;
        } else {
          content.innerHTML = `
                <h6 class="fw-semibold mb-2 text-center"><i class="ti ti-building-bank"></i> Transfer ke Virtual Account</h6>
                <div class="text-center p-3 rounded-3" style="background:#fff">
                    <div class="small text-secondary mb-1">Bank ${data.bank}</div>
                    <div class="fs-4 fw-bold" id="vaNumberText">${data.va_number}</div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="copyVA('${data.va_number}')">
                        <i class="ti ti-copy"></i> Salin Nomor
                    </button>
                </div>
                <p class="small text-secondary text-center mt-2 mb-0">Transfer sesuai jumlah total lewat ATM/m-banking ${data.bank}.</p>
            `;
        }

        resultBox.classList.remove('d-none');

        // Polling otomatis tiap 5 detik cek status order
        statusPollInterval = setInterval(checkStatus, 5000);
      })
      .catch(() => {
        btn.disabled = false;
        btn.innerHTML = 'Bayar Sekarang';
        alert('Terjadi kesalahan koneksi.');
      });
  });

  document.getElementById('btnCheckStatus').addEventListener('click', checkStatus);

  function checkStatus() {
    if (!currentInvoice) return;

    fetch('<?= BASE_URL ?>/checkout/order-status?invoice=' + currentInvoice)
      .then(res => res.json())
      .then(data => {
        if (data.success && data.status === 'paid') {
          clearInterval(statusPollInterval);
          window.location.href = '<?= BASE_URL ?>/checkout/pending?invoice=' + currentInvoice;
        }
      });
  }

  function copyVA(number) {
    navigator.clipboard.writeText(number);
    alert('Nomor VA disalin: ' + number);
  }
</script>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>