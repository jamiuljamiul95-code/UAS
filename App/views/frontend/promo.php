<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<div class="container my-5 pt-5">
    <!-- Header Promo -->
    <div class="text-center mb-5 mt-4">
        <span class="badge bg-danger px-3 py-2 rounded-pill mb-2">🔥 Penawaran Terbatas</span>
        <h2 class="fw-bold text-dark">Promo Spesial Hari Ini</h2>
        <p class="text-muted">Dapatkan aset digital terbaik dengan harga super miring sebelum kehabisan!</p>
    </div>

    <!-- Grid Produk Promo -->
    <div class="row g-4">
        <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
        <?php
                // Ambil persentase diskon langsung dari database
                $diskon = floatval($product['discount']);

                // Hitung harga setelah dipotong diskon persen secara dinamis
                $hargaAsli = $product['price'];
                $hargaPotongan = $hargaAsli - ($hargaAsli * ($diskon / 100));
                ?>
        <div class="col-md-3">
            <div clas s="card h-100 border-0 shadow-sm rounded-4 position-relative overflow-hidden">

                <!-- Badge Diskon Bulat Ungu/Biru -->
                <?php if ($diskon > 0): ?>
                <span
                    class="position-absolute top-0 start-0 bg-primary text-white px-2 py-1 small rounded-circle m-3 fw-bold"
                    style="z-index: 10; font-size: 0.75rem;">
                    -<?= round($diskon) ?>%
                </span>
                <?php endif; ?>

                <!-- Gambar Produk (Menggunakan kolom thumbnail dari database) -->
                <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['thumbnail'] ?? 'default.jpg') ?>"
                    class="card-img-top" alt="<?= htmlspecialchars($product['title'] ?? $product['name']) ?>"
                    style="height: 200px; object-fit: cover;">

                <!-- Detail Produk -->
                <div class="card-body d-flex flex-column">
                    <span class="text-uppercase text-primary fw-bold mb-1" style="font-size: 0.75rem;">
                        <?= htmlspecialchars($product['category_name'] ?? 'TEMPLATE') ?>
                    </span>
                    <h5 class="card-title fw-bold text-dark text-truncate mb-3" style="font-size: 1rem;">
                        <?= htmlspecialchars($product['title'] ?? $product['name']) ?>
                    </h5>

                    <!-- Area Harga -->
                    <div class="mt-auto">
                        <div class="d-flex align-items-center gap-2">
                            <!-- Harga Asli Dicoret -->
                            <span class="text-muted text-decoration-line-through small" style="font-size: 0.85rem;">
                                Rp <?= number_format($hargaAsli, 0, ',', '.') ?>
                            </span>
                            <!-- Harga Setelah Diskon -->
                            <span class="text-primary fw-bold" style="font-size: 1.1rem;">
                                Rp <?= number_format($hargaPotongan, 0, ',', '.') ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Link Detail Produk -->
                <a href="<?= BASE_URL ?>/product/<?= $product['slug'] ?>" class="stretched-link"></a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <!-- Jika tidak ada promo -->
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="ti ti-discount-2 fs-1 text-muted" style="font-size: 3rem;"></i>
            </div>
            <h4 class="fw-bold text-muted">Belum Ada Promo Aktif</h4>
            <p class="text-muted">Nantikan promo menarik berikutnya dari kami!</p>
            <a href="<?= BASE_URL ?>/shop" class="btn btn-outline-primary mt-2">Lihat Semua Produk</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>