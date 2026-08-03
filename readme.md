LAPORAN PROJECT
PENGEMBANGAN WEBSITE MARKETPLACE PRODUK DIGITAL
"MizuDesign"

Disusun Oleh :
Jami’ul muqtabirun 24TI083

PROGRAM STUDI TEKNIK INFORMATIKA
FAKULTAS TEKNOLOGI INFORMASI DAN KOMUNIKASI
UNIVERSITAS TEKNOLOGI MATARAM
2025/2026 

BAB I
PENDAHULUAN
1.1 Latar Belakang
Kebutuhan akan aset digital seperti template desain, preset foto, mockup, dan font premium terus meningkat seiring berkembangnya industri kreatif. Namun, aset-aset tersebut umumnya tersebar di berbagai platform yang berbeda-beda sehingga menyulitkan pengguna untuk menemukan produk yang sesuai dengan kebutuhan mereka dalam satu tempat. Berdasarkan permasalahan tersebut, dikembangkan sebuah platform marketplace khusus produk digital bernama MizuDesign.
MizuDesign dirancang sebagai wadah bagi kreator untuk memasarkan aset digital dan bagi pengguna untuk menemukan, mencari, serta membeli aset kreatif secara praktis melalui satu platform terpusat.
1.2 Tujuan

1. Membangun platform marketplace digital yang memudahkan transaksi jual-beli aset kreatif (template, preset, mockup, font, dan lain-lain).
2. Menyediakan sistem katalog produk yang terorganisir berdasarkan kategori.
3. Menyediakan panel administrasi (admin panel) untuk mengelola produk, kategori, pesanan (order), dan pengguna (user).
4. Menerapkan sistem autentikasi (login dan registrasi) bagi pengguna.
5. Mendukung berbagai metode pembayaran digital yang umum digunakan di Indonesia.
   1.3 Ruang Lingkup
   Laporan ini merupakan hasil revisi setelah penyusun memperoleh berkas project yang lebih lengkap, berupa seluruh source code aplikasi (folder app/, config/, routes/, storage/, vendor/) beserta berkas skema basis data (user.sql, kategoti&produk.sql, order.sql, wishlist.sql, copouns.sql, orderdanitemorder.sql) dan berkas konfigurasi lingkungan (.env, composer.json). Dengan tersedianya berkas-berkas tersebut, laporan ini diperluas agar mencakup analisis arsitektur backend, daftar teknologi yang benar-benar digunakan (bukan lagi dugaan), skema basis data aktual, serta seluruh fitur aplikasi secara menyeluruh — tidak lagi terbatas pada aset tampilan (CSS) dan tangkapan layar semata seperti pada versi laporan sebelumnya. 
   BAB II
   DESKRIPSI PROJECT
   2.1 Identitas Aplikasi
   Nama Aplikasi MizuDesign
   Jenis Aplikasi Marketplace / e-commerce produk digital
   Slogan "Temukan Aset Digital Terbaik untuk Karyamu"
   Segmentasi Desainer grafis, content creator, dan pekerja kreatif yang membutuhkan template Canva, preset Lightroom, mockup, font premium, dan UI Kit
   Arsitektur PHP native (tanpa framework pihak ketiga seperti Laravel/CodeIgniter) dengan pola MVC buatan sendiri: Model (app/models), View (app/views), Controller (app/controllers), ditambah custom router (routes/web.php) dan namespace App\ yang di-autoload melalui Composer (PSR-4)
   Status Pengembangan Berdasarkan source code, pengembangan telah jauh lebih maju dibanding yang tergambar pada dokumentasi screenshot (30 Juni 2026). Modul analitik penjualan, laporan PDF/Excel, blog, wishlist, ulasan produk, notifikasi, dan integrasi pembayaran Midtrans telah diimplementasikan di dalam kode
   Version Control Git — ditemukan folder .git pada root project, menandakan riwayat perubahan kode dikelola menggunakan Git

