<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/about.css">

<!-- ===== HERO ===== -->
<section class="about-hero">
    <div class="container text-center">
        <span class="badge-promo-light">✨ Kenalan Yuk</span>
        <h1>Marketplace Aset Digital<br>untuk Kreator Indonesia</h1>
        <p>Mizu Design hadir untuk membantu desainer, marketer, dan pemilik bisnis
            menemukan aset kreatif berkualitas tanpa ribet.</p>
    </div>
</section>

<div class="container py-5">

    <!-- ===== STORY ===== -->
    <div class="row align-items-center g-5 mb-5" data-aos="fade-up">
        <div class="col-lg-6">
            <span class="section-label">Cerita Kami</span>
            <h2 class="about-title">Dimulai dari kebutuhan sederhana</h2>
            <p class="about-text">
                Mizu Design lahir dari pengalaman langsung mencari template, preset, dan aset
                desain berkualitas yang sering kali sulit ditemukan dalam satu tempat dengan
                harga terjangkau. Dari situ, kami membangun marketplace yang fokus pada
                kemudahan: cari, beli, download — selesai.
            </p>
            <p class="about-text">
                Setiap produk yang masuk ke Mizu Design diseleksi supaya kreator, pelaku UMKM,
                dan siapa pun yang butuh aset digital bisa langsung pakai tanpa perlu edit
                besar-besaran.
            </p>
        </div>
        <div class="col-lg-6">
            <div class="about-stats-card">
                <div class="row g-4">
                    <div class="col-6">
                        <div class="stat-num">3+</div>
                        <div class="stat-lbl">Produk Digital</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-num">3+</div>
                        <div class="stat-lbl">Kategori</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-num">2+</div>
                        <div class="stat-lbl">Pelanggan</div>
                    </div>
                    <div class="col-6">
                        <div class="stat-num">4.9</div>
                        <div class="stat-lbl">Rating Rata-rata</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== VALUES ===== -->
    <div class="text-center mb-4" data-aos="fade-up">
        <span class="section-label">Kenapa Mizu Design</span>
        <h2 class="about-title">Nilai yang kami pegang</h2>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4" data-aos="fade-up">
            <div class="value-card">
                <div class="value-icon"><i class="ti ti-bolt"></i></div>
                <h5>Instant Download</h5>
                <p>Beli sekarang, langsung bisa diunduh. Tidak ada proses menunggu approval
                    berhari-hari.</p>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
            <div class="value-icon-wrap">
                <div class="value-card">
                    <div class="value-icon"><i class="ti ti-shield-check"></i></div>
                    <h5>Kualitas Terjamin</h5>
                    <p>Setiap produk dikurasi supaya file yang kamu dapat sesuai deskripsi dan
                        siap pakai.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
            <div class="value-card">
                <div class="value-icon"><i class="ti ti-headset"></i></div>
                <h5>Support Responsif</h5>
                <p>Ada kendala soal produk atau pembayaran? Tim kami siap bantu lewat kontak di
                    bawah.</p>
            </div>
        </div>
    </div>

    <!-- ===== CONTACT CTA ===== -->
    <div class="about-cta" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-md-7 mb-3 mb-md-0">
                <h4 class="fw-bold mb-2">Ada pertanyaan sebelum belanja?</h4>
                <p class="mb-0 text-secondary">Kami senang bantu kamu menemukan aset yang pas
                    untuk kebutuhanmu.</p>
            </div>
            <div class="col-md-5 text-md-end">
                <a href="mailto:hello@mizudesign.com" class="btn-about-primary">
                    <i class="ti ti-mail"></i> hello@mizudesign.com
                </a>
            </div>
        </div>
    </div>

</div>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>