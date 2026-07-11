<?php require ROOT . '/app/views/frontend/partials/header.php'; ?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/about.css">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/faq.css">


<!-- ===== HERO ===== -->
<section class="about-hero">
    <div class="container text-center">
        <span class="badge-promo-light">💬 Bantuan & FAQ</span>
        <h1>Ada yang bisa kami bantu?</h1>
        <p>Kumpulan pertanyaan yang paling sering ditanyakan seputar belanja di Mizu Design.</p>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="faq-list" data-aos="fade-up">

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Bagaimana cara download produk setelah beli?
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Setelah pembayaran berhasil dikonfirmasi, file akan otomatis muncul di halaman
                            <strong>Download Saya</strong> pada dashboard akun kamu. Kamu juga akan menerima
                            notifikasi begitu file siap diunduh.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Berapa lama link download berlaku?
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Setiap link download punya masa berlaku tertentu yang bisa kamu lihat di halaman
                            Download Saya. Kalau link sudah kedaluwarsa, silakan hubungi tim support kami
                            untuk mendapatkan link baru.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Metode pembayaran apa saja yang tersedia?
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Kami menerima pembayaran lewat QRIS, Dana, OVO, GoPay, Transfer Bank, dan Virtual
                            Account melalui payment gateway Midtrans yang aman dan terpercaya.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Apakah bisa refund kalau file tidak sesuai?
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Bisa. Kalau file yang kamu terima rusak, tidak sesuai deskripsi, atau ada kendala
                            teknis lainnya, hubungi kami maksimal 3x24 jam setelah pembelian untuk proses
                            refund.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Apakah saya bisa menggunakan produk untuk keperluan komersial?
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Sebagian besar produk bisa dipakai untuk keperluan komersial, namun lisensi bisa
                            berbeda-beda tiap produk. Selalu cek bagian deskripsi produk sebelum membeli untuk
                            memastikan ketentuan lisensinya.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <button class="faq-question" onclick="toggleFaq(this)">
                        Bagaimana cara menghubungi customer support?
                        <i class="ti ti-chevron-down"></i>
                    </button>
                    <div class="faq-answer">
                        <p>Kamu bisa menghubungi kami lewat email di
                            <a href="mailto:hello@mizudesign.com">hello@mizudesign.com</a>. Tim kami akan
                            merespons secepatnya.
                        </p>
                    </div>
                </div>

            </div>

            <!-- CTA -->
            <div class="about-cta mt-4" data-aos="fade-up">
                <div class="row align-items-center">
                    <div class="col-md-7 mb-3 mb-md-0">
                        <h5 class="fw-bold mb-2">Masih ada pertanyaan lain?</h5>
                        <p class="mb-0 text-secondary">Tim kami siap bantu jawab pertanyaanmu.</p>
                    </div>
                    <div class="col-md-5 text-md-end">
                        <a href="mailto:hello@mizudesign.com" class="btn-about-primary">
                            <i class="ti ti-mail"></i> Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function toggleFaq(btn) {
        const item = btn.closest('.faq-item');
        const isOpen = item.classList.contains('open');

        document.querySelectorAll('.faq-item.open').forEach(el => el.classList.remove('open'));

        if (!isOpen) {
            item.classList.add('open');
        }
    }
</script>

<?php require ROOT . '/app/views/frontend/partials/footer.php'; ?>