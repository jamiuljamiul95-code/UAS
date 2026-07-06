<?php require ROOT . '/app/views/frontend/partials/header.php';
$finalPrice = $product['discount'] > 0
  ? $product['price'] - ($product['price'] * $product['discount'] / 100)
  : $product['price'];
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/product-detail.css">

<div class="product-detail-wrapper">
    <div class="container">

        <div class="row g-4">

            <!-- Gambar Produk -->
            <div class="col-lg-6" data-aos="fade-right">
                <!-- Gambar Utama -->
                <div class="product-image-wrap" id="mainMedia">
                    <?php if ($product['discount'] > 0): ?>
                    <span class="badge-discount-lg">-<?= (int)$product['discount'] ?>% OFF</span>
                    <?php endif; ?>
                    <img id="mainImg"
                        src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image'] ?: $product['thumbnail'] ?: 'placeholder.jpg') ?>"
                        alt="<?= htmlspecialchars($product['title']) ?>">
                </div>

                <!-- Thumbnail Gallery -->
                <?php if (!empty($media)): ?>
                <div class="d-flex gap-2 mt-3 flex-wrap" id="mediaThumb">
                    <!-- Thumbnail utama (dari kolom thumbnail) -->
                    <div class="media-thumb active"
                        onclick="switchMedia('img', '<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image'] ?: $product['thumbnail']) ?>')">
                        <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['thumbnail']) ?>"
                            style="width:70px;height:70px;object-fit:cover;border-radius:8px;cursor:pointer;
                    border:2px solid #2563EB">
                    </div>

                    <?php foreach ($media as $m): ?>
                    <?php if ($m['type'] === 'image'): ?>
                    <div class="media-thumb"
                        onclick="switchMedia('img', '<?= BASE_URL ?>/assets/images/products/<?= $m['file_path'] ?>')">
                        <img src="<?= BASE_URL ?>/assets/images/products/<?= $m['file_path'] ?>"
                            style="width:70px;height:70px;object-fit:cover;border-radius:8px;cursor:pointer;border:2px solid transparent">
                    </div>
                    <?php else: ?>
                    <div class="media-thumb"
                        onclick="switchMedia('video', '<?= BASE_URL ?>/assets/videos/products/<?= $m['file_path'] ?>')">
                        <div style="width:70px;height:70px;border-radius:8px;background:#111827;
                        display:flex;align-items:center;justify-content:center;
                        cursor:pointer;border:2px solid transparent">
                            <i class="ti ti-player-play" style="color:#fff;font-size:24px"></i>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Info Produk -->
            <div class="col-lg-6" data-aos="fade-left">
                <div class="product-info-card">

                    <a href="<?= BASE_URL ?>/shop?category=<?= $product['category_slug'] ?>" class="cat-tag-lg">
                        <?= htmlspecialchars($product['category_name']) ?>
                    </a>

                    <h1 class="product-title-lg"><?= htmlspecialchars($product['title']) ?></h1>

                    <!-- Harga -->
                    <div class="price-wrap">
                        <span class="price-final">Rp <?= number_format($finalPrice,0,',','.') ?></span>
                        <?php if ($product['discount'] > 0): ?>
                        <span class="price-original">Rp <?= number_format($product['price'],0,',','.') ?></span>
                        <span class="discount-badge">Hemat <?= (int)$product['discount'] ?>%</span>
                        <?php endif; ?>
                    </div>

                    <!-- Meta Stats -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="val"><?= (int)$product['sales'] ?>+</div>
                            <div class="lbl">Terjual</div>
                        </div>
                        <div class="meta-item">
                            <div class="val">⭐ 5.0</div>
                            <div class="lbl">Rating</div>
                        </div>
                        <div class="meta-item">
                            <div class="val">✅</div>
                            <div class="lbl">Terverifikasi</div>
                        </div>
                    </div>

                    <!-- Feature Badges -->
                    <div class="feature-badges">
                        <span class="feature-badge"><i class="ti ti-download"></i> Instant Download</span>
                        <span class="feature-badge"><i class="ti ti-refresh"></i> Update Gratis</span>
                        <span class="feature-badge"><i class="ti ti-shield-check"></i> File Aman</span>
                        <span class="feature-badge"><i class="ti ti-clock-24"></i> Akses 24 Jam</span>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex gap-2 mb-3">
                        <button class="btn-cart-lg" onclick="addToCart(<?= $product['id'] ?>)">
                            <i class="ti ti-shopping-cart-plus"></i> Tambah ke Keranjang
                        </button>
                        <button class="btn-wish-lg" onclick="addWishlist(<?= $product['id'] ?>)" title="Wishlist">
                            <i class="ti ti-heart"></i>
                        </button>
                    </div>
                    <button class="btn-buynow-lg" onclick="buyNow(<?= $product['id'] ?>)">
                        <i class="ti ti-bolt"></i> Beli Sekarang — Rp <?= number_format($finalPrice,0,',','.') ?>
                    </button>

                    <!-- Info Pembayaran -->
                    <p class="text-center text-secondary mt-3 mb-0" style="font-size:12px">
                        🔒 Pembayaran aman via Midtrans · QRIS · Dana · OVO · GoPay · Transfer Bank
                    </p>

                </div>
            </div>

        </div>

        <!-- Deskripsi -->
        <div class="desc-card" data-aos="fade-up">
            <h6><i class="ti ti-file-description"></i> Deskripsi Produk</h6>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
        </div>

    </div>
</div>

<script>
function addToCart(id) {
    fetch('<?= BASE_URL ?>/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
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

function buyNow(id) {
    fetch('<?= BASE_URL ?>/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'product_id=' + id
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= BASE_URL ?>/checkout';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message
                });
            }
        });
}

function addWishlist(id) {
    fetch('<?= BASE_URL ?>/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'product_id=' + id
        })
        .then(r => r.json())
        .then(data => {
            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.message,
                timer: 1800,
                showConfirmButton: false
            });
        });
}
</script>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>