Penjelasan dari masing-masing identitas di atas adalah sebagai berikut:
• Nama Aplikasi — Aplikasi ini diberi nama "MizuDesign", sebuah nama yang mencerminkan fokus produk berupa aset desain digital. Nama ini konsisten digunakan pada logo navbar ("MizuDesign") maupun pada panel admin dengan variasi nama "MizuAdmin".
• Jenis Aplikasi — MizuDesign tergolong sebagai aplikasi marketplace atau e-commerce khusus produk digital (digital product marketplace), yaitu platform yang mempertemukan penjual dan pembeli aset kreatif berbentuk file digital, bukan barang fisik.
• Slogan — Slogan "Temukan Aset Digital Terbaik untuk Karyamu" ditampilkan pada hero section halaman beranda dan berfungsi sebagai pesan utama (value proposition) yang menjelaskan manfaat aplikasi kepada pengunjung baru.
• Segmentasi — Berdasarkan kategori produk yang tersedia (Template Canva, Preset Lightroom, Mockup Design, Font Premium, Template CapCut, Template Photoshop, Template PowerPoint, dan UI Kit), target pengguna aplikasi ini adalah desainer grafis, content creator, fotografer, dan pekerja kreatif lain yang membutuhkan aset siap pakai untuk mempercepat pekerjaan mereka.
• Arsitektur — Berkas .htaccess pada folder public/ mengaktifkan mod_rewrite dan mengarahkan seluruh permintaan menuju public/index.php (front controller). Berkas ini memuat routes/web.php, yaitu router buatan sendiri yang mencocokkan URI dan metode HTTP menggunakan konstruksi match(true) PHP 8, lalu memanggil controller yang sesuai (contoh: HomeController, ProductController, Admin\ProductController). Struktur folder app/controllers, app/models, dan app/views secara eksplisit menerapkan pola Model-View-Controller (MVC) tanpa bergantung pada framework pihak ketiga. Ketergantungan pustaka pihak ketiga (lihat composer.json) dikelola oleh Composer dengan namespace App\ yang di-autoload sesuai standar PSR-4.
• Status Pengembangan — Dokumentasi screenshot yang tersedia berasal dari tanggal 30 Juni 2026 dan menampilkan dashboard admin yang masih sangat sederhana (hanya Total Produk & Total User, dengan catatan "statistik akan ditambahkan di Minggu 9"). Namun, hasil audit terhadap source code menunjukkan bahwa AdminController::dashboard() saat ini SUDAH mengambil data pendapatan total, grafik penjualan harian/bulanan/tahunan, dan produk terlaris — artinya pengembangan aplikasi telah berlanjut jauh melampaui kondisi yang tergambar pada screenshot tersebut.
• Version Control — Ditemukan folder tersembunyi .git (berisi hooks, info, logs, objects, refs) pada root project, menandakan bahwa seluruh riwayat perubahan kode proyek ini dikelola dan dilacak menggunakan sistem kontrol versi Git.
2.2 Struktur Direktori Project
Struktur folder lengkap aplikasi (hasil ekstraksi berkas project yang direvisi) tersusun sebagai berikut:
mizu-design/
├── .env (konfigurasi lingkungan: DB, Midtrans, R2)
├── composer.json / composer.lock (daftar dependensi PHP)
├── user.sql, kategoti&produk.sql, order.sql,
│ wishlist.sql, copouns.sql, orderdanitemorder.sql (skema & seed database)
├── app/
│ ├── controllers/ (11 controller publik + 6 controller Admin\)
│ ├── models/ (10 model: User, Product, Category, Order, ...)
│ ├── views/ (tampilan admin/ dan frontend/, termasuk partials)
│ ├── middleware/ (AuthMiddleware: check, adminOnly, guest)
│ ├── helpers/ (CartHelper, StringHelper, UploadHelper)
│ └── index.php
├── config/
│ ├── database.php (koneksi PDO — singleton class Database)
│ └── config.php (konstanta BASE_URL)
├── routes/
│ └── web.php (custom router berbasis match(true))
├── storage/
│ ├── products/ (berkas digital: .zip, .pdf, .psd, .rar)
│ ├── invoices/ (invoice PDF hasil generate dompdf)
│ └── logs/
├── public/
│ ├── .htaccess, index.php, debug.php
│ └── assets/ (css/, images/, videos/products/)
├── vendor/ (dependensi Composer: dompdf, midtrans-php,
│ phpmailer, phpoffice/phpspreadsheet, dst.)
├── .git/ (riwayat version control)
└── foto tahapan/ (dokumentasi screenshot progres)
Struktur ini secara jelas memisahkan tanggung jawab tiap bagian aplikasi: app/ untuk logika MVC, config/ untuk konfigurasi, routes/ untuk pemetaan URL, storage/ untuk berkas yang diunggah/dihasilkan pengguna, public/ sebagai document root yang diakses browser, dan vendor/ untuk pustaka pihak ketiga yang dikelola Composer.
2.3 Daftar Modul Stylesheet
Kategori Berkas Fungsi
Layout Global style.css, navbar.css, footer.css Variabel warna, navigasi, dan footer di seluruh halaman
Landing Page home.css, hero.css, hero-interactive.css Tampilan beranda dan hero section
Autentikasi auth.css Halaman login dan registrasi
Katalog Produk kategori.css, product-detail.css Daftar kategori dan detail produk
Transaksi Checkout-custom.css Proses checkout / pembayaran
Area Pengguna dashboard.css, dashboard-home.css Dashboard akun pengguna
Panel Admin admin.css, admin-notice.css Tampilan panel administrasi
Konten Informasi blog.css, blog-detail.css, about.css, faq.css Halaman blog, tentang kami, dan FAQ
Lainnya error-page.css Halaman error (404, dsb.)

