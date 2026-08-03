<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css">

<div class="dashboard-wrapper">
    <div class="container">
        <div class="row g-4">

            <!-- Sidebar -->
            <div class="col-lg-3">
                <?php require ROOT . '/app/views/frontend/dashboard/partials/sidebar.php'; ?>
            </div>

            <!-- Konten -->
            <div class="col-lg-9">
                <div class="dashboard-content">

                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <div class="dashboard-title mb-0">
                            <i class="ti ti-receipt-2"></i> Detail Pesanan
                        </div>
                        <a href="<?= BASE_URL ?>/dashboard/orders" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="ti ti-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-8">
                            <!-- Produk dibeli -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Produk Dibeli</h6>
                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Produk</th>
                                                    <th class="text-end">Harga</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($order['items'] as $item): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($item['product_title']) ?></td>
                                                        <td class="text-end">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Bukti pembayaran (view-only) -->
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3"><i class="ti ti-file-invoice"></i> Bukti Pembayaran</h6>
                                    <?php if (!empty($order['payment_proof'])): ?>
                                        <?php $proofExt = strtolower(pathinfo($order['payment_proof'], PATHINFO_EXTENSION)); ?>
                                        <?php if ($proofExt === 'pdf'): ?>
                                            <a href="<?= BASE_URL ?>/uploads/payment_proof/<?= htmlspecialchars($order['payment_proof']) ?>"
                                                target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="ti ti-file-type-pdf"></i> Lihat Bukti Pembayaran (PDF)
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>/uploads/payment_proof/<?= htmlspecialchars($order['payment_proof']) ?>"
                                                target="_blank">
                                                <img src="<?= BASE_URL ?>/uploads/payment_proof/<?= htmlspecialchars($order['payment_proof']) ?>"
                                                    style="max-width:100%;max-height:400px;border-radius:10px;border:1px solid #e5e7eb">
                                            </a>
                                            <p class="small text-secondary mt-2 mb-0">Klik gambar untuk lihat ukuran penuh.</p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="small text-secondary mb-0">Belum ada bukti pembayaran yang diupload untuk pesanan ini.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Informasi order -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="fw-semibold mb-3">Informasi Pesanan</h6>
                                    <p class="small mb-1"><span class="text-secondary">Invoice:</span><br>
                                        <?= htmlspecialchars($order['invoice']) ?></p>
                                    <p class="small mb-1"><span class="text-secondary">Total:</span>
                                        <strong>Rp <?= number_format($order['total'], 0, ',', '.') ?></strong></p>
                                    <p class="small mb-1"><span class="text-secondary">Tanggal:</span>
                                        <?= date('d M Y, H:i', strtotime($order['created_at'])) ?></p>
                                    <p class="small mb-0"><span class="text-secondary">Status:</span>
                                        <span class="badge bg-<?= $order['status'] === 'paid' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'secondary') ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <?php if ($order['status'] === 'paid'): ?>
                                <!-- Kalau sudah dibayar, arahkan langsung ke Download Saya -->
                                <div class="card shadow-sm border-success-subtle">
                                    <div class="card-body text-center">
                                        <p class="small text-secondary mb-3">
                                            Pesanan ini sudah dibayar. File produk sudah bisa diunduh.
                                        </p>
                                        <a href="<?= BASE_URL ?>/dashboard/downloads" class="btn btn-primary rounded-pill w-100">
                                            <i class="ti ti-download"></i> Buka Download Saya
                                        </a>
                                    </div>
                                </div>
                            <?php elseif ($order['status'] === 'pending'): ?>
                                <div class="card shadow-sm border-warning-subtle">
                                    <div class="card-body">
                                        <p class="small text-secondary mb-0">
                                            Pesanan ini masih menunggu konfirmasi pembayaran. File akan muncul di
                                            <strong>Download Saya</strong> setelah pembayaran dikonfirmasi.
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>
