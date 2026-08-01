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
                    <span class="badge-discount-lg">
                        -<?= (int) $product['discount'] ?>%
                    </span>
                    <?php endif; ?>

                    <img id="mainImg"
                        src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image'] ?: $product['thumbnail'] ?: 'placeholder.jpg') ?>"
                        alt="<?= htmlspecialchars($product['title']) ?>">

                    <?php if (!empty($media)): ?>

                    <button class="slider-nav slider-nav-prev" onclick="navSlide(-1)">
                        <i class="ti ti-chevron-left"></i>
                    </button>

                    <button class="slider-nav slider-nav-next" onclick="navSlide(1)">
                        <i class="ti ti-chevron-right"></i>
                    </button>

                    <div class="gallery-counter">
                        <span id="currentSlide">1</span>
                        /
                        <span id="totalSlide">
                            <?= count($media) + 1 ?>
                        </span>
                    </div>

                    <?php endif; ?>

                </div>

                <!-- Thumbnail -->
                <?php if (!empty($media)): ?>

                <div class="thumb-wrapper">

                    <div class="media-thumb-list" id="mediaThumb">

                        <!-- Thumbnail utama -->
                        <div class="media-thumb active" data-type="img"
                            data-src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['preview_image'] ?: $product['thumbnail']) ?>"
                            onclick="switchMedia(this)">

                            <img src="<?= BASE_URL ?>/assets/images/products/<?= htmlspecialchars($product['thumbnail']) ?>"
                                alt="">

                        </div>

                        <?php foreach ($media as $m): ?>

                        <?php if ($m['type'] == 'image'): ?>

                        <div class="media-thumb" data-type="img"
                            data-src="<?= BASE_URL ?>/assets/images/products/<?= $m['file_path'] ?>"
                            onclick="switchMedia(this)">

                            <img src="<?= BASE_URL ?>/assets/images/products/<?= $m['file_path'] ?>" alt="">

                        </div>

                        <?php else: ?>

                        <div class="media-thumb media-thumb-video" data-type="video"
                            data-src="<?= BASE_URL ?>/assets/videos/products/<?= $m['file_path'] ?>"
                            onclick="switchMedia(this)">

                            <video muted>
                                <source src="<?= BASE_URL ?>/assets/videos/products/<?= $m['file_path'] ?>">
                            </video>

                            <span class="video-play">
                                <i class="ti ti-player-play-filled"></i>
                            </span>

                        </div>

                        <?php endif; ?>

                        <?php endforeach; ?>

                    </div>

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
                        <span class="price-final">Rp <?= number_format($finalPrice, 0, ',', '.') ?></span>
                        <?php if ($product['discount'] > 0): ?>
                        <span class="price-original">Rp <?= number_format($product['price'], 0, ',', '.') ?></span>
                        <span class="discount-badge">Hemat <?= (int) $product['discount'] ?>%</span>
                        <?php endif; ?>
                    </div>

                    <!-- Meta Stats -->
                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="val"><?= (int) $product['sales'] ?>+</div>
                            <div class="lbl">Terjual</div>
                        </div>
                        <div class="meta-item">
                            <div class="val">⭐ <?= number_format($ratingSummary['avg_rating'], 1) ?></div>
                            <div class="lbl"><?= (int) $ratingSummary['total_reviews'] ?> Ulasan</div>
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
                        <i class="ti ti-bolt"></i> Beli Sekarang — Rp <?= number_format($finalPrice, 0, ',', '.') ?>
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

        <!-- ===== Rating & Ulasan ===== -->
        <div class="desc-card" id="reviews" data-aos="fade-up">
            <h6><i class="ti ti-star"></i> Ulasan Pembeli (<?= (int) $ratingSummary['total_reviews'] ?>)</h6>

            <?php if ($canReview): ?>
            <form action="<?= BASE_URL ?>/product/<?= $product['slug'] ?>/review" method="POST"
                class="review-form mb-4">
                <label class="fw-semibold small mb-2 d-block">Kasih rating & ulasan kamu</label>
                <div class="star-rating-input mb-3">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>"
                        <?= $i == 5 ? 'checked' : '' ?>>
                    <label for="star<?= $i ?>"><i class="ti ti-star-filled"></i></label>
                    <?php endfor; ?>
                </div>
                <textarea name="comment" class="form-control mb-3" rows="3"
                    placeholder="Ceritakan pengalamanmu dengan produk ini (opsional)"></textarea>
                <button type="submit" class="btn-cart-lg" style="width:auto">Kirim Ulasan</button>
            </form>
            <?php elseif ($alreadyReviewed): ?>
            <p class="small text-secondary mb-4">✅ Kamu sudah memberi ulasan untuk produk ini. Terima kasih!</p>
            <?php elseif (isset($_SESSION['user_id'])): ?>
            <p class="small text-secondary mb-4">Beli & bayar produk ini dulu untuk bisa memberi ulasan.</p>
            <?php endif; ?>

            <?php if (empty($reviews)): ?>
            <p class="small text-secondary mb-0">Belum ada ulasan untuk produk ini.</p>
            <?php else: ?>
            <?php foreach ($reviews as $r): ?>
            <div class="review-item">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <strong class="small"><?= htmlspecialchars($r['user_name']) ?></strong>
                    <span class="text-warning small">
                        <?= str_repeat('★', $r['rating']) ?> <?= str_repeat('☆', 5 - $r['rating']) ?>
                    </span>
                </div>
                <?php if ($r['comment']): ?>
                <p class="small text-secondary mb-1"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
                <?php endif; ?>
                <span class="small text-secondary"
                    style="font-size:11px"><?= date('d M Y', strtotime($r['created_at'])) ?></span>
            </div>
            <hr class="my-3">
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
function switchMedia(el) {

    const type = el.dataset.type;
    const src = el.dataset.src;
    const mainMedia = document.getElementById("mainMedia");

    document.querySelectorAll(".media-thumb")
        .forEach(item => item.classList.remove("active"));

    el.classList.add("active");

    const index = [...document.querySelectorAll(".media-thumb")]
        .indexOf(el);

    document.getElementById("currentSlide").innerText = index + 1;

    const current = document.getElementById("mainImg");

    if (type === "img") {

        if (!current || current.tagName !== "IMG") {

            current?.remove();

            const img = document.createElement("img");
            img.id = "mainImg";
            mainMedia.prepend(img);

            img.style.opacity = 0;

            setTimeout(() => {
                img.src = src;
                img.style.opacity = 1;
            }, 180);

        } else {

            current.style.opacity = 0;

            setTimeout(() => {
                current.src = src;
                current.style.opacity = 1;
            }, 180);

        }

    } else {

        if (!current || current.tagName !== "VIDEO") {

            current?.remove();

            const video = document.createElement("video");
            video.controls = true;
            video.autoplay = true;
            video.id = "mainImg";
            mainMedia.prepend(video);
            video.src = src;

        } else {

            current.src = src;

        }

    }

}

function navSlide(direction) {
    const thumbs = Array.from(document.querySelectorAll('.media-thumb'));
    const activeIndex = thumbs.findIndex(t => t.classList.contains('active'));
    let nextIndex = activeIndex + direction;

    if (nextIndex < 0) nextIndex = thumbs.length - 1;
    if (nextIndex >= thumbs.length) nextIndex = 0;

    switchMedia(thumbs[nextIndex]);
    thumbs[nextIndex].scrollIntoView({
        behavior: 'smooth',
        inline: 'center',
        block: 'nearest'
    });
}

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