Penjelasan masing-masing kategori modul stylesheet di atas adalah sebagai berikut:
• Layout Global (style.css, navbar.css, footer.css) — Ketiga berkas ini menjadi fondasi tampilan yang dimuat di seluruh halaman. style.css berisi deklarasi variabel warna dasar (:root) yang dipakai ulang oleh berkas lain, navbar.css mengatur tampilan navigasi atas termasuk efek kaca buram (glassmorphism), sementara footer.css mengatur tata letak footer beserta tautan-tautannya.
• Landing Page (home.css, hero.css, hero-interactive.css) — Mengatur tampilan halaman beranda, khususnya bagian hero section (banner utama dengan gradasi warna biru-ungu), susunan kartu kategori, serta interaksi/animasi tambahan pada hero (hero-interactive.css) seperti efek hover.
• Autentikasi (auth.css) — Digunakan khusus untuk halaman Login dan Registrasi, mengatur tampilan kartu formulir gelap (dark card) yang terpusat di tengah layar beserta input field dan tombol aksi.
• Katalog Produk (kategori.css, product-detail.css) — kategori.css mengatur tampilan daftar/filter kategori pada halaman Shop, sedangkan product-detail.css mengatur tampilan halaman detail satu produk (yang belum tertangkap dalam dokumentasi screenshot yang tersedia).
• Transaksi (Checkout-custom.css) — Mengatur tampilan proses checkout atau pembayaran, mengindikasikan adanya alur transaksi setelah pengguna memilih produk untuk dibeli.
• Area Pengguna (dashboard.css, dashboard-home.css) — Mengatur tampilan dashboard milik pengguna/pembeli biasa (bukan admin), kemungkinan berisi riwayat pembelian atau produk yang telah diunduh.
• Panel Admin (admin.css, admin-notice.css) — admin.css mengatur keseluruhan layout panel admin (sidebar gelap, konten utama, tabel data), sedangkan admin-notice.css kemungkinan mengatur tampilan notifikasi/pemberitahuan khusus di dalam panel admin.
• Konten Informasi (blog.css, blog-detail.css, about.css, faq.css) — Menunjukkan bahwa aplikasi ini turut dilengkapi halaman konten pendukung seperti blog (artikel), halaman Tentang Kami (about), dan halaman Pertanyaan Umum (FAQ) untuk kebutuhan informasi dan SEO/edukasi pengguna.
• Lainnya (error-page.css) — Mengatur tampilan halaman kesalahan (misalnya error 404 saat halaman tidak ditemukan) agar tetap sesuai dengan identitas visual aplikasi..
2.4 Identitas Visual (Design System)
Berdasarkan variabel CSS yang didefinisikan pada style.css dan navbar.css, aplikasi ini menggunakan palet warna dan tipografi yang konsisten sebagai berikut:
Elemen Nilai Keterangan
Warna Primer #2563EB Biru — digunakan pada tombol, aksen harga, dan ikon kategori
Warna Sekunder #7C3AED Ungu — digunakan pada gradasi hero section dan label diskon
Warna Gelap #111827 Digunakan pada teks utama dan latar footer
Warna Terang #F9FAFB Warna latar belakang halaman
Tipografi Poppins Font sans-serif utama pada seluruh halaman
Gaya Visual — Efek glassmorphism (navbar transparan blur), sudut membulat (border-radius besar), gradasi diagonal pada hero, dan efek hover elevasi pada kartu produk

