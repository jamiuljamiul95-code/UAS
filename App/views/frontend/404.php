<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/error-page.css">

<div class="error-page-wrapper">
    <div class="container text-center">
        <div class="error-code">404</div>
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        <p class="error-desc">
            Ups, sepertinya halaman yang kamu cari sudah dipindahkan, dihapus,
            atau memang tidak pernah ada.
        </p>

        <div class="error-actions">
            <a href="<?= BASE_URL ?>/" class="btn-error-primary">
                <i class="ti ti-home"></i> Kembali ke Beranda
            </a>
            <a href="<?= BASE_URL ?>/shop" class="btn-error-outline">
                <i class="ti ti-shopping-bag"></i> Jelajahi Produk
            </a>
        </div>

        <div class="error-illustration">
            <i class="ti ti-mood-confuzed"></i>
        </div>
    </div>
</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>