Penjelasan masing-masing elemen identitas visual di atas adalah sebagai berikut:
• Warna Primer (#2563EB) — Warna biru ini merupakan warna dominan aplikasi, digunakan pada tombol utama (misalnya tombol "Masuk" dan "Cari"), harga produk, ikon kategori, serta salah satu warna gradasi pada hero section.
• Warna Sekunder (#7C3AED) — Warna ungu ini berfungsi sebagai warna aksen/pelengkap warna primer, terlihat pada ujung gradasi hero section, label kategori pada kartu produk, dan badge diskon (misalnya label "-50%").
• Warna Gelap (#111827) — Digunakan sebagai warna teks utama agar mudah dibaca, serta sebagai warna latar belakang footer dan sidebar panel admin sehingga menciptakan kontras dengan konten utama yang berwarna terang.
• Warna Terang (#F9FAFB) — Warna latar belakang utama halaman (background) yang memberi kesan bersih dan lapang, dipakai konsisten di halaman publik maupun panel admin.
• Tipografi (Poppins) — Seluruh teks aplikasi menggunakan font Poppins (jenis sans-serif) yang dideklarasikan melalui font-family pada elemen body, memberikan kesan modern dan mudah dibaca baik untuk judul maupun teks isi.
• Gaya Visual — Aplikasi konsisten menerapkan efek glassmorphism (navbar transparan dengan efek blur latar belakang), sudut membulat besar (border-radius) pada hero section dan kartu produk, gradasi warna diagonal pada elemen hero, serta efek hover berupa elevasi (kartu terangkat sedikit ke atas) saat kursor diarahkan ke kartu kategori atau produk.
2.5 Teknologi dan Integrasi yang Digunakan
Berbeda dari revisi laporan sebelumnya yang sebagian besar bersifat dugaan, seluruh poin teknologi berikut telah DIKONFIRMASI langsung dari source code (composer.json, .env, config/database.php, serta kode pada app/controllers dan app/models).
Lapisan/Aspek Teknologi Keterangan
Bahasa Server-side PHP (native, OOP) Dikonfirmasi dari seluruh berkas app/\*_/_.php yang ditulis dengan namespace App\ serta class/OOP penuh, tanpa framework pihak ketiga.
Pola Arsitektur MVC custom + Front Controller Folder app/controllers, app/models, app/views menerapkan MVC secara eksplisit. routes/web.php berperan sebagai router kustom yang mencocokkan URI/method dengan blok match(true) PHP 8, lalu memanggil controller terkait.
Autoload/Dependency Manager Composer (PSR-4) composer.json mendaftarkan autoload PSR-4 "App\\": "app/" serta 4 dependensi utama: phpmailer/phpmailer, dompdf/dompdf, phpoffice/phpspreadsheet, dan midtrans/midtrans-php.
Basis Data & Akses Data MySQL/MariaDB via PDO config/database.php mengimplementasikan class Database (pola Singleton) yang membuka koneksi PDO ke MySQL menggunakan kredensial dari .env (DB_HOST, DB_NAME, DB_USER, DB_PASS), dengan mode ERRMODE_EXCEPTION dan FETCH_ASSOC.
Sesi & Autentikasi PHP Session native session_start() dipanggil di awal routes/web.php. Status login disimpan pada $\_SESSION['user_id'] dan $\_SESSION['user_role'], diperiksa oleh AuthMiddleware::check() (harus login) dan AuthMiddleware::adminOnly() (harus login dan role='admin'). Password di-hash dengan password_hash(..., PASSWORD_BCRYPT, cost 12).
Keranjang Belanja (Cart) PHP Session (bukan tabel DB) CartHelper menyimpan daftar ID produk langsung di $\_SESSION['cart'] (array), bukan pada tabel database — sehingga isi keranjang akan hilang jika sesi berakhir/browser lain digunakan.
Payment Gateway Midtrans (midtrans/midtrans-php ^2.6) Dikonfirmasi dari composer.json, kunci MIDTRANS_SERVER_KEY/MIDTRANS_CLIENT_KEY pada .env (mode sandbox, MIDTRANS_IS_PRODUCTION=false), serta CheckoutController yang mengonfigurasi \Midtrans\Config (server key, 3DS aktif) dan menyediakan endpoint Snap (checkout/midtrans-token), Core API charge (checkout/core-charge) untuk QRIS & transfer bank/VA, serta webhook (checkout/midtrans-webhook) dengan verifikasi tanda tangan SHA-512 (hash_equals) sebelum mengubah status order menjadi 'paid'.
Pembuatan PDF dompdf/dompdf ^2.0 Digunakan pada AdminController::downloadReport() untuk mengekspor laporan penjualan admin ke PDF; folder storage/invoices/ mengindikasikan dompdf turut dipakai untuk mencetak invoice.
Ekspor Excel phpoffice/phpspreadsheet ^5.9 Digunakan pada AdminController::downloadReportExcel() untuk mengekspor laporan penjualan admin ke berkas Excel (.xlsx).
Pengiriman Email phpmailer/phpmailer ^6.9 Terdaftar sebagai dependensi pada composer.json untuk kebutuhan pengiriman email (mis. notifikasi transaksi/registrasi).
Penyimpanan Awan (direncanakan) Cloudflare R2 (belum aktif) Variabel R2_BUCKET, R2_ACCESS_KEY, dan R2_SECRET_KEY tersedia pada .env namun masih kosong, menandakan integrasi penyimpanan objek cloud (untuk berkas produk) sudah direncanakan tetapi belum diaktifkan/dipakai.
Framework/Library CSS Bootstrap-like custom CSS Kelas seperti navbar-nav, nav-link, btn, card, col-, dan container-fluid pada admin.css/navbar.css mengikuti konvensi Bootstrap, kemungkinan besar sebagai lapisan override di atasnya.
Tipografi & Ikon Google Fonts Poppins, Tabler Icons Font Poppins dipakai di seluruh halaman; kelas ikon berawalan "ti-" (ti-typography, ti-frame, dst.) pada data kategori menandakan pustaka Tabler Icons.
Server Lokal (Development) Kemungkinan XAMPP / Laragon DB_HOST=localhost, DB_USER=root, DB_PASS=(kosong) pada .env merupakan konfigurasi khas MySQL bawaan paket pengembangan lokal seperti XAMPP atau Laragon.

Secara ringkas, tumpukan teknologi (tech stack) proyek ini adalah PHP native (OOP, MVC custom) + MySQL/MariaDB (PDO) yang dikelola dependensinya lewat Composer, dengan integrasi Midtrans sebagai payment gateway (Snap & Core API), dompdf untuk PDF, PhpSpreadsheet untuk Excel, dan PHPMailer untuk email, dijalankan di atas server lokal (XAMPP/Laragon).
Catatan keamanan: berkas .env (yang memuat kredensial database dan kunci Midtrans) sudah didaftarkan pada .gitignore sehingga tidak ikut ter-commit ke Git — sebuah praktik keamanan yang tepat. Kunci Midtrans yang ditemukan berformat sandbox/testing (diawali Mid-server-/Mid-client- dengan MIDTRANS_IS_PRODUCTION=false), bukan kunci produksi.
2.6 Skema Basis Data (Direkonstruksi dari Source Code)
Berdasarkan penelusuran menyeluruh terhadap app/models/_.php dan app/controllers/\*\*/_.php (bukan hanya berkas .sql bawaan yang sudah agak tertinggal dari kode aktual), basis data mizu_design tersusun atas 12 tabel berikut:
Tabel Kolom Utama Sumber
users id, name, email, password, role, photo, status, created_at user.sql
categories id, name, slug, icon, parent_id, created_at kategoti&produk.sql + Category.php
products id, category_id, title, slug, description, thumbnail, preview_image, file_path, price, discount, sales, status, created_at kategoti&produk.sql + Admin/Productcontroller.php
product_media id, product_id, type, file_path, sort_order Product.php (getMedia/addMedia)
reviews id, product_id, user_id, rating, comment, created_at Product.php (addReview, getReviews)
coupons id, code, discount, expired_at, created_at copouns.sql
orders id, user_id, invoice, total, status, payment_status, is_hidden, midtrans_transaction_id, midtrans_payment_type, midtrans_paid_at, created_at order.sql + Order.php + Checkoutcontroller.php
order_items id, order_id, product_id, price order.sql
downloads id, user_id, product_id, order_id, token, download_count, is_hidden, hidden_by_user, expired_at, created_at Download.php
wishlists id, user_id, product_id, created_at wishlist.sql
blogs id, title, slug, content, thumbnail, created_at Blog.php + AdminBlogController.php
notifications id, user_id (nullable), type, title, message, is_read, url, created_at Notification.php

Beberapa temuan penting hasil audit skema terhadap kode aktual (dijelaskan lebih lanjut pada BAB V):
• Kolom is_hidden, midtrans_transaction_id, midtrans_payment_type, dan midtrans_paid_at pada tabel orders TIDAK terdapat pada order.sql asli, tetapi dipakai langsung pada query di Order::byUser() dan CheckoutController::midtransWebhook() — menandakan skema aktual di database production sudah berkembang melampaui berkas .sql yang disertakan.
• Kolom parent_id pada categories (untuk sub-kategori) juga tidak ada pada kategoti&produk.sql, tetapi dipakai aktif pada Category::allGrouped() dan Category::parentsWithCount().
• Tabel product_media, reviews, downloads, blogs, dan notifications sama sekali tidak memiliki berkas .sql tersendiri, sehingga strukturnya pada revisi database.sql ini disusun murni dari query SQL (CREATE-equivalent) yang ditemukan pada model masing-masing
BAB III
FITUR APLIKASI
Bab ini menjelaskan seluruh fitur aplikasi yang dikonfirmasi langsung dari routes/web.php beserta controller dan model terkait — jauh lebih lengkap dibanding yang tampak pada dokumentasi screenshot semata. Fitur dibagi menjadi empat kelompok: autentikasi, sisi pengguna publik (frontend), dashboard customer, dan panel administrasi.
3.1 Autentikasi dan Middleware
• Login (GET/POST /login) dan Register (GET/POST /register) — ditangani AuthController, dengan password di-hash menggunakan bcrypt (cost 12).
• Logout (/logout) — mengakhiri sesi pengguna.
• AuthMiddleware::check() — mewajibkan pengguna login (memeriksa $_SESSION['user_id']) sebelum mengakses halaman dashboard customer atau notifikasi.
•	AuthMiddleware::adminOnly() — mewajibkan login DAN role bernilai 'admin' sebelum dapat mengakses seluruh rute /admin/*; jika bukan admin, sistem mengembalikan HTTP 403.
•	AuthMiddleware::guest() — kebalikan dari check(), dipakai untuk mencegah pengguna yang sudah login mengakses ulang halaman login/register.
3.2 Sisi Pengguna Publik (Frontend)
3.2.1 Navigasi, Beranda, dan Halaman Informasi
•	Navbar global menampilkan logo, menu Home/Shop, kolom pencarian, jumlah item keranjang (cartCount), jumlah wishlist, serta lonceng notifikasi (unreadCount) yang datanya diambil BaseController::headerData() pada setiap request.
•	Beranda (/) — hero section, kategori (dengan dukungan sub-kategori via parentsWithCountAndThumbnail()), Produk Terbaru, dan Produk Terlaris.
•	Tentang Kami (/about) dan FAQ (/faq) — halaman informasi statis (PageController).
•	Halaman 404 — ditampilkan otomatis oleh router untuk setiap URI yang tidak cocok dengan rute manapun, baik di area publik, dashboard, maupun admin.
3.2.2 Katalog dan Detail Produk
•	Shop (/shop) — daftar seluruh produk dengan filter kategori, termasuk kategori bertingkat (kategori utama & sub-kategori).
•	Halaman Promo (/promo) — menampilkan khusus produk yang memiliki discount > 0 (ProductController::promo).
•	Detail Produk (/product/{slug}) — menampilkan galeri media produk (product_media: gambar dan/atau video, diurutkan berdasarkan sort_order), deskripsi, harga, serta ringkasan rating (rata-rata & jumlah ulasan).
•	Ulasan & Rating Produk (POST /product/{slug}/review) — pengguna hanya dapat memberi ulasan (rating 1-5 + komentar) jika terverifikasi PERNAH membeli produk tersebut (Product::userHasPurchased, dicek ke order_items berstatus 'paid'), dan tidak dapat mengulas produk yang sama dua kali (hasUserReviewed).
3.2.3 Keranjang Belanja (Cart)
•	Lihat Keranjang (GET /cart), Tambah Produk (POST /cart/add), dan Hapus Produk (POST /cart/remove) — seluruh isi keranjang disimpan pada PHP session ($\_SESSION['cart']), berupa daftar ID produk, BUKAN pada tabel database tersendiri.
• Terapkan Kupon (POST /cart/coupon) — memvalidasi kode kupon ke tabel coupons (harus belum kedaluwarsa, dicek dengan expired_at >= CURDATE()) lalu menghitung potongan harga sebagai persentase dari subtotal.
3.2.4 Checkout dan Pembayaran
• Checkout (GET/POST /checkout) — menampilkan ringkasan pesanan (subtotal, diskon kupon, total) dan mendukung jalur pembayaran MANUAL (unggah bukti transfer).
• Checkout Pending (/checkout/pending) — halaman status menunggu pembayaran.
• Pembayaran via Midtrans Snap (POST /checkout/midtrans-token) — membuat token Snap untuk ditampilkan sebagai pop-up pembayaran di sisi frontend.
• Pembayaran via Midtrans Core API (POST /checkout/core-charge) — memproses pembayaran QRIS dan transfer bank/Virtual Account secara langsung, termasuk mengambil nomor VA dari respons Midtrans (va_numbers).
• Cek Status Order (GET /checkout/order-status) — polling status transaksi terkini dari sisi frontend.
• Webhook Midtrans (POST /checkout/midtrans-webhook) — endpoint yang dipanggil server Midtrans untuk memberi tahu perubahan status pembayaran. Sistem MEMVERIFIKASI tanda tangan (signature_key) menggunakan SHA-512 dari kombinasi invoice + status_code + gross_amount + server key sebelum mempercayai notifikasi, lalu memperbarui status order (paid/failed/pending) dan mengirim notifikasi ke admin bila pembayaran diterima.
3.2.5 Wishlist dan Blog
• Wishlist (GET /wishlist, POST /wishlist/add) — pengguna dapat menandai/batal-menandai produk favorit; setiap pasangan (user_id, product_id) bersifat unik.
• Blog (GET /blog, GET /blog/detail) — daftar dan detail artikel blog yang dikelola admin, ditampilkan untuk pengunjung publik.
3.2.6 Notifikasi Customer
• Pengguna yang login dapat menandai semua notifikasi sudah dibaca (/notifications/read-all), menandai satu notifikasi (/notifications/read), menghapus satu (/notifications/delete), atau menghapus semua (/notifications/delete-all) miliknya sendiri.
3.3 Dashboard Customer (Area Akun Pengguna)
Seluruh rute berikut memerlukan login (AuthMiddleware::check), tidak harus admin:
• Ringkasan Dashboard (/dashboard) — halaman utama area akun.
• Profil (/dashboard/profile, update via POST /dashboard/profile/update) — mengubah nama, email, dan foto profil.
• Ganti Password (POST /dashboard/password/update).
• Riwayat Pesanan (/dashboard/orders) dan Detail Pesanan (/dashboard/orders/detail) — hanya menampilkan order milik user yang bersangkutan dan belum disembunyikan (is_hidden = 0).
• Sembunyikan Pesanan (POST /dashboard/orders/hide) — soft-hide, bukan menghapus data order dari database.
• Riwayat Download (/dashboard/downloads) — menampilkan tautan unduh (dengan token unik) untuk setiap produk yang sudah dibayar.
• Sembunyikan Download (POST /dashboard/downloads/hide) dan Sembunyikan Semua yang Kedaluwarsa (POST /dashboard/downloads/hide-expired).
• Unduh Produk (GET /download/{token}) — DownloadController memverifikasi token dan masa berlaku (24 jam sejak order dibuat) sebelum menyajikan (serve) berkas digital, sekaligus menambah hitungan download_count.
3.4 Panel Administrasi (Admin Panel)
Seluruh rute /admin/\* diproteksi AuthMiddleware::adminOnly() (wajib login sebagai role admin).
3.4.1 Dashboard Analitik
• Kartu ringkasan: Total Produk, Total User, Total Order (berstatus paid), dan Total Pendapatan (totalRevenue — SUM(total) dari order berstatus 'paid').
• Grafik penjualan Harian (30 hari terakhir), Bulanan (12 bulan terakhir), dan Tahunan (5 tahun terakhir) — masing-masing dihasilkan oleh Order::salesByDay/salesByMonth/salesByYear().
• Daftar Produk Terlaris (top 5) — dihitung dari jumlah baris order_items pada order yang berstatus paid (Order::topProducts()).
3.4.2 Kelola Produk
• CRUD produk lengkap: daftar (index), tambah (create/store), edit (editForm/update), dan hapus (destroy), termasuk unggah thumbnail, preview_image, dan file_path (berkas digital yang dijual).
• Kelola Media Produk (POST /admin/products/media/delete) — menghapus satu media (gambar/video) dari galeri produk.
3.4.3 Kelola Kategori
• CRUD kategori (index/store/update/destroy), termasuk dukungan sub-kategori melalui kolom parent_id.
3.4.4 Kelola Order
• Daftar Order (dapat difilter per status) beserta nama & email pembeli (JOIN ke tabel users).
• Detail Order (/admin/orders/detail) dan Ubah Status Order (POST /admin/orders/update-status) secara manual oleh admin.
3.4.5 Kelola User
• Daftar seluruh user, Nonaktifkan/Aktifkan (toggle-status), dan Hapus user (destroy).
3.4.6 Kelola Blog
• CRUD artikel blog admin (index/create/store/edit/update/delete) lengkap dengan unggah thumbnail; thumbnail lama otomatis dihapus dari server saat diganti atau saat artikel dihapus (kecuali masih memakai default-blog.jpg).
3.4.7 Notifikasi Admin
• Notifikasi untuk admin ditandai dengan user_id = NULL pada tabel notifications (terpisah dari notifikasi customer). Admin dapat menandai satu/semua sudah dibaca, serta menghapus satu/semua notifikasi.
• Sistem otomatis mengirim notifikasi ke admin setiap kali webhook Midtrans mengonfirmasi pembayaran diterima.
3.4.8 Laporan (Export PDF & Excel)
• Unduh Laporan PDF (/admin/reports/download?range=daily|monthly|yearly) — dibangkitkan dengan dompdf, berisi ringkasan pendapatan, jumlah order, produk terlaris, dan daftar transaksi pada rentang yang dipilih.
• Unduh Laporan Excel (/admin/reports/download-excel?range=...) — data yang sama diekspor ke berkas .xlsx menggunakan PhpSpreadsheet.

 
BAB IV. DOKUMENTASI TAMPILAN ANTARMUKA
Berikut adalah dokumentasi visual tahapan pengembangan antarmuka aplikasi MizuDesign yang diambil pada tanggal 30 Juni 2026. Perlu dicatat bahwa tangkapan layar ini merepresentasikan kondisi aplikasi pada TITIK WAKTU TERSEBUT saja; berdasarkan hasil audit source code pada BAB II dan BAB III, pengembangan aplikasi telah berlanjut jauh lebih lengkap (dashboard analitik, blog, wishlist, ulasan produk, integrasi Midtrans, laporan PDF/Excel, dan lain-lain) dibanding yang tergambar pada halaman-halaman berikut.
4.1 Halaman Beranda

Gambar 4.1 Tampilan Beranda MizuDesign — hero section, kategori, produk terbaru & terlaris
4.2 Halaman Shop / Semua Produk

Gambar 4.2 Tampilan halaman Shop dengan filter kategori
4.3 Halaman Login

Gambar 4.3 Tampilan halaman Login
4.4 Halaman Registrasi

Gambar 4.4 Tampilan halaman Daftar (Register)

 
4.5 Dashboard Admin

Gambar 4.5 Tampilan Dashboard MizuAdmin
4.6 Kelola Produk

Gambar 4.6 Tampilan halaman Kelola Produk
4.7 Kelola Kategori

Gambar 4.7 Tampilan halaman Kelola Kategori
4.8 Kelola Order

Gambar 4.8 Tampilan halaman Kelola Order
4.9 Kelola User

Gambar 4.9 Tampilan halaman Kelola User

 
BAB V. ANALISIS
5.1 Kelebihan
• Cakupan fitur sangat lengkap untuk sebuah marketplace produk digital: katalog dengan sub-kategori, keranjang, kupon diskon, checkout manual maupun otomatis, wishlist, ulasan & rating produk, blog, notifikasi dua arah (admin & customer), serta dashboard analitik dengan laporan yang dapat diekspor ke PDF maupun Excel.
• Integrasi payment gateway (Midtrans) dilakukan dengan benar dari sisi keamanan: webhook memverifikasi tanda tangan (signature_key) menggunakan SHA-512 sebelum mempercayai notifikasi status pembayaran, mencegah pemalsuan callback oleh pihak tak dikenal.
• Ulasan produk hanya dapat diberikan oleh pengguna yang terbukti sudah membeli (verified purchase), mencegah ulasan palsu — sebuah praktik yang baik untuk kredibilitas marketplace.
• Mekanisme unduh produk digital menggunakan token acak dengan masa berlaku terbatas (24 jam), bukan tautan langsung ke berkas, sehingga lebih aman dari akses tidak sah.
• Struktur kode rapi dengan pemisahan tanggung jawab yang jelas (MVC), autoload PSR-4 melalui Composer, dan penggunaan prepared statement (PDO) secara konsisten pada seluruh model — praktik yang baik untuk mencegah SQL Injection.
• Kredensial sensitif (.env) sudah benar didaftarkan pada .gitignore sehingga tidak ikut ter-commit ke riwayat Git.
5.2 Kekurangan dan Rekomendasi Perbaikan
• Inkonsistensi kolom pada tabel downloads: method hideFromUser() mengubah kolom is_hidden, sedangkan hideAllExpiredFromUser() mengubah kolom hidden_by_user yang berbeda — sementara query penampil (byUser()) hanya memfilter berdasarkan is_hidden. Akibatnya, unduhan yang "disembunyikan otomatis karena kedaluwarsa" berpotensi TIDAK benar-benar tersembunyi dari daftar customer. Disarankan menyatukan kedua method agar konsisten memakai satu kolom yang sama.
• Beberapa komentar kode (User.php, Download.php, Notification.php) menyebutkan penyesuaian tipe data ID "agar mendukung UUID", namun BaseModel::find()/update() masih memaksa (int) cast pada parameter ID — menandakan migrasi ke UUID belum selesai/konsisten di seluruh lapisan kode.
• Skema database tersebar pada 6 berkas .sql terpisah per fitur (beberapa dengan nama kurang konsisten/typo, seperti "kategoti&produk.sql" dan "copouns.sql"), dan sejumlah tabel penting (product_media, reviews, downloads, blogs, notifications, serta kolom tambahan pada orders dan categories) tidak memiliki berkas migrasi sama sekali. Disarankan menggunakan satu berkas skema terpadu atau sistem migrasi database (mis. Phinx) agar struktur basis data selalu tersinkron dengan kode aplikasi.
• Keranjang belanja (cart) sepenuhnya bergantung pada PHP session tanpa tabel database — isi keranjang akan hilang jika sesi berakhir, pengguna berpindah perangkat/browser, atau belum login. Untuk pengalaman multi-perangkat yang lebih baik, cart sebaiknya turut disinkronkan ke database untuk pengguna yang sudah login.
• Variabel integrasi Cloudflare R2 (R2_BUCKET, R2_ACCESS_KEY, R2_SECRET_KEY) sudah disediakan pada .env namun masih kosong dan belum dipakai kode manapun — perlu diperjelas apakah fitur ini masih direncanakan atau sudah tidak relevan lagi bagi arah project.
• Kunci Midtrans yang ditemukan masih berupa kunci sandbox (testing); sebelum aplikasi digunakan secara nyata (production), kunci tersebut wajib diganti dengan kunci produksi dan MIDTRANS_IS_PRODUCTION diubah menjadi true.
BAB VI. KESIMPULAN
MizuDesign merupakan platform marketplace digital yang jauh lebih matang dan lengkap dibanding yang tergambar pada dokumentasi tangkapan layar awal (30 Juni 2026). Hasil audit menyeluruh terhadap source code menunjukkan bahwa aplikasi ini telah mengimplementasikan siklus e-commerce yang utuh: penelusuran katalog dengan sub-kategori, keranjang belanja, kupon diskon, checkout dengan pembayaran manual maupun otomatis melalui Midtrans (Snap & Core API dengan verifikasi webhook yang aman), wishlist, ulasan produk berbasis pembelian terverifikasi, blog, sistem notifikasi dua arah, unduhan produk digital berbasis token dengan masa berlaku, serta dashboard admin dengan analitik penjualan dan laporan yang dapat diekspor ke PDF maupun Excel.
Dari sisi arsitektur, project ini dibangun murni menggunakan PHP native dengan pola MVC buatan sendiri, dikelola melalui Composer (PSR-4), dan terhubung ke basis data MySQL/MariaDB melalui PDO dengan prepared statement yang konsisten. Beberapa catatan teknis (seperti inkonsistensi kolom is_hidden/hidden_by_user, migrasi ID ke UUID yang belum tuntas, serta skema database yang tersebar pada banyak berkas .sql terpisah) perlu menjadi perhatian pengembang untuk iterasi berikutnya, sebagaimana dirinci pada BAB V. Secara keseluruhan, MizuDesign menunjukkan kualitas rekayasa perangkat lunak yang solid untuk skala sebuah project pembelajaran/tugas, dengan cakupan fitur yang setara aplikasi e-commerce produksi skala kecil-menengah.
